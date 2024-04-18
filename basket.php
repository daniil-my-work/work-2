<?php

require_once('./functions/init.php');
require_once('./functions/helpers.php');
require_once('./functions/models.php');
require_once('./functions/db.php');
require_once('./functions/formatter.php');
require_once('./data/data.php');


// Список ролей
$userRole = $appData['userRoles'];

// Список категорий меню
$categoryList = getCategories($con);

// Cписок адресов кафе
$cafeList = getCafeList($con);

// Получение данных из сессии
$productsData = isset($_SESSION['order']) ? $_SESSION['order'] : array();

// Cписок выбранных блюд 
$productList = getProductListInBasket($con, $productsData);
$productLength = count($productList);


// Инициализируем переменную для хранения общей стоимости
$fullPrice = 0;

// Подсчитываем общую стоимость товаров в корзине
foreach ($productList as $product) {
    $fullPrice += $product['item']['price'] * $product['quantity'];
}


$page_body = include_template(
    'basket.php',
    [
        'productsData' => $productsData,
        'products' => $productList,
        'productLength' => $productLength,
        'fullPrice' => $fullPrice,
        'cafeList' => $cafeList,
    ]
);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Генерируем уникальный идентификатор
    do {
        $order_id = uniqid();
        // Проверяем, существует ли уже такой идентификатор в базе данных
    } while (!checkUniquenessValue($con, $order_id, 'orders', 'order_id'));


    // Получает айди пользователя, совершившего заказ
    $userEmail = $_SESSION['user_email'] ?? null;
    $userId = null;

    if (!is_null($userEmail)) {
        $sql = "SELECT user.id FROM user WHERE user.user_email = '$userEmail'";
        $result = mysqli_query($con, $sql);
        $userId = get_arrow($result)['id'] ?? null;
    }

    // Формируем объект заказа
    $order = [
        'customer_id' => $userId,
        'total_amount' => $fullPrice,
        'order_id' => $order_id
    ];


    // Определяем обязательные поля в зависимости от типа доставки
    $required = ($_POST['delivery-type'] === 'delivery') ? ['user_address', 'entrance', 'apartment', 'floor'] : ['cafe_address'];

    // Создаем массив с фильтрами по умолчанию
    $filters = array_merge(['delivery-type' => FILTER_DEFAULT], array_fill_keys($required, FILTER_DEFAULT));

    // Добавляем поле order_comment-user или order_comment-cafe в массив фильтров
    if ($_POST['delivery-type'] === 'delivery') {
        $filters['order_comment-user'] = FILTER_DEFAULT;
    } else {
        $filters['order_comment-cafe'] = FILTER_DEFAULT;
    }

    // Получаем данные из формы с применением фильтров
    $address = filter_input_array(INPUT_POST, $filters, true);


    // Ошибки
    $errors = [];

    // Валидация полей
    $rules = [
        'is_number' => function ($value) {
            return is_numeric($value) ? '' : 'Укажите число';
        },
        'is_text' => function ($value) {
            return is_string($value) ? '' : 'Укажите число';
        },
        'delivery-type' => function ($value) {
            return in_array($value, ['pickup', 'delivery'], true) ? '' : 'Неверный тип доставки';
        },
    ];

    // Заполняет массив с ошибками
    foreach ($address as $key => $value) {
        // Проверка на незаполненное поле
        if (in_array($key, $required) && empty($value)) {
            $field = $fieldDescriptions[$key] ?? '';

            $errors[$key] = 'Поле ' . $field . ' должно быть заполнено.';
        }

        if ($key == 'user_address') {
            $rule = $rules['is_text'];
            $errors['user_address'] = $rule($value);
            continue;
        }

        if ($key == 'entrance' || $key == 'apartment' || $key == 'floor') {
            $rule = $rules['is_number'];
            $errors[$key] = $rule($value);
            continue;
        }
    }

    $errors = array_filter($errors);

    if (!empty($errors)) {
        $page_body = include_template(
            'basket.php',
            [
                'productsData' => $productsData,
                'products' => $productList,
                'productLength' => $productLength,
                'fullPrice' => $fullPrice,
                'cafeList' => $cafeList,
                'address' => $address,
                'errors' => $errors,
            ]
        );
    } else {
        // Формирует адрес заказа
        if ($address['delivery-type'] === 'delivery') {
            // Формирует адресс заказа
            $order['order_address'] = "{$address['user_address']}, кв. {$address['apartment']}, подъезд {$address['entrance']}, этаж {$address['floor']}";

            // Добавляет комментарий в объект для записи в БД
            $order['order_comment'] = $address['order_comment-user'];
        } else {
            $sql = "SELECT address_name FROM cafe_address WHERE cafe_address.id = '{$address['cafe_address']}'";
            $result = mysqli_query($con, $sql);
            $address_name = get_arrow($result)['address_name'] ?? '';

            // Формирует адресс заказа
            $order['order_address'] = $address_name;

            // Добавляет комментарий в объект для записи в БД
            $order['order_comment'] = $address['order_comment-cafe'];
        }

        // Добавляет запись в базу с заказами 
        $insertOrderNum = createNewOrder($con, $order);

        // SQL код для добавление записи в базу с состовляющими заказа 
        $createNewOrderItem = get_query_create_order_item();

        // Флаг успешности всех операций
        $allOperationsSuccessful = true;

        // Записывает в базу товары лежащие в корзине
        foreach ($productList as $product) {
            // Получаем данные о продукте и количестве
            $productItem = $product['item'];
            $quantity = $product['quantity'];

            // Формирует объект для отправки
            $data = array(
                "product_id" => $productItem['id'],
                "quantity" => $quantity,
                "unit_price" => $productItem['price'],
                "tableName" => $product['table'],
                "order_id" => $order_id,
            );

            // Подготавливаем и выполняем запрос
            $stmt = db_get_prepare_stmt($con, $createNewOrderItem, $data);
            $res = mysqli_stmt_execute($stmt);

            // Если хотя бы одна операция не выполнена успешно, изменяем флаг
            if (!$res) {
                $allOperationsSuccessful = false;
                break; // Прерываем цикл, так как дальнейшие операции не нужны
            }
        }


        if ($allOperationsSuccessful) {
            // Получает айди оформленного заказа
            $orderId = getOrderId($con, $insertOrderNum);

            if (!$orderId) {
                return;
            }

            echo "Запись успешно добавлена в базу данных.";

            // Удаляет данные из сессии и перенапрвляет на страницу аккаунт
            unset($_SESSION['order']);
            header("Location: ./order.php?orderId=$order_id&prevLink=basket");
        } else {
            echo "Ошибка при выполнении запроса: " . mysqli_error($con);
            echo "Номер ошибки: " . mysqli_errno($con);
        }
    }
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
