<?php
// Проверяет категорию
function validate_category($id, $allowed_list)
{
    if (!in_array($id, $allowed_list)) {
        return "Указана несуществующая категория";
    }
}

// Проверяет число
function validate_number($num)
{
    if (!empty($num)) {
        $num *= 1;

        if (is_int($num) && $num > 0) {
            return NULL;
        }

        return "Содержимое поля должно быть целым числом больше ноля";
    }
}

// Проверяет email
function validate_email($email)
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "E-mail должен быть корректным";
    }
};

// Проверяет что содержимое укладывается в допустимый диапазон
function validate_length($value, $min, $max)
{
    if ($value) {
        $len = strlen($value);
        if ($len < $min or $len > $max) {
            return "Значение должно быть от $min до $max символов";
        }
    }
}
