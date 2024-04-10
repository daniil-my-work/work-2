<?php

require_once('./functions/init.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получите данные из запроса
    $cityValue = isset($_POST['cityValue']) ? $_POST['cityValue'] : null;

    $_SESSION['city'] = $cityValue;
}
