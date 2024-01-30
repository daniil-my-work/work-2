<?php

require_once('./functions/helpers.php');
require_once('./functions/init.php');
require_once('./functions/models.php');
require_once('./functions/db.php');


// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     // Ответ сервера (может быть пустым или содержать информацию об успешном добавлении)
//     echo 'Товар добавлен в корзину';
// }


// Получение данных из сессии
$productsData = isset($_SESSION['order']) ? $_SESSION['order'] : array();
print_r($productsData);


// Получает активную категорию
$activeCategory = isset($_GET['category']) ? $_GET['category'] : 'rolls';

// Получает список категорий меню 
$sql = get_query_categories();
$categories = mysqli_query($con, $sql);
$categoryList = get_arrow($categories);


$sql1 = get_query_selectedCategory($activeCategory);
$category = mysqli_query($con, $sql1);
$categoryName = get_arrow($category);


// Получает список продуктов по выбранной категории 
$sql = get_query_selectedProducts($activeCategory);
$products = mysqli_query($con, $sql);
$productList = get_arrow($products);


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
        'productsData' => $productsData,
        'products' => $productList,
        'categoryList' => $categoryList,
        'activeCategory' => $activeCategory,
        'categoryName' => $categoryName,
    ]
);

$page_footer = include_template(
    'footer.php',
    []
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
