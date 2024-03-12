<?php

require_once('./functions/init.php');

// Получает данные о блюдах добавленных в сессию
$sessionData = isset($_SESSION['order']) ? $_SESSION['order'] : array();
$menuArr = isset($sessionData['menu']) ? $sessionData['menu'] : array();
$pokeArr = isset($sessionData['poke']) ? $sessionData['poke'] : array();

// Объединение двух подмассивов
$combinedArray = array_merge($menuArr, $pokeArr);

// Получение общей длины объединенного массива
$totalLength = count($combinedArray);

// Отправка данных о общей длине в формате JSON
header("Content-type: application/json");
echo json_encode(array('totalLength' => $totalLength));
