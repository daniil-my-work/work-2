<?php

require_once('./functions/init.php');
require_once('./functions/helpers.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получите данные из запроса
    $orderId = isset($_POST['orderId']) ? $_POST['orderId'] : null;
    $status = isset($_POST['status']) ? $_POST['status'] : null;
    
    // Обновляет статус заказа активный / завершенный
    updateOrderStatus($con, $orderId, $status);
}