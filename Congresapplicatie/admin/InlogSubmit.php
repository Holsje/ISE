<?php
/**
 * Created by PhpStorm.
 * User: erike
 * Date: 9-5-2016
 * Time: 13:00
 */
    $errorstring = "";
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        if (isset($_POST['login'])){
            if ($login->checkLogin($_POST['input-username'], $_POST['input-password'])){
                //setcookie('user', $_POST['input-username'], time() + (14*24*60*60));
                $_SESSION['user'] = $_POST['input-username'];
                echo "Ingevulde inloggegevens zijn juist!";
                $_SESSION['liberties'] = $login->checkUser($_SESSION['user']);
                $_SESSION['personno'] = $login->getPersonNo($_SESSION['user']);
                header('Location: index.php');
            }
            else{
                $errorstring = "Ingevulde inloggegevens zijn onjuist!";
            }
        }
        else if (isset($_POST['logout'])){
            unset($_SESSION['user']);
            unset($_SESSION['liberties']);
            if(isset($_COOKIE['user'])) {
                setcookie('user', '', 1);
            }
            header('Location: index.php');
        }
    }

?>


