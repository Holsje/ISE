<?php
/**
 * Created by PhpStorm.
 * User: erike
 * Date: 26-4-2016
 * Time: 14:29
 */
    require_once('Management.php');

    class Manage extends Management{
		private $congressNo;
		
        public function __construct(){
            parent::__construct();
        }
		
		public function getCongressNo() {
			return $this->congressNo;
		}
		
		public function setCongressNo($congressNo) {
			$this->congressNo = $congressNo;
		}
		public function createManageScreen() {
			echo '<div id="tabs">';
			  echo '<ul>';
				echo '<li><a href="#tabs-CongressDetails">Congresgegevens</a></li>';
				echo '<li><a href="#tabs-Location">Locatie</a></li>';
				echo '<li><a href="#tabs-Tracks">Tracks</a></li>';
				echo '<li><a href="#tabs-Events">Evenementen</a></li>';
				echo '<li><a href="#tabs-Speakers">Sprekers</a></li>';
				echo '<li><a href="#tabs-Visitors">Bezoekers</a></li>';
			  echo '</ul>';
			  echo '<div id="tabs-CongressDetails">';
				echo '<div class="tabs-content">';
					include("manageCongressDetails.php");
				echo '</div>';
			  echo '</div>';
			  echo '<div id="tabs-Location">';
				echo '<div class="tabs-content">';
					include("manageLocations.php");
				echo '</div>';
			  echo '</div>';
			  echo '<div id="tabs-Tracks">';
				echo '<div class="tabs-content">';
					include("manageTracks.php");
				echo '</div>';
			  echo '</div>';
			  echo '<div id="tabs-Events">';
				echo '<div class="tabs-content">';
					include("manageEvents.php");
				echo '</div>';
			  echo '</div>';
			  echo '<div id="tabs-Speakers">';
				echo '<div class="tabs-content">';
					include("manageSpeakers.php");
				echo '</div>';
			  echo '</div>';
			  echo '<div id="tabs-Visitors">';
				echo '<div class="tabs-content">';
					include("manageVisitors.php");
				echo '</div>';
			  echo '</div>';
			echo '</div>';
		}



	}
