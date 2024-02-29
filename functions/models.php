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
 * Формирует SQL-запрос для показа спика
 * @return string SQL-запрос
 */
function get_query_userInfo($user_email)
{
    return "SELECT * FROM user WHERE user.email = '$user_email'";
};

/**
 * Формирует SQL-запрос для показа спика
 * @return string SQL-запрос
 */
function get_query_userAuth($user_email)
{
    return "SELECT user.id, user.name, user.email, user.user_password FROM user WHERE user.email = '$user_email'";
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

/**
 * Формирует SQL-запрос для показа списка продуктов 
 * @return string SQL-запрос
 */
function get_query_products()
{
    return "SELECT * FROM menu";
}

/**
 * Формирует SQL-запрос для показа списка продуктов 
 * @return string SQL-запрос
 */
function get_query_selectedProducts($activeCategory)
{
    return "SELECT menu.id, menu.title, menu.img, menu.description, menu.price, category_menu.category_name, category_menu.category_title FROM menu LEFT JOIN category_menu ON menu.category_id = category_menu.id WHERE category_menu.category_title = '$activeCategory'";
}


/**
 * Формирует SQL-запрос для показа списка категорий 
 * @return string SQL-запрос
 */
function get_query_categories()
{
    return "SELECT * FROM category_menu";
}

/**
 * Формирует SQL-запрос для показа активной категории
 * @return string SQL-запрос
 */
function get_query_selectedCategory($activeCategory)
{
    return "SELECT * FROM category_menu WHERE category_menu.category_title = '$activeCategory'";
}


/**
 * Формирует SQL-запрос для добавления записи в таблицу orders
 * @return string SQL-запрос
 */
function get_query_create_order()
{
    return "INSERT INTO orders (order_date, customer_id, total_amount, order_id) 
    VALUES (NOW(), ?, ?, ?)";
}

/**
 * Формирует SQL-запрос для добавления записи в таблицу order_items
 * @return string SQL-запрос
 */
function get_query_create_orderItem()
{
    return "INSERT INTO order_items (product_id, quantity, unit_price, tableName, order_id) 
    VALUES (?, ?, ?, ?, ?)";
}


/**
 * Формирует SQL-запрос для получения компонентов Поке
 * @return string SQL-запрос
 */
function get_query_components()
{
    return "SELECT * FROM component";
}


/**
 * Формирует SQL-запрос для проверки наличия компонента в Поке
 * @return string SQL-запрос
 */
function get_query_checkComponent($componentId, $componentType)
{
    return "SELECT * FROM component WHERE component.id = '$componentId' AND component.component_type = '$componentType'";
}


/**
 * Формирует SQL-запрос для получения Названия компонентов
 * @return string SQL-запрос
 */
function get_query_componentNames()
{
    return "SELECT DISTINCT component_type, component_name FROM component";
}

/**
 * Формирует SQL-запрос для получения Заголовка компонента
 * @return string SQL-запрос
 */
function get_query_componentTitle($componentId)
{
    return "SELECT title, component_name FROM component WHERE id = '$componentId'";
}


/**
 * Формирует SQL-запрос для добавления записи в таблицу poke
 * @return string SQL-запрос
 */
function get_query_create_poke()
{
    return "INSERT INTO `poke`(
        title,
        img,
        description,
        price,
        cooking_time,
        category_id
    ) VALUES (?, ?, ?, ?, ?, ?)";
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
