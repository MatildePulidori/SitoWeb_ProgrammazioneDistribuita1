<?php
function getConnectionDB(){
    $con = mysqli_connect("localhost", "s265353", "cisingat", "s265353");
    if (mysqli_connect_errno()){
        echo "Errore connessione al DB: ".mysqli_connect_error();
    }else{
        return $con;
    }
}
?>