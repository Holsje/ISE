<?php

require_once('Login_Class.php');
$login = new Login();
include('LoginSubmits.php');

if (!isset($_SESSION['userWeb'])) {
    $login->createLoginScreen();
}

bottomLayout();
?>
