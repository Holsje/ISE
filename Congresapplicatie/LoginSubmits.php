<?php
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if (isset($_POST['submitLogin'])) {
			if ($login->checkLogin($_POST['input-username'], $_POST['input-password'])){
					header('Location: '. $_SERVER['HTTP_REFERER']);
			}
			else {
                $_SESSION['loginFail'] = $_SESSION['translations']['loginFailed'];
			}
		}
        else if (isset($_POST['logout'])){
            unset($_SESSION['userWeb']);
            if (isset($_COOKIE['userWeb'])){
                setcookie('userWeb', '', 1);
            }
			if (isset($_SESSION['pageCount'])) {
				unset($_SESSION['runningFormData']);
				unset($_SESSION['pageCount']);
                unset($_SESSION['userPersonNo']);
				header('Location: index.php?congressNo=' . $_SESSION['congressNo'] . '&lang=' . $_SESSION['lang']);
			}
			else {
				header('Location: index.php?' . $_SERVER['QUERY_STRING']);
			}
		}
	}

	
?>
