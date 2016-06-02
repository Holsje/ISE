<?php 
	if (isset($_POST['selectedLocationValue'])) {
		$_SESSION['chosenLocationName'] = $_POST['selectedLocationValue'][0];
		$_SESSION['chosenLocationCity'] = $_POST['selectedLocationValue'][1];
		echo 'gelukt';
		die();
	}
	
	if (isset($_POST['selectedBuildingValues'])) {
		$_SESSION['selectedBuildingValues'] = $_POST['selectedBuildingValues'];
		echo 'gelukt';
		die();
	}
	
	if (isset($_POST['buildingName']) && isset($_POST['streetName'])) {	
		$queryInsertNewBuilding = "INSERT INTO Building (LocationName, City, BName, Street, HouseNo, PostalCode) VALUES(?, ?, ?, ?, ?, ?)";
		$paramsInsertNewBuilding = array($_SESSION['chosenLocationName'], 
										 $_SESSION['chosenLocationCity'], 
										 $_POST['buildingName'], 
										 $_POST['streetName'], 
										 $_POST['houseNo'], 
										 $_POST['postalCode']);
		$result = $dataBase->sendQuery($queryInsertNewBuilding, $paramsInsertNewBuilding);
		if (!is_string($result)) {
			echo 'Het gebouw is opgeslagen';
		}
		else {
			echo 'Het gebouw is niet opgeslagen.';
		}
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
		echo var_dump($execString);
		$dataBase->sendQuery($execString, $params);
		$_SESSION['chosenLocationName'] = $_POST['locationName'];
		$_SESSION['chosenLocationCity'] = $_POST['locationCity'];
	}
	
	if (isset($_POST['confirmButton'])) {
		$queryDeleteSelection = "DELETE FROM Building WHERE ";
		$paramsDeleteSelection = array();
		if(isset($_SESSION['selectedBuildingValues'])) {
			for($i = 0; $i < sizeof($_SESSION['selectedBuildingValues']); $i++) {
				if ($i == 0 ) {
					$queryDeleteSelection .= "(LocationName = ? AND BName = ? AND City = ?)";
				}
				else {
					$queryDeleteSelection .= " OR (LocationName = ? AND BName = ? AND City = ?) ";
				}
				array_push($paramsDeleteSelection, $_SESSION['chosenLocationName']);
				array_push($paramsDeleteSelection, $_SESSION['selectedBuildingValues'][$i]);
				array_push($paramsDeleteSelection, $_SESSION['chosenLocationCity']);
			}
			$result = $dataBase->sendQuery($queryDeleteSelection, $paramsDeleteSelection);
			
			if (!is_string($result)) {
				echo 'De selectie is succesvol verwijderd';
			}
			else {
				echo 'Er is iets misgegaan bij het verwijderen.';
			}
		}
	}
?>