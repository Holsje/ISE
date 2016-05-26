<?php
require_once('Registration_Class.php');
$registration = new Registration();
include('RegistrationSubmits.php');

if(!isset($_POST['validInput'])){
    if (!isset($_SESSION['userWeb'])) {
        $registration->createRegistrationScreen();
    }
}