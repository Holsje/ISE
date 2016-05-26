<?php
	include('admin/sessionHandler.php');
	sessionHandlerWeb(false);
	include('inschrijven_Submit.php');
	require_once('Index_Class.php');
	require_once('inschrijven_class.php');
	if (isset($_SESSION['congressNo'])) {
		$congressNo = $_SESSION['congressNo'];
	}
	else {
		$congressNo = $_GET['congressNo'];
	}
	require_once('database.php');
	global $server, $databaseName, $uid, $password;
	$dataBase = new Database($server,$databaseName,$uid,$password);
	$inschrijven = new Inschrijven($congressNo, $dataBase);
	$indexClass = new Index();
	topLayout('Inschrijven',null,null);
	if (isset($_SESSION['lastPage'])) {
		header("Location: confirm.php");
	}
	
?>
	 <div class="row">
        <div class="container col-sm-12 col-md-12 col-xs-12">
            <div class="content col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
		      <div class="row">
				<h1>
				<?php 
					if ((integer)substr($inschrijven->dates['STARTDATE'], 8) < 10) { 
						echo $inschrijven->congressName . '<br>' . substr($inschrijven->dates['STARTDATE'],0, 8) . '0' . $inschrijven->currentDay;
					}
					else {
						echo $inschrijven->congressName . '<br>' . substr($inschrijven->dates['STARTDATE'],0, 8) . $inschrijven->currentDay;
					}
				?>
				</h1>
				<form name="formSignUpForCongress" method="POST" action="/ISE/Congresapplicatie/inschrijven.php">
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
                    
				</div>
				<?php
                    
					$inschrijven->createPreviousDayButton();
					$inschrijven->createNextDayButton();
				?>
				</form>
              </div>
            </div>
         </div>
    </div>
<?php
	bottomLayout();
$indexClass->createEventInfoPopup();
?>