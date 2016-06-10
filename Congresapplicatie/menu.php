<nav role="navigation" class="navbar navbar-default largeMenu col-xs-12 col-sm-12 col-md-12">
    <div class="navbar-header col-xs-12 col-sm-12 col-md-12">
        <div class="input-group col-xs-12 col-sm-12 col-md-12">
            <?php
                echo'<button type="button" name="planCongress" class="col-md-1 btn btn-default plan" onClick="location.href=&quot;inschrijven.php&quot;">Plan je Congres</button>';
                if (isset($_SESSION['preview'])){
                    echo '<form method="post" action="index.php">';
                    echo '<button type="submit" class="col-md-1 btn btn-default" name="quitPreview">Stop preview</button>';
                    echo '</form>';
                }
                if (!isset($_SESSION['userWeb'])){
            ?>
                <button type="button" name="login" class="login btn btn-default popupButton" data-file="#popUpLogin">Login</button>
                <?php
                }
                else if (isset($_SESSION['userWeb'])){
                    echo '<form method="post" class="logoutForm col-md-offset-9" action="'.$_SERVER['PHP_SELF'].'">';
                    echo '<span class="welcomeText col-md-6">Welkom ' . $_SESSION['userWeb'] . '</span>';
                    echo '<input type="submit" class="logout btn btn-default col-md-4" name="logout" value="Uitloggen">';
                    echo '</form>';
                }
            
            ?>

        </div>
    </div>
</nav>
