<?php
	if(isset($_POST['validInput'])) {
		$params = array( 
					 array($_POST['firstName'], SQLSRV_PARAM_IN),
					 array($_POST['lastName'], SQLSRV_PARAM_IN),
					 array($_POST['mailAddress'], SQLSRV_PARAM_IN),
					 array($_POST['phoneNum'], SQLSRV_PARAM_IN),
					 array(hash("sha256", $_POST['password']), SQLSRV_PARAM_IN),
					 array(0, SQLSRV_PARAM_IN), //haspaid
				   );
		echo $registration->addRecord("spRegisterVisitor",$params);
        die();
	}

?>