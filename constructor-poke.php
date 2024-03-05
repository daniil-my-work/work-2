<?php

require_once('./functions/helpers.php');
require_once('./functions/init.php');
require_once('./functions/models.php');
require_once('./functions/db.php');
require_once('./functions/validators.php');


print_r($_SESSION['order']);


// Получает список категорий меню 
$sql = get_query_components();
$components = mysqli_query($con, $sql);
$componentList = get_arrow($components);


// Массив для хранения уникальных ключей component_type
$uniqueComponentTypes = array();

// Проходим по массиву и извлекаем уникальные значения ключа component_type
foreach ($componentList as $item) {
    if (!in_array($item['component_type'], $uniqueComponentTypes)) {
        $uniqueComponentTypes[] = $item['component_type'];
    }
}


// Массив для хранения уникальных ключей component_type
$sortComponentList = array();

// Функция обратного вызова для фильтрации
function filterByComponentType($item, $uniqueType)
{
    return $item['component_type'] === $uniqueType;
}

foreach ($uniqueComponentTypes as $uniqueType) {
    // Фильтрация массива
    $sortComponentList[$uniqueType] = array_filter($componentList, function ($item) use ($uniqueType) {
        return filterByComponentType($item, $uniqueType);
    });
}

$proteinList = $sortComponentList['protein'];
$baseList = $sortComponentList['base'];
$fillerList = $sortComponentList['filler'];
$toppingList = $sortComponentList['topping'];
$sauceList = $sortComponentList['sauce'];
$crunchList = $sortComponentList['crunch'];
$proteinAddList = $sortComponentList['protein-add'];


// Получает список категорий меню 
$sql = get_query_categories();
$categories = mysqli_query($con, $sql);
$categoryList = get_arrow($categories);



// Массив для хранения уникальных ключей component_type
$uniqueComponentNames = array();

// Проходим по массиву и извлекаем уникальные значения ключа component_type
foreach ($componentList as $item) {
    if (!in_array($item['component_name'], $uniqueComponentNames)) {
        $uniqueComponentNames[$item['component_type']] = $item['component_name'];
    }
}


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
    ]
);

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


$pokeTitle = [
    '1' => 'c кальмаром',
    '2' => 'c коктельными креветками',
    '3' => 'c крабом',
    '4' => 'c курицей',
    '5' => 'c лососем',
    '6' => 'c тофу',
    '7' => 'c тунцом',
    '8' => 'c угрем',
    '9' => 'cо свининой',
    '10' => 'c морским гребешком',
    '11' => 'c лососем-тунцом',
    '12' => 'c креветкой',
    '13' => 'c морским миксом',
    '14' => 'c телятиной',
];



