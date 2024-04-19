<?php

require_once('./functions/init.php');
require_once('./functions/helpers.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получите данные из запроса
    $toastId = $_POST['toastId'] ?? null;

    // Добавляет блюдо в сессию
    deleteToastFromSession($toastId);
}
