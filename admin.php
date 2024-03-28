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
if (!$isAuth || $_SESSION['user_role'] != $userRole['admin']) {
    header("Location: ./auth.php");
    exit;
}

// Получает список категорий меню 
$getСategories = get_query_categories();
$categories = mysqli_query($con, $getСategories);

if ($categories && mysqli_num_rows($categories) > 0) {
    $categoryList = get_arrow($categories);
} else {
    $categoryList = NULL;
}

// Определяет вкладку
$statisticGroup = isset($_GET['group']) ? $_GET['group'] : 'orders';


// Получает данные о пользователе
$userEmail = $_SESSION['user_email'];
$sql = get_query_user_info($userEmail);
$result = mysqli_query($con, $sql);
$userInfo = get_arrow($result);



// Получает данные о заказах пользователя
// $userId = $userInfo['id'];
$dateFirst = null;
$dateSecond = null;


// Формирует запрос с учетом указанного промежутка времени
if (isset($_SESSION['orderTime']) && isset($_SESSION['orderTime']['start']) && isset($_SESSION['orderTime']['end'])) {
    $dateFirst = $_SESSION['orderTime']['start'];
    $dateSecond = $_SESSION['orderTime']['end'];
    $sql = "SELECT orders.*, order_items.product_id, order_items.quantity, menu.title FROM orders LEFT JOIN order_items ON orders.order_id = order_items.order_id LEFT JOIN menu ON order_items.product_id = menu.id WHERE orders.order_date BETWEEN '$dateFirst 00:00:00' AND '$dateSecond 23:59:59' ORDER BY orders.id DESC;";
} else {
    $sql = "SELECT orders.*, order_items.product_id, order_items.quantity, menu.title FROM orders LEFT JOIN order_items ON orders.order_id = order_items.order_id LEFT JOIN menu ON order_items.product_id = menu.id ORDER BY orders.id DESC;";
}


// Получает данные о заказах пользователя за промежуток времени
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получает данные о заказах пользователя за промежуток времени
    $dateFirst = $_POST['date-first'];
    $dateSecond = $_POST['date-second'];

    $sql = "SELECT orders.*, order_items.product_id, order_items.quantity, menu.title FROM orders LEFT JOIN order_items ON orders.order_id = order_items.order_id LEFT JOIN menu ON order_items.product_id = menu.id WHERE orders.order_date BETWEEN '$dateFirst 00:00:00' AND '$dateSecond 23:59:59' ORDER BY orders.id DESC;";

    // Присвоение данных сессии
    $_SESSION['orderTime']['start'] = $dateFirst;
    $_SESSION['orderTime']['end'] = $dateSecond;
}

// print_r($sql);

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


// ------- не используется
// // Соберает все даты в отдельный массив
// $allDates = [];
// foreach ($groupedItems as $order) {
//     foreach ($order as $item) {
//         $allDates[] = $item['order_date'];
//     }
// }

// // Cортирует массив дат по убыванию
// rsort($allDates);
// ------- не используется



// Создает фильтрованный массив
$filteredListActive = [];
$filteredListСomplete = [];

// Вставляет в массив отсортированные значения по активным и завершенным заказам
foreach ($groupedItems as $orderId => $orderItems) {
    foreach ($orderItems as $item) {
        if ($item['date_end'] != null) {
            $filteredListСomplete[$orderId][] = $item;
        } else {
            $filteredListActive[$orderId][] = $item;
        }
    }
}



// Определяет длину пагинации
function getPaginationLength($arr)
{
    $groupedItemLength = count($arr);
    $paginationLength = ceil($groupedItemLength / MAX_ROW);

    // Создаем массив чисел от 1 до $maxNumber
    if ($groupedItemLength == 0) {
        return $pagination[] = 0;
    } else {
        return $pagination = range(1, $paginationLength);
    }
}

$paginationActive = getPaginationLength($filteredListActive);
$paginationСomplete = getPaginationLength($filteredListСomplete);


// Текущая страница для таблиц
$currentPageActive = isset($_GET['pageActive']) ? $_GET['pageActive'] : 1;
$currentPageСomplete = isset($_GET['pageСomplete']) ? $_GET['pageСomplete'] : 1;


// Вычисляем начальный индекс для активных заказов
$startIndexActive = ($currentPageActive - 1) * MAX_ROW;
// Вычисляем начальный индекс для завершенных заказов
$startIndexСomplete = ($currentPageСomplete - 1) * MAX_ROW;


// Получает список заказов пользователя для отрисовки
$orderListActive = array_slice($filteredListActive, $startIndexActive, MAX_ROW);
$orderListСomplete = array_slice($filteredListСomplete, $startIndexСomplete, MAX_ROW);

$keysActive = array_keys($orderListActive);
$keysСomplete = array_keys($orderListСomplete);




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
    'admin.php',
    [
        'userInfo' => $userInfo,
        'statisticGroup' => $statisticGroup,
        'dateFirst' => $dateFirst,
        'dateSecond' => $dateSecond,
        'paginationActive' => $paginationActive,
        'paginationСomplete' => $paginationСomplete,
        'currentPageActive' => $currentPageActive,
        'currentPageСomplete' => $currentPageСomplete,
        'orderListActive' => $orderListActive,
        'orderListСomplete' => $orderListСomplete,
        'keysActive' => $keysActive,
        'keysСomplete' => $keysСomplete,
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
