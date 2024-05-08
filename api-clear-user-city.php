<?php

require_once('./functions/init.php');

unset($_SESSION['city']);

header("Location: ./index.php");
