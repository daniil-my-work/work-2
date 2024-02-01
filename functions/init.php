<?php

// Подключение к базе данных
$con = mysqli_connect('localhost', 'root', 'root', 'mnogoruba');
mysqli_set_charset($con, 'utf8');

// Старт сессии
session_start();

// Флаг авторизации
$isAuth = isset($_SESSION['user_email']);
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
