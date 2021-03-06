<?php

require_once('sessionHandler.php');
sessionHandler(true, false);
unset($_SESSION['congressNo']);
unset($_SESSION['currentLocationName']);
unset($_SESSION['currentLocationCity']);
unset($_SESSION['Public']);
require_once('ManageCongress_Class.php');
$manageCongress = new ManageCongress();
include('ManageCongressSubmits.php');

$js = '<script type="text/javascript" src="../js/congressManagement.js"></script>';
$js .= '<script type="text/javascript" src="../js/manage.js"></script>';
$js .= '<script type="text/javascript" src="../js/subjects.js"></script>';

topLayoutManagement('Beheren Congres',null,$js);
?>

    <div class="row">
        <div class="container   col-md-12 col-xs-12">
            <div class="content col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-xs-8 col-xs-offset-2">
                <?php
                $manageCongress->createManagementScreen(array("Congresnummer", "Naam", "Startdatum", "Einddatum", "Publiek"), $manageCongress->getCongresses());
            ?>
            </div>
        </div>
    </div>

    <?php 
    $manageCongress->createCreateCongressScreen();
    $manageCongress->createAddSubjectScreen();
    
    bottomLayout();

?>
