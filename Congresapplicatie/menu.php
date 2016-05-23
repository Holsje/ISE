<nav role="navigation" class="col-xs-12 col-sm-12 col-md-12 navbar navbar-default mobileMenu">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header col-xs-12">
        <button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <div class="input-group col-xs-10">
            <input type="text" class="form-control" name=search placeholder="Zoeken"> <span class="input-group-btn">
				<button class="btn btn-default" type="submit">Zoeken</button>
			</span>
        </div>
    </div>
    <!-- Collection of nav links and other content for toggling -->
    <div id="navbarCollapse" class="collapse navbar-collapse">
        <ul class="nav navbar-nav">
            <li><a href="#">Home</a></li>
        </ul>
    </div>
</nav>

<nav role="navigation" class="navbar navbar-default largeMenu col-xs-12 col-sm-12 col-md-12">
    <div class="navbar-header col-xs-12 col-sm-12 col-md-12">
        <div class="input-group col-xs-12 col-sm-12 col-md-12">
            <?php
                if (!isset($_SESSION['userWeb'])){
            ?>
                <button type="button" class="col-md-offset-11 btn btn-default popupButton" data-file="#popUpLogin">Login</button>
                <?php
                }
                else if (isset($_SESSION['userWeb'])){
                    echo '<form method="post" class="logoutForm col-md-offset-9" action="'.$_SERVER['PHP_SELF'].'">';
                    echo '<span class="welcomeText">Welkom ' . $_SESSION['userWeb'] . '</span>';
                    echo '<input type="submit" class=btn btn-default name="logout" value="Uitloggen">';
                    echo '</form>';
                }
            
            ?>

        </div>
    </div>
</nav>
