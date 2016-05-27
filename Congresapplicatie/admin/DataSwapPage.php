<?php

require_once('ManageCongress_Class.php');
$manageCongress = new ManageCongress();
include('ManageCongressSubmits.php');

$js = '<script type="text/javascript" src="../js/DataSwap.js"></script>';
topLayoutManagement('DataSwapPage',null,$js);
?>

    <div class="row">
        <div class="container   col-md-12 col-xs-12">
            <div class="content col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-xs-8 col-xs-offset-2">
				
                <?php					
					$listBoxLeft = new Listbox(null, null, null, "col-xs-3 col-md-3 col-sm-3", false, false, array("testhead","test2"), array(array("test1","test2"),array("test3","test4"),array("test5","test")), "listBoxLeft");
					$listBoxRight = new Listbox(null, null, null, "col-xs-3 col-md-3 col-sm-3", false, false, array("testhead","test2"), array(array("test8","test"),array("test7","test"),array("test6","test")), "listBoxRight");
					
					$manageCongress->getCreateScreen()->createDataSwapList($listBoxLeft,$listBoxRight,false);
            ?>
            </div>
        </div>
    </div>
