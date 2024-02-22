<?php

// Проверяет E-mail
function validate_email($email)
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "E-mail должен быть корректным";
    }
};

// Проверяет Номер телефона
function validate_phone($phone)
{
    // Удаление всех символов, кроме цифр
    $phone = preg_replace("/[^0-9]/", "", $phone);

    // Проверка, что телефон состоит из 11 цифр
    if (strlen($phone) !== 11) {
        return 'Неверная длина номера телефона';
    }

    // Проверка, что телефон начинается с кода страны "+7"
    if (substr($phone, 0, 2) !== '79') {
        return 'Телефон должен начинаться с кода страны +7';
    }

    // Все проверки пройдены, номер телефона валиден
    return '';
}

// Проверяет длину
function validate_length($value, $min, $max)
{
    if ($value) {
        $len = strlen($value);
        if ($len < $min or $len > $max) {
            return "Пароль должен быть от $min до $max символов";
        }
    }
}


// Проверяет наличие компонента
function validate_component($con, $componentType, $value)
{
    $sql = get_query_componentNames();
    $res = mysqli_query($con, $sql);

    if (!$res) {
        return 'Ошибка запроса';
    }

    $componentNames = get_arrow($res);

    $newData = [];
    // Проходимся по исходным данным и добавляем в новый массив
    foreach ($componentNames as $item) {
        $newData[$item['component_type']] = $item['component_name'];
    }

    $componentName = $newData[$componentType];

    if (check_is_array($value)) {
        foreach ($value as $id) {
            $sql = get_query_checkComponent($id, $componentType);
            $result = mysqli_query($con, $sql);

            if (mysqli_num_rows($result) == 0) {
                return "Ошибка при выборе $componentName. Вы выбрали несуществующий компонент.";
            }
        }

        return null;
    } else {
        $sql = get_query_checkComponent($value, $componentType);
        $result = mysqli_query($con, $sql);

        if (mysqli_num_rows($result) == 0) {
            return "Ошибка при выборе $componentName. Вы выбрали несуществующий компонент.";
        }

        return null;
    }
}
