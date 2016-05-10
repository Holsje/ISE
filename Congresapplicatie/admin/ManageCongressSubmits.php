<?php
	if(isset($_POST['toevoegen'])) {
		if($_POST['toevoegen'] == "createCongress") {
			$params = array( 
                 array($_POST['congressName'], SQLSRV_PARAM_IN),
                 array($_POST['congressSubject'], SQLSRV_PARAM_IN),
                 array($_POST['congressLocation'], SQLSRV_PARAM_IN),
                 array($_POST['startDate'], SQLSRV_PARAM_IN),
                 array($_POST['endDate'], SQLSRV_PARAM_IN)
               );
			$manageCongress->addRecord("spInsertCongress",$params);
		}
	}	
	else if(isset($_POST['bewerken'])) {
	   	$params = array( 
            array($_POST['congressNo'], SQLSRV_PARAM_IN),
            array($_POST['newCongressName'], SQLSRV_PARAM_IN),
            array($_POST['newCongressSubject'], SQLSRV_PARAM_IN),
            array($_POST['newCongressLocation'], SQLSRV_PARAM_IN),
            array($_POST['newCongressStartDate'], SQLSRV_PARAM_IN),
            array($_POST['newCongressEndDate'], SQLSRV_PARAM_IN),
            array($_POST['oldCongressName'], SQLSRV_PARAM_IN),
            array($_POST['oldCongressSubject'], SQLSRV_PARAM_IN),
            array($_POST['oldCongressLocation'], SQLSRV_PARAM_IN),
            array($_POST['oldCongressStartDate'], SQLSRV_PARAM_IN),
            array($_POST['oldCongressEndDate'], SQLSRV_PARAM_IN)  
        );
        echo $manageCongress->changeRecord("spUpdateCongress",$params);
        die();
	}	
	
	else if(isset($_POST['verwijderen'])) {
		
		echo $manageCongress->deleteRecord("DELETE FROM Congress WHERE CongressNo=?",array($_POST['CongressNo']));
		die();
	}
	
?>
