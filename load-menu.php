<?php

require_once('./functions/helpers.php');
require_once('./functions/init.php');
require_once('./functions/models.php');
require_once('./functions/db.php');
require_once('./data/data.php');


// Получение данных из сессии
$productsData = isset($_SESSION['order']) ? $_SESSION['order'] : array();

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



function handleFileUpload($fieldName, $uploadDir, $validExtensions)
{
    // Инициализация массива для хранения результатов
    $result = [
        'error' => null,
        'fileName' => null
    ];

    // Проверка наличия файла и ошибок загрузки
    if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
        $result['error'] = 'Файл не был загружен.';
        
        return $result;
    }

    // Проверка расширения файла
    $fileExtension = strtolower(pathinfo($_FILES[$fieldName]['name'], PATHINFO_EXTENSION));
    if (!in_array($fileExtension, $validExtensions)) {
        $result['error'] = 'Недопустимое расширение файла.';

        return $result;
    }

    // Перемещение файла и обновление имени файла в результате
    $fileName = uniqid("file_", true) . '.' . $fileExtension;
    $uploadFilePath = $uploadDir . $fileName;
    if (!move_uploaded_file($_FILES[$fieldName]['tmp_name'], $uploadFilePath)) {
        $result['error'] = 'Ошибка при сохранении файла.';

        return $result;
    }

    // Обновляем имя файла в результате
    $result['fileName'] = $fileName;

    // Возвращаем результат
    return $result;
}


function importCsvData($con, $filePath, $expectedColumns, $tableName)
{
    // Инициализация массива для хранения результатов
    $result = [
        'error' => null,
    ];

    $file = fopen($filePath, 'r');
    if (!$file) {
        $result['error'] = 'Ошибка при открытии файла.';

        return $result;
    }

    // Получаем первую строку (заголовки столбцов)
    $headersArray = fgetcsv($file, 0, ";");
    if (!$headersArray) {
        fclose($file);
        unlink($filePath);
        $result['error'] = 'Не удалось прочитать заголовки из файла.';

        return $result;
    }

    // Проверяем заголовки и удаляем BOM если он есть
    $headers = array_map(function ($headersArray) {
        return trim(str_replace('"', '', $headersArray), "\xEF\xBB\xBF");
    }, $headersArray);

    $headersString = implode(';', $headers); // Используем точку с запятой в качестве разделителя
    $headersColumn = fgetcsv($file, 0, ",");

    if ($headersString !== $expectedColumns) {
        fclose($file);
        unlink($filePath);
        $result['error'] = 'Названия столбцов в файле не соответствуют ожидаемым.';
        return $result;
    }

    // Очистка таблицы перед вставкой новых данных
    clearTable($con, $tableName);

    while (($row = fgetcsv($file, 0, ",")) !== false) {
        if (count($row) == count($headersColumn)) {
            if ($tableName === 'menu') {
                $row[3] = intval($row[3]);
            } else {
                $row[2] = intval($row[2]);
            }

            if (!insertData($con, $tableName, $row)) {
                $result['error'] = 'Ошибка при вставке данных.';
                break;
            }
        } else {
            $result['error'] = 'Количество элементов в строке не соответствует количеству столбцов.';
            break;
        }
    }

    // Закрытие файла после завершения чтения
    fclose($file);

    // Удаление файла только после того, как все данные были успешно обработаны или если возникла ошибка
    unlink($filePath);

    return $result;
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    $uploadDir = './uploads/';
    $validExtensions = ['csv'];
    $columnName = $tabGroup === 'menu' ? array_values($columnNameMenu) : array_values($columnNamePoke);
    $tableName = $tabGroup === 'menu' ? 'menu' : 'components';
    $expectedColumns = implode(',', $columnName);


    // Проверяет загруженный файл
    $uploadResult = handleFileUpload('file', $uploadDir, $validExtensions);

    // Проверяем наличие ошибок
    if ($uploadResult['error']) {
        $errors['file'] = $uploadResult['error'];
    } else {
        $uploadedFileName = $uploadResult['fileName'];
        $importResult = importCsvData($con, $uploadDir . $uploadedFileName, $expectedColumns, $tableName);

        if ($importResult['error']) {
            $errors['file'] = $importResult['error'];
        }
    }


    if (!empty($errors['file'])) {
        $page_body = include_template(
            'load-menu.php',
            [
                'errors' => $errors,
                'tabGroup' => $tabGroup,
            ]
        );
    }
}


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
