<?php
    require_once('manageEvents_Class.php');
    $manageEvents = new ManageEvents();
    require_once('manageEvents_Submits.php');
   

    $manageEvents->createManagementScreen(array('EventNo','Naam', 'Type','MaxVisitors'),$manageEvents->getEventsByCongress());
    $manageEvents->createCreateEventsScreen();
?>