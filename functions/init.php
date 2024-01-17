<?php

// Подключение к базе данных
$con = mysqli_connect('localhost', 'root', 'root', 'mnogoruba');
mysqli_set_charset($con, 'utf8');

// Старт сессии
session_start();

// Флаг авторизации
$is_auth = false;
