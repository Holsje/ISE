<?php  
require_once('SessionHandler.php');
sessionHandler(false, true);
require_once('Management.php');
require_once('manageCongressPlanning_Class.php');

$manageCongressPlanning = new manageCongressPlanning($_SESSION['congressNo']);

require_once('../Index_Class.php');
$indexClass = new Index();
require_once('manageCongressPlanningSubmits.php');


$css = '<link rel="stylesheet" href="../css/manageCongressPlanning.css">';
$css .= '<link rel="stylesheet" href="../css/public.css">';
$js = '<script src="../js/manageCongressPlanning.js"> </script>';
$js .= '<script src="../js/public.js"> </script>';
topLayoutManagement('Beheren Congres', $css, $js);

echo $manageCongressPlanning->getCreateScreen()->createEventPlanningPopUp($manageCongressPlanning->getBuildingsByCongressLocation());
echo $indexClass->createSpeakerInfoPopup();
echo $indexClass->createEventInfoPopup();
?>
    <div class="row">
        <div class="container col-md-12 col-xs-12">
            <div class="content col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
				<h1>Inplannen congres</h1>
				
				<div class="col-xs-8 col-sm-8 col-md-8 currentDay">
					<h1 class="col-xs-8 col-sm-8 col-md-8"><?php  echo $manageCongressPlanning->currentDay; ?></h1>
					<form name="formPublishCongress" method="POST" action="<?php $_SERVER['PHP_SELF'] ?>">
						<button name="publishCongressButton" class="btn btn-default publishCongressButton" type="submit">Publiceren</button>
					</form>
				</div>
				
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
            </div>
        </div>
    </div>
<?php 
    bottomLayout();
?>
