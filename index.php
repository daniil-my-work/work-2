<?php

require_once('./functions/helpers.php');
require_once('./functions/init.php');
require_once('./functions/models.php');
require_once('./functions/db.php');
require_once('./data/data.php');


// Получение данных из сессии
$productsData = isset($_SESSION['order']) ? $_SESSION['order'] : array();

$productsDataMenu = array();
if (isset($productsData['menu'])) {
    $productsDataMenu = $productsData['menu'];
}


// Получает список продуктов
$sql = get_query_products();
$products = mysqli_query($con, $sql);

// Список продуктов
$productList = mysqli_num_rows($products) > 0 ? get_arrow($products) : null;


// Получает список категорий меню 
$getСategories = get_query_categories();
$categories = mysqli_query($con, $getСategories);

// Список категорий меню 
$categoryList = mysqli_num_rows($categories) > 0 ? get_arrow($categories) : null;



// Пример модальных окон
// $modalList = [
//     [
//         'title' => 'Выберите ваш город',
//         'button' => [
//             ['text' => 'Ярославль', 'class' => 'btn btn-primary btn-sm'],
//             ['text' => 'Рыбинск', 'class' => 'btn btn-secondary btn-sm']
//         ],
//         'category' => 'city',
//     ],
//     [
//         'title' => 'Заголовок ошибки',
//         'error' => 'Текст ошибки',
//         'category' => 'error',
//     ],
//     [
//         'title' => 'Зарегистрируетесь, чтобы получить бонусы',
//         'link' => [
//             'linkTitle' => 'Создать личный кабинет',
//             'address' => './dsdsds',
//         ],
//         'category' => 'link',
//     ],
// ];

// $categoryList = null;


// Модальное окно: Контент для вставки
$modalList = [
    [
        'title' => 'Выберите ваш город',
        'button' => [
            ['text' => 'Ярославль', 'class' => 'btn btn-primary btn-sm'],
            ['text' => 'Рыбинск', 'class' => 'btn btn-secondary btn-sm']
        ],
        'category' => 'city',
    ],
    [
        'title' => 'Заголовок ошибки',
        'error' => 'Текст ошибки',
        'category' => 'error',
    ],
    [
        'title' => 'Зарегистрируетесь, чтобы получить бонусы',
        'link' => [
            'linkTitle' => 'Создать личный кабинет',
            'address' => './dsdsds',
        ],
        'category' => 'link',
    ],
];

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
