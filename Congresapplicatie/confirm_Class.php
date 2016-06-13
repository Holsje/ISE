<?php
	class Confirmation {
		public $congressNo;
		public $events;
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

				//echo '</div>';
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
	}
?>

