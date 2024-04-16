<?php

require_once('./functions/helpers.php');
require_once('./functions/init.php');
require_once('./functions/models.php');
require_once('./functions/db.php');
require_once('./functions/validators.php');
require_once('./data/data.php');



// Список категорий меню
$categoryList = getCategories($con);

// Список компонентов Поке
$componentList = getComponentList($con);


// Извлекаем уникальные значения ключа component_type
$uniqueComponentTypes = array_unique(array_column($componentList, 'component_type'));


// Создаем массив для хранения отфильтрованных компонентов
$sortComponentList = array_reduce($uniqueComponentTypes, function ($carry, $uniqueType) use ($componentList) {
    // Фильтруем массив $componentList для текущего уникального типа
    $filteredComponents = array_filter($componentList, function ($item) use ($uniqueType) {
        return $item['component_type'] === $uniqueType;
    });

    // Добавляем отфильтрованные компоненты в итоговый массив, используя тип как ключ
    $carry[$uniqueType] = $filteredComponents;

    return $carry;
}, []);



// Списки компонентов
$proteinList = $sortComponentList['protein'];
$baseList = $sortComponentList['base'];
$fillerList = $sortComponentList['filler'];
$toppingList = $sortComponentList['topping'];
$sauceList = $sortComponentList['sauce'];
$crunchList = $sortComponentList['crunch'];
$proteinAddList = $sortComponentList['protein-add'];


// Массив для хранения уникальных компонентов
$uniqueComponentNames = array_reduce($componentList, function ($carry, $item) {
    if (!isset($carry[$item['component_type']])) {
        $carry[$item['component_type']] = $item['component_type'];
    }

    return $carry;
}, []);


$page_body = include_template(
    'constructor-poke.php',
    [
        'proteinList' => $proteinList,
        'proteinAddList' => $proteinAddList,
        'baseList' => $baseList,
        'fillerList' => $fillerList,
        'toppingList' => $toppingList,
        'sauceList' => $sauceList,
        'crunchList' => $crunchList,
    ]
);



