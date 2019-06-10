<?php

function createTable($con, $m, $n){
    $associaz =  array( 0=>'A', 1=>'B', 2=>'C', 3=>'D', 4=>'E', 5=>'F', 6=>'G', 7=>'H', 8=>'I', 9=>'J', 10=>'K') ;
    $i = $j = 0;
    echo "<table>";
    while ( $i < $m  && $i < 25 ){  // righe 
        echo "<tr>";
        $fila = $associaz[$i];
        $j=0;
        while ($j < $n){ // colonne

            $colonna = $j+1;
            echo "<td>";

            $x = $fila.''.$colonna;
            $stato = checkIfInDB($con, $fila, $colonna); 
            if ($stato == 'occupied'){
               echo "<label class='$stato'><input type='checkbox' class='$stato' name='$x' value='$x' disabled >$x</label>";
            } else if ($stato == 'booked'){
                echo "<label class='$stato'><input type='checkbox' class='$stato' name='$x' value='$x' disabled checked>$x</label>";
            } else if ($stato == 'free'){
                echo "<label class='free'><input type='checkbox' class='free' name='$x' value='$x' disabled>$x</label>";
            }
            echo "</td>";
            $j++;
        }
        echo "</tr>";
    $i++;
    }
    echo "</table>";
}

function checkIfInDB($con, $fila, $colonna){
    $defaultSatus = "free";
    $query = "SELECT fila, posto, stato FROM prenotazioni WHERE fila=? AND posto = ?";
    $stmt = mysqli_prepare($con, $query);
    if (!$stmt){
        die ("Errore di query: ".mysqli_error($con));
    }  else {
        mysqli_stmt_bind_param($stmt, "si", $fila, $colonna);
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
?>