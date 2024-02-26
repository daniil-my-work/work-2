<?php

require_once('./functions/helpers.php');
require_once('./functions/init.php');
require_once('./functions/models.php');
require_once('./functions/db.php');
require_once('./functions/validators.php');


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

// print_r($_SESSION['order']);


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
        }
    ];

    $createdPoke = filter_input_array(INPUT_POST, ['protein' => FILTER_DEFAULT, 'base' => FILTER_DEFAULT, 'shema' => FILTER_DEFAULT, 'filler' => FILTER_DEFAULT, 'topping' => FILTER_DEFAULT, 'sauce' => FILTER_DEFAULT, 'crunch' => FILTER_DEFAULT, 'total-price' => FILTER_DEFAULT, 'proteinAdd' => FILTER_DEFAULT, 'sauceAdd' => FILTER_DEFAULT, 'crunchAdd' => FILTER_DEFAULT], true);

    $toppingPostList = isset($_POST['topping']) ? $_POST['topping'] : null;
    if (!is_null($toppingPostList)) {
        $createdPoke['topping'] = $_POST['topping'];
    }

    $fillerPostList = isset($_POST['filler']) ? $_POST['filler'] : null;
    if (!is_null($fillerPostList)) {
        $createdPoke['filler'] = $_POST['filler'];
    }

    $fillerAddPostList = isset($_POST['fillerAdd']) ? $_POST['fillerAdd'] : null;
    if (!is_null($fillerAddPostList)) {
        $createdPoke['fillerAdd'] = $_POST['fillerAdd'];
    }

    $toppingAddPostList = isset($_POST['toppingAdd']) ? $_POST['toppingAdd'] : null;
    if (!is_null($toppingAddPostList)) {
        $createdPoke['toppingAdd'] = $_POST['toppingAdd'];
    }


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
        $poke['title'] = "Поке " . $pokeTitle[$createdPoke['protein']];
        $poke['img'] = './poke-my-1.jpg';
        $poke['price'] = $createdPoke['total-price'];


        // Словарь для конструктора Поке
        $poke['dictionary'] = array();

        foreach ($createdPoke as $createdPokeKey => $createdPokeValue) {
            // Обработка filler
            if ($createdPokeKey == 'filler') {
                if (isset($createdPoke["fillerAdd"])) {
                    $fillerArray = array_merge($createdPoke["filler"], $createdPoke["fillerAdd"]);
                    $fillerCount = array_count_values($fillerArray); // Получаем количество каждого уникального значения
                    $poke['dictionary'][$createdPokeKey] = $fillerCount;
                    continue;
                }

                $poke['dictionary'][$createdPokeKey] = $createdPokeValue;
            }

            // Обработка topping
            if ($createdPokeKey == 'topping') {
                if (isset($createdPoke['toppingAdd'])) {
                    $toppingArray = array_merge($createdPoke["topping"], $createdPoke["toppingAdd"]);
                    $toppingCount = array_count_values($toppingArray);
                    $poke['dictionary'][$createdPokeKey] = $toppingCount;
                    continue;
                }

                $poke['dictionary'][$createdPokeKey] = $createdPokeValue;
            }
        }


        print_r($poke['dictionary']);


        // $poke['description'] = '';
        // foreach ($poke['dictionary'] as $pokeItem => $pokeItemValue) {
        //     if (is_array($pokeItemValue)) {
        //         foreach ($pokeItemValue as $pokeSubItemKey => $pokeSubItemValue) {
        //             // $sql = get_query_componentTitle($pokeSubItemValue);
        //             // $pokeSubItemInfo = get_arrow(mysqli_query($con, $sql));

        //             // print_r($pokeSubItemValue);
        //             //     $poke['description'] .= $pokeSubItemInfo['component_name'] . ': ' . $pokeSubItemInfo['title'] . ', ';
        //         }

        //         continue;
        //     }

        //     // print_r($pokeItemValue);

        //     $sql = get_query_componentTitle($pokeItemValue);
        //     $result = mysqli_query($con, $sql);

        //     $pokeItemInfo = get_arrow($result);

        //     // $poke['description'] .= $pokeItemInfo['title'] . ', ';
        // }

        // print_r($poke['description']);



        // Удаляем последнюю запятую и добавляем точку в конце
        // $poke['description'] = rtrim($poke['description'], ', ') . '.';
        // print_r($poke['description']);

        // $sql = get_query_create_poke();
        // $stmt = db_get_prepare_stmt($con, $sql, $createdPoke);
        // $res = mysqli_stmt_execute($stmt);


        if ($res) {
            echo 'Заказ отправлен в базу';
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
