<?php
	
	
	if (isset($_GET['congressNo'])) {
		$_SESSION['congressNo'] = $_GET['congressNo'];
		$_SESSION['pageCount'] = 0;
		$_SESSION['runningFormData'] = array();
		$_SESSION['lastPage'] = null;
	}
	else if (!isset($_SESSION['congressNo'])) {
		die("COngressnummer niet meegegeven ");
	}
	
	if(isset($_SESSION['pageCount'])) {
		if(isset($_POST['previousDayButton'])) {
			$_SESSION['pageCount']--;
		}
		if (isset($_POST['nextDayButton'])) {
			$_SESSION['pageCount']++;
		}
	}
	
	if (isset($_POST['eventNoSelected'])) {
		foreach($_POST['eventNoSelected'] as $eventAndTrack) {
			$trackNo = (integer) substr($eventAndTrack, 0, 1);
			$eventNo = (integer) substr($eventAndTrack, 2);
			array_push($_SESSION['runningFormData'], $trackNo);
			array_push($_SESSION['runningFormData'], $eventNo);
		}
		
		if (isset($_POST['ajaxRequest'])) {
			$_SESSION['lastPage'] = 'lastpage';
			if (isset($_SESSION['userWeb'])) {
				echo 'logged in';
			}
			die();
		}
	}	
?>
