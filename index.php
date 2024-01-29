<?php

require_once('./functions/helpers.php');
require_once('./functions/init.php');
require_once('./functions/models.php');
require_once('./functions/db.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получите данные из запроса
    $productId = isset($_POST['productId']) ? $_POST['productId'] : null;
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : null;

    // Инициализируйте или обновите данные корзины в сессии
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Добавьте товар в корзину
    if (isset($_SESSION['cart'][$productId])) {
        // Удаление конкретного элемента из сессии
        if ($quantity <= 0) {
            unset($_SESSION['cart'][$productId]);
            return;
        }

        $_SESSION['cart'][$productId] = $quantity;
    } else {
        $_SESSION['cart'][$productId] = 1;
    }

    // Ответ сервера (может быть пустым или содержать информацию об успешном добавлении)
    echo 'Товар добавлен в корзину';
}


// Получение данных из сессии
$productsData = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
print_r($productsData);


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
