<?php
	$manageSpeakers = new ManageSpeakers($manage->getCongressNo());
	
	if(isset($_POST['toevoegen'])){
		if($_POST['toevoegen'] == 'createSpeaker') {
			require_once('fileUploadHandler.php');
            $addedFile = 0;
            if(isset($_FILES['upoadCreateSpeaker'])){
                $addedFile = 1;
            }
			$params = array(
				array($_POST['speakerName'],SQLSRV_PARAM_IN),
				array($_POST['LastName'],SQLSRV_PARAM_IN),
				array($_POST['mailAddress'],SQLSRV_PARAM_IN),
				array($_POST['phoneNumber'],SQLSRV_PARAM_IN),
				array($_SESSION['personNo'],SQLSRV_PARAM_IN),
				array($addedFile,SQLSRV_PARAM_IN),
				array($_POST['description'],SQLSRV_PARAM_IN),		
				array($manage->getCongressNo(), SQLSRV_PARAM_IN),
				array($_POST['agreement'],SQLSRV_PARAM_IN),	
			);
			
			$result = $manageSpeakers->createSpeaker('spAddSpeakerToCongress',$params,$_POST['mailAddress']);
			if(!is_int($result)){
				$errorOnCreateSpeaker = $result;
			}else if($result) {
				handleFile("img/speakers/","uploadCreateSpeaker","speaker" . $result);
			}

		}
	}
	if(isset($_POST['aanpassen'])) {
        $addedFile = 0;
            if(isset($_FILES['uploadEditSpeaker'])){
                $addedFile = 1;
            }
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
				array($addedFile,SQLSRV_PARAM_IN),
				array($manage->getCongressNo(), SQLSRV_PARAM_IN),
				array($_SESSION['personNo'],SQLSRV_PARAM_IN)
			);
			$EditSpeakers = $manageSpeakers->editSpeaker('spUpdateSpeakerSpeakerOfCongress',$params);
			if($EditSpeakers != null){
				$editSpeakersError = $EditSpeakers;
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
				array($addedFile,SQLSRV_PARAM_IN)
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
			echo $manageSpeakers->getSpeakerOfCongressInfo($_POST['personNo'],$_SESSION['personNo']);
			die();
		}else {
			echo $manageSpeakers->getSpeakerInfo($_POST['personNo'],$_SESSION['personNo']);
			die();
		}
	}
	if(isset($_POST['uploadCreateSpeaker'])) {
        $addedFile = 0;
            if(isset($_FILES['uploadCreateSpeaker'])){
                $addedFile = 1;
            }
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
			array($addedFile,SQLSRV_PARAM_IN),
			array($manage->getCongressNo(),SQLSRV_PARAM_IN)		
		);
		
		handleFile("img/speakers/","uploadCreateSpeaker","speaker" . $personNo);
		echo $manageSpeakers->addRecord("spUpdateSpeaker",$params);		
		
		
		die();
	}
	if(isset($_POST['deleteSpeaker'])) {
        
		$result1 = $manageSpeakers->deleteRecord("DELETE FROM SpeakerOfCongress WHERE personNo = ? AND congressNo = ?",array($_POST['personNo'],$_SESSION['congressNo']));
		$result2 = $manageSpeakers->deleteRecord("DELETE S
                                       FROM Speaker S INNER JOIN PersonTypeOfPerson PTOP 
	                                       ON PTOP.PersonNo = ?
                                       WHERE S.PersonNo = ? AND (S.Owner = PTOP.PersonNo OR PTOP.TypeName='Algemene beheerder' )",array($_SESSION['personNo'],$_POST['personNo']));
        if(gettype($result2) == 'string'){
            echo 1;
        }
        elseif(sqlsrv_rows_affected($result2) === false){
            echo 1;
        }
        
		die();
	}
?>