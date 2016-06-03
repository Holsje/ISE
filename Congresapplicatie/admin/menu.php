
<nav role="navigation" class="navbar navbar-default largeMenu col-xs-12 col-sm-12 col-md-12">
    <div class="navbar-header col-xs-12 col-sm-12 col-md-12">
        <?php
            require_once('InlogSubmit.php');
            if (isset($_SESSION['user'])) {
        ?>
        <ul class="nav navbar-nav col-sm-12 col-md-12">
            <li class="col-sm-2 col-md-2"><a href="ManageCongress.php">Congres</a></li>
        <?php
            if ($_SESSION['liberties']=="Algemene beheerder") {
        ?>
            <li class="col-sm-2 col-md-2"><a href="ManageSpeakersGM.php">Sprekers</a></li>
            <li class="col-sm-2 col-md-2"><a href="#">Locaties</a></li>
            <li class="col-sm-2 col-md-2"><a href="#">Medewerkers beheren</a></li>

            <div class="col-sm-4 col-md-4">
            <?php
                }
                if (isset($_SESSION['user'])){
                    echo '<form method="post" class="logoutForm" action="'.$_SERVER['PHP_SELF'].'">';
                    echo '<span class="welcomeText">Welkom ' . $_SESSION['user'] . '</span>';
                    echo '<input type="submit" name="logout" value="Uitloggen">';
                    echo '</form>';
                }
            ?>
            </div>
        </ul>

        <?php
            }
        ?>
    </div>
</nav>