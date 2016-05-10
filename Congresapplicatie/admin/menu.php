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
                <li><a href="#">Congres</a></li>
        <?php
            if ($_SESSION['liberties']=="Algehele beheerder") {
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
            <li class="col-sm-2 col-md-2"><a href="#">Congres</a></li>
        <?php
            if ($_SESSION['liberties']=="Algehele beheerder") {
        ?>
            <li class="col-sm-2 col-md-2"><a href="#">Gebouwen</a></li>
            <li class="col-sm-2 col-md-2"><a href="#">Evenementen</a></li>
            <li class="col-sm-2 col-md-2"><a href="#">Medewerkers beheren</a></li>
        <?php
            }
        ?>
        </ul>
        <?php
            }
        ?>
    </div>
</nav>