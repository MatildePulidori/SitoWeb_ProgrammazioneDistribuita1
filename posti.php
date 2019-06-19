<?php
    $session = true;
    if( session_status() === PHP_SESSION_DISABLED ){
        $session = false;
    }
    else if( session_status() !== PHP_SESSION_ACTIVE ){
        session_start();

    }
    // redirect https
    if( empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off' ) {
        $redirect = 'https://' . $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        header('Location: '.$redirect);
    }

    // if non c'è nessun utente loggato
    if (!isset($_SESSION['265353_user'])&& empty($_SESSION['265353_user'])){
        $redirect ="index.php";
        header('Location: '.$redirect);
        exit();
    }

    // controllo se sono passati 2 minuti 
    include 'f_inactivity.php';
    checkInactivity();

    // includo tutti i file utili
    include 'f_checkDB.php';
    include 'f_connectionDB.php';
    include 'f_createTable.php'; 
    include 'f_logorsign.php';

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
  <script src="jquery-3.4.1.js"></script>
  <script type="text/javascript" src="posti.js"></script>
  <script type="text/javascript" src="utilities.js"></script>
  <script>
   
  </script>
  <title>HOME</title>
</head>
  
<!-------------- body ------------>
<body>

    <!-------------- se non ci sono javascript : errore ------------>
    <noscript>
        <p>Abilita javascript per poter usare la pagina.<p>
    </noscript>

     <!-------------- se non ci sono cookie attivi: qui dentro messaggio errore----------->
    <div id="alertcookie"></div>


<div id="body">
<script>
if (testCookies()==false){
    document.getElementById("body").style.display="none";
    document.getElementById("alertcookie").innerHTML="<p>Abilita i cookie per poter usare la pagina.<p>";
} else {
    document.getElementById("body").style.display="block";
}
</script>
<header>
    <h1>Prenotazioni Aereo</h1>
</header>

<!-------------- div login/signup ------------>
<div class='logorsign'>

<?php 
    // se c'è un utente loggato
    if ( isset($_SESSION['265353_user']) && !(empty($_SESSION['265353_user'])) ){
        $_SESSION['265353_logged']="yes";
?>
    <form name='logorsign' method='POST' action='page_logout.php'>
       <label>Benvenuto <?php echo $_SESSION['265353_user']; ?></label>
       <input type='submit' value='Logout'>
       </form>
        <div id='bokingresponse'>
            <p id='response'></p>
        <!-- stampo messaggi dal redirect se ce ne sono -->
        <?php
        if (isset($_GET['msg'])){
            echo "<p>".$_GET['msg']."</p>";
            $_GET = array();
        }
        ?>
        </div>

    <?php } ?>
</div>

<!-------------- div main:posti+info ------------>
<div class="main">


    <div class="posti">
    <h2>Posti</h2>
        <!-- 1) stampo la mappa (modificabile) dei posti -->
    <form name='formposti' method='POST' action='page_acquista.php'> 
    <?php 
        $m = 10;  // file
        $n = 6;   // posti
        $_SESSION['265353_n']=$n;
        $_SESSION['265353_m']=$m;
        checkOrResetMatrix($con, $m, $n);
        if ( isset($_SESSION['265353_logged']) && $_SESSION['265353_logged']=="yes" ){
            createEditableTable($con, $m, $n, $_SESSION['265353_user']);
        }
    ?>
    </form>
</div>



        
<div class="info"> 
    <!--  2.1) prendo le informazioni sullo stato delle prenotazioni -->
    <?php 
    $total = getTotal($con);
    $occupied= getPrenotati($con);
    $booked = getOccupati($con);
    $free = $total - ($booked + $occupied);
    mysqli_close($con);
    ?>

    <h2>Info</h2>
    <!-- 2.2) stampo le informazioni -->
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
</div>
</body>
</html>