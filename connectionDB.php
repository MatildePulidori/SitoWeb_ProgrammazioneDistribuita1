<?php
function getConnectionDB(){
    $con = mysqli_connect("localhost", "root", "", "aeroplano");
    if (mysqli_connect_errno()){
        echo "Errore connessione al DB: ".mysqli_connect_error();
    }else{
        return $con;
    }
}
?>