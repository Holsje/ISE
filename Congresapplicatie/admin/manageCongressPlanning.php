<?php  
require_once('SessionHandler.php');
sessionHandler(false, false);
require_once('Management.php');
require_once('../Inschrijven_Class.php');
require_once('manageCongressPlanning_Class.php');

$manageCongressPlanning = new manageCongressPlanning(1);

$css = '<link rel="stylesheet" href="../css/manageCongressPlanning.css">';
$css .= '<link rel="stylesheet" href="../css/public.css">';
topLayoutManagement('Beheren Congres', $css, null);
?>
    <div class="row">
        <div class="container col-md-12 col-xs-12">
            <div class="content col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
                <h1>Inplannen congres</h1>
				<?php  echo $manageCongressPlanning->getInschrijven()->writeOutCurrentDate() ?>
				<form name="formCongressPlanning" method="POST" action="<?php $_SERVER['PHP_SELF'] ?>">
				<div id="trackCarousel" class="carousel slide col-xs-8 col-sm-8 col-md-8" data-ride="carousel" data-interval="false">
				    <div id = "carousel" class="carousel-inner" role="listbox">
						<?php
							$manageCongressPlanning->createManageCongressTrackScreen();						
						?>
					</div>
					<?php 
					$manageCongressPlanning->getInschrijven()->createPreviousDayButton();
					$manageCongressPlanning->getInschrijven()->createNextDayButton();
					?>
				</div>
				<?php 
					$manageCongressPlanning->createManageCongressEventScreen();
				?>
				</form>
            </div>
        </div>
    </div>
<?php 
    bottomLayout();
?>