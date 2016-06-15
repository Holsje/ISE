<?php
require_once('SessionHandler.php');
sessionHandler(true, false);
require_once('Manage_Class.php');
require_once('Management.php');
require_once('ManageSpeakers_class.php');
require_once("manageLocations_Class.php");
require_once('ManageTracks_Class.php');
require_once('manageEvents_Class.php');
require_once('manageVisitors_Class.php');
global $server, $databaseName, $uid, $password;
$database = new Database($server,$databaseName,$uid,$password);

$manage = new Manage();
if(isset($_SESSION['congressNo'])) {
	$manage->setCongressNo($_SESSION['congressNo']);
}
else {
	$manage->setCongressNo(1);
}
include('manageEvents_Submits.php');
include('ManageCongressDetailsSubmits.php');
include('manageSpeakersSubmits.php');
include('manageLocationsSubmits.php');
include('manageTracksSubmits.php');
include('manageVisitorsSubmits.php');


$js = '<script src="../js/editCongressManagement.js"></script>';
$js .= '<script src="../js/evenement.js"></script>';
$js .= "<script src='../js/locationManagement.js'></script>";
$js .= "<script src='../js/manageSpeaker.js'></script>";
$js .= "<script src='../js/trackManagement.js'></script>";
$js .= "<script src='../js/manageVisitors.js'></script>";
$js .= "<script src='../js/manage.js'></script>";
$js .= "<script src='../js/subjects.js'></script>"; 

$css = '<link rel="stylesheet" href="../css/manage.css">';

topLayoutManagement('Beheren Congres', $css, $js);
?>

    <div class="row">
        <div class="container   col-md-12 col-xs-12">
            <div class="content col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
                <div class="row">
                    <h1 class="col-md-9">Aanpassen congres - <?php echo $_SESSION['congressName'];?></h1>
                    
                    <form name="previewCongressForm" class="col-md-3" method="POST" action="../index.php?congressNo=<?php echo $_SESSION['congressNo']?>">
                        <button type="submit" class=" col-md-offset-10 btn btn-default previewObject" value="Preview" name="previewCongress">Preview</button>
                    </form>
                    <?php 
                    $_SESSION['Public'];if($_SESSION['Public'] == 'Ja'){ ?>
                    <span class="col-md-6 errorMsg">Let op alle wijzigingen die u doorvoert zijn meteen zichtbaar op de website.</span>
                    <?php } ?>
                </div>
                <div class="row">
                    <?php
                        $manage->createAddSubjectScreen();
                        $manage->createManageScreen();
                        $manageSpeakers->createCreateSpeakerScreen();
                        $manageSpeakers->createEditSpeakerOfCongressScreen();
                        $manageSpeakers->createEditSpeakerScreen();
                    ?>
                </div>
            </div>
        </div>
    </div>

    <?php
    
    bottomLayout();
?>
