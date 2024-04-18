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


/**
 * Извлекает данные из базы данных по заданному SQL запросу. Функция выполняет SQL запрос и возвращает результаты 
 * в виде массива ассоциативных массивов, где каждый ассоциативный массив представляет одну строку результата запроса.
 *
 * @param mysqli $con Объект соединения с базой данных, через который выполняется запрос.
 * @param string $sql Строка SQL запроса, который будет выполнен для извлечения данных.
 * @return array Массив ассоциативных массивов, содержащий результаты запроса. Каждый элемент массива 
 * представляет собой строку из базы данных в виде ассоциативного массива.
 */
function fetchDataFromDb($con, $sql)
{
    $result = mysqli_query($con, $sql);
    $data = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    return $data;
}


/**
 * Получает имя категории на основе идентификатора активной категории.
 *
 * @param mysqli $con Подключение к базе данных.
 * @param int|string $activeCategory Идентификатор активной категории.
 * @return string|null Возвращает имя категории, если она найдена, иначе возвращает null.
 */
function fetchCategoryName($con, $activeCategory)
{
    $getSelectedCategory = get_query_selected_category($activeCategory);
    $category = mysqli_query($con, $getSelectedCategory);

    // Используем тернарный оператор для упрощения возврата значения
    return ($category && mysqli_num_rows($category) > 0) ? get_arrow($category) : null;
}


/**
 * Получает имя категории на основе идентификатора активной категории.
 *
 * @param mysqli $con Подключение к базе данных.
 * @param int|string $activeCategory Идентификатор активной категории.
 * @return string|null Возвращает имя категории, если она найдена, иначе возвращает null.
 */
function getProductsByCategory($con, $activeCategory)
{
    $getProductsByCategory = get_query_selected_products($activeCategory);
    $products = fetchDataFromDb($con, $getProductsByCategory);

    // Используем тернарный оператор для упрощения возврата значения
    return $products ?? [];
}


/**
 * Получает данные о конкретном заказе по id заказа
 *
 * @param mysqli $con Подключение к базе данных.
 * @param int|string $orderId Идентификатор заказа.
 * @return string|null Возвращает имя категории, если она найдена, иначе возвращает null.
 */
function getOrderInfoById($con, $orderId)
{
    $sql = get_query_order_info_by_id($orderId);
    $result = mysqli_query($con, $sql);
    $orderInfo = get_arrow($result);

    return $orderInfo ?? null;
}

/**
 * Получает данные о конкретном заказе по id заказа
 *
 * @param mysqli $con Подключение к базе данных.
 * @param int|string $orderId Идентификатор заказа.
 * @return string|null Возвращает имя категории, если она найдена, иначе возвращает null.
 */
function getOrderItems($con, $orderId)
{
    $sql = get_query_order_items($orderId);
    $result = mysqli_query($con, $sql);
    $orderItems = fetchResultAsArray($result);

    return $orderItems ?? [];
}


/**
 * Проверяет авторизацию пользователя и его роль.
 *
 * @param bool $isAuth Флаг, указывающий на состояние авторизации пользователя.
 * @param string $currentRole Текущая роль пользователя в сессии.
 * @param array $allowedRoles Массив допустимых ролей для доступа к ресурсу.
 * @param string $redirectUrl URL для перенаправления в случае неудачной проверки.
 */
function checkAccess($isAuth, $currentRole, $allowedRoles, $redirectUrl = './auth.php')
{
    // Проверка авторизации и наличия роли в списке разрешенных
    if (!$isAuth || !in_array($currentRole, $allowedRoles)) {
        // Если проверка не прошла, перенаправление на страницу аутентификации
        header("Location: $redirectUrl");
        exit;
    }
}


/**
 * Загружает файл, отправленный через форму, в указанную директорию.
 * Проверяет наличие файла, его тип и обрабатывает загрузку.
 *
 * @param string $fieldName Имя поля в форме, через которое был отправлен файл.
 * @param string $uploadDir Директория, куда файл будет загружен.
 * @param array $validExtensions Массив допустимых расширений файла.
 * @return array Ассоциативный массив с ключами 'error' и 'fileName'. 
 *               'error' содержит сообщение об ошибке (если ошибка произошла) и 'fileName' содержит имя файла, если файл был успешно загружен.
 */
