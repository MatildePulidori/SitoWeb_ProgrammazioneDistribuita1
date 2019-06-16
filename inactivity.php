<?php
function checkInactivity() {
        define('timeout', 1); // numero di minuti passati i quali non si e' piu' attivi

        $t = time();
        $diff = 0;
        $new = false;

        if( isset($_SESSION['time']) ) {
            $t0 = $_SESSION['time'];
            $diff = $t - $t0;
        } else {
            $new = true;
        }

        if( $new == true ) {
            $_SESSION['time'] = time();
        }
        else if( $diff > 60*timeout) {
            $_SESSION = array();
            if( ini_get("session.use_cookies") ) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time()-3600*24, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
            }    
            session_destroy();
            header("Location : index.php?msg='Timeout'");
        }
        else {
            $_SESSION['time'] = time();
        }
    }
?>
