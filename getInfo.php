<?php
function getTotal($con){
    $total = 0;
    $query= "SELECT totPosti FROM parametri";
    $result = mysqli_query($con, $query);
    if (!$result){
        die('Errore di query: '.mysqli_error($con));
    } else {
        $rows = mysqli_num_rows($result);
        if ($rows!=1){
            die('Errore parametri in db.');
        }else{
            $infos = mysqli_fetch_assoc($result);
            $total = $infos['totPosti'];
        }
    }
    return $total;
}

function getPrenotati($con){
    $totPrenotati = 0;
    $query= "SELECT totPrenotati FROM parametri";
    $result = mysqli_query($con, $query);
    if (!$result){
        die('Errore di query: '.mysqli_error($con));
    } else {
        $rows = mysqli_num_rows($result);
        if ($rows!=1){
            die('Errore parametri in db.');
        }else{
            $infos = mysqli_fetch_assoc($result);
            $totPrenotati = $infos['totPrenotati'];
        }
    }
    return $totPrenotati;
}

function getOccupati($con){
    $totOccupatii=0;
    $query= "SELECT totOccupati FROM parametri";
    $result = mysqli_query($con, $query);
    if (!$result){
        die('Errore di query: '.mysqli_error($con));
    } else {
        $rows = mysqli_num_rows($result);
        if ($rows!=1){
            die('Errore parametri in db.');
        }else{
            $infos = mysqli_fetch_assoc($result);
            $totOccupati = $infos['totOccupati'];
           
        }
    }
    return $totOccupatii;
}

?>