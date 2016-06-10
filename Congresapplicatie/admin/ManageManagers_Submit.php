<?php
    if(isset($_POST['ToevoegenManager'])){
        $type = '';
        if(isset($_POST['isGM'])){
            $type = 'G';
        }else if(isset($_POST['isCM'])){
            $type = 'C';
        }
        if(isset($_POST['isGM']) and isset($_POST['isCM'])){
            $type = 'A';
        }
        $result = $manageManagers->handleAddManager($type);
        if(is_string($result)){
            $_SESSION['errMsgAddManager'] = $result;
        }
    }

    if(isset($_POST['AanpassenManager'])){
        $result = $manageManagers->handleUpdateManager();
        if(is_string($result)){
            $_SESSION['errMsgEditManager'] = $result;
        }
    }
    if(isset($_POST['VerwijderManager'])){
        $manageManagers->handleDeleteManager();
        die();
    }

?>