<nav role="navigation" class="col-xs-12 col-sm-12 col-md-12 navbar navbar-default mobileMenu">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header col-xs-12">
        <button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>
    <!-- Collection of nav links and other content for toggling -->
    <div id="navbarCollapse" class="collapse navbar-collapse">
        <?php
            if (isset($_SESSION['user'])) {
        ?>
            <ul class="nav navbar-nav">
                <li><a href="ManageCongress.php">Congres</a></li>
        <?php
            if ($_SESSION['liberties']=="Algemene beheerder") {
        ?>
                <li><a href="#">Gebouwen</a></li>
                <li><a href="#">Evenementen</a></li>
                <li><a href="#">Medewerkers beheren</a></li>
        <?php
            }
        ?>
            </ul>
        <?php
            }
        ?>
    </div>
</nav>

<nav role="navigation" class="navbar navbar-default largeMenu col-xs-12 col-sm-12 col-md-12">
    <div class="navbar-header col-xs-12 col-sm-12 col-md-12">
        <?php
            if (isset($_SESSION['user'])) {
        ?>
        <ul class="nav navbar-nav col-sm-12 col-md-12">
            <li class="col-sm-2 col-md-2"><a href="ManageCongress.php">Congres</a></li>
        <?php
            if ($_SESSION['liberties']=="Algemene beheerder") {
        ?>
            <li class="col-sm-2 col-md-2"><a href="#">Gebouwen</a></li>
            <li class="col-sm-2 col-md-2"><a href="#">Evenementen</a></li>
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