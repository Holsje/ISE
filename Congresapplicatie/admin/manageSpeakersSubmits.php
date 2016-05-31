<?php
	if(isset($_POST['getSpeakerInfo'])){
		echo $manageSpeakers->getSpeakerInfo($_POST['personNo']);
		die();
	}	
	if(isset($_POST['toevoegen'])){
		if($_POST['toevoegen'] == 'createSpeaker') {
			/*
			
			$_POST['Voornaam'];
			$_POST['Achternaam'];
			$_POST['Mailadres'];
			$_POST['Telefoonnr'];
			$_POST['Description'];*/
		}
	}
	if(isset($_POST['buttonSaveSwapList'])) {
		if($_POST['buttonSaveSwapList'] == 'spreker') {
		
		
			$oldSpeakers = $_POST['oldPersons'];
			$newSpeakers = $_POST['newPersons'];
			/*
			echo "Oud:";
			var_dump($oldSpeakers);
			echo "Nieuw:";
			var_dump($newSpeakers);
			*/
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
			/*
			echo "Insert:";
			var_dump($speakersToInsert);
			echo "Delete:";
			var_dump($speakersToDelete);
			*/
			/*$params = array(
				array($_POST['congressNo'], SQLSRV_PARAM_IN),
				array($_POST['newCongressName'], SQLSRV_PARAM_IN),
				array($_POST['newCongressStartDate'], SQLSRV_PARAM_IN),
				array($_POST['newCongressEndDate'], SQLSRV_PARAM_IN),
				array($_POST['oldCongressName'], SQLSRV_PARAM_IN),
				array($_POST['oldCongressStartDate'], SQLSRV_PARAM_IN),
				array($_POST['oldCongressEndDate'], SQLSRV_PARAM_IN)
			);*/
			//$manageCongress->changeRecord("spUpdateCongress",$params,$oldSpeakers,$newSpeakers);
			
			
		
			die();
		}
	}
?>