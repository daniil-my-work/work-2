<?php

require_once('./functions/helpers.php');
require_once('./functions/init.php');
require_once('./functions/db.php');
require_once('./functions/models.php');


// Получает список категорий меню 
$getСategories = get_query_categories();
$categories = mysqli_query($con, $getСategories);

if ($categories && mysqli_num_rows($categories) > 0) {
    $categoryList = get_arrow($categories);
} else {
    $categoryList = NULL;
}

// Данные об айди заказа
$orderId = isset($_GET['orderId']) ? $_GET['orderId'] : null;
$nameLink = isset($_GET['prevLink']) ? $_GET['prevLink'] : null;

if ($nameLink == 'account') {
    $backLink = './account.php';
    $backLinkName = 'личный кабинет';
} else {
    $backLink = './index.php';
    $backLinkName = 'на главную';
}


if (is_null($orderId)) {
    header("Location: ./index.php");
    return;
}


// Получает данные о конкретном заказе по id заказа
$sql = "SELECT * FROM orders WHERE orders.order_id = '$orderId'";
$result = mysqli_query($con, $sql);

$orderInfo = null;
if ($result && mysqli_num_rows($result) > 0) {
    $orderInfo = get_arrow($result);
} else {
    header("Location: ./index.php");
}


if (is_null($orderInfo)) {
    $productList = array();
} else {
    $productList = array();

    // Данные о товарах в заказе
    $order_id = $orderInfo['order_id'];
    $sql = "SELECT order_items.product_id, order_items.tableName FROM order_items WHERE order_items.order_id = '$order_id'";
    $result = mysqli_query($con, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $orderItems = get_arrow($result);
        $orderItemsLength = mysqli_num_rows($result);
    } else {
        $orderItems = null;
        $orderItemsLength = 0;
    }

    if (!is_null($orderItems)) {
        if ($orderItemsLength == 1) {
            $productId = $orderItems['product_id'];
            $table = $orderItems['tableName'];

            if ($table == 'menu') {
                $sql = "SELECT order_items.product_id, order_items.quantity, order_items.unit_price, menu.title, menu.img, menu.description, menu.category_id 
                        FROM order_items 
                        LEFT JOIN menu ON order_items.product_id = menu.id
                        WHERE menu.id = '$productId' AND order_items.order_id = '$orderId'";
            } else {
                $sql = "SELECT order_items.product_id, order_items.quantity, order_items.unit_price, poke.title, menu.img, poke.description, poke.category_id 
                        FROM order_items
                        LEFT JOIN poke ON order_items.product_id = poke.id
                        WHERE order_items.product_id = '$productId' AND order_items.order_id = '$orderId'";
            }

            $result = mysqli_query($con, $sql);

            if ($result) {
                $productInfo = get_arrow($result);
                $productList[] = $productInfo;
            }
        } else {
            foreach ($orderItems as $orderItem) {
                $productId = $orderItem['product_id'];
                $table = $orderItem['tableName'];

                if ($table == 'menu') {
                    $sql = "SELECT order_items.product_id, order_items.quantity, order_items.unit_price, menu.title, menu.img, menu.description, menu.category_id 
                        FROM order_items 
                        LEFT JOIN menu ON order_items.product_id = menu.id
                        WHERE menu.id = '$productId' AND order_items.order_id = '$orderId'";
                } else {
                    $sql = "SELECT order_items.product_id, order_items.quantity, order_items.unit_price, poke.title, menu.img, poke.description, poke.category_id 
                        FROM order_items
                        LEFT JOIN poke ON order_items.product_id = poke.id
                        WHERE order_items.product_id = '$productId' AND order_items.order_id = '$orderId'";
                }

                $result = mysqli_query($con, $sql);

                if ($result) {
                    $productInfo = get_arrow($result);
                    $productList[] = $productInfo;
                }
            }
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
    ]
);

$page_body = include_template(
    'order.php',
    [
        'orderInfo' => $orderInfo,
        'productList' => $productList,
        'backLink' => $backLink,
        'backLinkName' => $backLinkName,
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
