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
	else if(isset($_POST['Bewerken'])) {
		if($_POST['Bewerken'] == "updateCongress") {
				$params = array( 
                 array($_POST['congressIdentifier'], SQLSRV_PARAM_IN),
                 array($_POST['congressName'], SQLSRV_PARAM_IN),
                 array($_POST['congressSubject'], SQLSRV_PARAM_IN),
                 array($_POST['congressLocation'], SQLSRV_PARAM_IN),
                 array($_POST['startDate'], SQLSRV_PARAM_IN),
                 array($_POST['endDate'], SQLSRV_PARAM_IN)
               );
			$manageCongress->addRecord("spUpdateCongress",$params);
		}
	}	
	
	else if(isset($_POST['Verwijderen'])) {
		
		echo $manageCongress->deleteRecord("DELETE FROM Congress WHERE CongressNo=?",array($_POST['CongressNo']));
		die();
	}
	
?>