<?php

require_once('sessionHandler.php');
sessionHandler(true, false);
require_once('ManageSpeakersGM_Class.php');

$manageSpeakersGM = new ManageSpeakersGM();
include('manageSpeakersGMSubmits.php');

$css = '<link rel="stylesheet" href="../css/manage.css">';
$js = '<script type="text/javascript" src="../js/manageSpeakerGM.js"></script>';
topLayoutManagement('Beheren Sprekers',$css,$js);
?>

    <div class="row">
        <div class="container   col-md-12 col-xs-12">
            <div class="content col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-xs-8 col-xs-offset-2">
                <?php
                $manageSpeakersGM->createManagementScreen();
            ?>
            </div>
        </div>
    </div>

    <?php 
    $manageSpeakersGM->createCreateSpeakerScreen(); 
    //$manageCongress->createEditCongressScreen();
    
    bottomLayout();

?>
