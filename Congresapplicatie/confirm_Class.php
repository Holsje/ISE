<?php
	class Confirmation {
		public $congressNo;
		public $events;
		private $eventsRegisteredUser;
		public $tracks;
		private $dataBase;
		
		public function __construct($__congressNo, $__dataBase) {
			$this->congressNo = $__congressNo;
			$this->dataBase = $__dataBase;
			$this->events['eventNos'] = array();
			$this->events['eventNames'] = array();
			$this->tracks['trackNos'] = array();
			$this->tracks['trackNames'] = array();
		}
		
		public function createConfirmationScreen() {
			echo '<div class="col-sm-12 col-md-12 col-xs-12 event">';
				echo '<h1>';
					echo $_SESSION['translations']['confirmRegistrationCongress'];
				echo '<h1>';
			echo '</div>';
			echo '<form name="formConfirm" method="POST" action="'. $_SERVER['PHP_SELF'].'" class="col-sm-12 col-md-12 col-xs-12 eventBox">';
					$this->getEventData();
					echo $_SESSION['translations']['chosenEventsText'] .'<br>';
					echo '<br>';

					echo '<ul id="eventList">';
						for($i = 0; $i < sizeof($this->events['eventNames']); $i++) {
							echo '<li>' .  $this->events['eventNames'][$i] .' in track ' . $this->tracks['trackNames'][$i] .'</li>';
						}
					echo '</ul>';
					echo '<br>';
					echo '<input type="hidden" value="'. $_SESSION['congressNo']. '" name="cancelSignUpValue">';
					echo '<button value="cancel" type="submit" name="cancelSignUp" class="btn btn-default cancelSignUp">' . $_SESSION['translations']['cancelButton'] . '</button>';
					echo '<button value="confirmation" type="submit" name="confirmSignUp" class="btn btn-default confirmSignUp">' . $_SESSION['translations']['confirmButton'] . '</button>';
			echo '</form>';
		}
		
		public function getEventData() {
			if (!empty($_SESSION['runningFormData'])) {
				$query = "SELECT E.EName, E.Type, E.Price, T.TName, E.EventNo, T.TrackNo
						  FROM EVENTINTRACK ET INNER JOIN EVENT E 
							  ON ET.EventNo = E.EventNo AND ET.CongressNo = E.CongressNo INNER JOIN Track T
							  ON T.TrackNo = ET.TrackNo AND ET.CongressNo = T.CongressNo
						  WHERE ET.CongressNo = ? AND (";
				$params = array($this->congressNo);		  
				for($i = 0; $i < sizeof($_SESSION['runningFormData']); $i++) {
					if ($i % 2 == 0) {
						$query .= '(ET.TRACKNO = ? '; ;
						array_push($params, $_SESSION['runningFormData'][$i]);
					}
					else {
						$query .= 'AND ET.EVENTNO = ?)';
						$query .= " OR ";
						array_push($params, $_SESSION['runningFormData'][$i]);
					}
				}
				$query = substr($query, 0, sizeof($query) - 5);
				$query .= ')';
				$result = $this->dataBase->sendQuery($query, $params);
				if ($result) {
					while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
						array_push($this->events['eventNames'], $row['EName']);
						array_push($this->tracks['trackNames'], $row['TName']);
						array_push($this->events['eventNos'], $row['EventNo']);
						array_push($this->tracks['trackNos'], $row['TrackNo']);
					}
				}
			}
		}
		
		public function getEventsFromRegisteredPerson() {
			$queryEvents = 		"SELECT E.EventNo, EOVOC.TrackNo, E.EName, E.Type, E.Price, T.TName, EIT.Start, EIT.[End], EIR.BName
								FROM EventOfVisitorOfCongress EOVOC 
								INNER JOIN Event E 
								ON EOVOC.EventNo = E.EventNo AND EOVOC.CongressNo = E.CongressNo INNER JOIN EventInTrack EIT 
									ON EOVOC.TrackNo = EIT.TrackNo AND EOVOC.CongressNo = EIT.CongressNo AND EOVOC.EventNo = EIT.EventNo INNER JOIN Track T
										ON EIT.TrackNo = T.TrackNo AND T.CongressNo = EIT.CongressNo INNER JOIN EventInRoom EIR
											ON EIR.CongressNo = EOVOC.CongressNo AND EIR.EventNo = EOVOC.EventNo AND EOVOC.TrackNo = EIR.TrackNo
								WHERE EOVOC.PersonNo = ? AND EOVOC.CongressNo = ?";
			$paramsEvents = array($_SESSION['userPersonNo'], $_SESSION['congressNo']);
		    $events = array();
			$result = $this->dataBase->sendQuery($queryEvents, $paramsEvents);
			if ($result) {
				while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
					array_push($events, array("EVENTNO" => $row["EventNo"], "TRACKNO" => $row["TrackNo"], "ENAME" => $row["EName"], "TYPE" => $row["Type"], "PRICE" => $row["Price"], "TRACKNAME" => $row["TName"], "START" => $row["Start"], "END" => $row["End"], "BNAME" => $row["BName"]));
				}					
			}
			return $events;
		}
		
		public function createRegisteredUserScreen() {
			$this->eventsRegisteredUser = $this->getEventsFromRegisteredPerson();
				
			echo '<h1>' . $_SESSION['translations']['chosenEventsText'] . '</h1>';
			
			echo '<br>';
			
			echo '<div class="col-sm-12 col-xs-12 col-md-12 eventBox">';
			
			foreach($this->eventsRegisteredUser as $event) {
				echo '<div class="eventInfo col-xs-12 col-sm-6 col-md-6">';
					echo '<h2>' . $event["ENAME"] . '</h2>';
					echo '<p> Type: ' . $event["TYPE"] . '</p>';
					echo '<p> Track : '. $event["TRACKNAME"] .'</p>';
					echo '<p> Gebouw : '. $event["BNAME"] .'</p>';
					echo '<p>Zalen:</p>';
					echo '<ul id="roomList">';
					$rooms = $this->getRoomsOfEvent($event["EVENTNO"], $event["TRACKNO"]);
					foreach($rooms as $room) {
						echo '<li>' . $room . '</li>'; 
					}
					echo '</ul>';
					if ($event["TYPE"] == 'Workshop') {
						echo '<p> Prijs: '. $event["PRICE"] .'</p>';
					}
					echo '<p> Starttijd : '. $event["START"]->format("Y-m-d H:i:s") .'</p>';
					echo '<p> Eindtijd : '. $event["END"]->format("Y-m-d H:i:s") .'</p>';					
				echo '</div>';
			}
			echo '<button value="cancel" type="button" name="backToHomeConfirm" class="btn btn-default backToHome" onclick=location.href="index.php?congressNo=' . $_SESSION['congressNo'] . '">' . $_SESSION['translations']['backToHome'] . '</button>';
			echo '</div>';
		}
		
		public function getRoomsOfEvent($eventNo, $trackNo) {
			$queryRoomsOfEvent = "SELECT RName 
								  FROM EventInRoom
								  WHERE EventNo = ? AND TrackNo = ? AND CongressNo = ?";
			$paramsRoomsOfEvent = array($eventNo, $trackNo, $this->congressNo);
			$result = $this->dataBase->sendQuery($queryRoomsOfEvent, $paramsRoomsOfEvent);
			$rooms = array();
			while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
				array_push($rooms, $row["RName"]);
			}
			return $rooms;
		}
	}
?>

