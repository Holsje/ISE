<?php
    if(isset($_POST['getInfo'])){
        echo $indexClass->getEventInfo($_POST['eventNo'],$_SESSION['congresNo']);
        die();
    }
    if(isset($_POST['speakerPop'])){
        echo $indexClass->getSpeakerInfo($_POST['personID']);
        die();
    }

?>
