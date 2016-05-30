<?php
	require_once('ManageSpeakers_class.php');
	$manageSpeakers = new ManageSpeakers($this->getCongressNo());
	include('manageSpeakersSubmits.php');
	
	$manageSpeakers->createManagementScreen();
	
	$manageSpeakers->createCreateSpeakerScreen();
	$manageSpeakers->createEditSpeakerScreen();
?>