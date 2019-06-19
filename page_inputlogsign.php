<?php
$session = true;
if( session_status() === PHP_SESSION_DISABLED ){
    $session = false;
}
else if( session_status() !== PHP_SESSION_ACTIVE ){
    session_start();
}
include 'f_connectionDB.php';
include 'f_logorsign.php';

$con  = getConnectionDB();

$log = checkLoginDB($con);

$sign = checkRegisterDB($con);

echo "log ".$log.", signup".$sign;
if ($log==true || $sign==true){
    $redirect="posti.php";
    header('Location: '.$redirect);
    exit();
} else {
    $redirect="index.php";
    $msg="";
    if ($log==false){
        $msg="Login errato. Username/password errati.";
    } else if ($sign==false){
        $msg="Registrazione non andata a buon fine. Parametri errati o username già presente nel database.";
    }
    header('Location: '.$redirect.'?msg='.$msg);
    exit();
}
mysqli_close($con);
?>
