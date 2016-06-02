<?php
	$manageSpeakers = new ManageSpeakers($manage->getCongressNo());
	
	if(isset($_POST['toevoegen'])){
		if($_POST['toevoegen'] == 'createSpeaker') {
			require_once('fileUploadHandler.php');
			$params = array(
				array($_POST['speakerName'],SQLSRV_PARAM_IN),
				array($_POST['LastName'],SQLSRV_PARAM_IN),
				array($_POST['mailAddress'],SQLSRV_PARAM_IN),
				array($_POST['phoneNumber'],SQLSRV_PARAM_IN),
				array($manage->getCongressNo(), SQLSRV_PARAM_IN),
				array($_POST['agreement'],SQLSRV_PARAM_IN),
				array($_POST['description'],SQLSRV_PARAM_IN),
				array(pathinfo(basename($_FILES['uploadCreateSpeaker']['name']),PATHINFO_EXTENSION),SQLSRV_PARAM_IN)
			);
			
			$personNo = $manageSpeakers->createSpeaker('spRegisterSpeakerFromCongress',$params,$_POST['mailAddress']);
			if(!is_int($personNo)){
				$emailIsWrong = true;
			}else if($personNo) {
				handleFile("img/speakers/","uploadCreateSpeaker","speaker" . $personNo);
			}

		}
	}
	if(isset($_POST['aanpassen'])) {
		if($_POST['aanpassen'] == "updateSpeakerOfCongress") {
			require_once('fileUploadHandler.php');
			$params = array(
				array($_POST['personNo'],SQLSRV_PARAM_IN),
				array($_POST['speakerName'],SQLSRV_PARAM_IN),
				array($_POST['LastName'],SQLSRV_PARAM_IN),
				array($_POST['mailAddress'],SQLSRV_PARAM_IN),
				array($_POST['phoneNumber'],SQLSRV_PARAM_IN),
				array($_POST['agreement'],SQLSRV_PARAM_IN),
				array($_POST['description'],SQLSRV_PARAM_IN),
				array(pathinfo(basename($_FILES['uploadEditSpeakerOfCongress']['name']),PATHINFO_EXTENSION),SQLSRV_PARAM_IN),
				array($manage->getCongressNo(), SQLSRV_PARAM_IN)
			);
			$EditSpeakers = $manageSpeakers->editSpeaker('spUpdateSpeakerSpeakerOfCongress',$params);
			if($EditSpeakers != null){
				echo "<script>alert('Email adres bestaat al Probeer opnieuw');</script>";
			}else if($_POST["personNo"]) {
				handleFile("img/speakers/","uploadEditSpeakerOfCongress","speaker" . $_POST['personNo']);
			}
		}
		if($_POST['aanpassen'] == "updateSpeaker") {
			require_once('fileUploadHandler.php');
			$params = array(
				array($_POST['personNo'],SQLSRV_PARAM_IN),
				array($_POST['speakerName'],SQLSRV_PARAM_IN),
				array($_POST['LastName'],SQLSRV_PARAM_IN),
				array($_POST['mailAddress'],SQLSRV_PARAM_IN),
				array($_POST['phoneNumber'],SQLSRV_PARAM_IN),
				array($_POST['description'],SQLSRV_PARAM_IN),
				array(pathinfo(basename($_FILES['uploadEditSpeaker']['name']),PATHINFO_EXTENSION),SQLSRV_PARAM_IN)
			);
			$EditSpeakers = $manageSpeakers->editSpeaker('spUpdateSpeaker',$params);
			if($EditSpeakers != null){
				echo "<script>alert('Email adres bestaat al Probeer opnieuw');</script>";
			}else if($_POST["personNo"]) {
				handleFile("img/speakers/","uploadEditSpeaker","speaker" . $_POST['personNo']);
			}
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
		if($_POST['getSpeakerInfo'] == "speakerOfCongress") {
			echo $manageSpeakers->getSpeakerOfCongressInfo($_POST['personNo']);
			DIE();
		}else {
			echo $manageSpeakers->getSpeakerInfo($_POST['personNo']);
			DIE();
		}
	}
	if(isset($_POST['updateSpeakerOfCongress'])) {
		
					
					
		
					
		
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
			array(pathinfo(basename($_FILES['uploadCreateSpeaker']['name'])),PATHINFO_EXTENSION,SQLSRV_PARAM_IN),
			array($manage->getCongressNo(),SQLSRV_PARAM_IN)		
		);
		
		handleFile("speakers/","uploadCreateSpeaker","speaker" . $personNo);
		echo $manageSpeakers->addRecord("spUpdateSpeaker",$params);		
		
		
		die();
	}
	if(isset($_POST['deleteSpeaker'])) {
		$manageSpeakers->deleteRecord("DELETE FROM SpeakerOfCongress WHERE personNo = ?",array($_POST['personNo']));
		$manageSpeakers->deleteRecord("DELETE FROM SPEAKER WHERE personNo = ?",array($_POST['personNo']));
		die();
	}
?>