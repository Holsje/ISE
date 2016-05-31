<?php
require_once('SessionHandler.php');
require_once('Manage_Class.php');
require_once('Management.php');
require_once('ManageSpeakers_class.php');
require_once("manageLocations_Class.php");
global $server, $databaseName, $uid, $password;
$database = new Database($server,$databaseName,$uid,$password);

sessionHandler(false, false);
$manage = new Manage();

if(isset($_SESSION['congressNo'])) {
	$manage->setCongressNo($_SESSION['congressNo']);
}
else {
	$manage->setCongressNo(1);
}

include('manageSpeakersSubmits.php');
include('manageLocationsSubmits.php');

$js = "<script src='../js/manage.js'></script>";
$js .= "<script src='../js/locationManagement.js'></script>";
$js .= "<script src='../js/management.js'></script>";
$css = '<link rel="stylesheet" href="../css/manage.css">"';
topLayoutManagement('Beheren Congres', $css, $js);
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
