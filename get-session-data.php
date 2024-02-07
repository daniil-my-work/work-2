<?php

require_once('./functions/init.php');

// Получение даты из Сессии
$sessionData = $_SESSION['order'];

// Отправка данных в формате JSON
header("Content-type: application/json");
echo json_encode($sessionData);
