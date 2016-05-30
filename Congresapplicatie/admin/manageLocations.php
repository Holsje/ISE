<?php
	require_once("manageLocations_Class.php");
	require_once("manageLocationsSubmits.php");
	$currentLocationName = 'default';
	$currentLocationCity = 'default';
	if (isset($_SESSION['currentLocationName']) && isset($_SESSION['currentLocationCity'])) {
		$manageLocations = new ManageLocations($_SESSION['currentLocationName'], $_SESSION['currentLocationCity'], array("Gebouw", "Straat", "Huisnummer", "Postcode"), null);
	}
	else {
		$manageLocations = new ManageLocations($currentLocationName, $currentLocationCity, array("Gebouw", "Straat", "Huisnummer", "Postcode"), null);
	}
	$manageLocations->createLocationScreen();	
	
?>
