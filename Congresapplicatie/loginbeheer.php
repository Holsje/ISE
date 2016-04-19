<?php
    session_start();
/**
* Created by PhpStorm.
* User: erike
* Date: 19-4-2016
* Time: 10:16
*/


    require('query_congres.php');
    require('connectDatabase.php');

    $congres = new Congres($server,
                           $database,
                           $uid,
                           $password);


    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        if (isset($_POST['login'])){
            if ($congres->checkLogin($_POST['input-username'], $_POST['input-password'])){
                setcookie('user', $_POST['input-username'], time() + (14*24*60*60));
                $_SESSION['user'] = $_POST['input-username'];
                echo "Ingevulde inloggegevens zijn juist!";
                header('Refresh: 5; indexbeheer.php');
            }
            else{
                echo "Ingevulde inloggegevens zijn onjuist!";
            }
        }
    }
?>

<html>
    <head>
        <title>Login Beheer</title>
    </head>
    <body>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <label for="input-username">Gebruikersnaam</label>
            <input type="text" id="input-username" placeholder="Gebruikersnaam" name="input-username">

            <label for="input-password">Wachtwoord</label>
            <input type="password" id="input-password" placeholder="Wachtwoord" name="input-password">

            <input type="submit" name="login" value="Inloggen">
        </form>
    </body>
</html>
<?php

?>