<?php

require_once('./functions/init.php');

$sessionData = $_SESSION['order'];

// Объединение двух подмассивов
$combinedArray = array_merge($sessionData['menu'], $sessionData['poke']);

// Получение общей длины объединенного массива
$totalLength = count($combinedArray);

// Отправка данных о общей длине в формате JSON
header("Content-type: application/json");
echo json_encode(array('totalLength' => $totalLength));
