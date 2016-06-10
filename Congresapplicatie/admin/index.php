<?php
    require_once('SessionHandler.php');
    require_once('Login_Class.php');
    require_once('../pageConfig.php');

    sessionHandler(false, false);
    $login = new Login();

    include('InlogSubmit.php');

    

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
            <h4>Welkom <?php echo $_SESSION['userName']; ?> in de congres beheerapplicatie!</h4></br>
            <h4>Maak hier boven in het menu een keuze om verder te gaan.</h4>
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
