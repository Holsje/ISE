<?php
	require_once('ScreenCreator/CreateScreen.php');
	require_once('connectDatabase.php');
	require_once('pageConfig.php');
	//@TODO: Make show empty tracks
	
	class Inschrijven {
		private $dataBase;
		public $createScreen;
		private $tracks;
		public $dates;
		private $congress;
		private $congressNo;
		public $congressName;
		public $daysOfCongress;
		public $currentDay;
		
		public function __construct($__congressNo, $__dataBase) {
			$this->dataBase = $__dataBase;
			$this->CreateScreen = new CreateScreen();
			$this->congressNo = $__congressNo;
			$this->tracks = $this->getTracks($this->congressNo);
			$this->dates =$this->getDays($this->congressNo);
			$this->congress = $this->getCongress($this->congressNo);
			$this->daysOfCongress = $this->getDaysOfCongressAsArray($this->congressNo);
			$this->congressName = $this->getCongressName();

			if (isset($_POST['nextDayButton'])) {
				if ($_SESSION['pageCount'] < sizeof($this->daysOfCongress)) {
					$this->currentDay = $this->daysOfCongress[$_POST['nextDayButton'] - ($_POST['nextDayButton'] - $_SESSION['pageCount'])];
				}
				else {
					$this->currentDay = $this->daysOfCongress[0];
					$_SESSION['pageCount'] = 0;
				}
			}
			else {
				$this->currentDay = $this->daysOfCongress[0];
			}
		}
		
		public function createSchedule() {
			if ((integer)substr($this->dates['STARTDATE'], 8) < 10) { 
				$this->createScheduleForOneDay(substr($this->dates['STARTDATE'],0, 8) . '0' . $this->currentDay);
			}
			else {
				$this->createScheduleForOneDay(substr($this->dates['STARTDATE'],0, 8) . $this->currentDay);
			}
		}
		
		public function createScheduleForOneDay($dayKey) {
			$amountOfTracks = sizeof($this->tracks);
			for($i = 1; $i <= $amountOfTracks; $i+= 3) {
				if ($i == 1) {
					$this->makeCarouselItem($this->getNextThreeTracks($i, $amountOfTracks), true, $dayKey);
				}
				else {
					$this->makeCarouselItem($this->getNextThreeTracks($i, $amountOfTracks), false, $dayKey);
				}
			}
		}
		
		private function getNextThreeTracks($currentIndex, $amountOfTracks) {
			$tracksInItem = array();
			if (($currentIndex + 2) > $amountOfTracks){
				$increment = $amountOfTracks;
			}
			else{
				$increment = $currentIndex + 2;
			}
			for($i = $currentIndex; $i <= $increment; $i++) {
				array_push($tracksInItem, $this->tracks[$i]);
			}
			return $tracksInItem;
		}
		
		public function makeCarouselItem($tracksInItem, $active, $dayKey) {
			$hourHeight = 150;
			$firstTrack = true;	
			if ($active) {
				echo '<div class="item active">';
			}
			else {
				echo '<div class="item">';
			}
			foreach($tracksInItem as $track) {
				
				$trackKey = $track['TRACKNO'];
				if($trackKey=="TIMES") {
					continue;
				}
	
				echo '<div class="event' . $dayKey . '">';
				if($firstTrack) {
					echo '<div id="timeBar' . $dayKey . '" class="timeBar col-xs-1 col-sm-1 col-md-1" style="height:' . (($this->congress['TIMES'][$dayKey]['ENDTIME'] - $this->congress['TIMES'][$dayKey]['STARTTIME'])*$hourHeight + $hourHeight) . 'px; top:100px;" class="col-sm-1 col-md-1 col-xs-1">';
					for($i = 0;$i < ($this->congress['TIMES'][$dayKey]['ENDTIME']-$this->congress['TIMES'][$dayKey]['STARTTIME'])+1;$i++) {
						echo '<div style="height:' . $hourHeight . ';">';
						if($this->congress['TIMES'][$dayKey]['STARTTIME']+$i < 10) {
							echo '0' . $this->congress['TIMES'][$dayKey]['STARTTIME']+$i;
						}
						else {
							echo $this->congress['TIMES'][$dayKey]['STARTTIME']+$i;
						}
						echo ':00</div>';
					}
					echo '</div>';
					$firstTrack = false;
				}
				
				echo '<div class="col-sm-3 col-md-3 col-xs-3 event">';
				echo '<div class="col-sm-12 col-md-12 col-xs-12 eventTitle"><h2>' . $track['TNAME'] . '</h2></div>';
				echo '<div class="col-sm-12 col-md-12 col-xs-12 eventBox" style="height:' . (($this->congress['TIMES'][$dayKey]['ENDTIME']-$this->congress['TIMES'][$dayKey]['STARTTIME'])*$hourHeight) . ';">';
				
				if(!isset($this->congress[$track['TRACKNO']]["DAYS"][$dayKey])) {
					echo '</div>';
					echo '</div>';
					echo '</div>';
					continue;
				}					
				$eventDates = $this->congress[$track['TRACKNO']]["DAYS"][$dayKey];
	
				foreach($eventDates["EVENTS"] AS $event) {
					$time = split(':',$event['START']);
					$startTimeInHours = $time[0] + $time[1]/60 + $time[2]/3600;
					$time = split(':',$event['END']);
					$endTimeInHours = $time[0] + $time[1]/60 + $time[2]/3600;
					
					$distanceFromTop = ($startTimeInHours - ($this->congress['TIMES'][$dayKey]['STARTTIME']))*$hourHeight;
					$height = ($endTimeInHours-$startTimeInHours)*$hourHeight;
					echo $this->CreateScreen->createEventInfo($event['ENAME'],$event["SUBJECTS"],$event["PRICE"],$event["TYPE"],$event["EVENTNO"], $track['TRACKNO'], "#popUpeventInfo","col-sm-12 col-md-12 col-xs-12 eventBoxSignUp","position:absolute; top:" . $distanceFromTop . "px; height:" . $height . "px; width:90%; left:5%;",$event['FILEDIRECTORY'],$event['START'] . " -  " . $event['END']);
				}
				echo '</div>';
				echo '</div>';
				echo '</div>';
			}
			$firstTrack = true;
			echo '</div>';
		}
		
		function sortFunction( $a, $b ) {
			return strtotime($a['startdate']) - strtotime($b['startdate']);
		}
	
		public function getTracks($congressNo) {
				$result  = $this->dataBase->sendQuery("SELECT TRACKNO,DESCRIPTION,TNAME " . 
														"FROM TRACK " . 
														"WHERE CongressNo = ?",array($congressNo));
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
		
		public function getDays($congressNo) {
			$dates = array();
			$result  = $this->dataBase->sendQuery("SELECT STARTDATE,ENDDATE FROM Congress WHERE CONGRESSNO = ?",array($congressNo));
			if($result) {
				while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
					$dates['STARTDATE'] = $row['STARTDATE']->format('Y-m-d');
					$dates['ENDDATE'] = $row['ENDDATE']->format('Y-m-d');
				}
			}
			else {
				return;
			}
			return $dates;
		}
		
		public function getCongress($congressNo) {
			$result  = $this->dataBase->sendQuery("SELECT T.TRACKNO,E.EVENTNO,T.CONGRESSNO,E.ENAME,E.TYPE,EIT.START,EIT.[END],E.PRICE,E.FILEDIRECTORY " .
													"FROM TRACK T " .
													"INNER JOIN EVENTINTRACK EIT " .
														"ON T.TRACKNO = EIT.TRACKNO " .
														"AND T.CongressNo = EIT.CongressNo " .
													"INNER JOIN EVENT E " .
														"ON E.EVENTNO = EIT.EVENTNO " .
														"AND E.CongressNo = T.CongressNo " .
													"WHERE T.CONGRESSNO = ? " . 
													"ORDER BY EIT.START",array($congressNo));
			$congress = array();
			if($result) {
				while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
				{
					if(!isset($congress[$row['TRACKNO']]['INFO'])) {
						$congress[$row['TRACKNO']]['INFO'] = $this->tracks[$row['TRACKNO']];
					}				
					
					if(!isset($congress[$row['TRACKNO']]['DAYS'][$row['START']->format('Y-m-d')]['EVENTS'])) {
						$congress[$row['TRACKNO']]['DAYS'][$row['START']->format('Y-m-d')]['EVENTS'] = array();
					}
					
					if(!isset($congress['TIMES'][$row['START']->format('Y-m-d')]['STARTTIME'])) {
						$congress['TIMES'][$row['START']->format('Y-m-d')]['STARTTIME'] = intval($row['START']->format('H'));
					}
					$congress['TIMES'][$row['START']->format('Y-m-d')]['ENDTIME']  = intval($row['END']->format('H')+1);
					$subjects = array();
					$subjectsResult = $this->dataBase->sendQuery("SELECT SUBJECT ".
																	"FROM SubjectOfEvent SOE ".
																	"WHERE EventNo = ? AND CongressNo = ?",array($row['EVENTNO'],$row['CONGRESSNO']));
					while ($subjectsRow = sqlsrv_fetch_array($subjectsResult,SQLSRV_FETCH_ASSOC)) {
						array_push($subjects,$subjectsRow["SUBJECT"]);
					}
					array_push($congress[$row['TRACKNO']]['DAYS'][$row['START']->format('Y-m-d')]['EVENTS'],array("ENAME"=>$row['ENAME'],"TYPE"=>$row['TYPE'],"START"=>$row['START']->format('H:i:s'),"END"=>$row['END']->format('H:i:s'),"PRICE"=>$row['PRICE'],"FILEDIRECTORY"=>$row['FILEDIRECTORY'],"SUBJECTS"=>$subjects,"EVENTNO"=>$row['EVENTNO']));
				}
			}
			else {
				return;
			}
			return $congress;
		}
		
		public function getCongressName() {
			$result = $this->dataBase->sendQuery("SELECT CName FROM Congress WHERE CongressNo = ?", array($this->congressNo));
			$congressName = '';
			if ($result) {
				while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
					$congressName = $row['CName'];
				}
			}
			return $congressName;
		}
		
		public function getDaysOfCongressAsArray($congressNo) {
			$congressStartDay = $this->getDayPartOfCongressDate($this->dates['STARTDATE']);
			$congressEndDay = $this->getDayPartOfCongressDate($this->dates['ENDDATE']);
			$congressDaysArray = array();
			for($i = $congressStartDay; $i <= $congressEndDay; $i++) {
				array_push($congressDaysArray, $i);
			}	
			return $congressDaysArray;
		}
	
		private function getDayPartOfCongressDate($congressDate) {
			$dayOfCongressDate = (integer) substr($congressDate, 8);
			return $dayOfCongressDate;
		}
		
		public function createNextDayButton() {
			if ($_SESSION['pageCount'] + 1 >= sizeof($this->daysOfCongress)) {
				if (empty($_SESSION['runningFormData'])) {
					echo '<button value="confirmation" disabled type="button" name="signUpForCongressButton" class="btn btn-default signUpForCongressButton popUpButton" data-file="#popUpLogin">Inschrijven</button>';
				} 
				else {
					echo '<button value="confirmation" type="button" name="signUpForCongressButton" class="btn btn-default signUpForCongressButton popUpButton" data-file="#popUpLogin">Inschrijven</button>';
				}
			}
			else {
				echo '<button value="'. $this->currentDay . '" type="submit" name="nextDayButton" class="btn btn-default nextDayButton">Volgende dag</button>';
			}
		}
		
		public function createPreviousDayButton() {
			if ($_SESSION['pageCount'] > 0) {
				echo '<button value="previous" type="submit" name="previousDayButton" class="btn btn-default previousDayButton">Vorige dag</button>';
			}
		}
	}
?>