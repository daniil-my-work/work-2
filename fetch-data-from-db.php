<?php

require_once('./functions/init.php');
require_once('./functions/helpers.php');
require_once('./functions/models.php');
require_once('./functions/db.php');
require_once('./data/data.php');



// Функция для выполнения запроса к базе данных и получения данных
function fetchDataFromDb($con, $sql)
{
    $result = mysqli_query($con, $sql);
    $data = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    return $data;
}

// Функция для создания CSV файла и записи данных
function createCsvFile($filename, $data, $columns)
{
    $fp = fopen($filename, 'w');
    fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF)); // Добавляем BOM для UTF-8

    // Добавляет хедер
    fputcsv($fp, $columns);
    
    foreach ($data as $row) {
        fputcsv($fp, $row);
    }

    fclose($fp);
}


$tabGroup = $_GET['tabGroup'] ?? 'menu';


// Получаем из массива строку с наименованием колонок
if ($tabGroup === 'menu') {
    $getDataFromMenu = "SELECT menu.title, menu.img, menu.description, menu.price, menu.cooking_time, menu.category_id FROM menu";

    // Получение данных из базы данных
    $data = fetchDataFromDb($con, $getDataFromMenu);

    // Названия колонок
    $columns = array_values($columnNameMenu);
} else {
    $getDataFromPoke = "SELECT components.title, components.img, components.price, components.component_type, components.component_name, components.component_poke_type FROM components";

    // Получение данных из базы данных
    $data = fetchDataFromDb($con, $getDataFromPoke);

    // Названия колонок
    $columns = array_values($columnNamePoke);
}


// Создание CSV файла
$filename = "{$tabGroup}.csv";
createCsvFile($filename, $data, $columns);

// Устанавливаем заголовки для скачивания файла
header('Content-Encoding: UTF-8');
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . filesize($filename));

// Читаем содержимое файла и передаем его клиенту
readfile($filename);

// Удаляем временный файл
unlink($filename);
