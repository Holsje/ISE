<?php

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if (isset($_POST['submitLogin'])) {
			if ($login->checkLogin($_POST['input-username'], $_POST['input-password'])){
				//setcookie('user', $_POST['input-username'], time() + (14*24*60*60));
				header('Location: index.php');
			}
			else{
                $_SESSION['loginFail'] = "Gebruikersnaam en/of wachtwoord zijn onjuist";
			}
		}
        else if (isset($_POST['logout'])){
            unset($_SESSION['userWeb']);
            if (isset($_COOKIE['userWeb'])){
                setcookie('userWeb', '', 1);
            }
			if (isset($_SESSION['congressNo'])) {
				header('Location: index.php?congresNo=' . $_SESSION['congressNo']);
			}
			else {
				header('Location: index.php?' . $_SERVER['QUERY_STRING']);
			}
		}
	}

	
?>
