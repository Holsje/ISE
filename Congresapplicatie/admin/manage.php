<?php

require_once('sessionHandler.php');
sessionHandler(true, false);
require_once('Manage_Class.php');
$manage = new Manage();
$js = '<script src="../js/manage.js"></script>';
$js .= '<script src="../js/editCongressManagement.js"></script>';
topLayoutManagement('Beheren Congres','<link rel="stylesheet" href="../css/manage.css">',$js);
?>

    <div class="row">
        <div class="container   col-md-12 col-xs-12">
            <div class="content col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-xs-8 col-xs-offset-2">
                <h1>Aanpassen congres</h1>
				<?php
					$manage->createManageScreen();
				?>
            </div>
        </div>
    </div>

    <?php     
    bottomLayout();

?>
