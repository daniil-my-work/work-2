<?php

require_once('./functions/init.php');
require_once('./functions/helpers.php');
require_once('./functions/models.php');
require_once('./functions/db.php');
require_once('./data/data.php');


// Список ролей
$userRole = $appData['userRoles'];

// Получение данных из сессии о добавленных в корзину блюд
$productsDataMenu = $_SESSION['order']['menu'] ?? [];

// Получает список блюд
$productList = getProductList($con);

// Список категорий меню
$categoryList = getCategories($con);


// Модальное окно: Контент для вставки
// $modalList = [
    // [
    //     'title' => 'Выберите ваш город',
    //     'button' => [
    //         ['text' => 'Ярославль', 'class' => 'btn btn-primary btn-sm'],
    //         ['text' => 'Рыбинск', 'class' => 'btn btn-secondary btn-sm']
    //     ],
    //     'category' => 'city',
    // ],
    // [
    //     'title' => 'Заголовок ошибки',
    //     'error' => 'Текст ошибки',
    //     'category' => 'error',
    // ],
    // [
    //     'title' => 'Зарегистрируетесь, чтобы получить бонусы',
    //     'link' => [
    //         'linkTitle' => 'Создать личный кабинет',
    //         'address' => './dsdsds',
    //     ],
    //     'category' => 'link',
    // ],
// ];

$modalList = null;


// ==== ШАБЛОНЫ ====
$page_modal = include_template(
    'modal.php',
    [
        'modalList' => $modalList,
    ]
);


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

$page_body = include_template(
    'main.php',
    [
        'productsData' => $productsDataMenu,
        'products' => $productList,
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
