<?php
	require_once('ManageSpeakers_class.php');
	$manageSpeakers = new ManageSpeakers($this->getCongressNo());
	
	$manageSpeakers->createManagementScreen();
	
	$manageSpeakers->createCreateSpeakerScreen();
	$manageSpeakers->createEditSpeakerScreen();
?>