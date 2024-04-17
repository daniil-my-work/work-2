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

/**
 * Проверяет уникальность значения в указанной таблице базы данных.
 *
 * @param object $con mysqli Объект подключения к базе данных.
 * @param int $order_id Идентификатор заказа, значение которого требуется проверить на уникальность.
 * @param string $table Имя таблицы, в которой требуется проверить уникальность значения.
 * @param string $value Название столбца, значение которого требуется проверить на уникальность.
 *
 * @return bool Возвращает true, если значение уникально, и false, если значение не уникально.
 */
function checkUniquenessValue($con, $order_id, $table, $value)
{
    // SQl код для проверки уникальности идентификатора заказа
    $sql = "SELECT COUNT(*) AS count FROM $table WHERE $value = '$order_id'";

    $result = mysqli_query($con, $sql);
    $idList = get_arrow($result);
    $count = $idList['count'];

    return $count == 0;
}

/**
 * Добавляет информацию о выбранном продукте в сессию корзины.
 *
 * @param object $con mysqli Объект подключения к базе данных.
 * @param string $tableName Имя таблицы, куда добавляется продукт.
 * @param int $productId Идентификатор продукта.
 * @param int $quantity Количество продукта.
 *
 * @return void
 */
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

/**
 * Возвращает список заказов, сгруппированных по идентификатору заказа.
 *
 * @param object $con mysqli Объект подключения к базе данных.
 * @param string $sql SQL-запрос для получения списка заказов.
 *
 * @return array Массив, содержащий список заказов, сгруппированных по идентификатору заказа.
 */
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

/**
 * Извлекает результат запроса в виде массива.
 *
 * @param object $result Результат запроса mysqli.
 *
 * @return array Возвращает массив данных из результата запроса.
 */
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

/**
 * Генерирует пагинацию на основе сгруппированных элементов.
 *
 * @param array $groupedItems Массив, содержащий сгруппированные элементы.
 *
 * @return array Возвращает массив страниц пагинации.
 */
function generatePagination($groupedItems)
{
    $groupedItemLength = count($groupedItems);
    $paginationLength = ceil($groupedItemLength / MAX_ROW);

    // Создаем массив чисел от 1 до $paginationLength
    return $paginationLength > 0 ? range(1, $paginationLength) : [0];
}

/**
 * Возвращает массив выбранных блюд из таблицы Меню / Поке.
 *
 * @param object $con mysqli Объект подключения к базе данных.
 * @param array $productsData Массив данных о выбранных продуктах.
 *
 * @return array Возвращает массив выбранных блюд из таблицы Меню / Поке.
 */
function getProductListInBasket($con, $productsData)
{
    $productList = [];

    foreach ($productsData as $category => $items) {
        $table = ($category == 'menu') ? 'menu' : 'poke';

        foreach ($items as $itemId => $quantity) {
            $sql = ($table == 'menu') ? get_query_product_item($itemId) : get_query_product_item_poke($itemId);

            $products = mysqli_query($con, $sql);
            $productItem = get_arrow($products);

            $productList[] = ['item' => $productItem, 'quantity' => $quantity, 'table' => $table];
        }
    }

    return $productList;
}


/**
 * Получает название компонента Поке и формирует заголовок Поке.
 *
 * @param string $proteinId ID компонента Поке.
 * @param mysqli $con Соединение с базой данных.
 * @return string Заголовок Поке.
 */
function get_poke_title($con, $proteinId)
{
    $sql = get_query_component_poke_type($proteinId);
    $result = mysqli_query($con, $sql);
    $pokeTitle = mysqli_fetch_assoc($result);

    if ($pokeTitle && isset($pokeTitle['component_poke_type'])) {
        return "Поке " . $pokeTitle['component_poke_type'];
    } else {
        return "Поке (название не найдено)";
    }
}


/**
 * Создает CSV файл и записывает в него данные. Функция открывает файл для записи, добавляет BOM для поддержки UTF-8,
 * записывает заголовки столбцов и последовательно добавляет данные из массива.
 *
 * @param string $filename Путь к файлу, который будет создан или перезаписан.
 * @param array $data Массив массивов, содержащий строки данных, которые будут записаны в CSV файл.
 * @param array $columns Массив, содержащий заголовки столбцов для CSV файла.
 * @return void Функция не возвращает значение.
 */
function createCsvFile($filename, $data, $columns)
{
    $fp = fopen($filename, 'w');
    fprintf($fp, chr(0xEF) . chr(0xBB) . chr(0xBF)); // Добавляем BOM для UTF-8

    // Добавляет хедер
    fputcsv($fp, $columns);

    foreach ($data as $row) {
        fputcsv($fp, $row);
    }

    fclose($fp);
}


// Функция для выполнения запроса к базе данных и получения данных
function fetchDataFromDb($con, $sql)
{
    $result = mysqli_query($con, $sql);
    $data = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    return $data;
}