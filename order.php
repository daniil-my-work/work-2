<?php

require_once('./functions/helpers.php');
require_once('./functions/init.php');
require_once('./functions/db.php');



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

print_r($orderItems);


$productList = array();
foreach ($orderItems as $orderItem) {
    $table = $orderItem['tableName'];
    $productId = $orderItem['product_id'];

    if ($table == 'menu') {
        $sql = "SELECT order_items.product_id, order_items.quantity, order_items.unit_price, order_items.tableName, menu.title, menu.img, menu.description, menu.category_id FROM order_items 
            LEFT JOIN menu
            ON order_items.product_id = menu.id
            WHERE order_items.order_id = '$productId'";
    } else {
        $sql = "SELECT order_items.product_id, order_items.quantity, order_items.unit_price, order_items.tableName, * FROM order_items 
            LEFT JOIN poke_consists
            ON order_items.product_id = poke_consists.id
            LEFT JOIN components
            ON poke_consists.component_id = components.id
            WHERE order_items.order_id = '$productId'";
    }

    $result = mysqli_query($con, $sql);
    $productInfo = get_arrow($result);
    $productList[] = $productInfo;
}


// $sql = "SELECT order_items.product_id, order_items.quantity, order_items.unit_price, order_items.tableName, * FROM order_items 
// LEFT JOIN menu
// ON order_items.product_id = menu.id
// WHERE order_items.order_id = '$orderId'";
// $result = mysqli_query($con, $sql);
// $orderItems = get_arrow($result);


print_r($orderItems);
print_r($productList);


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
