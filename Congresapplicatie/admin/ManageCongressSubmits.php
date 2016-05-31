<?php
	if(isset($_POST['getCongressInfo'])){
		echo $manageCongress->getCongressInfo($_POST['congressNo']);
		die();
	}

	if(isset($_POST['toevoegen'])) {
		if($_POST['toevoegen']) {
			$paramsCongress = array($_POST['congressName'], $_POST['startDate'], $_POST['endDate'], $_POST['Price'], $_POST['Public'], $_POST['Banner']);

			echo $manageCongress->addRecord($paramsCongress, $_POST['selectedSubjects']);
		}
		die();
	}	
	else if(isset($_POST['bewerken'])) {
		$oldSubjects = $_POST['oldCongressSubjects'];
		$newSubjects = $_POST['selectedSubjects'];

		for ($i = 0; $i < sizeof($oldSubjects); $i++){
			for ($j = 0; $j < sizeof($newSubjects); $j++){

			}
		}

	   	$params = array( 
            array($_POST['congressNo'], SQLSRV_PARAM_IN),
            array($_POST['newCongressName'], SQLSRV_PARAM_IN),
            array($_POST['newCongressStartDate'], SQLSRV_PARAM_IN),
            array($_POST['newCongressEndDate'], SQLSRV_PARAM_IN),
            array($_POST['oldCongressName'], SQLSRV_PARAM_IN),
            array($_POST['oldCongressStartDate'], SQLSRV_PARAM_IN),
            array($_POST['oldCongressEndDate'], SQLSRV_PARAM_IN)  
        );
        echo $manageCongress->changeRecord("spUpdateCongress",$params);
        die();
	}	
	
	else if(isset($_POST['verwijderen'])) {
		
		echo $manageCongress->deleteRecord("DELETE FROM Congress WHERE CongressNo=?",array($_POST['congressNo']));
		die();
	}
	
?>
