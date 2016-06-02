<?php

$manageTracks = new ManageTracks();
if(isset($_POST['getTrackInfo'])) {
    echo $manageTracks->getTrackInfo($_SESSION['congressNo'], $_POST['trackNo']);
    die();
}
else if(isset($_POST['createTrack'])){
    $params = array($_SESSION['congressNo'], $_POST['newTrackName'], $_POST['newTrackDescription']);
    echo $manageTracks->addRecord($params);
}
else if(isset($_POST['editTrack'])) {
    $params = array($_POST['newTrackName'], $_POST['newTrackDescription'], $_SESSION['congressNo'], $_POST['trackNo']);
    echo $manageTracks->changeRecord($params);
    die();
}

else if(isset($_POST['delete'])) {
    echo $manageTracks->deleteRecord("DELETE FROM Track WHERE TrackNo=? AND CongressNo=?",array($_POST['trackNo'],$_SESSION['congressNo']));
    die();
}


?>
