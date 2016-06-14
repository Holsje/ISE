<?php 
	if(isset($_POST['startTime']) && isset($_POST['endTime']) && isset($_POST['trackNo']) && isset($_POST['eventNo'])) {
		$manageCongressPlanning->addEventToTrack($_POST['trackNo'], 
												 $manageCongressPlanning->getCongressNo(), 
												 $_POST['eventNo'], 
												 $_POST['startTime'], 
												 $_POST['endTime'], 
												 $_POST['buildingName'],
												 $_POST['rooms']);
		die();
	}
	
	if(isset($_POST['changeRoomsOnSelectChange'])) {
		echo json_encode($manageCongressPlanning->getRoomsByBuilding($_POST['buildingName']));
		die();
	}
	
	if (isset($_POST['deleteEventFromPlanning'])) {
		$manageCongressPlanning->deleteEventFromTrack($_POST['eventNo'], $_POST['trackNo']);
		die();
	}
	
	if(isset($_POST['getInfo'])){
        echo $indexClass->getEventInfo($_POST['eventNo'],$_SESSION['congressNo']);
        die();
    }
    if(isset($_POST['speakerPop'])){
        echo $indexClass->getSpeakerInfo($_POST['personID']);
        die();
    }
?>