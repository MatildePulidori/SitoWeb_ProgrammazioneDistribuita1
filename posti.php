<?php
$session = true;
if( session_status() === PHP_SESSION_DISABLED ){
    $session = false;
}
else if( session_status() !== PHP_SESSION_ACTIVE ){
    session_start();
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
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
  <script type="text/javascript" src="posti.js"></script>
  <title>HOME</title>
</head>
  
<body>
<header>
    <h1>Prenotazioni Aereo</h1>
</header>
<div class='logorsign'>
<form method="POST" action="logout.php">
<?php 
    $con  = getConnectionDB();
    $logged = false;
    if ( isset($_SESSION['user']) && !(empty($_SESSION['user'])) ){
        $logged=true;
        echo "Benvenuto ".$_SESSION['user'];
        echo "<input type='submit' value='Logout'>";
    } else {
        header('Location: index.php');
    } 
    
?>
<form>
</div>


<div class="main">
    <div class="posti">
    <h2>Posti</h2>
    <form name="f" method='POST' action="<?php $_SERVER['PHP_SELF'] ?>">

        <?php
        $m = 10;  // file
        $n = 6;   // posti
        checkOrResetMatrix($con, $m, $n);

        if ($logged==true){
            createEditableTable($con, $m, $n, $_SESSION['user']);
        }
        else{
            createTable($con, $m, $n);
        }
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
</body>
</html>