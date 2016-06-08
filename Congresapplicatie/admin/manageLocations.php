<?php

	$manageLocations = new ManageLocations(null,null,array("Gebouw", "Straat", "Huisnummer", "Postcode"));
	$manageLocations->createLocationScreen();
	$manageLocations->createAddBuildingPopUp();
	$manageLocations->createDeleteBuildingPopUp();
	$manageLocations->createEditLocationPopUp();
	$manageLocations->createCreateRoomPopUp();
	$manageLocations->createEditRoomPopUp();
	
?>
