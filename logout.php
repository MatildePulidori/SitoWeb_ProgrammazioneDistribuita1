<?php
    $session = true;
    if( session_status() === PHP_SESSION_DISABLED ){
        $session = false;
    }
    else if( session_status() !== PHP_SESSION_ACTIVE ){
        session_start();
    }
    $_SESSION = array();
    if( ini_get("session.use_cookies") ) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time()-3600*24, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    } 
    session_destroy();
    session_regenerate_id();
    header('Location: index.php');
    exit;
?>