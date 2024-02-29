<?php

require_once('./data/data.php');
require_once('./functions/helpers.php');
require_once('./functions/init.php');
require_once('./functions/models.php');
require_once('./functions/validators.php');


$page_body = include_template('reg.php');


// Проверка на отправку формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Обязательные поля для заполненения 
    $required = ['user_name', 'user_phone', 'user_email', 'user_password'];
    $errors = [];

    // Валидация полей
    $rules = [
        'user_email' => function ($value) {
            return validate_email($value);
        },
        'user_phone' => function ($value) {
            return validate_phone($value);
        },
        'user_password' => function ($value) {
            return validate_length($value, 8, 12);
        }
    ];

    // Получает данные из формы
    $user = filter_input_array(INPUT_POST, ['user_name' => FILTER_DEFAULT, 'user_email' => FILTER_DEFAULT, 'user_phone' => FILTER_DEFAULT, 'user_password' => FILTER_DEFAULT], true);

    // Заполняет массив с ошибками
    foreach ($user as $key => $value) {
        // Проверка на незаполненное поле
        if (in_array($key, $required) && empty($value)) {
            $field = $fieldDescriptions[$key] ?? '';

            $errors[$key] = 'Поле ' . $field . ' должно быть заполнено.';
        }

        // Проверка по самописным правилам
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule($value);
        }
    }

    $errors = array_filter($errors);

    // Проверяет на наличие ошибок
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

        // Формирует sql запрос для добавления записи в таблицу User
        $sql = get_query_create_user();
        $user['user_role'] = 'client';

        // Устанавливает роль: Админ
        if (in_array($user['user_phone'], $adminTelephone)) {
            $user['user_role'] = 'admin';
        }

        // Устанавливает роль: Собственник
        if (in_array($user['user_phone'], $ownerTelephone)) {
            $user['user_role'] = 'owner';
        }

        // Хеширует пароль
        $user['user_password'] = password_hash($user['user_password'], PASSWORD_BCRYPT);

        var_dump($user);

        $stmt = db_get_prepare_stmt($con, $sql, $user);
        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            // echo "Запись успешно добавлена в базу данных.";

            header("Location: /auth.php");
            exit;
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
    [
        'isAuth' => $isAuth,
    ]
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
