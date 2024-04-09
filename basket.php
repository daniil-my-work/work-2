<?php

require_once('./functions/helpers.php');
require_once('./functions/init.php');
require_once('./functions/models.php');
require_once('./functions/db.php');
require_once('./functions/formatter.php');
require_once('./data/data.php');


// Получает список категорий меню 
$getСategories = get_query_categories();
$categories = mysqli_query($con, $getСategories);

if ($categories && mysqli_num_rows($categories) > 0) {
    $categoryList = get_arrow($categories);
} else {
    $categoryList = NULL;
}


// Получает список категорий меню 
$getCafeAddress = get_query_cafe_address();
$cafeAddress = mysqli_query($con, $getCafeAddress);

if ($cafeAddress && mysqli_num_rows($cafeAddress) > 0) {
    $cafeList = get_arrow($cafeAddress);
} else {
    $cafeList = NULL;
}

// Получение данных из сессии
$productsData = isset($_SESSION['order']) ? $_SESSION['order'] : array();


// Получает список продуктов для отрисовки в корзине
function getProductList($con, $productsData)
{
    $productList = array();

    foreach ($productsData as $key => $productByCategory) {
        if ($key == 'menu') {
            foreach ($productByCategory as $key => $value) {
                $sql = get_query_product_item($key);
                $products = mysqli_query($con, $sql);
                $productItem = get_arrow($products);

                $productList[] = array(
                    'item' => $productItem,
                    'quantity' => $value,
                    'table' => 'menu',
                );
            }
        } else {
            foreach ($productByCategory as $key => $value) {
                $sql = get_query_product_item_poke($key);
                $products = mysqli_query($con, $sql);
                $productItem = get_arrow($products);

                $productList[] = array(
                    'item' => $productItem,
                    'quantity' => $value,
                    'table' => 'poke',
                );
            }
        }
    }

    return $productList;
}

// Получает список продуктов 
$productList = getProductList($con, $productsData);
$productLength = count($productList);


// Цена товаров в корзине
$fullPrice = 0;

// Подсчитывает цену в заивисмости от кол-ва товаров в корзине
if (count($productList) > 0) {
    // Перебираем продукты из $productList
    foreach ($productList as $product) {
        $quantity = $product['quantity']; // Получаем количество продукта из $productsData
        $price = $product['item']['price']; // Получаем цену продукта

        // Умножаем цену продукта на его количество и добавляем к общей стоимости
        $fullPrice += $price * $quantity;
    }
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
    $userEmail = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : null;
    $userId = null;
    if (!is_null($userEmail)) {
        $sql = "SELECT user.id FROM user WHERE user.user_email = '$userEmail'";
        $result = mysqli_query($con, $sql);
        $userId = get_arrow($result)['id'];
    }

    // Формирует объект заказа
    $order['customer_id'] = $userId;
    $order['total_amount'] = $fullPrice;
    $order['order_id'] = $order_id;


    // Адресс заказа
    if ($_POST['delivery-type'] === 'delivery') {
        // Обязательные поля для заполненения 
        $required = ['user_address', 'entrance', 'apartment', 'floor'];

        // Получает данные из формы
        $address = filter_input_array(INPUT_POST, ['delivery-type' => FILTER_DEFAULT, 'user_address' => FILTER_DEFAULT, 'entrance' => FILTER_DEFAULT, 'apartment' => FILTER_DEFAULT, 'floor' => FILTER_DEFAULT], true);
    } else {
        // Обязательные поля для заполненения 
        $required = ['user_address'];

        // Получает данные из формы
        $address = filter_input_array(INPUT_POST, ['delivery-type' => FILTER_DEFAULT, 'user_address' => FILTER_DEFAULT], true);
    }


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
            return is_string($value) && $value === 'pickup' || $value === 'delivery' ? '' : 'Неверный тип доставки';
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
        // Формирует адресс заказа
        if ($address['delivery-type'] === 'delivery') {
            $order['order_address'] = $address['user_address'] . ", кв. " . $address['apartment'] . ", подъезд " . $address['entrance'] . ", этаж " . $address['floor'];
        } else {
            $sql = "SELECT address_name FROM cafe_address WHERE cafe_address.id = '{$address['user_address']}'";
            $result = mysqli_query($con, $sql);
            $address_name = get_arrow($result)['address_name'];

            $order['order_address'] = $address_name;
        }

        // Добавляет комментарий в объект для записи в БД
        $orderComment = isset($_POST['order_comment']) ? $_POST['order_comment'] : null;
        $order['order_comment'] = $orderComment;


        // Добавляет запись в базу с заказами 
        $createNewOrder = get_query_create_order();
        $stmt = db_get_prepare_stmt($con, $createNewOrder, $order);
        $res = mysqli_stmt_execute($stmt);

        // Получает ID последнего вставленного заказа
        $order_num = mysqli_insert_id($con);


        // SQL код для добавление записи в базу с состовляющими заказа 
        $createNewOrderItem = get_query_create_order_item();

        // Записывает в базу товары лежащие в корзине
        foreach ($productList as $product) {
            $productItem = $product['item']; // Получаем ID продукта

            // Формирует объект для отправки
            $data = array(
                "product_id" => $productItem['id'],
                "quantity" => $product['quantity'],
                "unit_price" => $productItem['price'],
                "tableName" => $product['table'],
                "order_id" => $order_id,
            );

            $stmt = db_get_prepare_stmt($con, $createNewOrderItem, $data);
            $res = mysqli_stmt_execute($stmt);
        }

        if ($res) {
            echo "Запись успешно добавлена в базу данных.";

            // Удаляет данные из сессии и перенапрвляет на страницу аккаунт
            unset($_SESSION['order']);

            $sql = "SELECT orders.order_id FROM orders WHERE orders.id = '$order_num'";
            $res = mysqli_query($con, $sql);
            $order_id = get_arrow($res)['order_id'];

            if (!$res) {
                return;
            }

            header("Location: ./order.php?orderId=$order_id&prevLink=basket");
        } else {
            echo "Ошибка при выполнении запроса: " . mysqli_error($con);
            echo "Номер ошибки: " . mysqli_errno($con);
        }
    }
}



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
