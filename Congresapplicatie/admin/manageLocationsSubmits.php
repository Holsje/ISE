<?php
	if (isset($_POST['LocationName']) && isset($_POST['City']) && $_POST['SelectedValue']) {
		$_SESSION['currentLocationName'] = $_POST['LocationName'];
		$_SESSION['currentLocationCity'] = $_POST['City'];
		$_SESSION['selectedLocation'] = $_POST['SelectedValue'];
		echo 'gelukt';
		die();
	}
?>