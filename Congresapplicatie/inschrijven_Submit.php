<?php
	if (isset($_SESSION['userPersonNo'])) {
		$queryPersonNoCheck = "SELECT PersonNo FROM VisitorOfCongress WHERE PersonNo = ? AND CongressNo = ?";
		$paramsPersonNoCheck = array($_SESSION['userPersonNo'], $_SESSION['congressNo']);
		$result = $dataBase->sendQuery($queryPersonNoCheck, $paramsPersonNoCheck);
		if ($result) {
			while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
				$personNo = $row['PersonNo'];
			}
			if (!empty($personNo)) {
				header("refresh:5;url='index.php?congressNo=". $_SESSION['congressNo'] . '');
				die("U bent al ingeschreven voor dit congres. U wordt in 5 seconden doorverwezen naar de homepagina.");
			}
		}
	}
	
	if (isset($_SESSION['lastPage'])) {
		header('Location: confirm.php');
	}
	else if (isset($_SESSION['congressNo']) && !isset($_SESSION['runningFormData'])) {
		$_SESSION['pageCount'] = 0;
		$_SESSION['runningFormData'] = array();
		$_SESSION['lastPage'] = null;
	}
	else if (!isset($_SESSION['congressNo'])) {
		die("Congresnummer niet meegegeven ");
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
	}
	
	if (isset($_POST['ajaxRequest'])) {
			$_SESSION['lastPage'] = 'lastpage';
			if (isset($_SESSION['userWeb'])) {
				echo 'logged in';
			}
			die();
	}
?>
