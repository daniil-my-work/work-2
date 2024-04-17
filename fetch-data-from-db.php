<?php

require_once('./functions/init.php');
require_once('./functions/helpers.php');
require_once('./functions/models.php');
require_once('./functions/db.php');
require_once('./data/data.php');


// Определяет вкладку
$tabGroup = $_GET['tab'] ?? 'menu';


// Конфигурация SQL запросов и названий колонок в зависимости от типа данных
$queryConfig = [
    'menu' => [
        'query' => "SELECT menu.title, menu.img, menu.description, menu.price, menu.cooking_time, menu.category_id FROM menu",
        'columns' => $columnNameMenu
    ],
    'poke' => [
        'query' => "SELECT components.title, components.img, components.price, components.component_type, components.component_name, components.component_poke_type FROM components",
        'columns' => $columnNamePoke
    ]
];

// Определение типа данных, которые нужно извлечь
$type = $tabGroup === 'menu' ? 'menu' : 'poke';

// Получение SQL запроса и названий колонок для заданного типа данных
$sql = $queryConfig[$type]['query'];
$columns = array_values($queryConfig[$type]['columns']);

// Получение данных из базы данных
$data = fetchDataFromDb($con, $sql);


// Создание CSV файла
$filename = "{$type}.csv";
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
