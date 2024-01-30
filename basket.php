<?php

require_once('./functions/helpers.php');
require_once('./functions/init.php');
require_once('./functions/models.php');
require_once('./functions/db.php');


// Получение данных из сессии
$productsData = isset($_SESSION['order']) ? $_SESSION['order'] : array();


// $_SESSION['order']['order_id'] = 3;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ответ сервера (может быть пустым или содержать информацию об успешном добавлении)
    $order_id = uniqid();
    print_r($order_id);


    $order['customer_id'] = 33;
    $order['total_amount'] = 2000;
    $order['order_id'] = intval($order_id);

    $sql = get_query_create_order();
    $stmt = db_get_prepare_stmt($con, $sql, $order);
    $res = mysqli_stmt_execute($stmt);

    if ($res) {
        echo "Запись успешно добавлена в базу данных.";
    } else {
        echo "Ошибка при выполнении запроса: " . mysqli_error($con);
        echo "Номер ошибки: " . mysqli_errno($con);
    }

    // if ($isAuth) {
    //     // echo $productsData;
    //     echo 'Товар добавлен в корзину';
    //     // return;
    // }

    // header("Location: ./index.php");
}


// Получает список продуктов для отрисовки в корзине
$productIds = array_keys($productsData);
$sql = get_query_productList($productIds);
$products = mysqli_query($con, $sql);
$productList = get_arrow($products);


// Итого стоимость
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
