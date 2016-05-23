<?php
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }
	
    if(isset($_POST['getInfo'])){
        echo $indexClass->getEventInfo($_POST['eventNo'],$_SESSION['congresNo']);
        die();
    }

?>
