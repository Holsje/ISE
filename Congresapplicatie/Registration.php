<?php

require_once('Registration_Class.php');
$registration = new Registration();
include('RegistrationSubmits.php');
$js = '<script type="text/javascript" src="../Congresapplicatie/js/registration.js"></script>';
topLayout("Registreren", null, $js);
?>

<div class="row">
        <div class="container   col-md-12 col-xs-12">
            <div class="content col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-xs-8 col-xs-offset-2">
                <?php
                $registration->createRegistrationScreen();
            ?>
            </div>
        </div>
    </div>