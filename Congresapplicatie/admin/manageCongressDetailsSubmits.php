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
        echo $manageCongress->getCongressInfo($_POST['congressNo']);
        die();
    }
    else if(isset($_POST['bewerken'])) {

        $oldSubjects = $_POST['oldCongressSubjects'];
        $newSubjects = $_POST['selectedSubjects'];
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
            array($_POST['congressNo'], SQLSRV_PARAM_IN),
            array($_POST['newCongressName'], SQLSRV_PARAM_IN),
            array($_POST['newCongressStartDate'], SQLSRV_PARAM_IN),
            array($_POST['newCongressEndDate'], SQLSRV_PARAM_IN),
            array($_POST['oldCongressName'], SQLSRV_PARAM_IN),
            array($_POST['oldCongressStartDate'], SQLSRV_PARAM_IN),
            array($_POST['oldCongressEndDate'], SQLSRV_PARAM_IN)
        );


        $manageCongress->changeRecord("spUpdateCongress",$params,$oldSubjects,$newSubjects);
        die();
    }
?>