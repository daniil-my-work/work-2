<?php

require_once('./functions/helpers.php');
require_once('./functions/init.php');
require_once('./functions/models.php');
require_once('./functions/db.php');
require_once('./data/data.php');


// Максимальное кол-во строк
define("MAX_ROW", 5);
define("PAGINATION_LENGTH", 3);


// Проверка на авторизацию
if (!$isAuth || $_SESSION['user_role'] != $userRole['client']) {
    header("Location: ./auth.php");
    exit;
} 

// else if ($isAuth && $_SESSION['user_role'] == $userRole['admin']) {
//     header("Location: ./admin.php");
//     exit;
// } else if ($isAuth && $_SESSION['user_role'] == $userRole['owner']) {
//     header("Location: ./owner.php");
//     exit;
// }


// Получает список категорий меню 
$getСategories = get_query_categories();
$categories = mysqli_query($con, $getСategories);

if ($categories && mysqli_num_rows($categories) > 0) {
    $categoryList = get_arrow($categories);
} else {
    $categoryList = NULL;
}


// Получает данные о пользователе
$userEmail = $_SESSION['user_email'];
$sql = get_query_user_info($userEmail);
$result = mysqli_query($con, $sql);
$userInfo = get_arrow($result);


// Получает данные о заказах пользователя
$userId = $userInfo['id'];
$dateFirst = null;
$dateSecond = null;


// Формирует запрос с учетом указанного промежутка времени
if (isset($_SESSION['orderTime']) && isset($_SESSION['orderTime']['start']) && isset($_SESSION['orderTime']['end'])) {
    $dateFirst = $_SESSION['orderTime']['start'];
    $dateSecond = $_SESSION['orderTime']['end'];
    $sql = "SELECT orders.*, order_items.product_id, order_items.quantity, menu.title FROM orders LEFT JOIN order_items ON orders.order_id = order_items.order_id LEFT JOIN menu ON order_items.product_id = menu.id WHERE orders.customer_id = '$userId' AND orders.order_date BETWEEN '$dateFirst 00:00:00' AND '$dateSecond 23:59:59' ORDER BY orders.id DESC;";
} else {
    $sql = "SELECT orders.*, order_items.product_id, order_items.quantity, menu.title FROM orders LEFT JOIN order_items ON orders.order_id = order_items.order_id LEFT JOIN menu ON order_items.product_id = menu.id WHERE orders.customer_id = '$userId' ORDER BY orders.id DESC;";
}


// var_dump($sql);


// Получает данные о заказах пользователя за промежуток времени
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dateFirst = $_POST['date-first'];
    $dateSecond = $_POST['date-second'];

    $sql = "SELECT orders.*, order_items.product_id, order_items.quantity, menu.title FROM orders LEFT JOIN order_items ON orders.order_id = order_items.order_id LEFT JOIN menu ON order_items.product_id = menu.id WHERE orders.customer_id = '$userId' AND orders.order_date BETWEEN '$dateFirst 00:00:00' AND '$dateSecond 23:59:59' ORDER BY orders.id DESC;";

    // Присвоение данных сессии
    $_SESSION['orderTime']['start'] = $dateFirst;
    $_SESSION['orderTime']['end'] = $dateSecond;
}


// Получает все записи из таблицы Заказы
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
        } else {
            // Если результат запроса пуст
            $groupedItems = array();
            // $keys = array();
        }
    } elseif (mysqli_num_rows($result) == 1) {
        $orderInfo = get_arrow($result);

        if ($orderInfo) {
            // Массив для объединенных элементов
            $groupedItems = array();

            $orderId = $orderInfo['order_id'];
            $groupedItems[$orderId][] = $orderInfo;

            // $keys = array_keys($groupedItems);
        } else {
            // Если результат запроса пуст
            $groupedItems = array();
            // $keys = array();
        }
    } else {
        // Если результат запроса пуст
        $groupedItems = array();
        // $keys = array();
    }
}


// Кол-во записей
$groupedItemLength = count($groupedItems);
$paginationLength = ceil($groupedItemLength / MAX_ROW);

// Создаем массив чисел от 1 до $maxNumber
if ($groupedItemLength == 0) {
    $pagination[] = 0;
} else {
    $pagination = range(1, $paginationLength);
}


// Соберает все даты в отдельный массив
$allDates = [];
foreach ($groupedItems as $order) {
    foreach ($order as $item) {
        $allDates[] = $item['order_date'];
    }
}

// Cортирует массив дат по убыванию
rsort($allDates);

// Создает фильтрованный массив
$filteredList = [];

// Вставляет в массив отсортированные значения 
foreach ($groupedItems as $orderId => $orderItems) {
    foreach ($orderItems as $item) {
        $filteredList[$orderId][] = $item;
    }
}


// Текущая страница
$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
if ($currentPage == 1) {
    $startIndex = 1;
} else {
    $startIndex = ($currentPage - 1) * MAX_ROW;
}


// Получает список заказов пользователя для отрисовки
$orderList = array_slice($filteredList, $startIndex, MAX_ROW);
$keys = array_keys($orderList);



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
        'userRole' => $userRole,
    ]
);

$page_body = include_template(
    'account.php',
    [
        'userInfo' => $userInfo,
        'orderList' => $orderList,
        'dateFirst' => $dateFirst,
        'dateSecond' => $dateSecond,
        'pagination' => $pagination,
        'currentPage' => $currentPage,
        'keys' => $keys,
    ]
);

$page_footer = include_template(
    'footer.php',
    [
        'categoryList' => $categoryList,
    ]
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
