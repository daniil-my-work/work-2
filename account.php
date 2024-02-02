<?php

require_once('./functions/helpers.php');
require_once('./functions/init.php');
require_once('./functions/models.php');
require_once('./functions/db.php');


// Проверка на авторизацию
if (!$isAuth) {
    header("Location: ./auth.php");
    exit;
}

// Получает данные о пользователе
$userEmail = $_SESSION['user_email'];
$sql = get_query_userInfo($userEmail);
$result = mysqli_query($con, $sql);
$userInfo = get_arrow($result);


// Данные о заказе
$userId = $userInfo['id'];
$sql = "SELECT * FROM orders WHERE orders.customer_id = '$userId' ORDER BY id DESC";
$sql = "SELECT orders.*, order_items.product_id, order_items.quantity, menu.title FROM orders LEFT JOIN order_items ON orders.order_id = order_items.order_id LEFT JOIN menu ON order_items.product_id = menu.id WHERE orders.customer_id = '$userId' ORDER BY orders.id DESC;";
$result = mysqli_query($con, $sql);


if ($result) {
    $orderInfo = get_arrow($result);

    // Массив для объединенных элементов
    $groupedItems = array();

    // Группирует заказы по айди заказа
    foreach ($orderInfo as $orderInfoItem) {
        $orderId = $orderInfoItem['order_id'];
        $groupedItems[$orderId][] = $orderInfoItem;
    }

    $keys = array_keys($groupedItems);
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
    'account.php',
    [
        'userInfo' => $userInfo,
        'groupedItems' => $groupedItems,
        'keys' => $keys,
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
