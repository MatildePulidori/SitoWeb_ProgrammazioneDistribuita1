<?php
// avvio la sessione
$session = true;
if( session_status() === PHP_SESSION_DISABLED  )
    $session = false;
else if( session_status() !== PHP_SESSION_ACTIVE ){
    session_start();
}
// redirect https
if( empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off' ) {
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] .
    $_SERVER['REQUEST_URI'];
    header('Location: '.$redirect);
}

// includo file utili .php
include 'f_checkDB.php';
include 'f_connectionDB.php';
include 'f_createTable.php'; 
include 'f_logorsign.php';

// connessione al DB + inizializz. valori di login/signup
$con  = getConnectionDB();
?>

<!DOCTYPE html>
<html>

<!-------------- head  ------------>
<head>
<meta charset= "utf-8">
  <meta name="author" content="Matilde Pulidori">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel = "stylesheet" type="text/css" href="styleCSS.css">
  <script type="text/javascript" src="utilities.js"></script>
   
  <title>HOME</title>
</head>
  

<!-------------- body  ------------>
<body>

  

     <!-------------- se non ci sono cookie attivi: qui dentro messaggio errore----------->
    <div id="alertcookie"></div>


<div id="body" >
<script>
if (testCookies()==false){
    document.getElementById("body").style.display="none";
    document.getElementById("alertcookie").innerHTML="<p>Abilita i cookie per poter usare la pagina.<p>";
} else {
    document.getElementById("body").style.display="block";
}
</script>  <!-------------- se non ci sono javascript : errore ------------>
<noscript>
        <p>Abilita javascript per poter usare la pagina.<p>
</noscript>

    <!-------------- header ------------>
<header>
    <h1>Prenotazioni Aereo</h1>
</header>

    <!-------------- div login/signup ------------>
    <div class='logorsign'>
    <?php
        // stampo messaggi dal redirect se ce ne sono
        if (isset($_GET['msg'])){
            echo "<div id='outputresponse'><p>".$_GET['msg']."</p></div>";
        }
        // controllo se qualcuno ha fatto login/signup
        if ( !isset($_SESSION['265353_logged']) || $_SESSION['265353_logged']=="no" ){ 
            // se no: stampo le aree per fare login/signup
            printLoginArea();
            printSingupArea();
        } else if (isset($_SESSION['265353_logged']) && $_SESSION['265353_logged']=="yes" ){
            // se qualcuno ha fatto login/signup 
            // redirect
            $redirect = "posti.php";
            header('Location: ' .$redirect);
            exit();
        }
         
    ?>
    </div>

    <!-------------- div main:posti+info ------------>
    <div class="main">

    <div class="posti">
        <h2>Posti</h2>
        <!-- 1) stampo la mappa (non modificabile) dei posti -->
        <form name='f'>
        <?php 
            $m = 10;  // file
            $n = 6;   // posti
            checkOrResetMatrix($con, $m, $n);
            createTable($con, $m, $n);
        ?>
        </form>
    </div>


        <!--  2) prendo le informazioni sullo stato delle prenotazioni -->
    <?php 
        $total = getTotal($con);
        $occupied= getPrenotati($con);
        $booked = getOccupati($con);
        $free = $total - ($booked + $occupied);
    ?>
        <!-- 3) stampo le informazioni -->
    <div class="info"> 
        <h2>Info</h2>
        <div class="table-info">
        <table>
        <tr><td>Posti totali: </td><td><?php echo $total; ?></td></tr>
        <tr><td>Posti liberi: </td><td><?php echo $free; ?></td></tr>
        <tr><td>Posti prenotati:</td><td> <?php echo $occupied; ?></td></tr>
        <tr><td>Posti occupati: </td><td><?php echo $booked; ?></td></tr>
        </table>
        </div>
    </div>

    </div>

    <!-- chiudo la connessione -->
    <?php mysqli_close($con);?>
</div>
</body>
</html>