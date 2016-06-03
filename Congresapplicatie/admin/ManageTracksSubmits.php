<?php

$manageTracks = new ManageTracks();
if(isset($_POST['getTrackInfo'])) {
    $_SESSION['trackNo'] = $_POST['trackNo'];
    echo $manageTracks->getTrackInfo($_SESSION['congressNo'], $_SESSION['trackNo']);
    die();
}
else if(isset($_POST['createTrack'])){
    $params = array($_SESSION['congressNo'], $_POST['trackName'], $_POST['trackDescription']);
    echo $manageTracks->createTrack($params);
}
else if(isset($_POST['editTrack'])) {
    $params = array($_POST['trackName'], $_POST['trackDescription'], $_SESSION['congressNo'], $_SESSION['trackNo']);
    echo $manageTracks->editTrack($params);
}

else if(isset($_POST['delete'])) {
    echo $manageTracks->deleteRecord("DELETE FROM Track WHERE TrackNo=? AND CongressNo=?",array($_POST['trackNo'],$_SESSION['congressNo']));
    die();
}


?>
