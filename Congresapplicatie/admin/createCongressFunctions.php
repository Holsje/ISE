<?php
	if(isset($_POST['CongressName']) && isset($_POST['Location']) && isset($_POST['Subject']) && isset($_POST['StartDate']) && isset($_POST['EndDate'])) {

		$newDate = DateTime::createFromFormat("d/m/Y", $_POST['StartDate']);
		$newDate2 = DateTime::createFromFormat("d/m/Y", $_POST['EndDate']);
		$startDate = "" . $newDate->format("Y-m-d");
		$endDate = "" .  $newDate2->format("Y-m-d");
		
		$succesCreateCongress = $CongressApplicationDB->createCongress(($_POST['CongressName']),$_POST['Location'], $_POST['Subject'], $startDate, $endDate);
	}
	
	

?>