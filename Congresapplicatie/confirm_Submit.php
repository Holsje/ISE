<?php
	if (!isset($_SESSION['userWeb'])) {
		session_unset();
		die("<script>location.href = 'inschrijven.php'</script>");
	}
	else {
		if (isset($_POST['cancelSignUp'])) {
			header('Location: inschrijven.php?congressNo=' . $_SESSION['congressNo']);
		}
		if (isset($_POST['confirmSignUp'])) {
			
			$queryPersonNoCheck = "SELECT 1 FROM VisitorOfCongress WHERE PersonNo = ? AND CongressNo = ?";
			$paramsPersonNoCheck = array($_SESSION['userPersonNo'], $_SESSION['congressNo']);
			$result = $dataBase->sendQuery($queryPersonNoCheck, $paramsPersonNoCheck);
			
			if ($result) {
				die("U bent al ingeschreven voor dit congres.");
			}
			
			if (sqlsrv_begin_transaction($dataBase->getConn()) == false) { //BEGIN TRANSACTION
				die( print_r( sqlsrv_errors(), true ));
			}
			$resultArray = array();
			
			$queryInsertVisitorOfCongress = "INSERT INTO VisitorOfCongress(PersonNo, congressNo, hasPaid) VALUES(?, ?, ?)";
			$hasPaid = 0;
			$params = array($_SESSION['userPersonNo'], $_SESSION['congressNo'], $hasPaid);
			array_push($resultArray, $dataBase->sendQuery($queryInsertVisitorOfCongress, $params));
			
			$queryInsertEventOfVisitorOfCongress = "INSERT INTO EventOfVisitorOfCongress(PersonNo, CongressNo, TrackNo, EventNo) VALUES(?,?,?,?)"; 
			$params = array();
			if (isset($_SESSION['runningFormData'])) {
				for($i = 0; $i < sizeof($_SESSION['runningFormData']); $i+= 2) {
					if ($i != 0){
					$query1 .= ", (?,?,?,?)";
					}
					array_push($params, $_SESSION['userPersonNo']);
					array_push($params, $_SESSION['congressNo']);
					array_push($params, $_SESSION['runningFormData'][$i]);
					array_push($params, $_SESSION['runningFormData'][$i+1]);
				}
			
				$queryFailed = false;
			
				array_push($resultArray, $dataBase->sendQuery($queryInsertEventOfVisitorOfCongress, $params));
				
				foreach($resultArray as $result) {
					if (is_string($result)){
						sqlsrv_rollback($dataBase->getConn());
						$queryFailed = true;
					}
				}
			
				if (!$queryFailed) {
					sqlsrv_commit($dataBase->getConn());
					echo 'Gegevens zijn opgeslagen';
					
				}
				else {
					echo $result . ' De gegevens zijn niet opgeslagen.';
				}
			}
		}
	}
?>