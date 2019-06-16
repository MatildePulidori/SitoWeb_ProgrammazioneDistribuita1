<?php
$session = true;
if( session_status() === PHP_SESSION_DISABLED ){
    $session = false;
}
else if( session_status() !== PHP_SESSION_ACTIVE ){
    session_start();
}

include 'connectionDB.php';
$con = getConnectionDB();
$associaz =  array( 0=>'A', 1=>'B', 2=>'C', 3=>'D', 4=>'E', 5=>'F', 6=>'G', 7=>'H', 8=>'I', 9=>'J', 10=>'K', 11=>'L', 12=>'M', 13=>'N', 14=>'O', 15=>'P', 16=>'Q', 17=>'R', 18=>'S', 19=>'T', 20=>'U', 21=>'V', 22=>'W', 23=>'X', 24=>'Y', 25=>'Z') ;
$i = $j = 0;
$n = $_SESSION['n'];
$m = $_SESSION['m'];

if (isset($_POST['posti'])){
    foreach($_POST['posti'] as $key=>$val){
        $postazione=$val;
        
        $fila=$postazione[0];
        $posto=$postazione[1];
        $query ="SELECT id FROM utenti WHERE username=?";
        $stmt=mysqli_prepare($con, $query);
        if(!$stmt){
            die("Error in prepary id user select.\n");
        }
        
        mysqli_stmt_bind_param($stmt, "s", $_SESSION['user']);
        if (!mysqli_execute($stmt)){
            die("Error executing id user select ".mysqli_stmt_error($stmt)."\n");
        } 
        mysqli_stmt_bind_result($stmt, $idUtente);

        // CASO A) il posto era già nel DB, quindi già stato prenotato/comprato
        if(mysqli_stmt_fetch($stmt)){

            mysqli_stmt_close($stmt);
    
            try{
                mysqli_autocommit($con, false);

                $query1="SELECT * FROM prenotazioni FOR UPDATE";
                if (!$result1=mysqli_query($con, $query1)){
                    throw new Exception("Error executing query: ".mysqli_error($con));
                }
                $query2="SELECT stato, utente FROM prenotazioni WHERE fila=? AND posto=? ";
                if (!($stmt2=mysqli_prepare($con,$query2))){
                    throw new Exception("Error in preparing select position statement ");
                }
                mysqli_stmt_bind_param($stmt2, "is", $fila, $posto);
                if (!mysqli_execute($stmt2)){
                    throw new Exception("Error in executing select position statement ".mysqli_stmt_error($stmt2));
                }
                mysqli_stmt_bind_result($stmt2, $stato, $id);
                if(!mysqli_stmt_fetch($stmt2)){
                    die("No position for the position indicated .\n");
                }
                mysqli_stmt_close($stmt2);

                // A1) prenotato dall'utente.. 
                if ($stato=='occupied' && $id==$idUtente){
                    // ..bisogna procedere con l'acquisto
                    echo "OK";
                } else {
                    throw new Exception("One other user have already booked this position.");
                }


            } catch (Exception $e){
                mysqli_rollback($con);
                echo "Rollback " . $e->getMessage();
                mysqli_autocommit($con,true);
            }
        } else {
        // CASO B ) il posto è libero, lo compro
        }
    }
}
mysqli_close($con);
    
?>