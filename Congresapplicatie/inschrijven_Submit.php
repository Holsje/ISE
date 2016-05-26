<?php
	if (isset($_SESSION['userWeb'])) {
		if (isset($_POST['signUpForCongressButton'])) {
			echo 'ingelogd en op laatste knop gedrukt';
		}
	}
	if (isset($_SESSION['congressNo'])) {
		$_SESSION['pageCount'] = 0;
		$_SESSION['runningFormData'] = array();
		$_SESSION['lastPage'] = null;
	}
	else if (!isset($_SESSION['congressNo'])) {
		die("Congressnummer niet meegegeven ");
	}
	
	if(isset($_SESSION['pageCount'])) {
		if(isset($_POST['previousDayButton'])) {
			$_SESSION['pageCount']--;
		}
		if (isset($_POST['nextDayButton'])) {
			$_SESSION['pageCount']++;
		}
	}
	
	if (isset($_POST['ajaxRequest'])) {
			$_SESSION['lastPage'] = 'lastpage';
			if (isset($_SESSION['userWeb'])) {
				echo 'logged in';
			}
			die();
	}
	
	if (isset($_POST['eventNoSelected'])) {
		foreach($_POST['eventNoSelected'] as $eventAndTrack) {
			$trackNo = (integer) substr($eventAndTrack, 0, 1);
			$eventNo = (integer) substr($eventAndTrack, 2);
			array_push($_SESSION['runningFormData'], $trackNo);
			array_push($_SESSION['runningFormData'], $eventNo);
		}
	}
?>
