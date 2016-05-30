<?php
	require_once('ManageSpeakers_class.php');
	$manageSpeakers = new ManageSpeakers();
	
	$manageSpeakers->createManagementScreen(array("Voornaam","Achternaam","Email"),array(array("Dave","Snowden","davesnowden@gmail.com")));
?>