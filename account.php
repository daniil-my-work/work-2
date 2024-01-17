<?php

require_once('./functions/helpers.php');
require_once('./functions/init.php');


if (!isset($_SESSION['user_email'])) {
    header("Location: ./auth.php");
    exit;
}

$page_head = include_template(
    'head.php',
    [
        'title' => 'poke-room «Много рыбы»',
    ]
);

$page_header = include_template(
    'header.php',
    [
        'is_auth' => $is_auth,
    ]
);

$page_body = include_template(
    'account.php',
    []
);

$page_footer = include_template(
    'footer.php',
    []
);

$layout_content = include_template(
    'layout.php',
    [
        'head' => $page_head,
        'header' => $page_header,
        'main' => $page_body,
        'footer' => $page_footer,
    ]
);


print($layout_content);
