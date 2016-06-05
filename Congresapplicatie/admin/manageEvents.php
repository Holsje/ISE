<?php
    global $manageEvents;
   

    $manageEvents->createManagementScreen(array('EventNo','Naam', 'Type','Prijs','MaxVisitors','Description'),$manageEvents->getEventsByCongress());
    $manageEvents->createCreateEventsScreen();
    $manageEvents->createEditEventsScreen();
    $manageEvents->createAddSpeakerToEvent();
?>