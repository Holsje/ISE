<?php

	if ($_SERVER['REQUEST_METHOD']) {
		if (isset($_POST['submitLogin'])) {
			if ($login->checkLogin($_POST['input-username'], $_POST['input-password'])){
				//setcookie('user', $_POST['input-username'], time() + (14*24*60*60));
				$_SESSION['userWeb'] = $_POST['input-username'];
				echo "Ingevulde inloggegevens zijn juist!";
				//header('Location: login.php');
			}
			else{
				$errorstring = "Ingevulde inloggegevens zijn onjuist!";
			}
		}
	}

	
?>
