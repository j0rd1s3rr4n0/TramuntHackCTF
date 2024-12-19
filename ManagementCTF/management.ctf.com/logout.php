<?php
    ob_start();
    foreach ($_COOKIE as $key => $value) {
        setcookie($key, '', time() - 3600, '/', '', isset($_SERVER['HTTPS']), true);
    }
    session_start();
    $_SESSION = array();
    session_destroy();
    header('Location: index.php', true, 301);
    ob_end_flush();
?>