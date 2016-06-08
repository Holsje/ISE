<?php
    global $manageEvents;
   

    $manageEvents->createManagementScreen(array('EventNo','Naam', 'Type','Prijs','MaxVisitors','Description'),$manageEvents->getEventsByCongress());
    $manageEvents->createCreateEventsScreen();
    $manageEvents->createEditEventsScreen();
    if(isset($_POST['eventNoReload'])){
       $manageEvents->createAddSpeakerToEvent('show',null,null);
        echo "<script> setDataTable = true;
                       eventNo = ". $_POST['eventNoReload'] ."</script>";
    }else{
        $manageEvents->createAddSpeakerToEvent('',null,null);
    }
?>