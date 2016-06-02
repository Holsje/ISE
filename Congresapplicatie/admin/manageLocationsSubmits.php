<?php
	if (isset($_POST['LocationName']) && isset($_POST['City']) && $_POST['SelectedValue']) {
		$_SESSION['currentLocationName'] = $_POST['LocationName'];
		$_SESSION['currentLocationCity'] = $_POST['City'];
		$_SESSION['selectedLocation'] = $_POST['SelectedValue'];
		die();
	}
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
	}
	
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
			$queryUpdateCongressLocation = "UPDATE Congress SET LocationName = ?, City = ? WHERE LocationName = ? AND City = ? AND CongressNo = ?";
			$paramsUpdateCongressLocation = array($_SESSION['currentLocationName'], $_SESSION['currentLocationCity'], $congress['locationName'], $congress['city'], $manage->getCongressNo());
			$result = $database->sendQuery($queryUpdateCongressLocation, $paramsUpdateCongressLocation);
		}
	}
	
	
?>