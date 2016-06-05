<?php
    global $manageTracks;
    $manageTracks->createManagementScreen(array("TrackNo", "TrackNaam", "Omschrijving"), $manageTracks->getTracks());

    $manageTracks->createCreateTrackScreen();
    $manageTracks->createEditTrackScreen();

?>
