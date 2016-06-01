<?php 
	if (isset($_POST['selectedLocationValues'])) {
		$_SESSION['selectedLocationValues'] = $_POST['selectedLocationValues'];
		echo 'gelukt';
		die();
	}
	
	if (isset($_POST['confirmDeleteLocationButton'])) {
		$queryDeleteSelectedLocations = "DELETE FROM Location WHERE ";
		$paramsDeleteSelectedLocations = array();
		for($i = 0; $i < sizeof($_SESSION['selectedLocationValues']); $i += 2) {
			if ($i == 0 ) {
				$queryDeleteSelectedLocations .= "(LocationName = ? AND City = ?)";
			}
			else {
				$queryDeleteSelectedLocations .= " OR (LocationName = ? AND City = ?) ";
			}
			array_push($paramsDeleteSelectedLocations, $_SESSION['selectedLocationValues'][$i]);
			array_push($paramsDeleteSelectedLocations, $_SESSION['selectedLocationValues'][$i + 1]);
		}
		$result = $dataBase->sendQuery($queryDeleteSelectedLocations, $paramsDeleteSelectedLocations);
		
		if (!is_string($result)) {
			echo 'De selectie is succesvol verwijderd';
		}
		else {
			echo 'Er is iets misgegaan bij het verwijderen.';
		}
	}
	
	if (isset($_POST['saveLocationGMButton'])) {
		$queryInsertNewLocation = "INSERT INTO LOCATION(LocationName, City) VALUES(?, ?)";
		$paramsInsertNewLocation = array($_POST['locationNameText'], $_POST['locationCityText']);
		$result = $dataBase->sendQuery($queryInsertNewLocation, $paramsInsertNewLocation);
		
		if (!is_string($result)) {
			echo 'De locatie is toegevoegd.';
		}
		else {
			echo 'De locatie is niet toegevoegd. Er is iets foutgegaan.';
		}
		
	}
?>