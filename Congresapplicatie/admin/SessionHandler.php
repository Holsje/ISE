<?php
/**
 * Created by PhpStorm.
 * User: erike
 * Date: 10-5-2016
 * Time: 14:45
 */



    function sessionHandler($logInRequired, $onlyAdmin){
        session_start();

        if ($logInRequired) {
            if (!isset($_SESSION['user'])) {
                header('Location:noEntry.php');
            }
        }

        if ($onlyAdmin){
            if ($_SESSION['liberties'] != "Algemene beheerder"){
                header('Location:noEntry.php');
            }
        }
    }

    function sessionHandlerWeb($logInRequired){
        session_start();
        
        if ($logInRequired) {
            if (!isset($_SESSION['userWeb'])) {
                header('Location:admin/noEntry.php');
            }
        }
    }




?>
