<?php

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = [])
{
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            } else if (is_string($value)) {
                $type = 's';
            } else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = [])
{
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}


// Проверка на уникальность айди заказа
function checkUniquenessValue($con, $order_id, $table, $value)
{
    // SQl код для проверки уникальности идентификатора заказа
    $sql = "SELECT COUNT(*) AS count FROM $table WHERE $value = '$order_id'";

    $result = mysqli_query($con, $sql);
    $idList = get_arrow($result);
    $count = $idList['count'];

    return $count == 0;
}


// Добавляет данные о выбранном продукте в сессию
function addProductInSession($con, $tableName, $productId, $quantity)
{
    // Инициализируйте или обновите данные корзины в сессии
    if (!isset($_SESSION['order'][$tableName])) {
        $_SESSION['order'][$tableName] = array();
    }

    // Добавьте товар в корзину
    if (isset($_SESSION['order'][$tableName][$productId])) {
        // Удаление конкретного элемента из сессии
        if ($quantity <= 0) {
            if ($tableName == 'poke') {
                $sql = get_query_poke_unique_id($productId);
                $result = mysqli_query($con, $sql);
                $pokeUniqueId = get_arrow($result);
                $pokeUniqueId = $pokeUniqueId['poke_id'];

                $deletePoke = get_query_delete_poke($productId);
                $resultDeletePoke = mysqli_query($con, $deletePoke);

                $deletePokeConsists = get_query_delete_poke_consists($pokeUniqueId);
                $resultDeletePokeConsists = mysqli_query($con, $deletePokeConsists);

                if ($resultDeletePoke && $resultDeletePokeConsists) {
                    // Успешно удалено, теперь удаляем из сессии
                    unset($_SESSION['order'][$tableName][$productId]);
                } else {
                    // Обработка ошибки, если удаление не удалось
                    echo "Ошибка при удалении записей из базы данных.";
                }

                return;
            }

            unset($_SESSION['order'][$tableName][$productId]);
            return;
        }

        $_SESSION['order'][$tableName][$productId] = $quantity;
    } else {
        $_SESSION['order'][$tableName][$productId] = 1;
    }
}


// Обновляет данные о статусе заказа
function updateOrderStatus($con, $orderId, $status)
{
    if ($status === 'true') {
        $date_end = date("Y-m-d H:i:s");
        $sql = "UPDATE orders SET orders.date_end = '$date_end' WHERE orders.order_id = '$orderId'";
    } else {
        $sql = "UPDATE orders SET orders.date_end = null WHERE orders.order_id = '$orderId'";
    }

    $result = mysqli_query($con, $sql);

    if ($result) {
        echo "Статус заказа успешно обновлен.";
    } else {
        echo "Ошибка при обновлении статуса заказа: " . mysqli_error($con);
    }
}


// Возвращает список категорий меню
function getCategories($con)
{
    // Получает список категорий меню 
    $sql = get_query_categories();
    $categories = mysqli_query($con, $sql);

    // Список категорий меню 
    $categoryList = mysqli_num_rows($categories) > 0 ? get_arrow($categories) : null;

    return $categoryList;
}


// Возвращает данные о пользователе
function getUserInfo($con)
{
    $userEmail = $_SESSION['user_email'];
    $sql = get_query_user_info($userEmail);
    $result = mysqli_query($con, $sql);
    $userInfo = get_arrow($result);

    return $userInfo;
}


// Функция для получения списка заказов
function getGroupOrderItems($con, $sql)
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
function fetchResultAsArray($result)
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


// Возвращает данные о пользователе
// function getSafeValue($value)
// {
//     $safevalue = isset($value) ? $value : null;
//     return $safevalue;
// }