<?php
/**
 * Created by PhpStorm.
 * User: erike
 * Date: 19-4-2016
 * Time: 11:40
 */
    session_start();
    if (isset($_POST['logout'])){
        echo "Uitloggen";
        unset($_SESSION['user']);
        if(isset($_COOKIE['user'])) {
            setcookie('user', '', 1);
        }
        header('Refresh: 5; loginbeheer.php');
    }
?>

<html>
    <head>
        <title>Index</title>
    </head>
    <body>
        <?php
            echo  $_SESSION['user'];
        ?>
        <form method="POST" action = "<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="submit" name="logout" value="Uitloggen">
        </form>
    </body>
</html>
