<?php

require_once('./functions/helpers.php');
require_once('./functions/init.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получите данные из запроса
    $productId = isset($_POST['productId']) ? $_POST['productId'] : null;
    $action = isset($_POST['action']) ? $_POST['action'] : null;

    // Инициализируйте или обновите данные корзины в сессии
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Добавьте товар в корзину
    if (isset($_SESSION['cart'][$productId])) {
        $quantity = $_SESSION['cart'][$productId];

        if ($action === 'minus') {
            $newValue = $quantity - 1;
        }

        $_SESSION['cart'][$productId] = $newValue;
    } else {
        $_SESSION['cart'][$productId] = 1;
    }

    // Ответ сервера (может быть пустым или содержать информацию об успешном добавлении)
    echo 'Товар добавлен в корзину';
}


// Получение данных из сессии
$productsData = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
print_r($productsData);


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
