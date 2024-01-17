<?php

require_once('./functions/init.php');

// Уничтожение сессии
session_destroy();

// Пользователь не авторизован
$isAuth = false;

// Перенаправление на страницу входа
header("Location: ./index.php");
exit;