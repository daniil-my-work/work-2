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
    if ($phone === null) {
        return 'Введите номер телефона';
    }

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

    if (is_array($value)) {
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


// Проверяет длину
function validate_component_length($name, $value, $shema)
{
    $len = count($value);

    if ($shema == 1) {
        if ($name === 'filler') {
            if ($len != 5) {
                print_r('Сработало');

                return "Для выбора доступно 5 наполнителей";
            }
        }

        if ($name === 'topping') {
            if ($len != 1) {
                return "Для выбора доступен 1 топпинг";
            }
        }
    } else {
        if ($name === 'filler') {
            if ($len != 3) {
                return "Для выбора доступно 3 наполнителя";
            }
        }

        if ($name === 'topping') {
            if ($len != 2) {
                return "Для выбора доступно 2 топпинга";
            }
        }
    }
}
