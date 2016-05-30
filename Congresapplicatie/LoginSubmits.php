<?php

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if (isset($_POST['submitLogin'])) {
			if ($login->checkLogin($_POST['input-username'], $_POST['input-password'])){
					header('Location: '. $_SERVER['HTTP_REFERER']);
			}
			else {
                $_SESSION['loginFail'] = "Gebruikersnaam en/of wachtwoord zijn onjuist";
			}
		}
        else if (isset($_POST['logout'])){
            unset($_SESSION['userWeb']);
            if (isset($_COOKIE['userWeb'])){
                setcookie('userWeb', '', 1);
            }
			if (isset($_SESSION['pageCount'])) {
				$congressNo = $_SESSION['congressNo'];
				session_unset();
				header('Location: index.php?congressNo=' . $congressNo);
			}
			else {
				header('Location: index.php?' . $_SERVER['QUERY_STRING']);
			}
		}
	}

	
?>
