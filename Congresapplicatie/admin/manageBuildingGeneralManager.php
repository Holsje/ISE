<?php
	require_once('SessionHandler.php');
	sessionHandler(true, true);
	require_once('manageBuildingGeneralManager_Class.php');
	require_once('../database.php');
	global $server, $databaseName, $uid, $password;
	$dataBase = new Database($server,$databaseName,$uid,$password);
	
	require_once('manageBuildingGeneralManager_Submit.php');
	if (!isset($_SESSION['chosenLocationCity']) || !isset($_SESSION['chosenLocationName'])) {
		header('Location: manageLocationGeneralManager.php');
		die();
	}
	$manageBuildingGeneralManager = new ManageBuildingGeneralManager(array("Gebouw", "Straat", "Huisnummer", "Postcode"), 
																	 $_SESSION['chosenLocationName'], 
																	 $_SESSION['chosenLocationCity']);

	$js = "<script src='../js/manage.js'></script>";
	$js .= "<script src='../js/buildingManagement.js'></script>";
	$css = '<link rel="stylesheet" href="../css/manage.css">"';
	$css .= '<link rel="stylesheet" href="../css/buildingManagement.css">';
	topLayoutManagement('Beheren Gebouw', $css, $js);
?>
    <div class="row">
        <div class="container   col-md-12 col-xs-12">
            <div class="content col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-xs-8 col-xs-offset-2">
                <h1>Aanpassen locatie</h1>
				<?php
					$manageBuildingGeneralManager->createManageBuildingScreenGM();
				?>
            </div>
        </div>
    </div>

<?php     
	$manageBuildingGeneralManager->createCreateBuildingPopUp();
	$manageBuildingGeneralManager->createDeleteBuildingPopUp();
	$manageBuildingGeneralManager->createEditLocationPopUp();
	$manageBuildingGeneralManager->createCreateRoomPopUp();
	$manageBuildingGeneralManager->createEditRoomPopUp();
    bottomLayout();
?>