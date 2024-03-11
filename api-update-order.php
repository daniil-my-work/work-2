<?php

require_once('./functions/init.php');
require_once('./functions/helpers.php');
require_once('./functions/models.php');
require_once('./functions/db.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получите данные из запроса
    $tableName = isset($_POST['tableName']) ? $_POST['tableName'] : null;
    $productId = isset($_POST['productId']) ? $_POST['productId'] : null;
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : null;

    // Проверка наличия необходимых данных
    // if ($tableName && $productId && $quantity) {
    //     // Добавление продукта в сессию
    // }

    addProductInSession($con, $tableName, $productId, $quantity);
}
