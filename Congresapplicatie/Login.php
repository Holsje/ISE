<?php

require_once('Login_Class.php');
$login = new Login();
include('LoginSubmits.php');

if (!isset($_SESSION['userWeb'])) {
    if(isset($_SESSION['loginFail'])){
        $login->createLoginScreen('show');
        unset($_SESSION['loginFail']);
    }else{
        $login->createLoginScreen('');
    }
}

bottomLayout();
?>
