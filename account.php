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


// Получает данные о заказах пользователя
$userId = $userInfo['id'];
$sql = "SELECT orders.*, order_items.product_id, order_items.quantity, menu.title FROM orders LEFT JOIN order_items ON orders.order_id = order_items.order_id LEFT JOIN menu ON order_items.product_id = menu.id WHERE orders.customer_id = '$userId' ORDER BY orders.id DESC;";
$dateFirst = null;
$dateSecond = null;


// Получает данные о заказах пользователя за промежуток времени
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dateFirst = $_POST['date-first'];
    $dateSecond = $_POST['date-second'];

    $sql = "SELECT orders.*, order_items.product_id, order_items.quantity, menu.title FROM orders LEFT JOIN order_items ON orders.order_id = order_items.order_id LEFT JOIN menu ON order_items.product_id = menu.id WHERE orders.customer_id = '$userId' AND orders.order_date BETWEEN '$dateFirst 00:00:00' AND '$dateSecond 23:59:59'  ORDER BY orders.id DESC;";
}


$result = mysqli_query($con, $sql);

if ($result === false) {
    // Обработка ошибки выполнения запроса
    echo "Ошибка выполнения запроса: " . mysqli_error($con);
} else {
    if (mysqli_num_rows($result) > 1) {
        $orderInfo = get_arrow($result);

        if ($orderInfo) {
            // Массив для объединенных элементов
            $groupedItems = array();

            // Группирует заказы по айди заказа
            foreach ($orderInfo as $orderInfoItem) {
                $orderId = $orderInfoItem['order_id'];
                $groupedItems[$orderId][] = $orderInfoItem;
            }

            $keys = array_keys($groupedItems);
        } else {
            // Если результат запроса пуст
            $groupedItems = array();
            $keys = array();
        }
    } elseif (mysqli_num_rows($result) == 1) {
        $orderInfo = get_arrow($result);

        if ($orderInfo) {
            // Массив для объединенных элементов
            $groupedItems = array();

            $orderId = $orderInfo['order_id'];
            $groupedItems[$orderId][] = $orderInfo;

            $keys = array_keys($groupedItems);
        } else {
            // Если результат запроса пуст
            $groupedItems = array();
            $keys = array();
        }
    } else {
        // Если результат запроса пуст
        $groupedItems = array();
        $keys = array();
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
    'account.php',
    [
        'userInfo' => $userInfo,
        'groupedItems' => $groupedItems,
        'dateFirst' => $dateFirst,
        'dateSecond' => $dateSecond,
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
