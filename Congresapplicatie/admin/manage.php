<?php
require_once('SessionHandler.php');
sessionHandler(false, false);
require_once('Manage_Class.php');
require_once('Management.php');
require_once('ManageSpeakers_class.php');
require_once("manageLocations_Class.php");
require_once('ManageTracks_Class.php');
global $server, $databaseName, $uid, $password;
$database = new Database($server,$databaseName,$uid,$password);

$manage = new Manage();
if(isset($_SESSION['congressNo'])) {
	$manage->setCongressNo($_SESSION['congressNo']);
}
else {
	$manage->setCongressNo(1);
}

include('manageSpeakersSubmits.php');
include('manageLocationsSubmits.php');
include('manageTracksSubmits.php');


$js =  "<script src='../js/editCongressManagement.js'></script>";
$js .= "<script src='../js/manage.js'></script>";
$js .= "<script src='../js/locationManagement.js'></script>";
$js .= "<script src='../js/manageSpeaker.js'></script>";
$css = '<link rel="stylesheet" href="../css/manage.css">';

topLayoutManagement('Beheren Congres', $css, $js);
?>

    <div class="row">
        <div class="container   col-md-12 col-xs-12">
            <div class="content col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
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
