<?php

require_once('./functions/init.php');
require_once('./functions/helpers.php');

// Получает данные о блюдах добавленных в сессию
// $sessionData = $_SESSION['order'] ?? [];
// $menuArr = $sessionData['menu'] ?? [];
// $pokeArr = $sessionData['poke'] ?? [];

// // Объединение двух подмассивов
// $combinedArray = array_merge($menuArr, $pokeArr);

// // Получение общей длины объединенного массива
// $totalLength = count($combinedArray);
$totalLength = getBasketList();

// Отправка данных о общей длине в формате JSON
header("Content-type: application/json");
echo json_encode(array('totalLength' => $totalLength));
