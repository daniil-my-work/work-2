<?php

require_once('./functions/init.php');
require_once('./functions/helpers.php');
require_once('./functions/models.php');
require_once('./functions/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получите данные из запроса
    $orderId = isset($_POST['orderId']) ? $_POST['orderId'] : null;
    $status = isset($_POST['status']) ? $_POST['status'] : null;
    
    updateOrderStatus($con, $orderId, $status);
}