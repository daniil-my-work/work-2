<?php

require_once('./functions/helpers.php');
require_once('./functions/init.php');
require_once('./functions/models.php');
require_once('./functions/db.php');
require_once('./functions/formatter.php');


// Получение данных из сессии
$productsData = isset($_SESSION['order']) ? $_SESSION['order'] : array();
$productsDataMenu = $productsData['menu'];


// Получает список категорий меню 
$getСategories = get_query_categories();
$categories = mysqli_query($con, $getСategories);

if ($categories && mysqli_num_rows($categories) > 0) {
    $categoryList = get_arrow($categories);
} else {
    $categoryList = NULL;
}


// Получает активную категорию
$activeCategory = isset($_GET['category']) ? $_GET['category'] : 'rolls';


// Получает данные о выбранной категории
$getSelectedCategory = get_query_selected_category($activeCategory);
$category = mysqli_query($con, $getSelectedCategory);

if ($category && mysqli_num_rows($category) > 0) {
    $categoryName = get_arrow($category);
} else {
    $categoryName = NULL;
}


// Получает список продуктов по выбранной категории 
$getProductsByCategory = get_query_selected_products($activeCategory);
$products = mysqli_query($con, $getProductsByCategory);

if ($products && mysqli_num_rows($products) > 0) {
    $productList = get_arrow($products);
} else {
    $productList = NULL;
}


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
