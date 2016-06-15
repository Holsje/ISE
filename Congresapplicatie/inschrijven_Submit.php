<?php
	if(isset($_POST['tracksPerCarouselSlide'])) {
		$_SESSION['tracksPerCarouselSlide'] = $_POST['tracksPerCarouselSlide'];
        echo  $_SESSION['translations']['moreInfo'];
		die();
	}
	
	if (isset($_SESSION['userPersonNo'])) {
		$queryPersonNoCheck = "SELECT PersonNo FROM VisitorOfCongress WHERE PersonNo = ? AND CongressNo = ?";
		$paramsPersonNoCheck = array($_SESSION['userPersonNo'], $_SESSION['congressNo']);
		$result = $dataBase->sendQuery($queryPersonNoCheck, $paramsPersonNoCheck);
		if ($result) {
			while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
				$personNo = $row['PersonNo'];
			}
			if (!empty($personNo)) {
				echo '<h1>'. $_SESSION['translations']['alreadyRegisteredCongress'].'</h1>';
				echo '<p class="errText">' . $_SESSION['translations']['referralHome'] . '</p>';
				header("refresh:5;url='index.php?congressNo=". $_SESSION['congressNo'] . '&lang=' . $_SESSION['lang']);
				die();
			}
		}
	}
	if (isset($_SESSION['congressNo'])) {
		$queryCongressExists = "SELECT CongressNo FROM Congress WHERE CongressNo = ?";
		$paramsCongressExists = array($_SESSION['congressNo']);
		$result = $dataBase->sendQuery($queryCongressExists, $paramsCongressExists);
		if ($result) {
			while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
				$congressNo = $row['CongressNo'];
			}
			if (empty($congressNo)) {
				echo '<h1>Dit congres bestaat niet.</h1>';
				echo '<p class="errText">U wordt doorverwezen naar de homepagina.</p>';
				header("refresh:2;url='index.php?congressNo=". $_SESSION['congressNo'] . '&lang=' . $_SESSION['lang']);
				die();
			}
		}
	}
	
	if(isset($_POST['getInfo'])){
        echo $indexClass->getEventInfo($_POST['eventNo'],$_SESSION['congressNo']);
		die();
    }
    if(isset($_POST['speakerPop'])){
        echo $indexClass->getSpeakerInfo($_POST['personID']);
		die();
    }
	
	if (isset($_SESSION['lastPage']) && isset($_SESSION['userPersonNo'])) {
		header('Location: confirm.php');
	}
	else if (isset($_SESSION['congressNo']) && !isset($_SESSION['runningFormData'])) {
		$_SESSION['pageCount'] = 0;
		$_SESSION['monthCount'] = 0;
		$_SESSION['yearCount'] = 0;

		$_SESSION['runningFormData'] = array();
		$_SESSION['lastPage'] = null;
	}
	else if (!isset($_SESSION['congressNo'])) {
		die("Congresnummer niet meegegeven ");
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
