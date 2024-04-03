<?php

require_once('./functions/init.php');
require_once('./functions/helpers.php');
require_once('./functions/models.php');
require_once('./functions/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получите данные из запроса
    $cityValue = isset($_POST['cityValue']) ? $_POST['cityValue'] : null;

    $_SESSION['city'] = $cityValue;
}
