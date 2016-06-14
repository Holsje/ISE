<?php
require_once('sessionHandler.php');
sessionHandler(true, true);
require_once('ManageManagers_Class.php');
$manageManagers = new ManageManagers();
require_once('ManageManagers_Submit.php');

//$css voeg css variable toe
//$js voeg js variable toe
$css = '';
$js = "<script src='../js/manageManagers.js'></script>";
$js .= " <script src='../js/manage.js'></script>";

topLayoutManagement('Beheren Beheerders',null,$js);
?>
    <div class="row">
        <div class="container   col-md-12 col-xs-12">
            <div class="content col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-xs-8 col-xs-offset-2">
                <h1>Beheren Beheerders</h1>
                <?php
                    $manageManagers->createManagementScreen(array('PersonNo', 'Voornaam', 'Achternaam','E-mailadres','Telefoonnummer','Type'), $manageManagers->getManagers());
                ?>
            </div>
        </div>
    </div>

    <?php 
        $manageManagers->createCreateManageMangersScreen(); 
        $manageManagers->createEditManageMangersScreen();
    
    bottomLayout();

?>
