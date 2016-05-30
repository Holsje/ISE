<?php
	if(isset($_POST['getSpeakerInfo'])){
		echo $manageSpeakers->getSpeakerInfo($_POST['personNo']);
		die();
	}	
?>