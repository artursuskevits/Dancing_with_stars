<?php
require_once ('conf.php');
global $yhendus;
session_start();

if (isset($_SESSION['ksautaja']) && isset($_SESSION['ksautajaid']))
    header("Location: ./index.php");  // redirect the user to the home page
if (isset($_POST['btn'])){
    $username = $_POST['ksautaja'];
    $passwd = $_POST['salasona'];
    $passwd_again = $_POST['salasona2'];


    // query the database to see if the username is taken
    global $yhendus;
    $kask= $yhendus->prepare("SELECT * FROM kasutaja WHERE kasutaja=?");
    $kask->bind_param("s",$username);
    $kask->execute();
    //$query = mysqli_query($yhendus, "SELECT * FROM kasutajad WHERE nimi='$username'");
    if (!$kask->fetch()){

        // create and format some variables for the database
        $id = '';
        $sool='superpaev';
        $krypt=crypt($passwd, $sool);
        $passwd_hashed = $krypt;
        $date_created = time();
        $last_login = 0;
        $status = 1;



        // verify all the required form data was entered
        if ($username != "" && $passwd != "" && $passwd_again != ""){
            // make sure the two passwords match
            if ($passwd === $passwd_again){
                // make sure the password meets the min strength requirements
                if ( strlen($passwd) >= 5 && strpbrk($passwd, "!#$.,:;()")){
                    // insert the user into the database
                    mysqli_query($yhendus, "INSERT INTO kasutaja (kasutaja, parool) VALUES ('$username', '$passwd_hashed')");
                    //echo "<script>alert('rrrr')</script>";
// verify the user's account was created
                    $query = mysqli_query($yhendus, "SELECT * FROM kasutaja WHERE kasutaja='{$username}'");
                    if (mysqli_num_rows($query) == 1){

                        /* IF WE ARE HERE THEN THE ACCOUNT WAS CREATED! YAY! */
                        /* WE WILL SEND EMAIL ACTIVATION CODE HERE LATER */
//echo "<script>alert('yay')</script>";
                        $success = true;
                    }
                }
                else
                    $error_msg = 'Your password is not strong enough. Please use another.';
            }
            else
                $error_msg = 'Your passwords did not match.';
        }
        else
            $error_msg = 'Please fill out all required fields.';
    }
    else
        $error_msg = 'The username <i>'.$username.'</i> is already taken. Please use another.';
}

else
    $error_msg = 'An error occurred and your account was not created.';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registratsion vorm</title>
    <link rel="stylesheet" href="loginstyle.css">
</head>
<body>
<div>
    <?php
    if (isset($success) && $success){
        echo '<p color="green">Yay!! Your account has been created. <a href="./login.php">Click here</a> to login!<p>';
    }
    else if (isset($error_msg))
        echo '<p color="red">'.$error_msg.'</p>';

    ?>
</div>
<h1>Registratsion vorm</h1>
<form action="./Registrationform.php" class="form" method="POST">
    <label for="kasutaja">Kasutaja nimi: </label>
    <input type="text" name="ksautaja" id="kasutaja"> <br>
    <label for="salasona">Salasõna: </label>
    <input type="text" name="salasona" id="salasona"> <br>
    <label for="salasona2">Korda salasõna: </label>
    <input type="text" name="salasona2" id="salasona2"> <br>
    <input type="submit" name="btn" id="btn" value="Loo">
</form>
<p class="center"><br />
    Already have an account? <a href="login.php">Login here</a>
</p>
</body>
</html>
