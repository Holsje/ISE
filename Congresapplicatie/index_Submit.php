<?php
    $_SESSION['pageCount'] = 0;
	$_SESSION['monthCount'] = 0;
	$_SESSION['yearCount'] = 0;

	$_SESSION['runningFormData'] = array();
	$_SESSION['lastPage'] = null;
	
	if(isset($_POST['getInfo'])){
        echo $indexClass->getEventInfo($_POST['eventNo'],$_SESSION['congressNo']);
        die();
    }
    if(isset($_POST['speakerPop'])){
        echo $indexClass->getSpeakerInfo($_POST['personID']);
        die();
    }
    if(isset($_POST['subjectClick'])){
        
        echo $indexClass->getEventsBySubject($_POST['subject']);
        die();
    }

?>
