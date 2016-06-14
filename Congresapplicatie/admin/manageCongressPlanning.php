<?php  
require_once('SessionHandler.php');
sessionHandler(false, true);
require_once('Management.php');
require_once('manageCongressPlanning_Class.php');

$manageCongressPlanning = new manageCongressPlanning($_SESSION['congressNo']);
require_once('manageCongressPlanningSubmits.php');

require_once('../Index_Class.php');
$indexClass = new Index();

$css = '<link rel="stylesheet" href="../css/manageCongressPlanning.css">';
$css .= '<link rel="stylesheet" href="../css/public.css">';
$js = '<script src="../js/manageCongressPlanning.js"> </script>';
$js .= '<script src="../js/public.js"> </script>';
topLayoutManagement('Beheren Congres', $css, $js);

echo $manageCongressPlanning->getCreateScreen()->createEventPlanningPopUp($manageCongressPlanning->getBuildingsByCongressLocation());
//echo $indexClass->createSpeakerInfoPopup();
//echo $indexClass->createEventInfoPopup();
?>
    <div class="row">
        <div class="container col-md-12 col-xs-12">
            <div class="content col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
                <h1>Inplannen congres</h1>
				<h1><?php  echo $manageCongressPlanning->currentDay; ?></h1>
				<!--<form name="formCongressPlanning" method="POST" action="<?php $_SERVER['PHP_SELF'] ?>">-->
				<div id="trackCarousel" class="carousel slide col-xs-8 col-sm-8 col-md-8" data-ride="carousel" data-interval="false">
				    <div id = "carousel" class="carousel-inner" role="listbox">
						<?php
							$manageCongressPlanning->createManageCongressTrackScreen();						
						?>
					</div>
					
					<form name="formDaySwitch" method="GET" action="<?php $_SERVER['PHP_SELF'] ?>">
					<?php 
					$manageCongressPlanning->createPreviousDayButton();
					$manageCongressPlanning->createNextDayButton();
					?>
					</form>
				</div>
				<?php 
					$manageCongressPlanning->createManageCongressEventScreen();
				?>
				<!--</form>-->
            </div>
        </div>
    </div>
<?php 
    bottomLayout();
?>