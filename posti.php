<?php
$session = true;
if( session_status() === PHP_SESSION_DISABLED ){
    $session = false;
}
else if( session_status() !== PHP_SESSION_ACTIVE ){
    session_start();
}
if (!isset($_SESSION['logged'])){
    header('Location: index.php');
}

?>
<!DOCTYPE html>
<html>
<head>
<?php 
include 'connectionDB.php';
include 'checkDB.php';
include 'createTable.php';
include 'getInfo.php'; 
include 'logorsign.php';
?>
<meta charset= "utf-8">
  <meta name="author" content="Matilde Pulidori">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel = "stylesheet" type="text/css" href="styleCSS.css">
  <script src="jquery-3.4.1.js"></script>
  <script type="text/javascript" src="posti.js"></script>
  <title>HOME</title>
</head>
  
<body>
<header>
    <h1>Prenotazioni Aereo</h1>
</header>
<div class='logorsign'>
<form name='logorsign' method="POST" action="logout.php">
<?php 
    $con  = getConnectionDB();
    $logged = false;
    if ( isset($_SESSION['user']) && !(empty($_SESSION['user'])) ){
        $logged=true;
        $_SESSION['logged']="yes";
        echo "<label>Benvenuto ".$_SESSION['user']."</label>";
        echo "<input type='submit' value='Logout'>";
    }
    
?>
</form>
</div>


<div class="main">
    <div class="posti">
    <h2>Posti</h2>
    <form name='formposti' method='POST' action='acquista.php'> 
    <?php 
        
        $m = 10;  // file
        $n = 6;   // posti
        $_SESSION['n']=$n;
        $_SESSION['m']=$n;
        checkOrResetMatrix($con, $m, $n);
        if ($logged==true){
            createEditableTable($con, $m, $n, $_SESSION['user']);
        }
    ?>
    </form>
    </div>
    <?php 
        $total = getTotal($con);
        $occupied= getPrenotati($con);
        $booked = getOccupati($con);
        $free = $total - ($booked + $occupied);
        mysqli_close($con);
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
</body>
</html>