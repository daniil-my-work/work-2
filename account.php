<?php

require_once('./functions/init.php');
require_once('./functions/helpers.php');
require_once('./functions/models.php');
require_once('./functions/db.php');
require_once('./data/data.php');


// Проверка прав доступа
$sessionRole = $_SESSION['user_role'] ?? null;
$allowedRoles = [$userRole['client']];
checkAccess($isAuth, $sessionRole, $allowedRoles);


// Текущая страница
$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
$startIndex = ($currentPage == 1) ? 1 : ($currentPage - 1) * MAX_ROW;

// Список категорий меню
$categoryList = getCategories($con);

// Получает данные о пользователе
$userInfo = getUserInfo($con);

// Получает данные о заказах пользователя
$userId = $userInfo['id'];

// Инициализация переменных для временного промежутка
$dateFirst = $_SESSION['orderTime']['start'] ?? null;
$dateSecond = $_SESSION['orderTime']['end'] ?? null;


// Устанавливает отрезок время в сессию
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Если запрос POST, обновляем данные о временном промежутке в $_SESSION
    $dateFirst = $_POST['date-first'];
    $dateSecond = $_POST['date-second'];

    // Обновление SQL-запроса с новым временным промежутком
    $sql = get_query_user_order($userId, $dateFirst, $dateSecond);

    // Присвоение данных сессии
    $_SESSION['orderTime']['start'] = $dateFirst;
    $_SESSION['orderTime']['end'] = $dateSecond;
}


// Формирует запрос для получения данных из таблицы Заказы 
$sql = get_query_user_order($userId, $dateFirst, $dateSecond);

// Получает все записи из таблицы Состовляющие заказа и группирует их по айди 
$groupedItems = getGroupOrderItems($con, $sql);

// Создание пагинации
$pagination = generatePagination($groupedItems);

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
