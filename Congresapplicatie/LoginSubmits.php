<?php

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if (isset($_POST['submitLogin'])) {
			if ($login->checkLogin($_POST['input-username'], $_POST['input-password'])){
				//setcookie('user', $_POST['input-username'], time() + (14*24*60*60));
				echo "Ingevulde inloggegevens zijn juist!";
				header('Location: index.php');
			}
			else{
				$errorstring = "Ingevulde inloggegevens zijn onjuist!";
			}
		}
        else if (isset($_POST['logout'])){
            unset($_SESSION['userWeb']);
            if (isset($_COOKIE['userWeb'])){
                setcookie('userWeb', '', 1);
            }
            header('Location: index.php');
        }
	}

	
?>
