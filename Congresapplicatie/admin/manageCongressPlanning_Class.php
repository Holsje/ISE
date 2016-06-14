<?php 
	
	class manageCongressPlanning extends Management {
		
		private $tracks;
		private $events;
		private $location;
		//private $eventsInTrack;
		private $daysOfCongress;
		private $numDayOfCongress;
		
		private $congressNo;
		private $hourHeight;
		public $currentDay;
		
		public function __construct($congressNo) {
			$this->congressNo = $congressNo;
			parent::__construct();
				
			$this->daysOfCongress = $this->getAllCongressDays();
			if (!isset($_GET['day'])) {
				$_GET['day'] = 0;
			}
			$this->currentDay = $this->determineCurrentDay();
			
			$this->tracks = $this->getTracks();
			$this->events = $this->getEvents();
			$this->location = $this->getCongressLocation();
			$this->getEventsInTrack();
			$this->hourHeight = 100;
			
		}
		
		public function determineCurrentDay() {
			$this->numDayOfCongress = $_GET['day'];
			if (isset($this->daysOfCongress[$_GET['day']])) {
				return $this->daysOfCongress[$_GET['day']];
			}
			else {
				return $this->daysOfCongress[0];
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
			
			$this->makeCarouselItem(true);
		}
		
		private function makeCarouselItem($active) {
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
					echo '<div class="eventBoxPlanning eventBox col-xs-12 col-sm-12 col-md-12" style="height:' . (24*$this->hourHeight) . 'px;" id=' . $track['TRACKNO'] . '>';
						if(isset($track['EVENTS'])) {
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
						}
					echo '</div>';
					
				echo '</div>';
			}
		}
		
		public function getTracks() {
				$result  = $this->database->sendQuery("SELECT TRACKNO,DESCRIPTION,TNAME " . 
														"FROM TRACK " . 
														"WHERE CongressNo = ?",array($this->congressNo));
				$tracks = array();
				if($result) {
					while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
						$tracks[$row['TRACKNO']] = $row;
					}
				}else {
					return;
				}
				return $tracks;
		}
		
		public function getBuildingsByCongressLocation() {
			$queryGetBuildingsByLocation = "SELECT BName
											FROM Building
											WHERE LocationName = ? AND City = ?";
			$params = array($this->location['LocationName'], $this->location['City']);
			$result = $this->database->sendQuery($queryGetBuildingsByLocation, $params);
			$resultArray = array();
			if ($result) {
				while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
					array_push($resultArray, $row['BName']);
				}
			}
			return $resultArray;
		}
		
		public function getRoomsByBuilding($buildingName) {
			$queryRooms = "SELECT RName FROM Room WHERE BName = ?";
			$paramsRooms = array($buildingName);
			
			$result = $this->database->sendQuery($queryRooms, $paramsRooms);
			$rooms = array();
			if ($result) {
				while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
					array_push($rooms, $row['RName']);
				}
			}
			return $rooms;
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
			array_push($daysOfCongress, $congressDates["ENDDATE"]->format("Y-m-d"));
			return $daysOfCongress;
		}
		
		public function createManageCongressEventScreen() {
			echo '<div class="col-xs-3 col-sm-3 col-md-3 eventPlanningBox">';
				foreach($this->events as $event) {
					$this->createScreen->createSmallEventInfo($event[0], $event[1], 1*$this->hourHeight,null);
				}
			echo '</div>';
		}
		
		public function createPreviousDayButton() {
			if ($this->currentDay == $this->daysOfCongress[0]) {
				return;
			}
			else {
				$previousDay = new Submit("Vorige dag", ($this->numDayOfCongress-1), "day", "btn btn-default", true, true);
				echo $previousDay->getObjectCode();
			}
		}
		
		public function createNextDayButton() {
			if ($this->currentDay == end($this->daysOfCongress)) {
				return;
			}
			else {
				$nextDay = new Submit("Volgende dag", ($this->numDayOfCongress+1), "day", "btn btn-default", true, true);
				echo $nextDay->getObjectCode();
			}
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
		
		public function deleteEventFromTrack($eventNo, $trackNo) {
			$queryDeleteEventFromTrack = "DELETE FROM EventInTrack WHERE EventNo = ? AND TrackNo = ? AND CongressNo = ?";
			$paramsDeleteEventFromTrack = array($eventNo, $trackNo, $this->congressNo);
			
			$this->database->sendQuery($queryDeleteEventFromTrack, $paramsDeleteEventFromTrack);
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
								WHERE T.CONGRESSNO = ? AND EIT.Start IS NOT NULL AND EIT.[End] IS NOT NULL AND DATEDIFF(day,start,?) = 0";
			$paramsEventInTrack = array($this->congressNo,$this->currentDay);
			$result = $this->database->sendQuery($queryEventInTrack, $paramsEventInTrack);
			if ($result) {
				while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
					if(!isset($this->tracks[$row['TRACKNO']]['EVENTS'])) {
						$this->tracks[$row['TRACKNO']]['EVENTS'] = array();						
					}
					array_push($this->tracks[$row['TRACKNO']]['EVENTS'],array('EVENTNO' => $row['EVENTNO'], 'ENAME' => $row['ENAME'], 'START' => $row['START'], 'END' => $row['END']));
				}
			}
		}
		
		public function addEventToTrack($trackNo, $congressNo, $eventNo, $startTime, $endTime, $buildingName, $rooms) {
			$startTimeDateTime = new DateTime($this->currentDay . ' ' . $startTime);
			$endTimeDateTime = new DateTime($this->currentDay . ' ' . $endTime);

			if (sqlsrv_begin_transaction($this->database->getConn()) == false) { //BEGIN TRANSACTION
				die( print_r( sqlsrv_errors(), true ));
			}
			
			$resultArray = array();
			
			$queryEventInTrack = "INSERT INTO EventInTrack(TrackNo, CongressNo, EventNo, Start, [End]) VALUES(?, ?, ?, ?, ?)";
			$paramsEventInTrack = array($trackNo, $congressNo, $eventNo, $startTimeDateTime->format("Y-m-d H:i:s"), $endTimeDateTime->format("Y-m-d H:i:s"));
			$resultEventInTrack = $this->database->sendQuery($queryEventInTrack, $paramsEventInTrack);
			array_push($resultArray, $resultEventInTrack);
			
			if (isset($rooms) && $rooms != '') {
				foreach($rooms as $room) {
					$queryEventInRoom = "INSERT INTO EventInRoom(CongressNo, TrackNo, EventNo, LocationName, City, BName, RName) VALUES(?, ?, ?, ?, ?, ?, ?)";
					$paramsEventInRoom = array($congressNo, $trackNo, $eventNo, $this->location['LocationName'], $this->location['City'], $buildingName, $room);
					$resultEventInRoom = $this->database->sendQuery($queryEventInRoom, $paramsEventInRoom);
					array_push($resultArray, $resultEventInRoom);
				}
			}
			
			foreach($resultArray as $result) {
				if (!is_string($result)) {
					sqlsrv_commit($this->database->getConn()); //COMMIT TRANSACTION
				}
				else {
					echo $result;
					sqlsrv_rollback($this->database->getConn()); //ROLLBACK TRANSACTION
				}
			}
		}
		
		public function getCongressLocation() {
			$queryLocations = "SELECT LocationName, City FROM Congress WHERE CongressNo = ?";
			$result = $this->database->sendQuery($queryLocations, array($this->getCongressNo()));
			$resultArray = array();
			if ($result) {
				while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
					$resultArray = $row;
				}
			}
			return $resultArray;
		}
		
		public function getCongressNo() {
			return $this->congressNo;
		}
		public function getAllLocations() {
			return $this->allLocations;
		}
	}
?>