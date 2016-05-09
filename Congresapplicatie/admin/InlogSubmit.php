<?php
/**
 * Created by PhpStorm.
 * User: erike
 * Date: 9-5-2016
 * Time: 13:00
 */
    session_start();
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        if (isset($_POST['login'])){
            if ($login->checkLogin($_POST['input-username'], $_POST['input-password'])){
                //setcookie('user', $_POST['input-username'], time() + (14*24*60*60));
                $_SESSION['user'] = $_POST['input-username'];
                echo "Ingevulde inloggegevens zijn juist!";
                header('Location: index2.php');
            }
            else{
                echo "Ingevulde inloggegevens zijn onjuist!";
            }
        }
        else if (isset($_POST['logout'])){
            unset($_SESSION['user']);
            if(isset($_COOKIE['user'])) {
                setcookie('user', '', 1);
            }
        }
    }

?>


