<?php
    $manageEvents = new ManageEvents();
    if(isset($_POST['ToevoegenEvent'])){
        $manageEvents->handleSubmitAdd();
    }
    if(isset($_POST['deleteEvent'])){
        $sqlDelete = '  DELETE FROM Event
                        WHERE CongressNo = ? and EventNo = ?';
        $params = array($_SESSION['congressNo'],$_POST['eventNo']);
        $result = $database->sendQuery($sqlDelete,$params);

        if (is_string($result)){
            $_SESSION['errorMsgDeleteEvent'] = $result;
        }
        $dir = '../Congresses/Congress' . $_SESSION['congressNo'] . '/Event'.$_POST['eventNo'];
        require_once('fileUploadHandler.php');
        Delete($dir);
        die();
    }
    if(isset($_POST['updateEvent'])){
        echo $manageEvents->getSelectedEventInfo($_POST['eventNo']);
        die();
    }
    if(isset($_POST['AanpassenEvent'])){
        $manageEvents->handleSubmitEdit($_POST['AanpassenEvent']);
    }
    if(isset($_POST['speakerOfEvent'])){
        $returnArray = array();
        $returnArray['event'] = $manageEvents->getSpeakersOfEvent($_SESSION['congressNo'],$_POST['eventNo']);
        $returnArray['congres'] = $manageEvents->getSpeakersOfCongress($_SESSION['congressNo'],$_POST['eventNo']);
        echo json_encode($returnArray);
        die();
    }
    if(isset($_POST['addSpeakerOfEvent'])){
        echo $manageEvents->handleSpeakerEdit($_SESSION['congressNo'], $_POST['eventNo']);
    }
?>