if ($_SERVER['REQUEST_METHOD'] === "POST") {
    // Обязательные поля для заполненения 
    $required = ['shema', 'protein', 'base', 'filler', 'topping', 'sauce', 'crunch', 'total-price'];
    $errors = [];

    $rules = [
        'component' => function ($key, $value) use ($con) {
            return validate_component($con, $key, $value);
        },
        'shema' => function ($value) {
            return !in_array($value, [1, 2]) ? 'Указана неверная схема для наполнителя и топпинга' : null;
        },
        'total-price' => function ($value) {
            return $value < 0 ? 'Вы ничего не выбрали' : null;
        },
        'component-length' => function ($name, $value, $shema) {
            return validate_component_length($name, $value, $shema);
        },
    ];

    $createdPoke = filter_input_array(INPUT_POST, ['protein' => FILTER_DEFAULT, 'base' => FILTER_DEFAULT, 'shema' => FILTER_DEFAULT, 'filler' => FILTER_DEFAULT, 'topping' => FILTER_DEFAULT, 'sauce' => FILTER_DEFAULT, 'crunch' => FILTER_DEFAULT, 'total-price' => FILTER_DEFAULT, 'proteinAdd' => FILTER_DEFAULT, 'fillerAdd' => FILTER_DEFAULT, 'toppingAdd' => FILTER_DEFAULT, 'sauceAdd' => FILTER_DEFAULT, 'crunchAdd' => FILTER_DEFAULT], true);

    // Список полей содеражащих массив
    $inputList = ['filler', 'topping', 'fillerAdd', 'toppingAdd'];

    // Форматирует поля в объекте $createdPoke
    foreach ($createdPoke as $key => $value) {
        if (in_array($key, $inputList)) {
            $createdPokeItem = isset($_POST[$key]) ? $_POST[$key] : null;

            if (is_null($createdPokeItem)) {
                unset($createdPoke[$key]);
                continue;
            }

            $createdPoke[$key] = $createdPokeItem;
            continue;
        }

        if ($value == '') {
            unset($createdPoke[$key]);
            continue;
        }
    }

    // Проверка на валидность полей формы 
    foreach ($createdPoke as $key => $value) {
        if (in_array($key, $required) && empty($value)) {
            $fieldName = $uniqueComponentNames[$key];
            $errors[$key] = "Поле . $fieldName . должно быть заполено";
        }

        if ($key == 'shema') {
            $shemaId = (int)$value;
            $rule = $rules['shema'];
            $errors['shema'] = $rule($shemaId);
            continue;
        }

        if ($key == 'filler' || $key == 'topping') {
            $rule = $rules['component-length'];
            $errors[$key] = $rule($key, $value, $createdPoke['shema']);

            if (!$errors[$key]) {
                // Проверка наличия компонента в Поке
                $ruleSecond = $rules['component'];
                $errors[$key] = $ruleSecond($key, $value);
            }

            continue;
        }

        if ($key == 'total-price') {
            $rule = $rules['total-price'];
            $errors['total-price'] = $rule($value);
            continue;
        }

        $isAddComponent = $key == 'proteinAdd' || $key == 'fillerAdd' || $key == 'toppingAdd' || $key == 'sauceAdd' || $key == 'crunchAdd';
        if ($isAddComponent && $value != '') {

            $newKey = '';
            if ($key === 'proteinAdd') {
                $newKey = 'protein-add';
            } elseif ($key === 'fillerAdd') {
                $newKey = 'filler';
            } elseif ($key === 'toppingAdd') {
                $newKey = 'topping';
            } elseif ($key === 'sauceAdd') {
                $newKey = 'sauce';
            } else {
                $newKey = 'crunch';
            }

            $rule = $rules['component'];
            $errors[$key] = $rule($newKey, $value);

            continue;
        }

        if ($isAddComponent && $value == '') {
            continue;
        }

        // Проверка на наличие компонента в Поке
        $rule = $rules['component'];
        $errors[$key] = $rule($key, $value);
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


        $pokeDescription = 'Основа: - ';
        // Заполняет словарь данными из каких компонентов собрано Поке 
        foreach ($createdPoke as $key => $createdPokeItem) {
            // Пропускает лишние поля
            if ($key == 'shema' || $key == 'total-price') {
                continue;
            }

            if (is_array($createdPokeItem)) {
                if ($key == 'fillerAdd' || $key == 'toppingAdd') {
                    if (!strpos($pokeDescription, 'Дополнительно')) {
                        $pokeDescription .= "Дополнительно: ";
                    }

                    foreach ($createdPokeItem as $value) {
                        $sql = get_query_componentName($value);
                        $result = mysqli_query($con, $sql);
                        $description = get_arrow($result);
                        $pokeDescription .= $description['title'] . " - ";
                    }
                    continue;
                }

                foreach ($createdPokeItem as $value) {
                    $sql = get_query_componentName($value);
                    $result = mysqli_query($con, $sql);
                    $description = get_arrow($result);
                    $pokeDescription .= $description['title'] . " - ";
                }
            } else {
                $sql = get_query_componentName($createdPokeItem);
                $result = mysqli_query($con, $sql);
                $description = get_arrow($result);

                if ($key == 'proteinAdd' || $key == 'sauceAdd' || $key == 'crunchAdd') {
                    if (!strpos($pokeDescription, 'Дополнительно')) {
                        $pokeDescription .= "Дополнительно: ";
                    }

                    $pokeDescription .= $description['title'] . " - ";
                    continue;
                }

                $pokeDescription .= $description['title'] . " - ";
            }
        }


        // Формирует объект для записи в таблицу Poke
        $poke['title'] = "Поке " . $pokeTitle[$createdPoke['protein']];
        $poke['img'] = './poke-my-1.jpg';
        $poke['description'] = $pokeDescription;
        $poke['price'] = $createdPoke['total-price'];
        $poke['cooking_time'] = 40;

        // Получает айди категории для авторского поке
        $sql = get_query_selectedCategory('constructor-poke');
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
            addProductInSession($tableName, $productId, $quantity);

            // header("Location: /order.php?orderId=$productId");
            // die;
        } else {
            echo 'Заказ незавершен в базу';
        }
    }
}




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
