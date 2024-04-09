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

// Список категорий меню 
$categoryList = mysqli_num_rows($categories) > 0 ? get_arrow($categories) : null;


// Получает данные о пользователе
$userEmail = $_SESSION['user_email'];
$sql = get_query_user_info($userEmail);
$result = mysqli_query($con, $sql);
$userInfo = get_arrow($result);


// Определяет вкладку
$statisticGroup = isset($_GET['group']) ? $_GET['group'] : 'orders';


// Устанавливает значение по умолчанию
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





// Функция для формирования SQL-запроса
function generateSearchQuery($searchValue)
{
    $sql = "SELECT orders.*, order_items.product_id, order_items.quantity, menu.title, user.user_name FROM orders 
           LEFT JOIN order_items ON orders.order_id = order_items.order_id 
           LEFT JOIN menu ON order_items.product_id = menu.id 
           LEFT JOIN user ON orders.customer_id = user.id
           WHERE orders.order_id LIKE '%$searchValue%'";

    return $sql;
}


// Функция для выполнения SQL-запроса и обработки результата
function executeSearchQuery($con, $sql)
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

    // Формирование SQL-запроса
    $sql = generateSearchQuery($searchValue);

    // Выполнение запроса и обработка результата
    $groupedItems = executeSearchQuery($con, $sql);

    // Создание пагинации
    $paginationSearch = generatePagination($groupedItems);



    // // Формирует запрос с учетом указанного промежутка времени
    // if (isset($_SESSION['searchValue'])) {
    //     $searchValue = $_SESSION['searchValue'];
    // }

    // // Получает данные: айди заказа для поиска в базе
    // if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //     $searchValue = isset($_POST['order-id']) ? $_POST['order-id'] : null;

    //     // Присвоение данных сессии
    //     $_SESSION['searchValue'] = $searchValue;
    // }


    // SQL код для получения заказа
    $sql = "SELECT orders.*, order_items.product_id, order_items.quantity, menu.title, user.user_name FROM orders 
        LEFT JOIN order_items ON orders.order_id = order_items.order_id 
        LEFT JOIN menu ON order_items.product_id = menu.id 
        LEFT JOIN user ON orders.customer_id = user.id
        WHERE orders.order_id LIKE '%$searchValue%'";

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

    // Создает фильтрованный массив
    $filteredListSearch = [];

    // Вставляет в массив отсортированные значения по активным и завершенным заказам
    foreach ($groupedItems as $orderId => $orderItems) {
        foreach ($orderItems as $item) {
            $filteredListSearch[$orderId][] = $item;
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

    $paginationSearch = getPaginationLength($filteredListSearch);

    // Текущая страница для таблиц
    $currentPageSearch = isset($_GET['pageSearch']) ? $_GET['pageSearch'] : 1;

    // Вычисляем начальный индекс для активных заказов
    $startIndexSearch = ($currentPageSearch - 1) * MAX_ROW;

    // Получает список заказов пользователя для отрисовки
    $orderListSearch = array_slice($filteredListSearch, $startIndexSearch, MAX_ROW);

    // Формирует список ключей для итерации 
    $keysSearch = array_keys($orderListSearch);
} elseif ($statisticGroup === 'clients') {
    // Получает данные: айди заказа для поиска в базе
    // Формирует запрос с учетом указанного промежутка времени
    if (isset($_SESSION['phoneValue'])) {
        $searchValue = $_SESSION['phoneValue'];
    }

    // Получает данные: айди заказа для поиска в базе
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $phoneValue = isset($_POST['user-phone']) ? $_POST['user-phone'] : null;

        // Присвоение данных сессии
        $_SESSION['phoneValue'] = $phoneValue;
    }

    // SQL код для получения списка пользователей
    $sql = "SELECT user.id, user.user_name, user.user_telephone, user.user_address, user.user_rating, SUM(orders.total_amount) AS total_order_amount, COUNT(orders.id) AS total_orders_count, ROUND(AVG(orders.total_amount)) AS average_order_amount
        FROM 
            user
        LEFT JOIN 
            orders ON user.id = orders.customer_id
        WHERE 
            user.user_telephone LIKE '%$phoneValue%'
        GROUP BY 
            user.id, 
            user.user_name,
            user.user_telephone,
            user.user_address,
            user.user_rating;";


    $result = mysqli_query($con, $sql);

    if ($result === false || mysqli_num_rows($result) == 0) {
        $userList = null;
    } else {
        $userList = get_arrow($result);
        $userListLength = mysqli_num_rows($result);
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

    $paginationUser = getPaginationLength($userList);

    // Текущая страница для таблиц
    $currentPageUser = isset($_GET['pageUser']) ? $_GET['pageUser'] : 1;

    // Вычисляем начальный индекс для активных заказов
    $startIndexUser = ($currentPageUser - 1) * MAX_ROW;

    // Получает список заказов пользователя для отрисовки
    $userListFormatted = array_slice($userList, $startIndexUser, MAX_ROW);
} else {
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

// $phoneValue = null;
// $paginationUser = null;
// $userList = null;
// $userListFormatted = null;
// $userListLength = null;

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
