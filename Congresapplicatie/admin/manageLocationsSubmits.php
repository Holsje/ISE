<?php
	if (isset($_POST['LocationName']) && isset($_POST['City']) && $_POST['SelectedValue']) {
		$_SESSION['currentLocationName'] = $_POST['LocationName'];
		$_SESSION['currentLocationCity'] = $_POST['City'];
		$_SESSION['selectedLocation'] = $_POST['SelectedValue'];
		die();
	}
	/*
	if (isset($_POST['confirmButton'])) {
		$queryDeleteSelection = "DELETE FROM Building WHERE ";
		$paramsDeleteSelection = array();
		for($i = 0; $i < sizeof($_SESSION['selectedBuildingValues']); $i++) {
			if ($i == 0 ) {
				$queryDeleteSelection .= "(LocationName = ? AND BName = ? AND City = ?)";
			}
			else {
				$queryDeleteSelection .= " OR (LocationName = ? AND BName = ? AND City = ?) ";
			}
			array_push($paramsDeleteSelection, $_SESSION['currentLocationName']);
			array_push($paramsDeleteSelection, $_SESSION['selectedBuildingValues'][$i]);
			array_push($paramsDeleteSelection, $_SESSION['currentLocationCity']);
		}
		$result = $database->sendQuery($queryDeleteSelection, $paramsDeleteSelection);
	}*/
	
	if (isset($_POST['selectedBuildingValues'])) {
		$_SESSION['selectedBuildingValues'] = $_POST['selectedBuildingValues'];
		die();
	}
	
	if (isset($_POST['buildingName']) && isset($_POST['streetName'])) {	
		$queryInsertNewBuilding = "INSERT INTO Building (LocationName, City, BName, Street, HouseNo, PostalCode) VALUES(?, ?, ?, ?, ?, ?)";
		$paramsInsertNewBuilding = array($_SESSION['currentLocationName'], 
										 $_SESSION['currentLocationCity'], 
										 $_POST['buildingName'], 
										 $_POST['streetName'], 
										 $_POST['houseNo'], 
										 $_POST['postalCode']);

		$result = $database->sendQuery($queryInsertNewBuilding, $paramsInsertNewBuilding);
		if (is_string($result)){
			$_SESSION['errorMsgInsertBuilding'] = $result;
		}
		header('Location: '. $_SERVER['PHP_SELF']);
	}
	
	if (isset($_POST['buttonLinkToCongress'])) {
		$queryCongressInfo = "SELECT LocationName, City FROM Congress WHERE CongressNo = ?";
		$result = $database->sendQuery($queryCongressInfo, array($manage->getCongressNo()));
		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			$congress['locationName'] = $row['LocationName'];
			$congress['city'] = $row['City'];
		}
		if (!is_string($result)) {

			$queryUpdateCongressLocation = "UPDATE Congress SET LocationName = ?, City = ? WHERE CongressNo = ?";

			$paramsUpdateCongressLocation = array($_SESSION['currentLocationName'], $_SESSION['currentLocationCity'], $manage->getCongressNo());
			var_dump($queryUpdateCongressLocation);
			var_dump($paramsUpdateCongressLocation);
			$result = $database->sendQuery($queryUpdateCongressLocation, $paramsUpdateCongressLocation);
			if (is_string($result)){
				$_SESSION['errorMsgLinkToCongress'] = $result;
			}
		}
	}
	
	if(isset($_POST['getRooms'])) {
		if($_POST['getRooms'] == 'rooms') {			
			$queryRoomInfo = "SELECT LocationName, City, BName, RName, Description, MaxNumberOfParticipants " .
							"FROM ROOM WHERE LocationName = ? AND City = ? AND BName = ?";
			$params = array($_SESSION['currentLocationName'],$_SESSION['currentLocationCity'],$_POST['building']);
			
			$result = $database->sendQuery($queryRoomInfo, $params);
			$rooms = array();
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
			$params = array($_SESSION['currentLocationName'],$_SESSION['currentLocationCity'],$_POST['BName'],$_POST['roomName'],$_POST['roomDescription'],$_POST['roomCapacity']);
			
			$result = $database->sendQuery($queryRoomInfo, $params);
			if($result) {
				echo json_encode($result);
			}
			else if (is_string($result)){
				$err['err'] = $result;
				echo json_encode($err);
			}
			die();
		}
	}
	
	if(isset($_POST['getRoomInfo'])){
		$queryRoomInfo = "SELECT LocationName, City, BName, RName, Description, MaxNumberOfParticipants " .
							"FROM ROOM WHERE LocationName = ? AND City = ? AND BName = ? AND RName = ?";
		$params = array($_SESSION['currentLocationName'],$_SESSION['currentLocationCity'],$_POST['building'],$_POST['RName']);
		
		
		$result = $database->sendQuery($queryRoomInfo, $params);
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
		$queryUpdateRoom = " UPDATE ROOM SET RName = ? AND [Description] = ?, MaxNumberOfParticipants = ? ".
							" WHERE LocationName = ? AND City = ? AND BName = ? AND RName = ?";
							
							
		$params = array($_POST['roomName'],$_POST['roomDescription'],$_POST['roomCapacity'],$_SESSION['currentLocationName'],$_SESSION['currentLocationCity'],$_POST['BName'],$_POST['oldRoomName']);
		
		
		$result = $database->sendQuery($queryUpdateRoom, $params);
		
		if($result) {
			echo json_encode($result);
		}
		else if (is_string($result)){
			$err['err'] = $result;
			echo json_encode($err);
		}
		die();	
	}
	
	if(isset($_POST['deleteRoom'])) {
		$queryDeleteRoom = "DELETE FROM ROOM WHERE LocationName = ?, City = ? AND BName = ? AND RName = ?";
		$params = array($_SESSION['currentLocationName'],$_SESSION['currentLocationCity'],$_POST['BName'],$_POST['roomName']);
		
		$result = $database->sendQuery($queryDeleteRoom, $params);
		
		if($result) {
			echo json_encode($result);
		}
		else if (is_string($result)){
			$err['err'] = $result;
			echo json_encode($err);
		}
		die();
	}
	
	
	
	
	
?>