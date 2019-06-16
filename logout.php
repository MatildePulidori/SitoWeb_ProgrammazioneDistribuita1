<?php
    $session = true;
    if( session_status() === PHP_SESSION_DISABLED ){
        $session = false;
    }
    else if( session_status() !== PHP_SESSION_ACTIVE ){
        session_start();
    }
    $_SESSION = array();
    session_destroy();
    header('Location: index.php');
    exit;
?>