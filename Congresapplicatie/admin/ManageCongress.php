<?php
/**
 * Created by PhpStorm.
 * User: erike
 * Date: 26-4-2016
 * Time: 14:16
 */
require_once('ManageCongress_Class.php');
$manageCongress = new ManageCongress();

topLayoutManagement('Beheren Congres','<link rel="stylesheet" href="../css/congressManagement/congressManagement.css">','<script type="text/javascript" src="../js/congressManagement.js"></script>');
?>

<div class="row">
    <div class="container   col-md-12 col-xs-12">
        <div class="content col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-xs-8 col-xs-offset-2">
            <?php
                $manageCongress->createManagementScreen(array("Congresnummer", "Naam", "Onderwerp", "Locatie", "Startdatum", "Einddatum"), $manageCongress->getCongresses());
            ?>
        </div>
    </div>
</div>
