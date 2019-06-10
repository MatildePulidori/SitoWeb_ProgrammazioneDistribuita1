<!DOCTYPE html>
<html>
<head>
<?php 
include 'controlloDB.php';
include 'createTable.php';
include 'getInfo.php'; 
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

    <div class="logorsign">

        <div class="login">
            <label>Login</label><br>
            <div class="log-content">
            <form> 
            <input type="text" name="usernameLogin" placeholder="Username"><br>
            <input type="password" name="passwordLogin" placeholder="Password">
            <input type="submit" value="Login">
            </div>
            </form>
        </div>
        <div class="signup">
        <label>Registrazione</label><br>
            <div class="log-content">
            <form> 
            <input type="text" name="usernameSignUp" placeholder="Username"><br>
            <input type="password" name="passwordSignUp" placeholder="Password">
            <input type="submit"value="Signup">
            </div>
            </form>
        </div>
   
</div>


<div class="main">
    <div class="posti">
    <h2>Posti</h2>
    <form name="f">

        <?php
        $m = 10;  // file
        $n = 6;   // posti
        $con  = getConnectionDB();
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
</body>
</html>