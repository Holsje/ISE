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

    if (!isset($_POST['previewCongress'])){
        if (!$indexClass->congressPublic($_SESSION['congressNo']) && !isset($_SESSION['preview'])){
            die("U kunt deze pagina niet bezoeken!");
        }
    }
    else{
        $_SESSION['preview'] = $_SESSION['congressNo'];
    }

    if (isset($_POST['quitPreview'])){
        unset($_SESSION['preview']);
        header('Location: admin/manage.php?congressNo='. $_SESSION['congressNo']);
    }

?>
