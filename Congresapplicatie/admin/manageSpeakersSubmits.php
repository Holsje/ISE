<?php
	$manageSpeakers = new ManageSpeakers($manage->getCongressNo());
	
	if(isset($_POST['getSpeakerInfo'])){
		echo $manageSpeakers->getSpeakerInfo($_POST['personNo']);
		die();
	}	
	if(isset($_POST['toevoegen'])){
		if($_POST['toevoegen'] == 'createSpeaker') {
		
			$params = array(
				array($_POST['speakerName'],SQLSRV_PARAM_IN),
				array($_POST['LastName'],SQLSRV_PARAM_IN),
				array($_POST['mailAddress'],SQLSRV_PARAM_IN),
				array($_POST['phoneNumber'],SQLSRV_PARAM_IN),
				array($manage->getCongressNo(), SQLSRV_PARAM_IN),
				array($_POST['agreement'],SQLSRV_PARAM_IN),
				array($_POST['description'],SQLSRV_PARAM_IN),
				array(null,SQLSRV_PARAM_IN)		
			);
			
			echo $manageSpeakers->addRecord('spRegisterSpeakerFromCongress',$params);
		}
	}
	if(isset($_POST['buttonSaveSwapList'])) {
		if($_POST['buttonSaveSwapList'] == 'spreker') {
		
			if(isset($_POST['oldPersons'])) {
				$oldSpeakers = $_POST['oldPersons'];
			}else {
				$oldSpeakers = null;
			}
			if(isset($_POST['newPersons'])) {
				$newSpeakers = $_POST['newPersons'];
			}else {
				$newSpeakers = null;
			}
			
			$speakersToInsert = array();
			$speakersToDelete = array();
			for ($i = 0; $i < sizeof($oldSpeakers); $i++){
				$speakerDelete = true;
				for ($j = 0; $j < sizeof($newSpeakers); $j++){
					if ($oldSpeakers[$i] == $newSpeakers[$j]){
						$speakerDelete = false;
						break;
					}
				}
				if($speakerDelete) {
					array_push($speakersToDelete, $oldSpeakers[$i]);
				}
			}
			for ($i = 0; $i < sizeof($newSpeakers); $i++){
				$speakerInsert = true;
				for ($j = 0; $j < sizeof($oldSpeakers); $j++){
					if ($newSpeakers[$i] == $oldSpeakers[$j]){
						$speakerInsert = false;
						break;
					}
				}
				if ($speakerInsert){
					array_push($speakersToInsert, $newSpeakers[$i]);
				}
			}
			
			$manageSpeakers->updateSpeakers($speakersToDelete,$speakersToInsert);	
		
			die();
		}
	}
	if(isset($_POST['getSpeakerInfo'])) {	
		return $manageSpeakers->getSpeakerInfo();
	}
	if(isset($_POST['updateSpeakerInfo'])) {
		
					
					
					
					
		
		$params = array(
			/*
				Mogelijk later voor lost updates
				
				$_POST["oldFirstName"],
				$_POST["oldLastName"],
				$_POST["oldMailAddress"],
				$_POST["oldPhoneNumber"],
				$_POST["oldDescription"],
				$_POST["oldAgreement"],				
			*/
			array($_POST["personNo"],SQLSRV_PARAM_IN),
			array($_POST["newFirstName"],SQLSRV_PARAM_IN),
			array($_POST["newLastName"],SQLSRV_PARAM_IN),
			array($_POST["newMailAddress"],SQLSRV_PARAM_IN),
			array($_POST["newPhoneNumber"],SQLSRV_PARAM_IN),
			array($_POST["newAgreement"],SQLSRV_PARAM_IN),
			array($_POST["newDescription"],SQLSRV_PARAM_IN),
			array(null,SQLSRV_PARAM_IN),
			array($manage->getCongressNo(),SQLSRV_PARAM_IN)		
		);
		
		echo $manageSpeakers->addRecord("spUpdateSpeaker",$params);		
		
		
		die();
	}
	if(isset($_POST['deleteSpeaker'])) {
		$manageSpeakers->deleteRecord("DELETE FROM SpeakerOfCongress WHERE personNo = ?",array($_POST['personNo']));
		$manageSpeakers->deleteRecord("DELETE FROM SPEAKER WHERE personNo = ?",array($_POST['personNo']));
		die();
	}
?>