<?php
    include 'connectionDB.php';
    $con = getConnetionDB();
    if (!isset($_SERVER['user']) ){
        echo "Utente non loggato.";
    }
    if (isset($_POST['row']) && isset($_POST['column']) && !empty($_POST['row']) && !empty($_POST['column'])){
        $row = $_POST['row'];
        $column = $_POST['column'];
        
        try{
            mysqli_autocommit($con, false);
            $query1="SELECT fila, posto, stato, utente FROM prenotazioni WHERE fila=? AND posto=? FOR UPDATE";
            
            if (!$stmt1 = mysqli_prepare($con, $query1)){
                throw new Exception("Error in query");
            }
            mysqli_stmt_bind_param($stmt1, "ii", $row, $column);
            if ( !mysqli_execute($stmt1) ) {
                throw new Exception("Stmt error: ".mysqli_stmt_error($stmt2) );
            }
            mysqli_stmt_bind_result($stmt1, $fila, $posto, $stato, $utente); 
            if (mysqli_stmt_fetch($stmt1)){
                if ($stato=="booked"){
                    echo "booked";
                } else if ($stato=="occupied"){
                    if ($utente==$_SERVER['user']){
                        // vuol dire che l'utente vuole liberare il posto
                        $query3="UPDATE prenotazioni SET stato=? AND utente=? ";
                        if (!($stmt3=mysqli_prepare($con, $stmt3))){
                            throw new Exception("Error stmt3");
                        }
                        $currStatus="free";
                        mysqli_stmt_bind_param($stmt3, "si", $currStatus, $utente );
                        if (!mysqli_execute($stmt3)){
                            throw new Exception("Error stmt3");
                        }
                        

                        echo "free";
                    }else{
                        // vuol dire che il posto era prenotato ma ora lo sto prenotando io


                        echo "occupieduser";
                    }
                }
            } else{
                echo "free";
            }

        } catch( Exception $e){
            mysqli_rollback($con);
            echo "Rollback " . $e->getMessage();
            mysqli_autocommit($con,true);
        }
    }
    mysqli_close($con);
?>