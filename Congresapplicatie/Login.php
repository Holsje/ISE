<?php

require_once('Login_Class.php');
$login = new Login();
include('LoginSubmits.php');

topLayout('Inloggen',null,null);
?>

    <div class="row">
        <div class="container   col-md-12 col-xs-12">
            <div class="content col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-xs-8 col-xs-offset-2">
                <?php
                    if (!isset($_SESSION['userWeb'])) {
                        $login->createLoginScreen();
                    }
                    else if(isset($_SESSION['userWeb'])){
                        echo "Welkom op de website!";
                    }
                ?>
            </div>
        </div>
    </div>

<?php
    bottomLayout();
?>
