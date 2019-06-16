<?php
$session = true;
if( session_status() === PHP_SESSION_DISABLED  )
    $session = false;
else if( session_status() !== PHP_SESSION_ACTIVE ){
    session_start();
}
?>
<?php 
include 'connectionDB.php';
include 'checkDB.php';
include 'createTable.php';
include 'getInfo.php'; 
include 'logorsign.php';
?>
<!DOCTYPE html>
<html>
<head>

<?php
if (isset($_SESSION['logged']) && $_SESSION['logged']=="yes"){
    header('Location: posti.php');
}
$log = $sign = false;
$con  = getConnectionDB();
$log = checkLoginDB($con);
$sign = checkRegisterDB($con);

?>
<meta charset= "utf-8">
  <meta name="author" content="Matilde Pulidori">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel = "stylesheet" type="text/css" href="styleCSS.css">
  <title>HOME</title>
</head>
  
<body>
<header>
    <h1>Prenotazioni Aereo</h1>
</header>

<div class='logorsign'>
<?php 
    if ( $log==false && $sign==false){ 
            printLoginArea();
            printSingupArea();
    } else {
        $redirect = "posti.php";
        if ($log===true){
            header('Location: ' .$redirect);
        } else if ($sign===true){
            echo "Registrazione avvenuta con successo. \n";
            header('Location: ' .$redirect);
        }

    }
?>
</div>


<div class="main">
    <div class="posti">
    <h2>Posti</h2>
    <form name='f'>
    <?php 
        
        $m = 10;  // file
        $n = 6;   // posti
        checkOrResetMatrix($con, $m, $n);
        createTable($con, $m, $n);
        ?>

    </form>
    </div>

    
    <?php 
        $total = getTotal($con);
        $occupied= getPrenotati($con);
        $booked = getOccupati($con);
        $free = $total - ($booked + $occupied);
    ?>
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
<?php mysqli_close($con);?>
</body>
</html>