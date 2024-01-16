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
