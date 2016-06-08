<?php


	if(isset($_POST['toevoegen'])) {
		$paramsCongress = array($_POST['congressName'], $_POST['startDate'], $_POST['endDate'], $_POST['Price'], $_POST['Public']);
		if (!isset($_POST['selectedSubjects'])){
			$_POST['selectedSubjects'] = null;
		}
		echo $manageCongress->addRecord($paramsCongress, $_POST['selectedSubjects']);
		die();
	}	

	else if(isset($_POST['verwijderen'])) {
		echo $manageCongress->deleteRecord("DELETE FROM Congress WHERE CongressNo=?",array($_POST['congressNo']));
		die();
	}

	else if (isset($_POST['goToEdit'])){
		$_SESSION['congressNo'] = $_POST['congressNo'];
		$_SESSION['congressName'] = $_POST['congressName'];
        echo 'testdasdsda'.$_POST['congressName'];
		//header('Location: manage.php');
		die();
	}
	
?>
