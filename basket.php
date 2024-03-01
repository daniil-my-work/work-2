<?php

require_once('./functions/helpers.php');
require_once('./functions/init.php');
require_once('./functions/models.php');
require_once('./functions/db.php');


// Проверка на авторизацию
if (!$isAuth) {
    header("Location: ./auth.php");
    return;
}

// unset($_SESSION['order']);

// Получение данных из сессии
$productsData = isset($_SESSION['order']) ? $_SESSION['order'] : array();


// Получает список продуктов для отрисовки в корзине
function getProductList($con, $productsData)
{
    $productList = array();

    foreach ($productsData as $key => $productByCategory) {
        $productsKey = array_keys($productByCategory);

        if ($key == 'menu') {
            $sql = get_query_productList($productsKey);
        } else {
            $sql = get_query_productPokeList($productsKey);
        }

        $products = mysqli_query($con, $sql);
        $productItem = get_arrow($products);

        $productList[] = array(
            'item' => $productItem,
            'quantity' => $productByCategory[$productItem['id']],
        );
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



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Генерируем уникальный идентификатор
    do {
        $order_id = uniqid();
        // Проверяем, существует ли уже такой идентификатор в базе данных
    } while (!checkUniquenessValue($con, $order_id, 'orders', 'order_id'));


    // Получает айди пользователя, совершившего заказ
    $userEmail = $_SESSION['user_email'];
    $sql = "SELECT user.id FROM user WHERE user.user_email = '$userEmail'";
    $result = mysqli_query($con, $sql);
    $userId = get_arrow($result)['id'];

    // Формирует объект заказа
    $order['customer_id'] = $userId;
    $order['total_amount'] = $fullPrice;
    $order['order_id'] = $order_id;

    // Добавляет запись в базу с заказами 
    $createNewOrder = get_query_create_order();
    $stmt = db_get_prepare_stmt($con, $createNewOrder, $order);
    $res = mysqli_stmt_execute($stmt);

    // SQL код для добавление записи в базу с состовляющими заказа 
    $createNewOrderItem = get_query_create_orderItem();


    // Записывает в базу товары лежащие в корзине
    foreach ($productList as $product) {
        $productItem = $product['item']; // Получаем ID продукта

        // Формирует объект для отправки
        $data = array(
            "product_id" => $productItem['id'],
            "quantity" => $product['quantity'],
            "unit_price" => $productItem['price'],
            // TODO должно подставляться динамически добавить !!!!
            "tableName" => 'menu',
            "order_id" => $order_id,
        );

        $stmt = db_get_prepare_stmt($con, $createNewOrderItem, $data);
        $res = mysqli_stmt_execute($stmt);
    }

    if ($res) {
        echo "Запись успешно добавлена в базу данных.";

        // Удаляет данные из сессии и перенапрвляет на страницу аккаунт
        unset($_SESSION['order']);

        // Получает ID последнего вставленного заказа
        $order_num = mysqli_insert_id($con);
        $sql = "SELECT orders.order_id FROM orders WHERE orders.customer_id = '$userId' AND orders.id = '$order_num'";
        $res = mysqli_query($con, $sql);
        $order_id = get_arrow($res)['order_id'];

        if (!$res) {
            return;
        }

        header("Location: ./order.php?order=$order_id");
    } else {
        echo "Ошибка при выполнении запроса: " . mysqli_error($con);
        echo "Номер ошибки: " . mysqli_errno($con);
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
    ]
);

$page_body = include_template(
    'basket.php',
    [
        'products' => $productList,
        'productLength' => $productLength,
        'fullPrice' => $fullPrice,
    ]
);

$page_footer = include_template(
    'footer.php',
    []
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
