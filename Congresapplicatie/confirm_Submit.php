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
			$queryPersonNoCheck = "SELECT PersonNo FROM VisitorOfCongress WHERE PersonNo = ? AND CongressNo = ?";
			$paramsPersonNoCheck = array($_SESSION['userPersonNo'], $_SESSION['congressNo']);
			$result = $dataBase->sendQuery($queryPersonNoCheck, $paramsPersonNoCheck);
			if ($result) {
				while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
					$personNo = $row['PersonNo'];
				}
				if (!empty($personNo)) {
					header("refresh:5;url='index.php?congressNo=". $_SESSION['congressNo'] . '');
					die("U bent al ingeschreven voor dit congres. U wordt in 5 seconden doorverwezen naar de homepagina.");
				}
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
					$queryInsertEventOfVisitorOfCongress .= ", (?,?,?,?)";
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
					unset($_SESSION['lastPage']);
					echo 'Gegevens zijn opgeslagen. U wordt binnen 5 seconden doorverwezen naar de homepagina.';
					header("refresh:5;url='index.php?congressNo=". $_SESSION['congressNo'] . '');
					
				}
				else {
					echo 'Het inschrijven is mislukt. U wordt binnen 5 seconden doorverwezen naar de homepagina.';
					header("refresh:5;url='index.php?congressNo=". $_SESSION['congressNo'] . '');
				}
			}
		}
	}
?>