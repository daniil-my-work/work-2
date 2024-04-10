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


// Список категорий меню
$categoryList = getCategories($con);

// Получает данные о пользователе
$userInfo = getUserInfo($con);


// Определяет вкладку
$statisticGroup = isset($_GET['group']) ? $_GET['group'] : 'orders';


// Значение по умолчанию
// -- Вкладка: Поиск заказа
$searchValue = null;
$paginationSearch = null;
$currentPageSearch = null;
$orderListSearch = null;
$keysSearch = null;

// -- Вкладка: Клиенты
$phoneValue = null;
$paginationUser = null;
$currentPageUser = null;
$userListFormatted = null;
$userListLength = null;

// -- Вкладка: Заказы
$dateFirst = null;
$dateSecond = null;
$paginationActive = null;
$paginationСomplete = null;
$currentPageActive = null;
$currentPageСomplete = null;
$orderListActive = null;
$orderListСomplete = null;
$keysActive = null;
$keysСomplete = null;



// Функция для получения списка заказов
function getGroupItems($con, $sql)
{
    $result = mysqli_query($con, $sql);

    if ($result === false) {
        // Обработка ошибки выполнения запроса
        echo "Ошибка выполнения запроса: " . mysqli_error($con);
        return [];
    } else {
        $groupedItems = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $orderId = $row['order_id'];
            $groupedItems[$orderId][] = $row;
        }
        return $groupedItems;
    }
}

// Функция для получения списка 
function getResultAsArray($result)
{
    $data = [];
    if ($result !== false) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }
    return $data;
}


// Функция для создания пагинации
function generatePagination($groupedItems)
{
    $groupedItemLength = count($groupedItems);
    $paginationLength = ceil($groupedItemLength / MAX_ROW);

    // Создаем массив чисел от 1 до $paginationLength
    return $paginationLength > 0 ? range(1, $paginationLength) : [0];
}



if ($statisticGroup === 'search') {

    // Получает данные: айди заказа для поиска в базе
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $searchValue = isset($_POST['order-id']) ? $_POST['order-id'] : null;

        // Присвоение данных сессии
        $_SESSION['searchValue'] = $searchValue;
    } elseif (isset($_SESSION['searchValue'])) {
        $searchValue = $_SESSION['searchValue'];
    } else {
        $searchValue = null;
    }

    // Формирует SQL-запрос для поиска заказа по Айди 
    $sql = get_query_search_order_by_id($searchValue);

    // Выполнение запроса и обработка результата
    $groupedItems = getGroupItems($con, $sql);

    // Создание пагинации
    $paginationSearch = generatePagination($groupedItems);

    // Определение текущей страницы
    $currentPageSearch = isset($_GET['pageSearch']) ? $_GET['pageSearch'] : 1;

    // Вычисление начального индекса для пагинации
    $startIndexSearch = ($currentPageSearch - 1) * MAX_ROW;

    // Получение списка заказов для отрисовки
    $orderListSearch = array_slice($groupedItems, $startIndexSearch, MAX_ROW);

    // Формирование списка ключей для итерации
    $keysSearch = array_keys($orderListSearch);
} elseif ($statisticGroup === 'clients') {

    // Получает данные: номер телефона для поиска в базе
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $phoneValue = isset($_POST['user-phone']) ? $_POST['user-phone'] : null;

        // Присвоение данных сессии
        $_SESSION['phoneValue'] = $phoneValue;
    } elseif (isset($_SESSION['phoneValue'])) {
        $phoneValue = $_SESSION['phoneValue'];
    } else {
        $phoneValue = null;
    }

    // Формирует SQL-запрос для клиента по номеру телефона
    $sql = get_query_search_clients_by_phone($phoneValue);
    $result = mysqli_query($con, $sql);

    // Список юзеров
    $userList = getResultAsArray($result);
    $userListLength = count($userList);

    // Создание пагинации
    $paginationSearch = generatePagination($userList);

    // Определение текущей страницы
    $currentPageSearch = isset($_GET['pageSearch']) ? $_GET['pageSearch'] : 1;

    // Вычисление начального индекса для пагинации
    $startIndexSearch = ($currentPageSearch - 1) * MAX_ROW;

    // Получение списка заказов для отрисовки
    $userListFormatted = array_slice($userList, $startIndexSearch, MAX_ROW);

    // Формирование списка ключей для итерации
    $keysSearch = array_keys($userListFormatted);
} else {

    // Определение значений $dateFirst и $dateSecond на основе сессии или POST-запроса
    $dateFirst = isset($_SESSION['orderTime']['start']) ? $_SESSION['orderTime']['start'] : (isset($_POST['date-first']) ? $_POST['date-first'] : null);
    $dateSecond = isset($_SESSION['orderTime']['end']) ? $_SESSION['orderTime']['end'] : (isset($_POST['date-second']) ? $_POST['date-second'] : null);


    // Формирование SQL запроса
    $sql = "SELECT orders.*, order_items.product_id, order_items.quantity, menu.title 
        FROM orders 
        LEFT JOIN order_items ON orders.order_id = order_items.order_id 
        LEFT JOIN menu ON order_items.product_id = menu.id";

    // Добавление условия по дате, если она задана
    if ($dateFirst && $dateSecond) {
        $sql .= " WHERE orders.order_date BETWEEN '$dateFirst 00:00:00' AND '$dateSecond 23:59:59'";
    }

    $sql .= " ORDER BY orders.id DESC;";

    // Получает все записи из таблицы Заказы
    $result = mysqli_query($con, $sql);

    // Выполнение запроса и обработка результата
    $groupedItems = getGroupItems($con, $sql);


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

    // Формирует список ключей для итерации 
    $keysActive = array_keys($orderListActive);
    $keysСomplete = array_keys($orderListСomplete);
}



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
    'admin.php',
    [
        'userInfo' => $userInfo,
        'statisticGroup' => $statisticGroup,
        // Поиск
        'searchValue' => $searchValue,
        'paginationSearch' => $paginationSearch,
        'currentPageSearch' => $currentPageSearch,
        'orderListSearch' => $orderListSearch,
        'keysSearch' => $keysSearch,
        // Заказы
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
        // Поиск
        'phoneValue' => $phoneValue,
        'paginationUser' => $paginationUser,
        'currentPageUser' => $currentPageUser,
        'userListFormatted' => $userListFormatted,
        'userListLength' => $userListLength,
    ]
);



// ==== ШАБЛОНЫ ====
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
