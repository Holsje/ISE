<?php
	if (isset($_POST['LocationName']) && isset($_POST['City']) && $_POST['SelectedValue']) {
		$_SESSION['currentLocationName'] = $_POST['LocationName'];
		$_SESSION['currentLocationCity'] = $_POST['City'];
		$_SESSION['selectedLocation'] = $_POST['SelectedValue'];
		echo 'gelukt';
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
		
		if (!is_string($result)) {
			echo 'De selectie is succesvol verwijderd';
		}
		else {
			echo 'Er is iets misgegaan bij het verwijderen.';
		}
	}
	
	if (isset($_POST['selectedBuildingValues'])) {
		$_SESSION['selectedBuildingValues'] = $_POST['selectedBuildingValues'];
		echo 'gelukt';
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
		if (!is_string($result)) {
			echo 'Het gebouw is opgeslagen';
		}
		else {
			echo 'Het gebouw is niet opgeslagen.';
		}
	}
	
	if (isset($_POST['buttonLinkToCongress'])) {
		
	}
	
	
?>