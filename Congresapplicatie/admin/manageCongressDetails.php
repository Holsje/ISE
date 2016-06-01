<?php
/**
 * Created by PhpStorm.
 * User: erike
 * Date: 30-5-2016
 * Time: 19:18
 */
    require_once('ManageCongress_Class.php');
    $manageCongress = new ManageCongress();
if(isset($_POST['getCongressInfo'])){
    echo $manageCongress->getCongressInfo($_POST['congressNo']);
    die();
}
$manageCongress->createEditCongressScreen();



?>