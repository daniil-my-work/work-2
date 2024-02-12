<?php

require_once('./functions/helpers.php');
require_once('./functions/init.php');
require_once('./functions/models.php');
require_once('./functions/db.php');



// Получает список категорий меню 
$sql = get_query_components();
$components = mysqli_query($con, $sql);
$componentList = get_arrow($components);


// print_r($componentList);

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

// print_r($proteinAddList);


// Получает список категорий меню 
$sql = get_query_categories();
$categories = mysqli_query($con, $sql);
$categoryList = get_arrow($categories);


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
