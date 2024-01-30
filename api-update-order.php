<?php

require_once('./functions/init.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получите данные из запроса
    $productId = isset($_POST['productId']) ? $_POST['productId'] : null;
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : null;

    // Инициализируйте или обновите данные корзины в сессии
    if (!isset($_SESSION['order'])) {
        $_SESSION['order'] = array();
    }

    // Добавьте товар в корзину
    if (isset($_SESSION['order'][$productId])) {
        // Удаление конкретного элемента из сессии
        if ($quantity <= 0) {
            unset($_SESSION['order'][$productId]);
            return;
        }

        $_SESSION['order'][$productId] = $quantity;
    } else {
        $_SESSION['order'][$productId] = 1;
    }
}
