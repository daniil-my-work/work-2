<?php

require_once('./functions/helpers.php');
require_once('./functions/init.php');
require_once('./functions/db.php');



// Данные о заказе
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
$sql = "SELECT order_items.product_id, order_items.quantity, menu.title, menu.img, menu.description FROM order_items 
LEFT JOIN menu
ON order_items.product_id = menu.id
WHERE order_items.order_id = '$orderId'";
$result = mysqli_query($con, $sql);
$orderItems = get_arrow($result);


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
