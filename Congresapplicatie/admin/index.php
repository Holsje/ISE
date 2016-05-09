<?php

    require_once('Login_Class.php');
    $login = new Login();
    require_once('Management.php');
    $management = new Management();
    require_once('CreateScreen.php');
    $createscreen = new CreateScreen();
    require_once('../pageConfig.php');
    require_once('InlogSubmit.php');


    $inputUsername = new Text(null, "Gebruikersnaam", "input-username", "form-control col-xs-12 col-md-4 col-sm-4", true, true, true);
    $inputPassword = new Password(null, "Wachtwoord", "input-password", "form-control col-xs-12 col-md-4 col-sm-4", true, true, true);
    $submitButtonLogin = new Submit("Inloggen", null, "login", "form-control col-md-4 col-md-offset-4 btn btn-default", true, true);
    $screenObjects = array($inputUsername, $inputPassword, $submitButtonLogin);

    $submitButtonLogout = new Submit("Uitloggen", null, "logout", "form-control col-md-4 col-md-offset-4 btn btn-default", true, true);
    $screenObjectsLoggedIn = array($submitButtonLogout);
    topLayoutManagement("Index", "", "");
?>

<div class="row">
    <div class="container col-sm-12 col-md-12 col-xs-12">
        <div class="content col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-xs-8 col-xs-offset-2">
            <?php
                if (!isset($_SESSION['user'])) {
            ?>
                    <h1>Inloggen</h1>
            <?php
                    $createscreen->createForm($screenObjects, null);
                }
                else{
            ?>
            <h1>Welkom <?php echo $_SESSION['user']; ?> in de beheerapplicatie!</h1>
            <?php
                    $createscreen->createForm($screenObjectsLoggedIn, null);
                }
            ?>
        </div>
    </div>
</div>
<?php
    bottomLayout();
?>
