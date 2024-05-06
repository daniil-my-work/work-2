<?php

require_once('./functions/init.php');
require_once('./functions/helpers.php');
require_once('./functions/models.php');
require_once('./functions/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получите данные из запроса
    $tableName = $_POST['tableName'] ?? null;
    $productId = $_POST['productId'] ?? null;
    $quantity = $_POST['quantity'] ?? null;

    // Добавляет блюдо в сессию
    addProductInSession($con, $tableName, $productId, $quantity);
}
