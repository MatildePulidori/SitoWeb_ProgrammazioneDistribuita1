<?php

function validateEmail($email){
  $regexpmail= '/^([a-z0-9]+)((\.)?[a-z0-9\_\-]?)([@])([a-z0-9\-]+\.)+[a-z]{2,6}$/';
  if (preg_match($regexpmail,$email)==true){
    return true;
  }
  else{
    echo "Inserisci una mail valida: deve contenere @, un nome di dominio ed un top-level domain - TLD). Esempio: ilmio.nome1@ilmiodominio.it, ilmionome@posta.ilmiodominio.com. ";
    return false;
  }
}

function validatePassword($password){
    $regexpwd= '/^((([a-z])+([A-Z0-9])+)([a-zA-Z0-9]*)|(([A-Z0-9])+([a-z])+([a-zA-Z0-9]*)))$/';
    if (preg_match($regexpwd,$password)==true){
      return true;
    }
    else{
      echo "Inserisci una password valida: deve contenere almeno una minuscola e almeno una maiuscola/un numero.";
      return false;
    }
  }


function checkLoginDB($con){
    if ( isset($_POST['usernameLogin']) && isset($_POST['passwordLogin']) && !empty($_POST['usernameLogin']) && !empty($_POST['passwordLogin'])  ){
        $email = $_POST['usernameLogin'];
        $password = $_POST['passwordLogin'];

        if (validateEmail($email)==true && validatePassword($password)==true){
            

            $query = "SELECT username, password FROM utenti WHERE username=?";
            if ( !($stmt = mysqli_prepare($con, $query)) ){
                die("Error in preparing statement.");
            }

            mysqli_stmt_bind_param($stmt, "s", $email);
            if ( !(mysqli_execute($stmt))){
                die("Error in execution of statement ".mysqli_stmt_error($stmt));
            } else {

                mysqli_stmt_bind_result($stmt, $usrnm, $pwd);

                if ( mysqli_stmt_fetch($stmt) ){
                    // check if the password is the same
                    if ($email == $usrnm && password_verify($password, $pwd)){

                        $_SESSION['265353_user']=$email;
                        $_SESSION['265353_logged']="yes";
                        return true;
                    } else {
                        return false;
                    }
                } else { 
                    return false;
                } 
            }
        } else {
            return false;
        }
    }
    else {
        return false;
    }
}

function printLoginArea(){
    $string = " <div class='login'>\n";
    $string .= "  <label>Login</label>\n";
    $string .= "   <div class='log-content'>\n";
    $string .= "    <form name='loginForm' method='POST' onSubmit='return validate(usernameLogin, passwordLogin);' action='page_inputlogsign.php'>\n"; 
    $string .= "    <input type='mail' name='usernameLogin' placeholder='Username' value=''>\n";
    $string .= "    <input type='password' name='passwordLogin' placeholder='Password' value=''>\n";
    $string .= "    <input type='submit' value='Login' >\n";
    $string .= "   </div>\n   </form>\n  </div>\n<br>";
    echo $string;
}


function checkRegisterDB($con){
    if ( isset($_POST['usernameSignUp']) && isset($_POST['passwordSignUp']) && !empty($_POST['usernameSignUp']) && !empty($_POST['passwordSignUp'])  ){
        $email = $_POST['usernameSignUp'];
        $password = $_POST['passwordSignUp'];

        if (validateEmail($email)==true && validatePassword($password)==true){
            try{
               
                $password= password_hash($password, PASSWORD_DEFAULT);

                mysqli_autocommit($con, false);
                
                $query1 = "SELECT * FROM utenti FOR UPDATE";
                if (!$result1=mysqli_query($con, $query1)){
                    throw new Exception("Error in select 'utenti' for update query");
                    return false;
                }
                $query2= "SELECT username, password FROM utenti WHERE username=?";
                $query3= "INSERT INTO utenti(username, password) VALUES(?,?)";
                
                // SELECT username, password FROM utenti WHERE username=$email
                if (!($stmt2=mysqli_prepare($con, $query2))){
                    throw new Exception("Error in preparing statement");
                    return false;
                }
                mysqli_stmt_bind_param($stmt2, "s", $email);
                if (!(mysqli_execute($stmt2))){
                        throw new Exception("Cannot execute statement 2");
                        return false;
                } 
                mysqli_stmt_bind_result($stmt2, $userIfExist, $pwdIfExist);
                if (mysqli_stmt_fetch($stmt2) && ($email==$userIfExist && $password==$pwdIfExist)){
                    throw new Exception("Username ".$email." already existing");
                    return false;
                }

                // INSERT INTO utenti(username, password) VALUES($email,$password)
                if ( !($stmt3 = mysqli_prepare($con, $query3)) ){
                    throw new Exception("Error in preparing statement");
                    return false;
                }

                mysqli_stmt_bind_param($stmt3, "ss", $email, $password);
                if ( !(mysqli_execute($stmt3))){
                    throw new Exception("Stmt error: ".mysqli_stmt_error($stmt));
                    return false;
                } else {
                        $_SESSION['265353_user']=$email;
                        $_SESSION['265353_logged']="yes";
                        echo "<p>Sign up success!<p>\n";
                }

                mysqli_stmt_close($stmt3);
                if (!mysqli_commit($con)){
                    throw new Exception ("Commit failure.\n");
                    return false;
                }
                return true;

            } catch (Exception $e){
                mysqli_rollback($con);
                echo "Rollback: ". $e->getMessage().".\n";
                mysqli_autocommit($con, true);
                return false;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
    
}


function printSingupArea(){
    $string = "  <div class='signup'>\n";
    $string .= "  <label>Registrazione</label>\n";
    $string .= "   <div class='log-content'>";
    $string .= "   <form name='signupForm' method='POST' onSubmit='return validate(usernameSignUp, passwordSignUp);' action='page_inputlogsign.php'>\n";
    $string .= "   <input type='mail' name='usernameSignUp' placeholder='Username' value=''>\n";
    $string .= "   <input type='password' name='passwordSignUp' placeholder='Password' value=''>\n";
    $string .= "   <input type='submit' value='Signup'>\n";
    $string .= " </div>\n  </form>\n </div>\n";
    echo $string;
}
?>