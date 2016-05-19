<?php
	require_once('Index_Class.php');
    $indexClass = new Index();
    topLayout('Index','','');
?>
    <div class="row">
        <div class="container col-md-12 col-xs-12">
            <div class="content col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-xs-8 col-xs-offset-2">
                <?php
                    $indexClass->createCongresOverzicht();
                ?>
            </div>
        </div>
    </div>

    <?php	
	bottomLayout();
$indexClass->createEventInfoPopup();
?>
