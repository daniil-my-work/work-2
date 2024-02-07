<?php

require_once('./functions/helpers.php');
require_once('./functions/init.php');
require_once('./functions/models.php');
require_once('./functions/db.php');


// Получение данных из сессии
$productsData = isset($_SESSION['order']) ? $_SESSION['order'] : array();


// Получает список продуктов
$sql = get_query_products();
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
    'main.php',
    [
        'productsData' => $productsData,
        'products' => $productList,
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
