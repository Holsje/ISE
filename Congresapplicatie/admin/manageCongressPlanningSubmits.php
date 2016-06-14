<?php 
	if(isset($_POST['startTime']) && isset($_POST['endTime']) && isset($_POST['trackNo']) && isset($_POST['eventNo'])) {
		if (isset($_POST['eventUpdate'])) {
			if ($_POST['eventUpdate'] != 'false') {
				$manageCongressPlanning->updateEventInTrack($_POST['trackNo'], 
															 $manageCongressPlanning->getCongressNo(), 
															 $_POST['eventNo'], 
															 $_POST['startTime'], 
															 $_POST['endTime'], 
															 $_POST['buildingName'],
															 $_POST['rooms']);
				die();
			}
			else {
				$manageCongressPlanning->addEventToTrack($_POST['trackNo'], 
												 $manageCongressPlanning->getCongressNo(), 
												 $_POST['eventNo'], 
												 $_POST['startTime'], 
												 $_POST['endTime'], 
												 $_POST['buildingName'],
												 $_POST['rooms']);
				die();
			}
		}
		
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
	
	if (isset($_POST['publishCongressButton'])) {
		$result = $manageCongressPlanning->changeRecord("spPublishCongress", array($manageCongressPlanning->getCongressNo()));
		if(is_string($result))
			echo '<script>alert("' . $result . '".replace("[NR]","\n").replace("[NR]","\n").replace("[NR]","\n").replace("[NR]","\n").replace("[NR]","\n").replace("[NR]","\n").replace("[NR]","\n").replace("[NR]","\n").replace("[NR]","\n").replace("[NR]","\n").replace("[NR]","\n").replace("[NR]","\n").replace("[NR]","\n").replace("<br>","").replace("ERROR",""));</script>';
		else	
			echo '<script>alert("Congres is gepubliceerd");</script>';
	}
?>