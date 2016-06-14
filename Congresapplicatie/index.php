<?php
    require_once('admin/SessionHandler.php');
    sessionHandlerWeb(false);
	if(isset($_GET['congressNo'])){
		$_SESSION['congressNo'] = $_GET['congressNo'];
	}

	require_once('Index_Class.php');
    $indexClass = new Index();
    $_SESSION['translations'] = $indexClass->getTranslations();
	require_once('index_Submit.php');
echo'<div id="wrapper">';
    topLayout('Index',null,null);

?>
    <div class="row">
        <div class="container col-md-12 col-xs-12">
            <div class="content col-sm-10 col-sm-offset-1 col-md-11 col-md-offset-1 col-xs-10 col-xs-offset-1">
                <?php
                    $indexClass->createCongressOverview();
                ?>
            </div>
        </div>
    </div>
    </div>

    <?php	
	bottomLayout();
$indexClass->createSpeakerInfoPopup();
$indexClass->createEventInfoPopup();
?>
