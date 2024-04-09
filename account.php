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


// Текущая страница
$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
$startIndex = ($currentPage == 1) ? 1 : ($currentPage - 1) * MAX_ROW;


// Получает список категорий меню 
$getСategories = get_query_categories();
$categories = mysqli_query($con, $getСategories);

// Список категорий меню 
$categoryList = mysqli_num_rows($categories) > 0 ? get_arrow($categories) : null;


// Получает данные о пользователе
$userEmail = $_SESSION['user_email'];
$sql = get_query_user_info($userEmail);
$result = mysqli_query($con, $sql);
$userInfo = get_arrow($result);


// Получает данные о заказах пользователя
$userId = $userInfo['id'];


// Инициализация переменных для временного промежутка
$dateFirst = $_SESSION['orderTime']['start'] ?? null;
$dateSecond = $_SESSION['orderTime']['end'] ?? null;


// Определение базового SQL-запроса
$sql = "SELECT orders.*, order_items.product_id, order_items.quantity, menu.title 
        FROM orders 
        LEFT JOIN order_items ON orders.order_id = order_items.order_id 
        LEFT JOIN menu ON order_items.product_id = menu.id 
        WHERE orders.customer_id = '$userId'";

// Проверка наличия данных о временном промежутке
if ($dateFirst !== null && $dateSecond !== null) {
    $sql .= " AND orders.order_date BETWEEN '$dateFirst 00:00:00' AND '$dateSecond 23:59:59'";
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Если запрос POST, обновляем данные о временном промежутке в $_SESSION
    $dateFirst = $_POST['date-first'];
    $dateSecond = $_POST['date-second'];

    // Обновление SQL-запроса с новым временным промежутком
    $sql .= " AND orders.order_date BETWEEN '$dateFirst 00:00:00' AND '$dateSecond 23:59:59'";

    // Присвоение данных сессии
    $_SESSION['orderTime']['start'] = $dateFirst;
    $_SESSION['orderTime']['end'] = $dateSecond;
}

// Завершение SQL-запроса
$sql .= " ORDER BY orders.id DESC;";


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
        }
    } elseif (mysqli_num_rows($result) == 1) {
        $orderInfo = get_arrow($result);

        if ($orderInfo) {
            // Массив для объединенных элементов
            $groupedItems = array();

            $orderId = $orderInfo['order_id'];
            $groupedItems[$orderId][] = $orderInfo;
        } else {
            // Если результат запроса пуст
            $groupedItems = array();
        }
    } else {
        // Если результат запроса пуст
        $groupedItems = array();
    }
}


// Получает кол-во записей
$groupedItemLength = count($groupedItems);
$paginationLength = ceil($groupedItemLength / MAX_ROW);

// Создаем массив чисел от 1 до $maxNumber
$pagination = ($groupedItemLength == 0) ? [0] : range(1, $paginationLength);


// Получает список заказов пользователя для отрисовки
$orderList = array_slice($groupedItems, $startIndex, MAX_ROW);
$keys = array_keys($orderList);


// ==== ШАБЛОНЫ ====
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
