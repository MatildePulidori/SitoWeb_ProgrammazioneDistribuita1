<?php
$session = true;
if( session_status() === PHP_SESSION_DISABLED ){
    $session = false;
}
else if( session_status() !== PHP_SESSION_ACTIVE ){
    session_start();
}

include 'f_connectionDB.php';
$con = getConnectionDB();
$associaz =  array( 0=>'A', 1=>'B', 2=>'C', 3=>'D', 4=>'E', 5=>'F', 6=>'G', 7=>'H', 8=>'I', 9=>'J', 10=>'K', 11=>'L', 12=>'M', 13=>'N', 14=>'O', 15=>'P', 16=>'Q', 17=>'R', 18=>'S', 19=>'T', 20=>'U', 21=>'V', 22=>'W', 23=>'X', 24=>'Y', 25=>'Z') ;
$i = $j = 0;
$n = $_SESSION['265353_n'];
$m = $_SESSION['265353_m'];
$output= "";

 // 1 - Recupero l'id utente 
 $query1 ="SELECT id FROM utenti WHERE username=?";
 $stmt1=mysqli_prepare($con, $query1);
 if(!$stmt1){
     die("Error in prepary id user select.\n");
 }
 
 mysqli_stmt_bind_param($stmt1, "s", $_SESSION['265353_user']);
 if (!mysqli_execute($stmt1)){
     die("Error executing id user select ".mysqli_stmt_error($stmt1)."\n");
 } 
 mysqli_stmt_bind_result($stmt1, $idUtente);

 // 2 - Se l'utente esiste ...
 if(mysqli_stmt_fetch($stmt1)){
    mysqli_stmt_close($stmt1);

    // .. e ci sono posti prenotati
    if (isset($_POST['posti'])){

        try{
            mysqli_autocommit($con, false);

            // 3 - Blocco la tabella prenotazioni 
            $query="SELECT * FROM prenotazioni FOR UPDATE";
            if (!$result=mysqli_query($con, $query)){
                throw new Exception("Error executing query: ".mysqli_error($con));
            }

            // Per ogni posto prenotato, guardo se c'è corrispondenza nel database
            foreach($_POST['posti'] as $key=>$val){

                $postazione=$val;
                $posto=$postazione[0];
                $fila=$postazione[1];

                $query2="SELECT stato, utente FROM prenotazioni WHERE fila=? AND posto=? ";
                if (!($stmt2=mysqli_prepare($con,$query2))){
                    throw new Exception("Error in preparing select position statement ");
                }
                mysqli_stmt_bind_param($stmt2, "is", $fila, $posto);
                if (!mysqli_execute($stmt2)){
                    throw new Exception("Error in executing select position statement ".mysqli_stmt_error($stmt2));
                }
                mysqli_stmt_bind_result($stmt2, $stato, $id);

                // 4A - Il posto era già nel DB 
                // quindi già stato prenotato/comprato ... 
                if(mysqli_stmt_fetch($stmt2)){
                    mysqli_stmt_close($stmt2);

                    // 4A.1 - ... prenotato ma non dall'utente
                    if ($stato == 'occupied' && $id!==$idUtente){
                        // DEVO LIBERARE TUTTI I POSTI PRENOTATI DA UTENTE
                        throw new Exception ("Position ".$postazione." already occupied by an other user");
                    }
                    // 4A.2 - ... già acquistato 
                    else if ($stato=='booked'){
                        // DEVO LIBERARE TUTTI I POSTI PRENOTATI DA UTENTE
                        throw new Exception ("Position ".$postazione." already booked by an other user");
                    }
                    
                }
                else {
                    // 4B - Il posto è libero
                    mysqli_stmt_close($stmt2);
                    //  ... lo prenoto per l'utente
                    $query3 = "INSERT INTO prenotazioni(posto, fila, stato, utente) VALUES(?,?,?,?)";
                    $stmt3 = mysqli_prepare($con, $query3);
                    if(!$stmt3){
                        throw new Exception("Error in preparing insert statement");
                    }
                    $status = "occupied";
                    mysqli_stmt_bind_param($stmt3, "isii", $fila, $posto, $status , $idUtente);
                    if (!mysqli_execute($stmt3)){
                        throw new Exception("Error in executing insert statement ".mysqli_stmt_error($stmt3));
                    }
                    mysqli_stmt_close($stmt3);

                    }
                } // fine : foreach posto prenotato

                $query4 = "SELECT * FROM prenotazioni WHERE utente=".$idUtente." AND stato='occupied'";
                if (!($result4=mysqli_query($con, $query4))){
                    throw new Exception("Error executing select of occupied position from the current user");
                }
                while (($occ = mysqli_fetch_assoc($result4))){
                    $fila = $occ['fila'];
                    $posto = $occ['posto'];
                    $query5 = "UPDATE prenotazioni SET stato='booked' WHERE fila=".$fila." AND posto='".$posto."' AND utente=".$idUtente."";
                    if (!($result5= mysqli_query($con, $query5))){
                        throw new Exception("Error executing query: ".mysqli_error($con));
                    }
                    
                    $output .= "Position ".$posto."".$fila." correctly booked.";
                
                }
                if (!mysqli_commit($con)) { // per avere il corretto messaggio di errore
                    throw new Exception("Commit failure");
                }

                header('Location: posti.php?msg='.$output);
                exit();
        // fine : se l'utente esiste

        } catch (Exception $e){
            mysqli_rollback($con);
            echo "Rollback " . $e->getMessage()."\n";
            mysqli_autocommit($con,true);
            $msg="";
            // DEVO LIBERARE TUTTI I POSTI PRENOTATI DA UTENTE
            $query6="DELETE FROM prenotazioni WHERE stato='occupied' AND utente=".$idUtente."";
            if (!mysqli_query($con, $query6)){
                $msg .= "Error in freeing all the position occupied by the current user.".mysqli_error($con);
            }
            if (empty($msg)){
                $msg .="All the positon occupied by the current user are free now.";
            }
            $redirect="posti.php";
            header('Location: '.$redirect.'?msg='.$e->getMessage().", ".$msg);
            exit();
        }
    }
} 
mysqli_close($con);
    
?>