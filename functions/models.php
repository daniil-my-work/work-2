<?php

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
 * Формирует SQL-запрос для добавления записи в таблицу users
 * @return string SQL-запрос
 */
function get_query_create_user()
{
    return "INSERT INTO users (`date_registration`, `email`, `user_password`, `user_name`, `contacts`) 
     VALUES (NOW(), ?, ?, ?, ?)";
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
 * Записывает в БД сделанную ставку
 * @param $link mysqli Ресурс соединения
 * @param int $sum Сумма ставки
 * @param int $user_id ID пользователя
 * @param int $lot_id ID лота
 * @return bool $res Возвращает true в случае успешной записи
 */
function add_bet_database($con, $sum, $user_id, $lot_id)
{
    $sql = "INSERT INTO bets (date_bet, price_bet, user_id, lot_id) VALUE (NOW(), ?, $user_id, $lot_id);";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $sum);
    $res = mysqli_stmt_execute($stmt);
    if ($res) {
        return $res;
    }
    $error = mysqli_error($con);
    return $error;
}

/**
 * Возвращает массив из десяти последних ставок на этот лот
 * @param $con Подключение к MySQL
 * @param int $id_lot Id лота
 * @return [Array | String] $list_bets Ассоциативный массив со списком ставок на этот лот из базы данных
 * или описание последней ошибки подключения
 */
function get_bets_history($con, $id_lot)
{
    if (!$con) {
        $error = mysqli_connect_error();
        return $error;
    } else {
        $sql = "SELECT users.user_name, bets.price_bet, DATE_FORMAT(date_bet, '%d.%m.%y %H:%i') AS date_bet
        FROM bets
        JOIN lots ON bets.lot_id=lots.id
        JOIN users ON bets.user_id=users.id
        WHERE lots.id=$id_lot
        ORDER BY bets.date_bet DESC LIMIT 10;";
        $result = mysqli_query($con, $sql);
        if ($result) {
            $list_bets = mysqli_fetch_all($result, MYSQLI_ASSOC);
            return $list_bets;
        }
        $error = mysqli_error($con);
        return $error;
    }
}

/**
 * Возвращает массив ставок пользователя
 * @param $con Подключение к MySQL
 * @param int $id Id пользователя
 * @return [Array | String] $list_bets Ассоциативный массив ставок
 *  пользователя из базы данных
 * или описание последней ошибки подключения
 */
function get_bets ($con, $id) {
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