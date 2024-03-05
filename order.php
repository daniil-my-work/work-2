<?php

require_once('./functions/helpers.php');
require_once('./functions/init.php');
require_once('./functions/db.php');


print_r($_SESSION['order']);

// Данные об айди заказа
$getOrderId = isset($_GET['orderId']) ? $_GET['orderId'] : null;

if (is_null($getOrderId)) {
    header("Location: ./index.php");
    return;
}

// Получает данные о конкретном заказе по id заказа
$sql = "SELECT * FROM orders WHERE orders.customer_id = '$userId' AND orders.order_id = '$getOrderId'";
$result = mysqli_query($con, $sql);
$orderInfo = get_arrow($result);


// Данные о товарах в заказе
$orderId = $orderInfo['order_id'];
$sql = "SELECT order_items.product_id, order_items.tableName FROM order_items WHERE order_items.order_id = '$orderId'";
$result = mysqli_query($con, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $orderItems = get_arrow($result);
} else {
    $orderItems = null;
}

// unset($_SESSION['order']);
// print_r($_SESSION['order']);


// print_r($orderId);
print_r($orderItems);


$productList = array();
foreach ($orderItems as $orderItem) {
    $productId = $orderItem['product_id'];
    $table = $orderItem['tableName'];

    if ($table == 'menu') {
        $sql = "SELECT order_items.product_id, order_items.quantity, order_items.unit_price, menu.title, menu.img, menu.description, menu.category_id 
                FROM order_items 
                LEFT JOIN menu ON order_items.product_id = menu.id
                WHERE menu.id = '$productId' AND order_items.order_id = '$orderId'";
    } else {
        $sql = "SELECT order_items.product_id, order_items.quantity, order_items.unit_price, poke.title, menu.img, poke.description, poke.category_id 
        FROM order_items
        LEFT JOIN poke ON order_items.product_id = poke.id
        WHERE order_items.product_id = '$productId' AND order_items.order_id = '$orderId'";
    }


    $result = mysqli_query($con, $sql);

    if ($result) {
        $productInfo = get_arrow($result);
    }

    $productList[] = $productInfo;
}

// print_r($orderItems);
// print_r($productList);


// Список продуктов это массив
$isArrayOrderItems = true;

foreach ($orderItems as $orderItem) {
    if (!is_array($orderItem)) {
        $isArrayOrderItems = false;
        break;
    }
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
    'order.php',
    [
        'orderInfo' => $orderInfo,
        'orderItems' => $orderItems,
        'isArrayOrderItems' => $isArrayOrderItems,
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
