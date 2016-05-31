<?php
/**
 * Created by PhpStorm.
 * User: erike
 * Date: 26-4-2016
 * Time: 14:29
 */
    require_once('Management.php');
	require_once('ManageCongressDetailsSubmits.php');
    class Manage extends Management{

        public function __construct(){
            parent::__construct();
        }
		
		public function createManageScreen() {
			echo '<div id="tabs">';
			  echo '<ul>';
				echo '<li><a href="#tabs-CongressDetails">Congresgegevens</a></li>';
				echo '<li><a href="#tabs-Location">Locatie</a></li>';
				echo '<li><a href="#tabs-Tracks">Tracks</a></li>';
				echo '<li><a href="#tabs-Events">Evenementen</a></li>';
				echo '<li><a href="#tabs-Speakers">Sprekers</a></li>';
			  echo '</ul>';
			  echo '<div id="tabs-CongressDetails">';
				include("manageCongressDetails.php");
			  echo '</div>';
			  echo '<div id="tabs-Location">';
				include("manageLocations.php");
			  echo '</div>';
			  echo '<div id="tabs-Tracks">';
				include("manageTracks.php");
			  echo '</div>';
			  echo '<div id="tabs-Events">';
				include("manageEvents.php");
			  echo '</div>';
			  echo '<div id="tabs-Speakers">';
				include("manageSpeakers.php");
			  echo '</div>';
			echo '</div>';
		}


	}