function handleFileUpload($fieldName, $uploadDir, $validExtensions)
{
    // Инициализация массива для хранения результатов
    $result = [
        'error' => null,
        'fileName' => null
    ];

    // Проверка наличия файла и ошибок загрузки
    if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
        $result['error'] = 'Файл не был загружен.';

        return $result;
    }

    // Проверка расширения файла
    $fileExtension = strtolower(pathinfo($_FILES[$fieldName]['name'], PATHINFO_EXTENSION));
    if (!in_array($fileExtension, $validExtensions)) {
        $result['error'] = 'Недопустимое расширение файла.';

        return $result;
    }

    // Перемещение файла и обновление имени файла в результате
    $fileName = uniqid("file_", true) . '.' . $fileExtension;
    $uploadFilePath = $uploadDir . $fileName;
    if (!move_uploaded_file($_FILES[$fieldName]['tmp_name'], $uploadFilePath)) {
        $result['error'] = 'Ошибка при сохранении файла.';

        return $result;
    }

    // Обновляем имя файла в результате
    $result['fileName'] = $fileName;

    // Возвращаем результат
    return $result;
}


/**
 * Импортирует данные из CSV файла в указанную таблицу базы данных.
 * Функция открывает файл, проверяет соответствие заголовков ожидаемым, и осуществляет вставку данных в таблицу.
 *
 * @param mysqli $con Соединение с базой данных.
 * @param string $filePath Путь к файлу CSV.
 * @param string $expectedColumns Строка с ожидаемыми заголовками столбцов, разделёнными точкой с запятой.
 * @param string $tableName Имя таблицы в базе данных, куда будут импортироваться данные.
 * @return array Массив с ключом 'error', содержащий сообщение об ошибке, если ошибка произошла, или null, если данные были успешно обработаны.
 *
 * Примечание: функция также удаляет файл после завершения импорта, независимо от того, произошла ошибка или нет.
 */
function importCsvData($con, $filePath, $expectedColumns, $tableName)
{
    // Инициализация массива для хранения результатов
    $result = [
        'error' => null,
    ];

    $file = fopen($filePath, 'r');
    if (!$file) {
        $result['error'] = 'Ошибка при открытии файла.';

        return $result;
    }

    // Получаем первую строку (заголовки столбцов)
    $headersArray = fgetcsv($file, 0, ";");
    if (!$headersArray) {
        fclose($file);
        unlink($filePath);
        $result['error'] = 'Не удалось прочитать заголовки из файла.';

        return $result;
    }

    // Проверяем заголовки и удаляем BOM если он есть
    $headers = array_map(function ($headersArray) {
        return trim(str_replace('"', '', $headersArray), "\xEF\xBB\xBF");
    }, $headersArray);

    $headersString = implode(';', $headers); // Используем точку с запятой в качестве разделителя
    $headersColumn = fgetcsv($file, 0, ",");

    if ($headersString !== $expectedColumns) {
        fclose($file);
        unlink($filePath);
        $result['error'] = 'Названия столбцов в файле не соответствуют ожидаемым.';
        return $result;
    }

    // Очистка таблицы перед вставкой новых данных
    clearTable($con, $tableName);

    while (($row = fgetcsv($file, 0, ",")) !== false) {
        if (count($row) == count($headersColumn)) {
            if ($tableName === 'menu') {
                $row[3] = intval($row[3]);
            } else {
                $row[2] = intval($row[2]);
            }

            if (!insertData($con, $tableName, $row)) {
                $result['error'] = 'Ошибка при вставке данных.';
                break;
            }
        } else {
            $result['error'] = 'Количество элементов в строке не соответствует количеству столбцов.';
            break;
        }
    }

    // Закрытие файла после завершения чтения
    fclose($file);

    // Удаление файла только после того, как все данные были успешно обработаны или если возникла ошибка
    unlink($filePath);

    return $result;
}
