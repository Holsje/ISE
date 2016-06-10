<?php
	include('admin/sessionHandler.php');
	sessionHandlerWeb(false, false);
	require_once('Index_Class.php');
	$indexClass = new Index();
	include('inschrijven_Submit.php');
	require_once('ScreenCreator/CreateScreen.php');
	require_once('connectDatabasePublic.php');
	require_once('pageConfig.php');
	require_once('inschrijven_class.php');
	$inschrijven = new Inschrijven($_SESSION['congressNo'], $dataBase, $createScreen);
	topLayout('Inschrijven',null,null);	
?>
	 <div class="row">
        <div class="container col-sm-12 col-md-12 col-xs-12">
            <div class="content col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
		      <div class="row">
				<h1>
				<?php 
					echo '<div class="congressTitle">';
						echo $inschrijven->getCongressName();
					echo '</div>';
				?>
				</h1>
				<?php
					echo '<p class="congressDescription">';
						echo $inschrijven->getCongressDescription();
					echo '</p>';
					echo '<h1 class="congressCurrentDate">';
						echo $inschrijven->writeOutCurrentDate();
					echo '</h1>';
				?>
				<form name="formSignUpForCongress" method="POST" action="<?php $_SERVER['PHP_SELF'] ?>">
				<div id="myCarousel" class="carousel slide" data-ride="carousel" data-interval="false">
				    <div id = "carousel" class="carousel-inner" role="listbox">
						<?php
							$inschrijven->createSchedule();
						?>
					</div>
					<a id="carouselButtonLeft" class="carouselButton left carousel-control" href="#myCarousel" role="button" data-slide="prev">
	    				<span class="glyphicon glyphicon-arrow-left carouselIcon" aria-hidden="true"></span>
	    				<span class="sr-only">Previous</span>
	  				</a>
	  				<a class="carouselButton right carousel-control" href="#myCarousel" role="button" data-slide="next">
	    				<span class="glyphicon glyphicon-arrow-right carouselIcon" aria-hidden="true"></span>
	    				<span class="sr-only">Next</span>
	  				</a>
                    <?php
						$inschrijven->createPreviousDayButton();
						$inschrijven->createNextDayButton();
					?>
				</div>
				</form>
              </div>
            </div>
         </div>
    </div>
<?php
	bottomLayout();
	$indexClass->createSpeakerInfoPopup();
	$indexClass->createEventInfoPopup();
?>