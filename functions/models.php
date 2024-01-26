<?php

/**
 * Формирует SQL-запрос для добавления записи в таблицу user
 * @return string SQL-запрос
 */
function get_query_create_user()
{
    return "INSERT INTO user (date_reg, user_name, email, telephone, user_password, user_ip, user_role) 
    VALUES (NOW(), ?, ?, ?, ?, ?, ?)";
}

/**
 * Формирует SQL-запрос для показа спика лотов
 * @return string SQL-запрос
 */
function get_query_userAuth($user_email)
{
    return "SELECT user.id, user.user_name, user.email, user.user_password FROM user WHERE user.email = '$user_email'";
};

/**
 * Формирует SQL-запрос для показа списка продуктов по списку идентификаторов продуктов
 * @param array $productIds Массив идентификаторов продуктов
 * @return string SQL-запрос
 */
function get_query_productList(array $productIds)
{
    // Преобразуем массив идентификаторов в строку для использования в операторе IN
    $productIdList = implode(',', array_map('intval', $productIds));

    // Формируем SQL-запрос
    return "SELECT * FROM menu WHERE menu.id IN ($productIdList)";
}













// ============ Примеры ============

/**
 * Формирует SQL-запрос для показа спика лотов
 * @return string SQL-запрос
 */
function get_query_goods()
{
    return "SELECT lots.id, lots.title, lots.start_price, lots.img, lots.date_finish, categories.name_category FROM lots 
    JOIN categories ON lots.category_id = categories.id 
    WHERE lots.date_finish < NOW()
    ORDER BY date_creation DESC LIMIT 6";
};

/**
 * Формирует SQL-запрос для показа лота на страницу lot.php
 * @param integer $id_lot id лота
 * @return string SQL-запрос
 */
function get_query_lot($id_lot)
{
    return "SELECT * FROM lots 
    JOIN categories ON lots.category_id = categories.id 
    WHERE lots.id = $id_lot";
}

/**
 * Формирует SQL-запрос для добавления записи в таблицу lot
 * @param integer $user_id id пользователя
 * @return string SQL-запрос
 */
function get_query_create_lot($user_id)
{
    return "INSERT INTO lots (title, category_id, lot_description, start_price, step, date_finish, img, user_id) 
     VALUES (?, ?, ?, ?, ?, ?, ?, $user_id)";
}



/**
 * Формирует SQL-запрос для добавления записи в таблицу bets
 * @return string SQL-запрос
 */
function get_query_create_bet($cost, $user_id, $lot_id)
{
    return "INSERT INTO bets (`date_bet`, `price_bet`, `user_id`, `lot_id`) 
    VALUES (NOW(), $cost, $user_id, $lot_id)";
}

/**
 * Формирует SQL-запрос для обновления записи: цена товара в таблице lots
 * @return string SQL-запрос
 */
function get_query_update_lot($cost, $lot_id)
{
    return "UPDATE lots SET start_price = $cost WHERE id = $lot_id";
}


/**
 * Формирует SQL-запрос для получения информации о лотах на которые пользователь поставил ставку
 * @return string SQL-запрос
 */
function get_query_bets($user_id)
{
    return "SELECT lots.id, lots.user_id, lots.winner_id, lots.img, lots.title, categories.name_category, lots.date_finish, MAX(bets.price_bet) AS max_price_bet 
    FROM lots 
    JOIN bets ON lots.id = bets.lot_id 
    JOIN categories ON lots.category_id = categories.id 
    WHERE bets.user_id = $user_id 
    GROUP BY lots.id, lots.user_id, lots.winner_id, lots.img, lots.title, categories.name_category, lots.date_finish;";
}

/**
 * Возвращает массив ставок пользователя
 * @param $con Подключение к MySQL
 * @param int $id Id пользователя
 * @return [Array | String] $list_bets Ассоциативный массив ставок
 *  пользователя из базы данных
 * или описание последней ошибки подключения
 */
function get_bets($con, $id)
{
    if (!$con) {
        $error = mysqli_connect_error();
        return $error;
    } else {
        $sql = "SELECT DATE_FORMAT(bets.date_bet, '%d.%m.%y %H:%i') AS date_bet, bets.price_bet, lots.title, lots.lot_description, lots.img, lots.date_finish, lots.id, users.user_name, users.contacts, categories.name_category
        FROM bets
        JOIN lots ON bets.lot_id=lots.id
        JOIN users ON bets.user_id=users.id
        JOIN categories ON lots.category_id=categories.id
        WHERE bets.user_id=$id
        ORDER BY bets.date_bet DESC;";
        $result = mysqli_query($con, $sql);
        if ($result) {
            $list_bets = mysqli_fetch_all($result, MYSQLI_ASSOC);
            return $list_bets;
        }
        $error = mysqli_error($con);
        return $error;
    }
}
