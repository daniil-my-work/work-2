<?php

require_once('./functions/helpers.php');
require_once('./functions/init.php');
require_once('./functions/models.php');
require_once('./functions/db.php');
require_once('./functions/formatter.php');
require_once('./data/data.php');


// Получение данных из сессии
$productsData = isset($_SESSION['order']) ? $_SESSION['order'] : array();

$productsDataMenu = array();
if (isset($productsData['menu'])) {
    $productsDataMenu = $productsData['menu'];
}


// Список категорий меню
$categoryList = getCategories($con);

// Получает активную категорию
$activeCategory = isset($_GET['category']) ? $_GET['category'] : 'rolls';

// Получает данные о выбранной категории
$categoryName = fetchCategoryName($con, $activeCategory);

// Получает список продуктов по выбранной категории 
$productList = getProductsByCategory($con, $activeCategory);


// ==== ШАБЛОНЫ ====
$page_head = include_template(
    'head.php',
    [
        'title' => 'poke-room «Много рыбы»',
    ]
);

$page_header = include_template(
    'header.php',
    [
        'isAuth' => $isAuth,
        'userRole' => $userRole,
    ]
);

$page_body = include_template(
    'menu.php',
    [
        'productsData' => $productsDataMenu,
        'products' => $productList,
        'categoryList' => $categoryList,
        'activeCategory' => $activeCategory,
        'categoryName' => $categoryName,
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
