<?php
	if (isset($_POST['cancelSignUp'])) {
		unset($_SESSION['lastPage']);
		unset($_SESSION['runningFormData']);
		header('Location: index.php?congressNo=' . $_SESSION['congressNo'] . '&lang=' . $_SESSION['lang']);
	}
	if (!isset($_SESSION['userPersonNo'])) {
		header('Location: inschrijven.php');
	}
	else if (isset($_POST['confirmSignUp'])) {
		$queryPersonNoCheck = "SELECT PersonNo FROM VisitorOfCongress WHERE PersonNo = ? AND CongressNo = ?";
		$paramsPersonNoCheck = array($_SESSION['userPersonNo'], $_SESSION['congressNo']);
		$result = $dataBase->sendQuery($queryPersonNoCheck, $paramsPersonNoCheck);
		if ($result) {
			while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
				$personNo = $row['PersonNo'];
			}
			if (!empty($personNo)) {
				echo '<h1>U kunt zich niet inschrijven.</h1>';
				echo '<p class="errText">U bent al ingeschreven voor dit congres. U wordt doorverwezen naar de homepagina.</p>';
				header("refresh:5;url='index.php?congressNo=". $_SESSION['congressNo'] . '');
				die();
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
		
		
		for($i = 0; $i < sizeof($confirm->events['eventNos']); $i++) {
			if ($i != 0) {
				$queryInsertEventOfVisitorOfCongress .= ", (?,?,?,?)";
			}
			array_push($params, $_SESSION['userPersonNo']);
			array_push($params, $_SESSION['congressNo']);
			array_push($params, $confirm->tracks['trackNos'][$i]);
			array_push($params, $confirm->events['eventNos'][$i]);
		}
		$queryFailed = false;

		array_push($resultArray, $dataBase->sendQuery($queryInsertEventOfVisitorOfCongress, $params));
		
		$resultErrorcode = sqlsrv_query($dataBase->getConn(), "SELECT @@ERROR");
		if ($resultErrorcode) {
			$error = sqlsrv_fetch_array($resultErrorcode)[0];
			if ($error != 0) {
				echo "SQL ERROR CODE: ". $error;
			}
		}
		foreach($resultArray as $result) {
			if (!is_string($result)) {
				sqlsrv_commit($dataBase->getConn());
				unset($_SESSION['lastPage']);
				unset($_SESSION['runningFormData']);
				header("refresh:5;url='index.php?congressNo=". $_SESSION['congressNo'] . '');
			}
			else {
				sqlsrv_rollback($dataBase->getConn());
				unset($_SESSION['lastPage']);
				unset($_SESSION['runningFormData']);
				$queryFailed = true;
				echo $result;
				header("refresh:5;url='index.php?congressNo=". $_SESSION['congressNo'] . '');
			}
		}
		if ($queryFailed) {
			echo "<br>";
			echo 'Het inschrijven is mislukt en uw gegevens zijn niet opgeslagen. U wordt doorverwezen naar de homepagina.';
			die();
		}
		else {
			echo '<br>';
			echo 'Gegevens zijn opgeslagen. U wordt binnen 5 seconden doorverwezen naar de homepagina.';
			die();
		}
	}
?>