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
                $pokeId = $_SESSION['order'][$tableName][$productId];
                $sql = get_query_poke_unique_Id($pokeId);
                $result = mysqli_query($con, $sql);

                // Начало транзакции
                mysqli_begin_transaction($con);

                if ($result) {
                    $pokeUniqueId = get_arrow($result);

                    $sqlDeletePoke = get_query_delete_poke($pokeId);
                    $resultDeletePoke = mysqli_query($con, $sqlDeletePoke);

                    $sqlDeletePokeConsists = get_query_delete_poke_consists($pokeUniqueId);
                    $resultDeletePokeConsists = mysqli_query($con, $sqlDeletePokeConsists);

                    // Проверка успешности выполнения запросов
                    if ($resultDeletePoke && $resultDeletePokeConsists) {
                        // Если оба запроса выполнены успешно, фиксируем транзакцию
                        mysqli_commit($con);
                    } else {
                        // Если хотя бы один из запросов не выполнен успешно, откатываем транзакцию
                        mysqli_rollback($con);
                    }
                }
            }


            unset($_SESSION['order'][$tableName][$productId]);
            return;
        }

        $_SESSION['order'][$tableName][$productId] = $quantity;
    } else {
        $_SESSION['order'][$tableName][$productId] = 1;
    }
}
