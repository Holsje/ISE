<?php	
	if(isset($_POST['toevoegen'])){
		if($_POST['toevoegen'] == 'createSpeaker') {
			require_once('fileUploadHandler.php');
			$params = array(
				array($_POST['speakerName'],SQLSRV_PARAM_IN),
				array($_POST['LastName'],SQLSRV_PARAM_IN),
				array($_POST['mailAddress'],SQLSRV_PARAM_IN),
				array($_POST['phoneNumber'],SQLSRV_PARAM_IN),
				array($_POST['description'],SQLSRV_PARAM_IN),
				array(pathinfo(basename($_FILES['uploadCreateSpeaker']['name']),PATHINFO_EXTENSION),SQLSRV_PARAM_IN)
			);
			
			$personNo = $manageSpeakersGM->createSpeaker('spRegisterSpeaker',$params,$_POST['mailAddress']);
			if(!is_int($personNo)){
				$emailIsWrong = true;
			}else if($personNo) {
				handleFile("../img/speakers/","uploadCreateSpeaker","speaker" . $personNo);
			}

		}
	}
	if(isset($_POST['aanpassen'])) {
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
		$EditSpeakers = $manageSpeakersGM->editSpeaker('spUpdateSpeaker',$params);
		if($EditSpeakers != null){
			echo "<script>alert('Email adres bestaat al Probeer opnieuw');</script>";
		}else if($_POST["personNo"]) {
			handleFile("../img/speakers/","uploadEditSpeaker","speaker" . $_POST['personNo']);
		}
	}
	if(isset($_POST['getSpeakerInfo'])) {			
		echo $manageSpeakersGM->getSpeakerInfo($_POST['personNo']);
		die();
	}
	
	if(isset($_POST['deleteSpeaker'])) {
		$manageSpeakersGM->deleteRecord("DELETE FROM SpeakerOfCongress WHERE personNo = ?",array($_POST['personNo']));
		$manageSpeakersGM->deleteRecord("DELETE FROM SPEAKER WHERE personNo = ?",array($_POST['personNo']));
		die();
	}
?>