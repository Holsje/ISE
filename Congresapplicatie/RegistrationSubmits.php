<?php
	if(isset($_POST['validInput'])) {
		$queryMailAddressAlreadyExists = "SELECT mailAddress FROM Person WHERE mailAddress = ?";
		$paramsMailAddressAlreadyExists = array($_POST['mailAddress']);
		$result = $registration->getDatabase()->sendQuery($queryMailAddressAlreadyExists, $paramsMailAddressAlreadyExists);
		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			$mail = $row['mailAddress'];
		}
		if (!empty($mail)) {
			echo 'mailInUse';
			die();
		}
		else {
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
	}

?>