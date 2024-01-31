<?php

require_once('./functions/helpers.php');
require_once('./functions/init.php');
require_once('./functions/models.php');
require_once('./functions/db.php');


// Получение данных из сессии
$productsData = isset($_SESSION['order']) ? $_SESSION['order'] : array();


// Получает список продуктов для отрисовки в корзине
$productIds = array_keys($productsData);
$sql = get_query_productList($productIds);
$products = mysqli_query($con, $sql);
$productList = get_arrow($products);

print_r($productsData);


// Цена товароа в корзине
$fullPrice = 0;

// Подсчитывает цену в заивисмости от кол-ва товаров в корзине
if (count($productIds) == 1) {
    $productId = $productList['id']; // Получаем ID продукта
    $quantity = $productsData[$productId]; // Получаем количество продукта из $productsData
    $price = $productList['price']; // Получаем цену продукта

    // Умножаем цену продукта на его количество и добавляем к общей стоимости
    $fullPrice += $price * $quantity;
} else {
    // Перебираем продукты из $productList
    foreach ($productList as $product) {
        $productId = $product['id']; // Получаем ID продукта
        $quantity = $productsData[$productId]; // Получаем количество продукта из $productsData
        $price = $product['price']; // Получаем цену продукта

        // Умножаем цену продукта на его количество и добавляем к общей стоимости
        $fullPrice += $price * $quantity;
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ответ сервера (может быть пустым или содержать информацию об успешном добавлении)
    $order_id = uniqid();
    print_r($order_id);

    $order['customer_id'] = 33;
    $order['total_amount'] = $fullPrice;
    $order['order_id'] = $order_id;

    // Добавляет запись в базу с заказами 
    $sql = get_query_create_order();
    $stmt = db_get_prepare_stmt($con, $sql, $order);
    $res = mysqli_stmt_execute($stmt);


    // Добавляет запись в базу с состовляющими заказа 
    $sqlSecond = get_query_create_orderItem();

    // Подсчитывает цену в заивисмости от кол-ва товаров в корзине
    if (count($productIds) == 1) {
        $productId = $productList['id']; // Получаем ID продукта

        // Формирует объект для отправки
        $data = array(
            "product_id" => $productId,
            "quantity" => $productsData[$productId],
            "unit_price" => $productList['price'],
            "order_id" => $order_id,
        );

        $stmt = db_get_prepare_stmt($con, $sqlSecond, $data);
        $res = mysqli_stmt_execute($stmt);
    } else {
        foreach ($productList as $product) {
            $productId = $product['id']; // Получаем ID продукта

            // Формирует объект для отправки
            $data = array(
                "product_id" => $product['id'],
                "quantity" => $productsData[$productId],
                "unit_price" => $product['price'],
                "order_id" => $order_id,
            );

            $stmt = db_get_prepare_stmt($con, $sqlSecond, $data);
            $res = mysqli_stmt_execute($stmt);
        }
    }



    if ($res) {
        echo "Запись успешно добавлена в базу данных.";
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
        'productsData' => $productsData,
        'products' => $productList,
        'productLength' => count($productIds),
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
