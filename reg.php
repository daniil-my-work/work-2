<?php

require_once('./functions/helpers.php');
require_once('./functions/init.php');


$page_body = include_template(
    'reg.php',
    []
);

// Проверка на отправку формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Обязательные поля для заполненения 
    $required = ['user_name', 'telephone', 'email', 'user_password'];
    $errors = [];

    // Валидация полей
    $rules = [
        'user_name' => function ($value) {
            return $value == 'Da' ? 'Имя занято' : '';
        },
        'email' => function ($value) {
            return $value;
        },
        'phone' => function ($value) {
            return $value;
        },
        'user_password' => function ($value) {
            return $value;
        },
    ];

    // Получает данные из формы
    $user = filter_input_array(INPUT_POST, ['user_name' => FILTER_DEFAULT, 'email' => FILTER_VALIDATE_EMAIL, 'phone' => FILTER_DEFAULT, 'user_password' => FILTER_DEFAULT], true);


    foreach ($user as $key => $value) {
        // Проверка на незаполненное поле
        if (empty($value)) {
            $errors[$key] = 'Поле' . $user[$key] . 'должно быть заполнено.';
        }

        // Проверка по самописным правилам
        if (array_key_exists($key, $rules)) {
            $rule = $rules[$key];
            $errors[$key] = $rule($value);
        }
    }

    $errors = array_filter($errors);

    if (!empty($errors)) {
        $page_body = include_template(
            'reg.php',
            [
                'errors' => $errors,
            ]
        );
    } else {
        // Получаем реальный IP-адрес пользователя, учитывая прокси
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $userIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $userIP = $_SERVER['REMOTE_ADDR'];
        }
        $user['user_ip'] = $userIP;

        $sql = "INSERT INTO user (date_reg, user_name, email, telephone, user_password, user_ip) VALUES (NOW(), ?, ?, ?, ?, ?)";

        $stmt = db_get_prepare_stmt($con, $sql, $user);
        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            echo "Запись успешно добавлена в базу данных.";
        } else {
            echo "Ошибка при выполнении запроса:" . mysqli_error($con);
            echo "Номер ошибки" . mysqli_errno($con);


            $page_body = include_template('reg.php');
        }

        mysqli_stmt_close($stmt);
    }
}


$page_head = include_template(
    'head.php',
    [
        'title' => 'poke-room «Много рыбы»',
    ]
);

$page_header = include_template(
    'header.php',
    []
);

$page_footer = include_template(
    'footer.php',
    []
);

$layout_content = include_template('layout.php', [
    'head' => $page_head,
    'header' => $page_header,
    'main' => $page_body,
    'footer' => $page_footer,
]);

print($layout_content);
