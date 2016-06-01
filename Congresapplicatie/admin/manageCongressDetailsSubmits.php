<?php
/**
 * Created by PhpStorm.
 * User: erike
 * Date: 30-5-2016
 * Time: 21:32
 */
    require_once('ManageCongress_Class.php');
    $manageCongress = new ManageCongress();
    if(isset($_POST['getCongressInfo'])) {
        echo $manageCongress->getCongressInfo($_SESSION['congressNo']);
        die();
    }
    else if(isset($_POST['bewerken'])) {

        if (isset($_POST['oldCongressSubjects'])) {
            $oldSubjects = $_POST['oldCongressSubjects'];
        }else{
            $oldSubjects = null;
        }

        if (isset($_POST['selectedSubjects'])) {
            $newSubjects = $_POST['selectedSubjects'];
        }else{
            $newSubjects = null;
        }
        /*
        echo "Oud:";
        var_dump($oldSubjects);
        echo "Nieuw:";
        var_dump($newSubjects);
        */
        $subjectsToInsert = array();
        $subjectsToDelete = array();

        for ($i = 0; $i < sizeof($oldSubjects); $i++){
            $subjectDelete = true;
            for ($j = 0; $j < sizeof($newSubjects); $j++){
                if ($oldSubjects[$i] == $newSubjects[$j]){
                    $subjectDelete = false;
                    break;
                }
            }
            if($subjectDelete) {
                array_push($subjectsToDelete, $oldSubjects[$i]);
            }
        }

        for ($i = 0; $i < sizeof($newSubjects); $i++){
            $subjectInsert = true;
            for ($j = 0; $j < sizeof($oldSubjects); $j++){
                if ($newSubjects[$i] == $oldSubjects[$j]){
                    $subjectInsert = false;
                    break;
                }
            }
            if ($subjectInsert){
                array_push($subjectsToInsert, $newSubjects[$i]);
            }
        }
        /*
        echo "Insert:";
        var_dump($subjectsToInsert);
        echo "Delete:";
        var_dump($subjectsToDelete);
        */



        $params = array(
            array($_SESSION['congressNo'], SQLSRV_PARAM_IN),
            array($_POST['newCongressName'], SQLSRV_PARAM_IN),
            array($_POST['newCongressStartDate'], SQLSRV_PARAM_IN),
            array($_POST['newCongressEndDate'], SQLSRV_PARAM_IN),
            array($_POST['newCongressPrice'], SQLSRV_PARAM_IN),
            array($_POST['oldCongressName'], SQLSRV_PARAM_IN),
            array($_POST['oldCongressStartDate'], SQLSRV_PARAM_IN),
            array($_POST['oldCongressEndDate'], SQLSRV_PARAM_IN),
            array($_POST['oldCongressPrice'], SQLSRV_PARAM_IN)
        );
        $manageCongress->changeRecord("spUpdateCongress",$params,$subjectsToDelete,$subjectsToInsert,$oldSubjects);
        die();
    }
    elseif (isset($_POST['saveBanner'])){
        $filename = handleFile('img/Banners/', 'bannerPic', 'Congress'.$_SESSION['congressNo']);
        var_dump($filename);
        $sqlStmt = "UPDATE Congress SET Banner = ? WHERE CongressNo = ?";
        $params = array($filename, $_SESSION['congressNo']);

        $result = $manageCongress->getDatabase()->sendQuery($sqlStmt, $params);
    }
?>