<?php
function checkOrResetMatrix($con, $x, $y){
  $m = $x; // file
  $n = $y; // posti 
  $totPosti = $m*$n;
  transaction($con, $m, $n, $totPosti);
}
function transaction($con, $m, $n, $totPosti){
    try {
        mysqli_autocommit($con, false);

        // controllo parametri
        $query1 = "SELECT * FROM parametri FOR UPDATE";
        $query11 = "SELECT * FROM prenotazioni FOR UPDATE";
        if ( !($result1 = mysqli_query($con,$query1)) ) {
            throw new Exception("Error in select 'parametri' for update query.");
        }
        if ( !($result11=mysqli_query($con, $query11)) ){
            throw new Exception("Error in select 'prenotazioni' for update query.");
        }

        // CASO A) se ci sono già dei parametri ...
        if (($rows=mysqli_num_rows($result1))==1){
            $infos = mysqli_fetch_assoc($result1);

        // ma se i paramteri sono cambiati, resetto nel DB
        if ($infos['file']!=$m || $infos['colonne']!=$n){

            // 1- elimino gli elementi nelle prenotazioni
            $query2 = "DELETE FROM prenotazioni";
            // 2 - elimino i vecchi parametri
            $query3 = "DELETE FROM parametri";
            // 3- scrivo i nuovi parametri
            $query4 = "INSERT INTO parametri(file, colonne, totPosti) VALUES (?, ?, ?)";

            if ( !($result2 = mysqli_query($con, $query2)) ){
                throw new Exception("Deleting table 'prenotazione' failed.");
            }
            if ( !($result3 = mysqli_query($con, $query3)) ){
                throw new Exception("Deleting table 'parametri' failed.");
            }
            if ( !($stmt = mysqli_prepare($con, $query4))){
                throw new Exception("Insert of new parameters failed.");
            }

            mysqli_stmt_bind_param($stmt, "iii", $m, $n, $totPosti);
            if ( !mysqli_execute($stmt) ) {
                throw new Exception("Stmt error: ".mysqli_stmt_error($stmt) );
            }
            mysqli_stmt_close($stmt);
            if (!mysqli_commit($con)) { // per avere il corretto messaggio di errore
                throw new Exception("Commit failure");
            }

        } else if ($infos['file']==$m && $infos['colonne']==$n){
            $totPosti = $infos['totPosti'];
        }

        // CASO B) non ci sono ancora parametri
        } else if (($rows=mysqli_num_rows($result1))==0){

            // 3- scrivo i nuovi parametri
            $query4 = "INSERT INTO parametri(file, colonne, totPosti) VALUES (?, ?, ?)";
            if ( !($stmt = mysqli_prepare($con, $query4))){
                throw new Exception("Insert of first parameters failed.");
            }
            mysqli_stmt_bind_param($stmt, "iii", $m, $n, $totPosti);
            if ( !mysqli_execute($stmt) ) {
                throw new Exception("Stmt error: ".mysqli_stmt_error($stmt) );
            }
            mysqli_stmt_close($stmt);
        }

    } catch(Exception $e) {
        mysqli_rollback($con);
        echo "Rollback " . $e->getMessage();
        mysqli_autocommit($con,true);
    }
        // pulizia tabella prenotazioni andata a buon fine
        // pulizia tabella parametri andata a buon fine
        // inserimento nuovi parametri andato a buon fine
}
?>