<?php

require_once('./functions/helpers.php');
require_once('./functions/init.php');
require_once('./functions/models.php');
require_once('./functions/db.php');
require_once('./data/data.php');



// Cписок выбранных блюд 
$productList = getProductListInBasket($con, $productsData);


// Список категорий меню
$categoryList = getCategories($con);


// Определяет вкладку
$tabGroup = isset($_GET['tab']) ? $_GET['tab'] : 'menu';


$page_modal = null;

$page_body = include_template('load-menu.php', [
    'tabGroup' => $tabGroup,
]);


$errors = [];
$uploadDir = './uploads/';
$validExtensions = ['csv'];
$expectedColumns = array_values($columnNameMenu); 


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
}


// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $errors = [];

//     // Проверка, что файл был загружен
//     if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
//         // Получаем расширение файла
//         $fileExtension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

//         // Получаем временное имя файла
//         $tempFilePath = $_FILES['file']['tmp_name'];

//         // Проверяем, что расширение файла соответствует ожидаемому формату (csv)
//         if ($fileExtension === 'csv') {
//             // Путь для сохранения файла
//             $uploadDir = './uploads/';

//             // Имя файла
//             $fileName = basename($_FILES['file']['name']);

//             // Полный путь к файлу на сервере
//             $uploadFile = $uploadDir . $fileName;


//             // Перемещаем загруженный файл в указанную директорию
//             if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
//                 // Открываем файл CSV для чтения
//                 $file = fopen($uploadFile, 'r');

//                 // Получаем первую строку (заголовки столбцов)
//                 $headers = fgetcsv($file)[0];
//                 $headers = trim($headers, "\xEF\xBB\xBF");

//                 // Преобразуем строку в массив, используя разделитель ";"
//                 $headersArray = str_getcsv($headers, ';');

//                 // Конвертируем массив заголовков в обычную строку
//                 $headersString = implode(',', $headersArray);


//                 // Получаем из массива строку с наименованием колонок
//                 if ($tabGroup === 'menu') {
//                     // Конвертируем массив заголовков в обычную строку
//                     $columnNames = implode(',', array_values($columnNameMenu));
//                 } else {
//                     // Конвертируем массив заголовков в обычную строку
//                     $columnNames = implode(',', array_values($columnNamePoke));
//                 }


//                 // Вставляет данные из csv таблицы в БД
//                 if ($columnNames != $headersString) {
//                     $errors['file'] = 'Названия столбцов в файле не соответствуют ожидаемым. Скачайте';
//                 } else {
//                     if ($tabGroup === 'menu') {
//                         $sqlClear = "DELETE FROM menu";

//                         // Очищает таблицу
//                         mysqli_query($con, $sqlClear);

//                         // Считываем и обрабатываем каждую строку CSV-файла
//                         while (($row = fgetcsv($file, 0, ";")) !== false) {
//                             if (count($row) == 6) {
//                                 // Экранируем и обрабатываем каждое значение для предотвращения SQL инъекций
//                                 $title = mysqli_real_escape_string($con, $row[0]);
//                                 $img = mysqli_real_escape_string($con, $row[1]);
//                                 $description = mysqli_real_escape_string($con, $row[2]);
//                                 $price = mysqli_real_escape_string($con, $row[3]);
//                                 $cooking_time = mysqli_real_escape_string($con, $row[4]);
//                                 $category_id = mysqli_real_escape_string($con, $row[5]);

//                                 // Формируем SQL запрос с явным указанием столбцов
//                                 $sql = "INSERT INTO menu (`title`, `img`, `description`, `price`, `cooking_time`, `category_id`) 
//                                     VALUES ('$title', '$img', '$description', '$price', '$cooking_time', '$category_id')";

//                                 // Выполняем запрос
//                                 mysqli_query($con, $sql);
//                             } else {
//                                 // Обработка случая, если количество элементов в строке не соответствует количеству столбцов
//                                 echo "Ошибка: количество элементов в строке не соответствует количеству столбцов в таблице Меню.";
//                             }
//                         }

//                         // Вывести сообщение об успешной загрузке и обработке файла
//                         echo 'Файл успешно загружен и обработан.';
//                     } else {
//                         $sqlClear = "DELETE FROM components";

//                         // Очищает таблицу
//                         mysqli_query($con, $sqlClear);

//                         // Считываем и обрабатываем каждую строку CSV-файла
//                         while (($row = fgetcsv($file, 0, ";")) !== false) {
//                             if (count($row) == 6) {
//                                 // Экранируем и обрабатываем каждое значение для предотвращения SQL инъекций
//                                 $title = mysqli_real_escape_string($con, $row[0]);
//                                 $img = mysqli_real_escape_string($con, $row[1]);
//                                 $price = mysqli_real_escape_string($con, $row[2]);
//                                 $component_type = mysqli_real_escape_string($con, $row[3]);
//                                 $component_name = mysqli_real_escape_string($con, $row[4]);
//                                 $component_poke_type = mysqli_real_escape_string($con, $row[5]);

//                                 // Формируем SQL запрос с явным указанием столбцов
//                                 $sql = "INSERT INTO components (`title`, `img`, `price`, `component_type`, `component_name`, `component_poke_type`) 
//                                     VALUES ('$title', '$img', '$price', '$component_type', '$component_name', '$component_poke_type')";

//                                 // Выполняем запрос
//                                 mysqli_query($con, $sql);
//                             } else {
//                                 // Обработка случая, если количество элементов в строке не соответствует количеству столбцов
//                                 echo "Ошибка: количество элементов в строке не соответствует количеству столбцов в таблице Поке.";
//                             }
//                         }

//                         // Вывести сообщение об успешной загрузке и обработке файла
//                         // echo 'Файл успешно загружен и обработан.';
//                     }
//                 }

//                 // Закрываем файл CSV
//                 fclose($file);

//                 // Удаляет загруженный файл
//                 unlink($uploadFile);
//             } else {
//                 $errors['file'] = 'Ошибка при загрузке файла.';
//             }
//         } else {
//             $errors['file'] = 'Загружен файл другого формата. Загрузить в формате CSV.';
//         }
//     } else {
//         $errors['file'] = 'Файл не был загружен.';
//     }


//     $page_body = include_template(
//         'load-menu.php',
//         [
//             'errors' => $errors,
//             'tabGroup' => $tabGroup,
//         ]
//     );
// }



// ==== ШАБЛОНЫ ====
$page_head = include_template(
    'head.php',
    [
        'title' => 'poke-room «Много рыбы»',
    ]
);

$page_header = include_template(
    'header.php',
    [
        'isAuth' => $isAuth,
        'userRole' => $userRole,
    ]
);

$page_footer = include_template(
    'footer.php',
    [
        'categoryList' => $categoryList,
    ]
);

$layout_content = include_template(
    'layout.php',
    [
        'head' => $page_head,
        'modal' => $page_modal,
        'header' => $page_header,
        'main' => $page_body,
        'footer' => $page_footer,
    ]
);

print($layout_content);
