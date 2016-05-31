<?php
	if(isset($_POST['getSpeakerInfo'])){
		echo $manageSpeakers->getSpeakerInfo($_POST['personNo']);
		die();
	}	
	if(isset($_POST['toevoegen'])){
		if($_POST['toevoegen'] == 'createSpeaker') {
			
			
			$_POST['Voornaam'];
			$_POST['Achternaam'];
			$_POST['Mailadres'];
			$_POST['Telefoonnr'];
			$_POST['Description'];
		}
	}
?>