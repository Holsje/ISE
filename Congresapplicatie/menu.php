<nav role="navigation" class="navbar navbar-default largeMenu col-xs-12 col-sm-12 col-md-12">
    <div class="navbar-header col-xs-12 col-sm-12 col-md-12">
        <div class="input-group col-xs-12 col-sm-12 col-md-12">
            <?php
				if (basename($_SERVER['SCRIPT_NAME']) == "inschrijven.php"){
					echo'<button type="button" name="planCongress" class="col-md-2 btn btn-default plan" onClick="location.href=&quot;index.php?lang='.$_SESSION["lang"].'&quot;">'.$_SESSION['translations']['backToHome'].'</button>';
				}else{
					echo'<button type="button" name="planCongress" class="col-md-2 btn btn-default plan" onClick="location.href=&quot;inschrijven.php&quot;">'.$_SESSION['translations']['planCongress'].'</button>';
                }
				if (isset($_SESSION['preview'])){
                    echo '<form method="post" action="index.php">';
                    echo '<button type="submit" class="col-md-1 btn btn-default" name="quitPreview">Stop preview</button>';
                    echo '</form>';
                }

                echo '<a href="index.php?lang=NL"><img src="img/CountryFlags/Netherlands.png" class="countryFlag" alt="Netherlands"></a>';
                echo '<a href="index.php?lang=EN"><img src="img/CountryFlags/United Kingdom.png" class="countryFlag" alt="United Kingdom"></a>';
                echo '<a href="index.php?lang=DE"><img src="img/CountryFlags/Germany.png" class="countryFlag" alt="Germany"></a>';

                if (!isset($_SESSION['userWeb'])){
            ?>
                <button type="button" name="login" class="login btn btn-default popupButton" data-file="#popUpLogin"><?php echo $_SESSION['translations']['login']?></button>
                <?php
                }
                else if (isset($_SESSION['userWeb'])){
                    echo '<form method="post" class="logoutForm col-md-offset-9" action="'.$_SERVER['PHP_SELF'].'?lang='. $_SESSION['lang'] .'">';
                    echo '<span class="welcomeText col-md-6">'. $_SESSION['translations']['welcomeText'] . ' ' . $_SESSION['userWeb'] . '</span>';
                    echo '<input type="submit" class="logout btn btn-default col-md-4" name="logout" value="'. $_SESSION['translations']['logout'] .'">';
                    echo '</form>';
                }
            
            ?>

        </div>
    </div>
</nav>
