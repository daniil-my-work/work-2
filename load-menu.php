<?php

require_once('./functions/init.php');
require_once('./functions/helpers.php');
require_once('./functions/models.php');
require_once('./functions/db.php');
require_once('./data/data.php');



// Список ролей
$userRole = $appData['userRoles'];

// Получение данных из сессии
$productsData = isset($_SESSION['order']) ? $_SESSION['order'] : array();

// Cписок выбранных блюд 
$productList = getProductListInBasket($con, $productsData);

// Список категорий меню
$categoryList = getCategories($con);

// Определяет вкладку
$tabGroup = $_GET['tabGroup'] ?? 'menu';
$activeGroup = $tabGroup === 'menu' ? 'меню' : 'поке';

$page_modal = null;

$page_body = include_template('load-menu.php', [
    'tabGroup' => $tabGroup,
    'activeGroup' => $activeGroup,
]);



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
                'activeGroup' => $activeGroup,
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
