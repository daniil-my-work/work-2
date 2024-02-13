<?php

require_once('./functions/helpers.php');
require_once('./functions/init.php');
require_once('./functions/models.php');
require_once('./functions/db.php');





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



if ($_SERVER['REQUEST_METHOD'] === "POST") {
    // Обязательные поля для заполненения 
    $required = ['protein', 'base', 'shema', 'filler', 'topping', 'sauce', 'crunch'];
    $errors = [];

    $rules = [
        'component' => function ($value) use ($uniqueComponentTypes) {
            return validate_component($value, $uniqueComponentTypes);
        },
        'shema' => function ($value) {
            return in_array((int)$value, [1, 2]);
        }
    ];

    // var_dump($_POST['shema']);

    $createdPoke = filter_input_array(INPUT_POST, ['protein' => FILTER_DEFAULT, 'base' => FILTER_DEFAULT, 'shema' => FILTER_DEFAULT, 'filler' => FILTER_DEFAULT, 'topping' => FILTER_DEFAULT, 'sauce' => FILTER_DEFAULT, 'crunch' => FILTER_DEFAULT, 'proteinAdd' => FILTER_DEFAULT, 'sauceAdd' => FILTER_DEFAULT, 'crunchAdd' => FILTER_DEFAULT], true);

    $toppingPostList = isset($_POST['topping']) ? $_POST['topping'] : null;
    if (!is_null($toppingPostList)) {
        $createdPoke['topping'] = $_POST['topping'];
    }

    $fillerPostList = isset($_POST['filler']) ? $_POST['filler'] : null;
    if (!is_null($fillerPostList)) {
        $createdPoke['filler'] = $_POST['filler'];
    }

    $toppingAddPostList = isset($_POST['toppingAdd']) ? $_POST['toppingAdd'] : null;
    if (!is_null($toppingAddPostList)) {
        $createdPoke['toppingAdd'] = $_POST['toppingAdd'];
    }

    $fillerAddPostList = isset($_POST['fillerAdd']) ? $_POST['fillerAdd'] : null;
    if (!is_null($fillerAddPostList)) {
        $createdPoke['fillerAdd'] = $_POST['fillerAdd'];
    }

    print_r($createdPoke);

    foreach ($createdPoke as $key => $value) {
        if (in_array($key, $required) && empty($value)) {
            $fieldName = $uniqueComponentNames[$key];
            $errors[$key] = "Поле . $fieldName . должно быть заполено";
        }

        if ($key == 'shema') {
            $errors['shema'] = 'Указана неверная схема для наполнителя и топпинга';
        }
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
