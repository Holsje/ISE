<?php
require_once('SessionHandler.php');
require_once('Manage_Class.php');
sessionHandler(false, false);
$manage = new Manage();
if(isset($_SESSION['congressNo'])) {
	$manage->setCongressNo($_SESSION['congressNo']);
}else {
	$manage->setCongressNo(1);
}
$js = "<script src='../js/manage.js'></script>";
$js .= "<script src='../js/locationManagement.js'></script>";
$css = '<link rel="stylesheet" href="../css/manage.css">"';
topLayoutManagement('Beheren Congres', $css, $js);
?>

    <div class="row">
        <div class="container   col-md-12 col-xs-12">
            <div class="content col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-xs-8 col-xs-offset-2">
                <h1>Aanpassen congres</h1>
				<?php
					$manage->createManageScreen();
				?>
            </div>
        </div>
    </div>

    <?php     
    bottomLayout();
?>