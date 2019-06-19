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
    if (!isset($_SESSION['265353_user']) ){
        echo "Utente non loggato.";
    }
   
    if (isset($_POST['row']) && isset($_POST['column']) && !empty($_POST['row']) && !empty($_POST['column'])){
        $row = $_POST['row'];
        $column = $_POST['column'];
        
        try{
            mysqli_autocommit($con, false);
            // Seleziono l'utente
            $queryUtente = "SELECT id FROM utenti WHERE username='".$_SESSION['265353_user']."'";
            if (!($resultUtente=mysqli_query($con, $queryUtente))){
                throw new Exception("Error in executing select utente query ".mysqli_error($con) );
            }
            if (!($info=mysqli_fetch_assoc($resultUtente))){
                throw new Exception("Error: non esiste l'utente");
            } 

            $sessionIdUtente=$info['id'];
            mysqli_free_result($resultUtente);

            // Guardo se nel DB c'è il posto selezionato 
            $query1="SELECT fila, posto, stato, utente FROM prenotazioni WHERE fila=? AND posto=? FOR UPDATE";
            
            if (!$stmt1 = mysqli_prepare($con, $query1)){
                throw new Exception("Error in preparing select statement1");
            }
            mysqli_stmt_bind_param($stmt1, "is", $row, $column);
            if ( !mysqli_execute($stmt1) ) {
                throw new Exception("Error executing prenotation select: ".mysqli_stmt_error($stmt2) );
            }
  
            mysqli_stmt_bind_result($stmt1, $fila, $posto, $stato, $idUtentePosto); 
            mysqli_stmt_store_result($stmt1);
           
            if (mysqli_stmt_num_rows($stmt1)==1){
                mysqli_stmt_fetch($stmt1);
                // se il posto è nella tabella prenotazioni
                // CASO A) è già acquistato
                mysqli_stmt_close($stmt1);
                if ($stato=="booked"){
                    echo "booked";

                } // CASO B) è già occupato.. 
                else if ($stato=="occupied"){
    
                    // B1) ...occupato, dall'utente
                    if ($idUtentePosto == $sessionIdUtente){

                        // quindi vuol dire che l'utente vuole liberare il posto
                        $query3="DELETE FROM prenotazioni WHERE utente=".$idUtentePosto." AND fila=".$row." AND posto='".$column."'";
                        if (!($result3=mysqli_query($con, $query3))){
                            throw new Exception("Error executing query 3 ".mysqli_error($con));
                        }
                        echo "free";

                    } else{
                        // B2) ... occupato, da un altro utente
                        // ma ora lo sto prenotando io

                        $query4="UPDATE prenotazioni SET stato='occupied', utente=? WHERE fila=? AND posto=? ";
                        if (!($stmt4=mysqli_prepare($con, $query4))){
                            throw new Exception("Error preparing update statement4");
                        }
                       
                        mysqli_stmt_bind_param($stmt4, "iis", $sessionIdUtente, $row, $column);
                        if (!mysqli_execute($stmt4)){
                            throw new Exception("Error executing update statement: ".mysqli_stmt_error($stmt4));
                        }
                        mysqli_stmt_close($stmt4);
                        echo "occupieduser";
                    }
                }
               
            } else {
                // CASO C) il posto è libero (non è presente nel DB)
                // quindi devo inserirlo, con stato 'occupied'

                $query5="INSERT INTO prenotazioni(posto, fila, stato, utente) VALUES(?,?,?,?)";
                if (!($stmt5=mysqli_prepare($con, $query5))){
                    throw new Exception("Error preparing insert statement5");
                }
                $currStatus="occupied";
                mysqli_stmt_bind_param($stmt5, "sisi", $column, $row, $currStatus, $sessionIdUtente);
                if (!mysqli_execute($stmt5)){
                    throw new Exception("Error executing insert statement: ".mysqli_stmt_error($stmt5).", session ID: ".$sessionIdUtente." ".$_SESSION['265353_user']);
                }
                mysqli_stmt_close($stmt5);
                echo "occupieduser";
            } 
            if (!mysqli_commit($con)) { // per avere il corretto messaggio di errore
                throw new Exception("Commit failure");
            }
           
        } catch( Exception $e){
            mysqli_rollback($con);
            echo "Rollback " . $e->getMessage();
            mysqli_autocommit($con,true);
        }


        
        

    }
    mysqli_close($con);
?>