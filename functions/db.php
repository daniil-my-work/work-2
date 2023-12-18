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
 * Возвращает массив товаров
 * @param $con Подключение к MySQL
 * @return $error Описание последней ошибки подключения
 * @return array $goods Ассоциативный массив с лотами из базы данных
 */
function get_goods($con)
{
    if (!$con) {
        $error = mysqli_connect_error();
        return $error;
    } else {
        $sql_get_goods = get_query_goods();
        $result_goods = mysqli_query($con, $sql_get_goods);

        if ($result_goods) {
            $goods = get_arrow($result_goods);
            return $goods;
        } else {
            $error = mysqli_error($con);
            return $error;
        }
    }
};

/**
 * Возвращает массив категорий
 * @param $con Подключение к MySQL
 * @return array|string Ассоциативный массив с категориями лотов из базы данных или сообщение об ошибке
 */
function get_categories($con)
{
    if (!$con) {
        $error = mysqli_connect_error();
        return $error;
    } else {
        // Запрос для получения списка категорий
        $sql = "SELECT id, character_code, name_category FROM categories";
        $result = mysqli_query($con, $sql);

        if ($result) {
            $categories = get_arrow($result);
            return $categories;
        } else {
            $error = mysqli_error($con);
            return $error;
        }
    }
};


/**
 * Возвращает массив данных пользователей: адресс электронной почты и имя
 * @param $con Подключение к MySQL
 * @return [Array | String] $users_data Двумерный массив с именами и емейлами пользователей
 * или описание последней ошибки подключения
 */
function get_users_data($con)
{
    if (!$con) {
        $error = mysqli_connect_error();
        return $error;
    } else {
        $sql = "SELECT email, user_name FROM users;";
        $result = mysqli_query($con, $sql);
        if ($result) {
            $users_data = get_arrow($result);
            return $users_data;
        }
        $error = mysqli_error($con);
        return $error;
    }
}

/**
 * Возвращает массив данных пользователя: id адресс электронной почты имя и хеш пароля
 * @param $con Подключение к MySQL
 * @param $email введенный адрес электронной почты
 * @return [Array | String] $users_data Массив с данными пользователя: id адресс электронной почты имя и хеш пароля
 * или описание последней ошибки подключения
 */
function get_login($con, $email)
{
    if (!$con) {
        $error = mysqli_connect_error();
        return $error;
    } else {
        $sql = "SELECT id, email, user_name, user_password FROM users WHERE email = '$email'";
        $result = mysqli_query($con, $sql);
        if ($result) {
            $users_data = get_arrow($result);
            return $users_data;
        }
        $error = mysqli_error($con);
        return $error;
    }
}

/**
 * Возвращает кол-во лотов соответсвущих поисковому запросу
 */
function get_count_lots($con, $words) {
    $sql = "SELECT COUNT(*) as cnt FROM lots
    WHERE MATCH(title, lot_description) AGAINST(?);";

    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "s", $words);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if ($res) {
        $count = mysqli_fetch_assoc($res)["cnt"];
        return $count;
    }

    $error = mysqli_error($con);
    return $error;
}

/**
 * Возвращает массив лотов соответствующих поисковым словам
 */
function get_found_lots($con, $words, $limit, $offset) {
    $sql = "SELECT * FROM lots 
    JOIN categories ON lots.category_id = categories.id 
    WHERE MATCH(title, lot_description) AGAINST(?)
    ORDER BY date_creation DESC LIMIT $limit OFFSET $offset;";

    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 's', $words);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if ($res) {
        $goods = get_arrow($res);
        return $goods;
    }

    $error = mysqli_error($con);
    return $error;
}
