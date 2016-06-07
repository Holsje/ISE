<?php 

	class manageCongressPlanning extends Management {
		
		private $inschrijven;
		private $tracks;
		private $events;
		//private $eventsInTrack;

		private $daysOfCongress;
		
		private $congressNo;
		private $hourHeight;
		
		public function __construct($congressNo) {
			$this->congressNo = $congressNo;
			parent::__construct();
			$this->inschrijven = new Inschrijven($congressNo);
			$this->tracks = $this->inschrijven->getTracks();
			$this->events = $this->getEvents();
			//$this->eventsInTrack = $this->getEventsInTrack();
			$this->getEventsInTrack();
			$this->hourHeight = 100;
			$this->daysOfCongress = $this->getAllCongressDays();
			if (!isset($_SESSION['pageCountPlanning'])) {
				$_SESSION['pageCountPlanning'] = 0;
			}
		}
		
		public function createManageCongressTrackScreen() {
			echo '<div class="carouselControls">';
			echo '<a id="carouselButtonLeft" class="carouselButton left carousel-control" href="#myCarousel" role="button" data-slide="prev">';
				echo '<span class="glyphicon glyphicon-arrow-left carouselIcon" aria-hidden="true"></span>';
				echo '<span class="sr-only">Previous</span>';
			echo '</a>';
			echo '<a class="carouselButton right carousel-control" href="#myCarousel" role="button" data-slide="next">';
				echo '<span class="glyphicon glyphicon-arrow-right carouselIcon" aria-hidden="true"></span>';
				echo '<span class="sr-only">Next</span>';
			echo '</a>';	
			echo '</div>';	
			echo '<div class="trackBoxPlanning">';
				$this->createSchedule();
			echo '</div>';
		}
		
		public function createSchedule() {
			$this->createTimeBar();
			
			$this->makeCarouselItem($this->daysOfCongress[$_SESSION['pageCountPlanning']], true);
		}
		
		private function makeCarouselItem($dayKey, $active) {
			if ($active) {
				echo '<div class="item active">';
			}
			else {
				echo '<div class="item">';
			}
				$this->createTracks();
			echo '</div>';
		}
		
		private function createTracks() {
			foreach($this->tracks as $track) {
				echo '<div class="track col-xs-3 col-sm-3 col-md-3">';
					echo '<div class="trackTitle col-xs-12 col-sm-12 col-md-12">';
						echo '<h2>' . $track["TNAME"] . '</h2>';
					echo '</div>';
					echo '<div class="eventBoxPlanning col-xs-12 col-sm-12 col-md-12" style="' . (24*$this->hourHeight) . ';">';
						foreach($track['EVENTS'] AS $event) {
							
							$time = explode('-',$event['START']->format("H-i-s"));
							$startTimeInHours = $time[0] + $time[1]/60 + $time[2]/3600;
							$topOffset = $startTimeInHours * $this->hourHeight;
							$time = explode('-',$event['END']->format("H-i-s"));
							$endTimeInHours = $time[0] + $time[1]/60 + $time[2]/3600;
							
							$distanceFromTop = 1*$this->hourHeight;
							$height = ($endTimeInHours-$startTimeInHours)*$this->hourHeight;
							
							echo $this->createScreen->createSmallEventInfo($event["EVENTNO"], $event["ENAME"], $height,$topOffset);
						}
					echo '</div>';
					
				echo '</div>';
			}
		}
		
		private function getAllCongressDays() {
			$queryStartEndDate = "SELECT Startdate, Enddate FROM Congress WHERE CongressNo = ?";
			$params = array($this->congressNo);
			$result = $this->database->sendQuery($queryStartEndDate, $params);
			if ($result) {
				while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
					$congressDates = array("STARTDATE" => $row['Startdate'], "ENDDATE" => $row['Enddate']);
				}
			}
			$daysOfCongress = array();
			$interval = DateInterval::createFromDateString("1 day");
			$period = new DatePeriod($congressDates["STARTDATE"], $interval, $congressDates["ENDDATE"]);
			
			foreach($period as $dt) {
				array_push($daysOfCongress, $dt->format("Y-m-d"));
			}
			return $daysOfCongress;
		}
		
		public function createManageCongressEventScreen() {
			echo '<div class="col-xs-3 col-sm-3 col-md-3 eventPlanningBox">';
				foreach($this->events as $event) {
					$this->createScreen->createSmallEventInfo($event[0], $event[1], 1*$this->hourHeight,null);
				}
			echo '</div>';
		}
		
		public function createTimeBar() {
			echo '<div id="timeBarPlanning" class="timeBar col-xs-1 col-sm-1 col-md-1" style="height: '. (24* $this->hourHeight) . ';">';
			for($i = 0;$i < 25; $i++) {
				echo '<div style="height:' . $this->hourHeight . ';">';
				if($i < 10) {
					echo '0' . $i;
				}
				else {
					echo $i;
				}
				echo ':00</div>';
			}
			echo '</div>';
		}
		
		public function getEvents() {
			$queryEvents = "SELECT EventNo, EName FROM EVENT WHERE CongressNo = ?";
			$params = array($this->congressNo);
			$result = $this->database->sendQuery($queryEvents, $params);
			$events = array();
			if ($result) {
				while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
					array_push($events, array($row['EventNo'], $row['EName']));
				}
			}

			return $events;
		}
		
		public function getEventsInTrack() {
			$queryEventInTrack = "SELECT T.TRACKNO,E.EVENTNO,E.ENAME,EIT.START,EIT.[END]
								FROM TRACK T
								INNER JOIN EVENTINTRACK EIT
									ON T.TRACKNO = EIT.TRACKNO
									AND T.CongressNo = EIT.CongressNo
								INNER JOIN EVENT E 
									ON E.EVENTNO = EIT.EVENTNO
									AND E.CongressNo = T.CongressNo
								WHERE T.CONGRESSNO = ? AND EIT.Start IS NOT NULL AND EIT.[End] IS NOT NULL";
			$paramsEventInTrack = array($this->congressNo);
			$result = $this->database->sendQuery($queryEventInTrack, $paramsEventInTrack);
			//$eventsInTrack = array();
			if ($result) {
				while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
					if(!isset($this->tracks[$row['TRACKNO']]['EVENTS'])) {
						$this->tracks[$row['TRACKNO']]['EVENTS'] = array();						
					}
					array_push($this->tracks[$row['TRACKNO']]['EVENTS'],array('EVENTNO' => $row['EVENTNO'], 'ENAME' => $row['ENAME'], 'START' => $row['START'], 'END' => $row['END']));
					//array_push($eventsInTrack, array('TRACKNO' => $row['TRACKNO'], 'EVENTNO' => $row['EVENTNO'], 'ENAME' => $row['ENAME'], 'START' => $row['START'], 'END' => $row['END']));
				}
			}
			//echo '<script>console.log(JSON.parse(\'' . json_encode($this->tracks) . '\'));</script>';
			//return $eventsInTrack;
		}
		
		public function getInschrijven() {
			return $this->inschrijven;
		}
	}
?>