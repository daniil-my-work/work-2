<?php

require_once('./functions/db.php');
require_once('./functions/helpers.php');
require_once('./functions/init.php');
require_once('./functions/models.php');
require_once('./functions/validators.php');


echo $is_auth;

$page_body = include_template(
    'auth.php',
    []
);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Обязательные поля для заполненения 
    $required = ['user_email', 'user_password'];
    $errors = [];

    $rules = [
        'email' => function ($value) {
            return validate_email($value);
        },
        'user_password' => function ($value) {
            return validate_length($value, 8, 12);
        }
    ];

    // Получает данные из формы
    $user = filter_input_array(INPUT_POST, ['user_email' => FILTER_DEFAULT, 'user_password' => FILTER_DEFAULT], true);

    // Заполняет массив с ошибками
    foreach ($user as $key => $value) {
        if (in_array($key, $required) && empty($value)) {
            $fieldName = $fieldDescriptions[$key];
            $errors[$key] = 'Поле ' . $fieldName . ' должно быть заполнено.';
        }

        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule($value);
        }
    }

    $errors = array_filter($errors);


    // Проверяет на наличие ошибок
    if (!empty($errors)) {
        $page_body = include_template(
            'auth.php',
            [
                'errors' => $errors,
            ]
        );
    } else {
        // Формирует sql запрос для проверки наличия юзера в таблицу User
        $sql = get_query_userAuth($user['user_email']);

        $userInfo = mysqli_query($con, $sql);

        if (!$userInfo) {
            $errors['email'] = 'Вы ввели неправильный email';

            $page_body = include_template(
                'auth.php',
                [
                    'errors' => $errors,
                ]
            );
        } else {
            $userInfo = get_arrow($userInfo);

            // Проверка совпадения пароля
            $isAuth = password_verify($user['user_password'], $userInfo['user_password']);

            if (!$isAuth) {
                $errors['user_password'] = 'Вы ввели неправильный пароль';

                $page_body = include_template(
                    'auth.php',
                    [
                        'errors' => $errors,
                    ]
                );
            } else {
                // Пользователь авторизован
                $is_auth = true;

                // Добавление данных в сессию
                $_SESSION['user_id'] = $userInfo['id'];
                $_SESSION['user_name'] = $userInfo['user_name'];
                $_SESSION['user_email'] = $userInfo['email'];

                header("Location: /index.php");
                exit;
            };
        }
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
        'is_auth' => $is_auth,
    ]
);

$page_footer = include_template(
    'footer.php',
    []
);

$layout_content = include_template(
    'layout.php',
    [
        'head' => $page_head,
        'header' => $page_header,
        'main' => $page_body,
        'footer' => $page_footer,
    ]
);

print($layout_content);
