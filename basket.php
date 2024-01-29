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

// Получает список продуктов для отрисовки в корзине
$productIds = array_keys($productsData);
$sql = get_query_productList($productIds);
$products = mysqli_query($con, $sql);
$productList = get_arrow($products);
// print_r($productList);

$fullPrice = 0;

// Перебираем продукты из $productList
foreach ($productList as $product) {
    $productId = $product['id']; // Получаем ID продукта
    $quantity = $productsData[$productId]; // Получаем количество продукта из $productsData
    $price = $product['price']; // Получаем цену продукта

    // Умножаем цену продукта на его количество и добавляем к общей стоимости
    $fullPrice += $price * $quantity;
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
    'basket.php',
    [
        'productsData' => $productsData,
        'products' => $productList,
        'fullPrice' => $fullPrice,
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
