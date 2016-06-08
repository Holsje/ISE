<?php 
	if (isset($_POST['selectedLocationValue'])) {
		$_SESSION['chosenLocationName'] = $_POST['selectedLocationValue'][0];
		$_SESSION['chosenLocationCity'] = $_POST['selectedLocationValue'][1];
		die();
	}
	
	if (isset($_POST['saveBuildingButton'])) {
		$queryInsertNewBuilding = "INSERT INTO Building (LocationName, City, BName, Street, HouseNo, PostalCode) VALUES(?, ?, ?, ?, ?, ?)";
		$paramsInsertNewBuilding = array($_SESSION['chosenLocationName'], 
										 $_SESSION['chosenLocationCity'], 
										 $_POST['buildingName'], 
										 $_POST['streetName'], 
										 $_POST['houseNo'], 
										 $_POST['postalCode']);
		$result = $dataBase->sendQuery($queryInsertNewBuilding, $paramsInsertNewBuilding);
	}
	
	if (isset($_POST['confirmEditLocationButton'])) {
		$params = array( 
					 array($_POST['locationName'], SQLSRV_PARAM_IN),
					 array($_POST['locationCity'], SQLSRV_PARAM_IN),
					 array($_SESSION['chosenLocationName'], SQLSRV_PARAM_IN),
					 array($_SESSION['chosenLocationCity'], SQLSRV_PARAM_IN));
		$execString = "{call spUpdateLocation(";
		for($i = 0;$i<sizeof($params)-1;$i++) {
			$execString .= " ?,";
		}
		$execString .= " ?)}";
		$dataBase->sendQuery($execString, $params);
		$_SESSION['chosenLocationName'] = $_POST['locationName'];
		$_SESSION['chosenLocationCity'] = $_POST['locationCity'];
	}
	
	if (isset($_POST['deleteBuildings'])) {
		$queryDeleteSelection = "DELETE FROM Building WHERE ";
		$paramsDeleteSelection = array();
		if(isset($_POST['selectedBuildingValues'])) {
			for($i = 0; $i < sizeof($_POST['selectedBuildingValues']); $i++) {
				if ($i == 0 ) {
					$queryDeleteSelection .= "(LocationName = ? AND BName = ? AND City = ?)";
				}
				else {
					$queryDeleteSelection .= " OR (LocationName = ? AND BName = ? AND City = ?) ";
				}
				array_push($paramsDeleteSelection, $_SESSION['chosenLocationName']);
				array_push($paramsDeleteSelection, $_POST['selectedBuildingValues'][$i]);
				array_push($paramsDeleteSelection, $_SESSION['chosenLocationCity']);
			}
			$result = $dataBase->sendQuery($queryDeleteSelection, $paramsDeleteSelection);
			die();
		}
	}
	
	if(isset($_POST['getRooms'])) {
		if($_POST['getRooms'] == 'rooms') {
			$rooms = array();
			$queryBuildingInfo = "SELECT LocationName, City, BName, Street, HouseNo, PostalCode
			 				 FROM Building WHERE Locationname = ? AND City = ? AND BName = ?";
			$params = array($_SESSION['chosenLocationName'],$_SESSION['chosenLocationCity'],$_POST['building']);

			$resultBuildingInfo = $dataBase->sendQuery($queryBuildingInfo, $params);
			if ($resultBuildingInfo) {
				if ($row = sqlsrv_fetch_array($resultBuildingInfo, SQLSRV_FETCH_ASSOC)){
					$_SESSION['BName'] = $row['BName'];
					array_push($rooms,$row);
				}

			}

			$queryRoomInfo = "SELECT RName, Description, MaxNumberOfParticipants " .
							"FROM ROOM WHERE LocationName = ? AND City = ? AND BName = ?";

			$result = $dataBase->sendQuery($queryRoomInfo, $params);

			if($result) {
				while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
					array_push($rooms,$row);
				}
				echo json_encode($rooms);
			}

			die();
		}
	}
	
	
	if(isset($_POST['createRoom'])) {
		if($_POST['createRoom'] == 'createRoom') {			
			$queryRoomInfo = "INSERT INTO Room VALUES(?,?,?,?,?,?)";
			$params = array($_SESSION['chosenLocationName'],$_SESSION['chosenLocationCity'],$_POST['BName'],$_POST['roomName'],$_POST['roomDescription'],$_POST['roomCapacity']);
			
			$result = $dataBase->sendQuery($queryRoomInfo, $params);
			if($result) {
				echo json_encode($result);
			}
			die();
		}
	}
	
	if(isset($_POST['getRoomInfo'])){
		$queryRoomInfo = "SELECT BName, RName, Description, MaxNumberOfParticipants " .
							"FROM ROOM WHERE LocationName = ? AND City = ? AND BName = ? AND RName = ?";
		$params = array($_SESSION['chosenLocationName'],$_SESSION['chosenLocationCity'],$_POST['building'],$_POST['RName']);
		
		
		$result = $dataBase->sendQuery($queryRoomInfo, $params);
		$roomInfo = array();
		if($result) {
			while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
				array_push($roomInfo,$row);
			}
			echo json_encode($roomInfo);
		}
		die();
	}
	
	if(isset($_POST['editRoom'])) {
		$queryUpdateRoom = " UPDATE ROOM SET RName = ?, [Description] = ?, MaxNumberOfParticipants = ? ".
							" WHERE LocationName = ? AND City = ? AND BName = ? AND RName = ?";
							
							
		$params = array($_POST['roomName'],$_POST['roomDescription'],$_POST['roomCapacity'],$_SESSION['chosenLocationName'],$_SESSION['chosenLocationCity'],$_POST['BName'],$_POST['oldRoomName']);
		
		
		$result = $dataBase->sendQuery($queryUpdateRoom, $params);
		
		if($result) {
			echo json_encode($result);
		}		
		die();	
	}
	
	if(isset($_POST['deleteRoom'])) {
		$queryDeleteRoom = "DELETE FROM ROOM WHERE LocationName = ? AND City = ? AND BName = ? AND RName = ?";
		$params = array($_SESSION['chosenLocationName'],$_SESSION['chosenLocationCity'],$_POST['BName'],$_POST['roomName']);
		
		$result = $dataBase->sendQuery($queryDeleteRoom, $params);
		
		if($result) {
			echo json_encode($result);
		}		
		die();
	}

	if (isset($_POST['buttonSaveUpdateBuilding'])){
		$queryUpdateBuilding = "UPDATE Building SET BName = ?, Street = ?, HouseNo = ?, PostalCode = ? WHERE LocationName = ? AND City = ? AND BName = ?";
		$params = array($_POST['buildingName'], $_POST['streetName'], $_POST['houseNo'], $_POST['postalCode'], $_POST['LocationName'], $_POST['cityName'], $_SESSION['BName']);
		$result = $dataBase->sendQuery($queryUpdateBuilding, $params);

	}
?>