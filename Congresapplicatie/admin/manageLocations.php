<?php	
	if (isset($_SESSION['currentLocationName']) && isset($_SESSION['currentLocationCity'])) {
		$manageLocations = new ManageLocations($_SESSION['currentLocationName'], $_SESSION['currentLocationCity'], array("Gebouw", "Straat", "Huisnummer", "Postcode"));
	}
	else {
		$manageLocations = new ManageLocations(null, null, array("Gebouw", "Straat", "Huisnummer", "Postcode"));
	}
	$manageLocations->createLocationScreen();
	$manageLocations->createAddBuildingPopUp();
	$manageLocations->createDeleteBuildingPopUp();
	
?>