if ($_SERVER['REQUEST_METHOD'] === "POST") {
    // Обязательные поля для заполненения 
    $required = ['shema', 'protein', 'base', 'filler', 'topping', 'sauce', 'crunch', 'total-price'];
    $errors = [];

    $rules = [
        'component' => fn ($key, $value) => validate_component($con, $key, $value),
        'shema' => fn ($value) => !in_array($value, [1, 2]) ? 'Указана неверная схема для наполнителя и топпинга' : null,
        'total-price' => fn ($value) => $value < 0 ? 'Вы ничего не выбрали' : null,
        'component-length' => fn ($name, $value, $shema) => validate_component_length($name, $value, $shema),
    ];

    $createdPoke = filter_input_array(INPUT_POST, ['protein' => FILTER_DEFAULT, 'base' => FILTER_DEFAULT, 'shema' => FILTER_DEFAULT, 'filler' => FILTER_DEFAULT, 'topping' => FILTER_DEFAULT, 'sauce' => FILTER_DEFAULT, 'crunch' => FILTER_DEFAULT, 'total-price' => FILTER_DEFAULT, 'proteinAdd' => FILTER_DEFAULT, 'fillerAdd' => FILTER_DEFAULT, 'toppingAdd' => FILTER_DEFAULT, 'sauceAdd' => FILTER_DEFAULT, 'crunchAdd' => FILTER_DEFAULT], true);

    // Список полей содеражащих массив
    $inputList = ['filler', 'topping', 'fillerAdd', 'toppingAdd'];

    // Форматирует поля в объекте $createdPoke
    foreach ($createdPoke as $key => $value) {
        if (in_array($key, $inputList)) {
            $createdPokeItem = $_POST[$key] ?? null;

            // Если значение отсутствует, удаляем поле из $createdPoke
            if (is_null($createdPokeItem)) {
                unset($createdPoke[$key]);
            } else {
                // Иначе, заменяем значение в $createdPoke на значение из $_POST
                $createdPoke[$key] = $createdPokeItem;
            }
        } else {
            // Если значение пустое, удаляем поле из $createdPoke
            if ($value == '') {
                unset($createdPoke[$key]);
            }
        }
    }

    // Проверка на валидность полей формы 
    foreach ($createdPoke as $key => $value) {
        if (in_array($key, $required) && empty($value)) {
            $fieldName = $uniqueComponentNames[$key] ?? null;

            if ($fieldName) {
                $errors[$key] = "Поле . $fieldName . должно быть заполено";
            }
        }

        switch ($key) {
            case 'shema':
                $errors['shema'] = $rules['shema']((int)$value);
                break;

            case 'filler':
            case 'topping':
                $errors[$key] = $rules['component-length']($key, $value, $createdPoke['shema'] ?? null);
                if (!$errors[$key]) {
                    $errors[$key] = $rules['component']($key, $value);
                }
                break;

            case 'total-price':
                $errors['total-price'] = $rules['total-price']($value);
                break;

            case 'proteinAdd':
            case 'fillerAdd':
            case 'toppingAdd':
            case 'sauceAdd':
            case 'crunchAdd':
                if ($value !== '') {
                    $newKey = ($key === 'proteinAdd') ? 'protein-add' : (($key === 'fillerAdd') ? 'filler' : (($key === 'toppingAdd') ? 'topping' : (($key === 'sauceAdd') ? 'sauce' : 'crunch')));
                    $errors[$key] = $rules['component']($newKey, $value);
                }
                break;

            default:
                $errors[$key] = $rules['component']($key, $value);
                break;
        }
    }

    // Фильтрует массив ошибок
    $errors = array_filter($errors);


    // Проверяет на наличие ошибок
    if (!empty($errors)) {
        $page_body = include_template(
            'constructor-poke.php',
            [
                'proteinList' => $proteinList,
                'proteinAddList' => $proteinAddList,
                'baseList' => $baseList,
                'fillerList' => $fillerList,
                'toppingList' => $toppingList,
                'sauceList' => $sauceList,
                'crunchList' => $crunchList,
                'errors' => $errors,
            ]
        );
    } else {
        // Генерируем уникальный идентификатор
        do {
            $order_id = uniqid();
            // Проверяем, существует ли уже такой идентификатор в базе данных
        } while (!checkUniquenessValue($con, $order_id, 'poke', 'poke_id'));


        // Формирует описание для Поке
        $pokeDescription = 'Основа: - ';

        foreach ($createdPoke as $key => $createdPokeItem) {
            if ($key == 'shema' || $key == 'total-price') {
                continue;
            }

            $sql = get_query_component_names(is_array($createdPokeItem) ? $createdPokeItem[0] : $createdPokeItem);
            $result = mysqli_query($con, $sql);
            $description = get_arrow($result);
            $componentTitle = $description['title'];

            if ($key == 'fillerAdd' || $key == 'toppingAdd') {
                $pokeDescription .= (!strpos($pokeDescription, 'Дополнительно') ? "Дополнительно: " : '') . $componentTitle . " - ";
            } elseif ($key == 'proteinAdd' || $key == 'sauceAdd' || $key == 'crunchAdd') {
                $pokeDescription .= (!strpos($pokeDescription, 'Дополнительно') ? "Дополнительно: " : '') . $componentTitle . " - ";
            } else {
                $pokeDescription .= $componentTitle . " - ";
            }
        }


        // Получает название Поке
        $pokeTitle = get_poke_title($con, $createdPoke['protein']);


        // Формирует объект для записи в таблицу Poke
        // $poke = [
        //     'title' => $pokeTitle,
        //     'img' => './poke-my-1.jpg',
        //     'description' => $pokeDescription,
        //     'price' => $createdPoke['total-price'],
        //     'cooking_time' => 40
        // ];


        // ========= Доделать =========

        // Формирует объект для записи в таблицу Poke
        $poke['title'] = $pokeTitle;
        $poke['img'] = './poke-my-1.jpg';
        $poke['description'] = $pokeDescription;
        $poke['price'] = $createdPoke['total-price'];
        $poke['cooking_time'] = 40;


        // Получает айди категории для авторского поке
        $sql = get_query_selected_category('constructor-poke');
        $result = mysqli_query($con, $sql);
        $categoryInfo = get_arrow($result);
        $poke['category_id'] = (int) $categoryInfo['id'];
        $poke['poke_id'] = $order_id;


        // Добавляет запись в таблицу Poke
        $sql = get_query_create_poke();
        $stmt = db_get_prepare_stmt($con, $sql, $poke);
        $addedPoke = mysqli_stmt_execute($stmt);
        $insertId = mysqli_insert_id($con);

        // Словарь для конструктора Поке
        $pokeDictionary = array();

        // Заполняет словарь данными из каких компонентов собрано Поке 
        foreach ($createdPoke as $key => $value) {
            // Пропускает лишние поля
            if ($key == 'shema' || $key == 'fillerAdd' || $key == 'toppingAdd' || $key == 'proteinAdd' || $key == 'sauceAdd' || $key == 'crunchAdd' || $key == 'total-price') {
                continue;
            }

            if ($key == 'filler' || $key == 'topping') {
                $keyAdd = $key . 'Add';
                if (isset($createdPoke[$keyAdd])) {
                    $combineArray = array_merge($createdPoke[$key], $createdPoke[$keyAdd]);
                    $combineArray = array_count_values($combineArray);
                    $pokeDictionary[$key] = $combineArray;
                    continue;
                }

                $pokeDictionary[$key] = array_count_values($value);
                continue;
            }

            if ($key == 'protein' || $key == 'sauce' || $key == 'crunch') {
                $keyAdd = $key . 'Add';

                if (isset($createdPoke[$keyAdd])) {
                    $createdPokeArr = [$createdPoke[$key]];
                    $createdPokeAddArr = [$createdPoke[$keyAdd]];

                    $combineArray = array_merge($createdPokeArr, $createdPokeAddArr);
                    $combineArray = array_count_values($combineArray);
                    $pokeDictionary[$key] = $combineArray;
                    continue;
                }

                $pokeDictionary[$key] = array_count_values([$createdPoke[$key]]);
                continue;
            }

            $pokeDictionary[$key] = array_count_values([$value]);
        }


        // Добавляет запись в таблицу Poke contains
        $getPokeContains = get_query_create_poke_contains();
        $pokeContains = [
            'poke_id' => $order_id,
            'component_id' => 0,
            'quantity' => 0,
        ];

        foreach ($pokeDictionary as $pokeDictionaryItem) {
            foreach ($pokeDictionaryItem as $key => $value) {
                $pokeContains['component_id'] = $key;
                $pokeContains['quantity'] = $value;

                $stmt = db_get_prepare_stmt($con, $getPokeContains, $pokeContains);
                $res = mysqli_stmt_execute($stmt);
            }
        }


        if ($addedPoke) {
            // echo $insertId;
            echo 'Заказ отправлен в базу';

            $tableName = 'poke';
            $productId = $insertId;
            $quantity = 1;
            addProductInSession($con, $tableName, $productId, $quantity);

            // header("Location: /order.php?orderId=$productId");
            // die;
        } else {
            echo 'Заказ незавершен в базу';
        }
    }
}



// ==== ШАБЛОНЫ ====
$page_head = include_template(
    'head.php',
    [
        'title' => 'Конструктор «Много рыбы»',
    ]
);

$page_header = include_template(
    'header.php',
    [
        'isAuth' => $isAuth,
        'userRole' => $userRole,
    ]
);

$page_footer = include_template(
    'footer.php',
    [
        'categoryList' => $categoryList,
    ]
);

$layout_content = include_template(
    'layout.php',
    [
        'head' => $page_head,
        'header' => $page_header,
        'main' => $page_body,
        'footer' => $page_footer,
    ]
);

print($layout_content);
