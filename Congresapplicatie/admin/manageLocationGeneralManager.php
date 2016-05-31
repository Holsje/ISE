<?php
	require_once('SessionHandler.php');
	sessionHandler(false, false);
	require_once('manageLocationGeneralManager_Class.php');
	require_once('../database.php');
	global $server, $databaseName, $uid, $password;
	$dataBase = new Database($server,$databaseName,$uid,$password);
	require_once('manageLocationGeneralManager_Submit.php');
	$manageLocationGeneralManager = new ManageLocationGeneralManager(array("Locatie", "Plaats"));

	$js = "<script src='../js/locationManagementGM.js'></script>";
	$css = '<link rel="stylesheet" href="../css/manage.css">"';
	topLayoutManagement('Beheren Locatie', $css, $js);
?>
    <div class="row">
        <div class="container   col-md-12 col-xs-12">
            <div class="content col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-xs-8 col-xs-offset-2">
                <h1>Locaties beheren</h1>
				<?php
					$manageLocationGeneralManager->createManageLocationScreenGM();
				?>
            </div>
        </div>
    </div>

<?php     
	$manageLocationGeneralManager->createCreateLocationPopUp();
	$manageLocationGeneralManager->createDeleteLocationPopUp();
    bottomLayout();
?>