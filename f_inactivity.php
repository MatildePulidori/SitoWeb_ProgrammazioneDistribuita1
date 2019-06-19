<?php
function checkInactivity() {
        define('timeout', 10); // numero di minuti passati i quali non si e' piu' attivi

        $t = time();
        $diff = 0;
        $new = false;

        if( isset($_SESSION['265353_time']) ) {
            $t0 = $_SESSION['265353_time'];
            $diff = $t - $t0;
        } else {
            $new = true;
        }

        if( $new == true ) {
            $_SESSION['265353_time'] = time();
        }
        else if( $diff > 60*timeout) {
            $_SESSION = array();
            if( ini_get("session.use_cookies") ) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time()-3600*24, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
            }    
            session_destroy();
            $redirect="index.php";
            $msg="Timeout 2min has exiperd: you need to re-do your login!";
            header('Location: '.$redirect.'?msg='.$msg);
            exit();
         
        }
        else {
            $_SESSION['265353_time'] = time();
        }
    }



?>
