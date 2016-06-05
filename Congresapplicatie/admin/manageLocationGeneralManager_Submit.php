<?php 
	if (isset($_POST['deleteLocation'])) {
		$queryDeleteSelectedLocations = "DELETE FROM Location WHERE ";
		$paramsDeleteSelectedLocations = array();
		for($i = 0; $i < sizeof($_POST['selectedLocationValues']); $i += 2) {
			if ($i == 0 ) {
				$queryDeleteSelectedLocations .= "(LocationName = ? AND City = ?)";
			}
			else {
				$queryDeleteSelectedLocations .= " OR (LocationName = ? AND City = ?) ";
			}
			array_push($paramsDeleteSelectedLocations, $_POST['selectedLocationValues'][$i]);
			array_push($paramsDeleteSelectedLocations, $_POST['selectedLocationValues'][$i + 1]);
		}
		$result = $dataBase->sendQuery($queryDeleteSelectedLocations, $paramsDeleteSelectedLocations);
		die();
	}
	
	if (isset($_POST['saveLocationGMButton'])) {
		$queryInsertNewLocation = "INSERT INTO LOCATION(LocationName, City) VALUES(?, ?)";
		$paramsInsertNewLocation = array($_POST['locationNameText'], $_POST['locationCityText']);
		$result = $dataBase->sendQuery($queryInsertNewLocation, $paramsInsertNewLocation);	
	}
?>