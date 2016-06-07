<?php  
require_once('SessionHandler.php');
sessionHandler(false, false);
require_once('Management.php');
require_once('../Inschrijven_Class.php');
require_once('manageCongressPlanning_Class.php');

$manageCongressPlanning = new manageCongressPlanning(new Inschrijven(1));

$css = '<link rel="stylesheet" href="../css/manageCongressPlanning.css">';

topLayoutManagement('Beheren Congres', $css, null);
?>
    <div class="row">
        <div class="container col-md-12 col-xs-12">
            <div class="content col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
                <h1>Inplannen congres</h1>
				<?php
					$manageCongressPlanning->createManageCongressPlanningScreen();
				?>
            </div>
        </div>
    </div>
<?php 
    bottomLayout();
?>