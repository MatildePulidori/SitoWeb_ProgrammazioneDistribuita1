<?php
function checkIfInDB($con, $fila, $colonna){
    $defaultSatus = "free";
    $query = "SELECT fila, posto, stato FROM prenotazioni WHERE fila=? AND posto = ?";
    $stmt = mysqli_prepare($con, $query);
    if (!$stmt){
        die ("Errore di query: ".mysqli_error($con));
    }  else {
        mysqli_stmt_bind_param($stmt, "is", $fila, $colonna);
        if (!(mysqli_stmt_execute($stmt))){
            die("Statement error: ".mysqli_stmt_error($stmt5) );     
        } else {
            mysqli_stmt_bind_result($stmt,  $f, $p, $stato);
            if( mysqli_stmt_fetch($stmt) && ($f == $fila && $p == $colonna)){
                    return $stato;
            } else {
                return $defaultSatus;
            }
        }
    }
    mysqli_stmt_close($stmt);
}

function createTable($con, $m, $n){
    $associaz =  array( 0=>'A', 1=>'B', 2=>'C', 3=>'D', 4=>'E', 5=>'F', 6=>'G', 7=>'H', 8=>'I', 9=>'J', 10=>'K', 11=>'L', 12=>'M', 13=>'N', 14=>'O', 15=>'P', 16=>'Q', 17=>'R', 18=>'S', 19=>'T', 20=>'U', 21=>'V', 22=>'W', 23=>'X', 24=>'Y', 25=>'Z') ;
    $i = $j = 0;
    echo "<table>";
    while ($j < $m ){  // righe 
        echo "<tr>"; 
        $fila = $j+1;
        $i=0;
        while ($i < $n  && $i < 25){ // colonne

            $colonna = $associaz[$i];
           
            $x = $colonna.''.$fila;
            $stato = checkIfInDB($con, $fila, $colonna); 
            if ($stato == 'occupied'){
               echo "<td class='$stato'><input  type='checkbox' name='$x' value='$x' id='$x' disabled><label for='$x'>$x</label></td>  ";
            } else if ($stato == 'booked'){
                echo "<td class='$stato'><input type='checkbox' name='$x' value='$x' id='$x' disabled><label for='$x'>$x</label></td>  ";
            } else if ($stato == 'free'){
                echo "<td class='free'><input type='checkbox' name='$x' value='$x' id='$x' disabled><label for='$x'>$x</label></td>  ";
            }
            $i++;
        }
        echo "</tr>";
    $j++;
    }
    echo "</table>";
}

function checkIfInDBUser($con, $fila, $colonna, $user){
    $defaultSatus = "free";

    $query1 = "SELECT id FROM utenti WHERE username=?";
    $stmt1 = mysqli_prepare($con, $query1);
    if (!$stmt1){
        die ("Errore di query: ".mysqli_error($con));
    } else {
        mysqli_stmt_bind_param($stmt1, "s", $user);
        if (!(mysqli_stmt_execute($stmt1)) ){
            die("Statement error: ".mysqli_stmt_error($stmt1) );    
        }else{
            mysqli_stmt_bind_result($stmt1, $id);
            if (mysqli_stmt_fetch($stmt1) ){
                mysqli_stmt_close($stmt1);

                $query2 = "SELECT fila, posto, stato, utente FROM prenotazioni WHERE fila=? AND posto =?";
                $stmt2 = mysqli_prepare($con, $query2);
                if (!$stmt2){
                    die ("Errore di query: ".mysqli_error($con));
                }  else {
                    mysqli_stmt_bind_param($stmt2, "is", $fila, $colonna);
                    if (!(mysqli_stmt_execute($stmt2))){
                        die("Statement error: ".mysqli_stmt_error($stmt2) );     
                    } else {
                        mysqli_stmt_bind_result($stmt2,  $f, $p, $stato, $idUtente);
                        if( mysqli_stmt_fetch($stmt2) && ($f == $fila && $p == $colonna)){
                            if ($stato=='occupied' &&  $idUtente==$id){
                                return $stato."user";
                            } else {
                                return $stato;
                            }
                        } else {
                            return $defaultSatus;
                        }
                    }
                }
                mysqli_stmt_close($stmt2);
            }
        }
    }
}



function createEditableTable($con, $m, $n, $user){
    $associaz =  array( 0=>'A', 1=>'B', 2=>'C', 3=>'D', 4=>'E', 5=>'F', 6=>'G', 7=>'H', 8=>'I', 9=>'J', 10=>'K', 11=>'L', 12=>'M', 13=>'N', 14=>'O', 15=>'P', 16=>'Q', 17=>'R', 18=>'S', 19=>'T', 20=>'U', 21=>'V', 22=>'W', 23=>'X', 24=>'Y', 25=>'Z') ;
    $i = $j = 0;
    
    echo "<table>\n"; 
    while ($j < $m  ){  // righe 
        echo "<tr>\n"; 
        $fila = $j+1;
        $i=0;
        while ($i < $n  && $i < 25){ // colonne

            $colonna = $associaz[$i];
           
            $x = $colonna.''.$fila;
            $stato = checkIfInDBUser($con, $fila, $colonna, $user); 
            if ($stato == 'occupied'){
               echo "<td class='$stato'><input type='checkbox' name='posti[]' value='$x' id='$x'><label for='$x'>$x</label></td>\n";
            }  else if ($stato == 'occupieduser'){
                echo "<td class='occupieduser'><input type='checkbox' name='posti[]' value='$x' id='$x' checked><label for='$x'>$x</label></td>\n";
            } else if ($stato == 'booked'){
                echo "<td class='$stato'><input type='checkbox' name='posti[]' value='$x' id='$x' disabled><label for='$x'>$x</label></td>\n";
            } else if ($stato == 'free'){
                echo "<td class='free'><input type='checkbox' name='posti[]' value='$x' id='$x'><label for='$x'>$x</label></td>\n";
            }
            $i++;
        }
        echo "</tr>\n";
        $j++;   
    }
    echo "</table>\n";
    echo "<input type='button' name='aggiorna' value='Aggiorna' onclick='window.location.reload()'>\n";
    echo "<input type='submit' name='acquista' value='Acquista' >\n";

 
    
}
?>