<?php
    require_once('manageEvents_Class.php');
    require_once('manageEvents_Submits.php');
    $manageEvents = new ManageEvents();

    $manageEvents->createManagementScreen(array('EventNo','Naam', 'Type','MaxVisitors'),$manageEvents->getEventsByCongress());
    
?>