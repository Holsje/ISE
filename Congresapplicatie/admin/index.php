<?php
    require_once('SessionHandler.php');
    sessionHandler(false, false);
    require_once('Login_Class.php');
    $login = new Login();

    require_once('../pageConfig.php');

    require_once('InlogSubmit.php');

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
                    $login->createLoginScreenNotLoggedIn($errorstring);
                }
                else{
            ?>
            <h1>Welkom <?php echo $_SESSION['user']; ?> in de beheerapplicatie!</h1>
            <?php
                    $login->createLoginScreenLoggedIn();
                }
            ?>
        </div>
    </div>
</div>
<?php
    bottomLayout();
?>
