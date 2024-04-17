<?php

/**
 * Возвращает массив из объекта результатов запроса
 * @param object $result_query mysqli Результат запроса к базе данных
 * @return array
 */
function get_arrow($result_query)
{
    $row = mysqli_num_rows($result_query);

    if ($row === 1) {
        $arrow = mysqli_fetch_assoc($result_query);
    } else if ($row > 1) {
        $arrow = mysqli_fetch_all($result_query, MYSQLI_ASSOC);
    }

    return $arrow;
};

/**
 * Обновляет данные о статусе заказа.
 *
 * @param object $con mysqli Объект подключения к базе данных.
 * @param int $orderId Идентификатор заказа, который требуется обновить.
 * @param string $status Новый статус заказа. Может быть 'true' (заказ завершен) или любая другая строка (заказ не завершен).
 *
 * @return void
 */
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

/**
 * Получает список категорий меню из базы данных.
 *
 * @param object $con mysqli Объект подключения к базе данных.
 *
 * @return array|null Возвращает список категорий меню в виде массива объектов или null, если список пуст.
 */
function getCategories($con)
{
    // Получает список категорий меню 
    $sql = get_query_categories();
    $categories = mysqli_query($con, $sql);

    // Список категорий меню 
    $categoryList = mysqli_num_rows($categories) > 0 ? get_arrow($categories) : null;

    return $categoryList;
}

/**
 * Получает список компонентов из базы данных.
 *
 * @param object $con mysqli Объект подключения к базе данных.
 *
 * @return array|null Возвращает список компонентов в виде массива объектов или null, если список пуст.
 */
function getComponentList($con)
{
    // Получает список категорий меню 
    $sql = get_query_components();
    $components = mysqli_query($con, $sql);

    // Список категорий меню 
    $componentList = mysqli_num_rows($components) > 0 ? get_arrow($components) : null;

    return $componentList;
}

/**
 * Получает список адресов кафе из базы данных.
 *
 * @param object $con mysqli Объект подключения к базе данных.
 *
 * @return array|null Возвращает список адресов кафе в виде массива объектов или null, если список пуст.
 */
function getCafeList($con)
{
    // Получает список категорий меню 
    $sql = get_query_cafe_address();
    $cafeAddress = mysqli_query($con, $sql);

    $cafeList = mysqli_num_rows($cafeAddress) > 0 ? get_arrow($cafeAddress) : null;

    return $cafeList;
}

/**
 * Получает информацию о текущем пользователе из базы данных.
 *
 * @param object $con mysqli Объект подключения к базе данных.
 *
 * @return array|null Возвращает информацию о пользователе в виде массива объектов или null, если информация не найдена.
 */
function getUserInfo($con)
{
    $userEmail = $_SESSION['user_email'];
    $sql = get_query_user_info($userEmail);
    $result = mysqli_query($con, $sql);
    $userInfo = get_arrow($result);

    return $userInfo;
}

/**
 * Получает список продуктов из базы данных.
 *
 * @param object $con mysqli Объект подключения к базе данных.
 * @param string|null $category Необязательный параметр. Если задан, возвращает список продуктов определенной категории.
 *
 * @return array|null Возвращает список продуктов в виде массива объектов или null, если список пуст.
 */
function getProductList($con, $category = null)
{
    if (is_null($category)) {
        $sql = get_query_products();
    } else {
        $sql = get_query_selected_category($category);
    }

    $products = mysqli_query($con, $sql);

    return mysqli_num_rows($products) > 0 ? get_arrow($products) : null;
}

/**
 * Создает новый заказ в базе данных.
 *
 * @param object $con mysqli Объект подключения к базе данных.
 * @param array $order Массив с данными о заказе, который требуется добавить в базу данных.
 *
 * @return int Возвращает идентификатор нового заказа.
 */
function createNewOrder($con, $order)
{
    // Добавляет запись в базу с заказами
    $createNewOrder = get_query_create_order();
    $stmt = db_get_prepare_stmt($con, $createNewOrder, $order);
    mysqli_stmt_execute($stmt);

    // Получает ID последнего вставленного заказа
    $orderNum = mysqli_insert_id($con);

    return $orderNum;
}

/**
 * Получает идентификатор заказа по его порядковому номеру.
 *
 * @param object $con mysqli Объект подключения к базе данных.
 * @param int $insertOrderId Порядковый номер заказа, для которого требуется получить идентификатор.
 *
 * @return int|null Возвращает идентификатор заказа или null, если заказ с указанным порядковым номером не найден.
 */
function getOrderId($con, $insertOrderId)
{
    // Добавляет запись в базу с заказами
    $sql = get_query_order_id($insertOrderId);
    $res = mysqli_query($con, $sql);
    $orderId = ($res) ? get_arrow($res)['order_id'] : null;

    return $orderId;
}

/**
 * Получает информацию о категории из базы данных.
 *
 * @param object $con mysqli Объект подключения к базе данных.
 * @param string $category Идентификатор категории, для которой требуется получить информацию.
 *
 * @return array|null Возвращает информацию о категории в виде массива или null, если данные не найдены.
 */
function getCategoryInfo($con, $category)
{
    $sql = get_query_selected_category($category);
    $res = mysqli_query($con, $sql);
    $categoryInfo = ($res) ? get_arrow($res) : null;

    return $categoryInfo;
}


/**
 * Добавляет данные о поке в базу данных.
 *
 * @param object $con Объект подключения к базе данных.
 * @param object $poke Данные о поке для добавления в базу данных.
 * @return number|null Идентификатор вставленной записи или null в случае ошибки.
 */
function insertPokeInDb($con, $poke)
{
    $sql = get_query_create_poke();
    $stmt = db_get_prepare_stmt($con, $sql, $poke);
    $addedPoke = mysqli_stmt_execute($stmt);
    $insertId = ($addedPoke) ? mysqli_insert_id($con) : null;

    return $insertId;
}


/**
 * Получает название компонента по его идентификатору из базы данных.
 *
 * @param object $con Объект подключения к базе данных.
 * @param int $componentId Идентификатор компонента, для которого необходимо получить название.
 * @return string Название компонента, если найдено, или строку "Неизвестный компонент" в случае ошибки или если компонент не найден.
 */
function getComponentTitle($con, $componentId)
{
    $sql = get_query_component_names($componentId);
    $result = mysqli_query($con, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        return $row['title'];
    }

    return "Неизвестный компонент";
}


/**
 * Очищает все записи из указанной таблицы в базе данных.
 *
 * @param object $con Объект подключения к базе данных.
 * @param string $tableName Имя таблицы, из которой будут удалены все записи.
 */
function clearTable($con, $tableName)
{
    $sqlClear = "DELETE FROM {$tableName}";
    mysqli_query($con, $sqlClear);
}


/**
 * Вставляет данные в указанную таблицу на основе предоставленных данных.
 *
 * @param object $con Объект подключения к базе данных.
 * @param string $tableName Имя таблицы, в которую будут вставлены данные.
 * @param array $rowData Массив данных, которые необходимо вставить в таблицу.
 * @return boolean Возвращает true, если операция вставки прошла успешно, иначе false.
 */
function insertData($con, $tableName, $rowData)
{
    $placeholders = implode(',', array_fill(0, count($rowData), '?'));
    $sql = get_query_insert_data_from_file($tableName, $placeholders);

    // Подготавливаем и выполняем запрос
    $stmt = db_get_prepare_stmt($con, $sql, $rowData);
    $res = mysqli_stmt_execute($stmt);

    return $res;
}
