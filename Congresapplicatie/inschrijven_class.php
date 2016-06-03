<?php
	class Inschrijven {
		public $createScreen;
		public $dates;
		public $congressName;
		public $daysOfCongress;
		public $currentDay;
		
		private $dataBase;
		private $tracks;
		private $congress;
		private $congressNo;

		public function __construct($__congressNo, $__dataBase, $__createScreen) {
			$this->dataBase = $__dataBase;
			$this->createScreen = $__createScreen;
			$this->congressNo = $__congressNo;
			$this->tracks = $this->getTracks($this->congressNo);
			$this->dates =$this->getDays($this->congressNo);
			$this->congress = $this->getCongress($this->congressNo);
			$this->daysOfCongress = $this->getDaysOfCongressAsArray($this->congressNo);
			$this->congressName = $this->getCongressName();
			if (isset($_POST['nextDayButton'])) {
				if ($_SESSION['pageCount'] < sizeof($this->daysOfCongress)) {
					$this->currentDay = $this->daysOfCongress[$_SESSION['pageCount']];
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
			$this->createScheduleForOneDay($this->writeOutCurrentDate());
		}
		
		public function createScheduleForOneDay($dayKey) {
			$amountOfTracks = sizeof($this->tracks);
			if (isset($_SESSION['tracksPerCarouselSlide'])) {
				$this->tracksPerCarouselSlide = $_SESSION['tracksPerCarouselSlide'];
			}
			else {
				$this->tracksPerCarouselSlide = 3;
			}
			if (empty($this->congress[2]["DAYS"][$dayKey]["EVENTS"])) {
				echo '<p class="noEventsText">Er zijn geen evenementen ingepland voor deze dag</p>';
				return;
			}
			
			for($i = 1; $i <= $amountOfTracks; $i+= $this->tracksPerCarouselSlide) {
				if ($i == 1) {
					$this->makeCarouselItem($this->getNextFewTracks($i, $amountOfTracks, $this->tracksPerCarouselSlide), true, $dayKey);
				}
				else {
					$this->makeCarouselItem($this->getNextFewTracks($i, $amountOfTracks, $this->tracksPerCarouselSlide), false, $dayKey);
				}
			}
		}
		
		private function getNextFewTracks($currentIndex, $amountOfTracks, $tracksPerCarouselSlide) {
			$tracksInItem = array();
			if (($currentIndex + $tracksPerCarouselSlide - 1) > $amountOfTracks){
				$increment = $amountOfTracks;
			}
			else {
				$increment = $currentIndex + $tracksPerCarouselSlide - 1;
			}
			for($i = $currentIndex; $i <= $increment; $i++) {
				array_push($tracksInItem, $this->tracks[$i]);
			}
			return $tracksInItem;
		}
		
		public function makeCarouselItem($tracksInItem, $active, $dayKey) {
			if ($this->tracksPerCarouselSlide == 3) {
				$hourHeight = 200;
			}
			else {
				$hourHeight = 250;
			}
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
				
				if($firstTrack) {
					echo '<div id="timeBar" class="timeBar col-xs-1 col-sm-1 col-md-1" style="height:' . (($this->congress['TIMES'][$dayKey]['ENDTIME'] - $this->congress['TIMES'][$dayKey]['STARTTIME'])*$hourHeight + $hourHeight) . 'px; top:100px;" class="col-sm-1 col-md-1 col-xs-1">';
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
					echo '<div class="col-xs-1 col-sm-1 col-md-1 whiteSpace">';
					echo '</div>';
					$firstTrack = false;
				}
				
				if (isset($_SESSION['tracksPerCarouselSlide'])) {
					if ($_SESSION['tracksPerCarouselSlide'] == 2) {
						echo '<div class="track col-xs-5 col-sm-5 col-md-5">';
					}
					else {
						echo '<div class="track col-xs-3 col-sm-3 col-md-3">';
					}
				}
				else {
					echo '<div class="track col-xs-3 col-sm-3 col-md-3">';
				}
				
				if (isset($_SESSION['tracksPerCarouselSlide'])) {
					if ($_SESSION['tracksPerCarouselSlide'] == 2) {
						echo '<div class="col-xs-12 col-sm-12 col-md-12 event">';
					}
					else {
						echo '<div class="col-xs-12 col-sm-12 col-md-12 event">';
					}
				}
				else {
					echo '<div class="col-xs-12 col-sm-12 col-md-12 event">';
				}
				echo '<div class="eventTitle">' . $track['TNAME'] . '</div>';
				echo '<div class="eventBox" style="height:' . (($this->congress['TIMES'][$dayKey]['ENDTIME']-$this->congress['TIMES'][$dayKey]['STARTTIME'])*$hourHeight) . ';">';
				
				if(!isset($this->congress[$track['TRACKNO']]["DAYS"][$dayKey])) {
					echo '</div>';
					echo '</div>';
					echo '</div>';
					continue;
				}					
				$eventDates = $this->congress[$track['TRACKNO']]["DAYS"][$dayKey];
				
				foreach($eventDates["EVENTS"] AS $event) {
					$time = explode(':',$event['START']);
					$startTimeInHours = $time[0] + $time[1]/60 + $time[2]/3600;
					$time = explode(':',$event['END']);
					$endTimeInHours = $time[0] + $time[1]/60 + $time[2]/3600;
					
					$distanceFromTop = ($startTimeInHours - ($this->congress['TIMES'][$dayKey]['STARTTIME']))*$hourHeight;
					$height = ($endTimeInHours-$startTimeInHours)*$hourHeight;
					$distanceFromTop += 100;
					echo $this->createScreen->createEventInfo($event['ENAME'],$event["SUBJECTS"],$event["PRICE"],$event["TYPE"],$event["EVENTNO"], $track['TRACKNO'], "#popUpeventInfo","eventBoxSignUp","top:" . $distanceFromTop ."px; height:" . $height . "px;overflow: auto;width: 87%;",$event['FILEDIRECTORY'] . 'thumbnail.png', substr($event['START'], 0, 5) . " -  " . substr($event['END'], 0, 5));
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
			if ($result) {
				while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
					$congressName = $row['CName'];
				}
			}
			return $congressName;
		}
		
		public function getCongressDescription() {
			$result = $this->dataBase->sendQuery("SELECT Description FROM Congress WHERE CongressNo = ?", array($this->congressNo));
			if ($result) {
				while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
					$congressDescription = $row['Description'];
				}
			}
			return $congressDescription;
		}
		
		public function getDaysOfCongressAsArray($congressNo) {
			$congressStartDay = $this->getDayPartOfCongressDate($this->dates['STARTDATE']);
			$congressEndDay = $this->getDayPartOfCongressDate($this->dates['ENDDATE']);
			$congressDaysArray = array();
			if ($congressStartDay >= 28 && $congressEndDay < 28) {
				$monthInDate = (integer)substr($this->dates['STARTDATE'], 5, 2);
				$yearInDate = (integer)substr($this->dates['STARTDATE'], 0, 4);
				$totalDaysInThisMonth = cal_days_in_month(CAL_GREGORIAN, $monthInDate, $yearInDate);
				for($i = $congressStartDay; $i <= $totalDaysInThisMonth; $i++) {
					array_push($congressDaysArray, $i);
				}
				for($i = 1; $i <= $congressEndDay; $i++) {
					array_push($congressDaysArray, $i);
				}
				return $congressDaysArray;
			}
			else {
				for($i = $congressStartDay; $i <= $congressEndDay; $i++) {
					array_push($congressDaysArray, $i);
				}	
			}
			return $congressDaysArray;
		}
		
		public function writeOutCurrentDate() {
			$thisMonth = (integer)substr($this->dates['STARTDATE'], 5, 2);
			$thisYear = (integer) substr($this->dates['STARTDATE'], 0, 4);
			$totalDaysInThisMonth = cal_days_in_month(CAL_GREGORIAN, $thisMonth, $thisYear);
			if ($_SESSION['yearIncrements'] != 0) {
				if ($this->daysOfCongress[$_SESSION['pageCount']] == $totalDaysInThisMonth) {
					$_SESSION['yearIncrements']--;
				}
				if ($_SESSION['monthIncrements'] == 0 && $_SESSION['yearIncrements'] != 0) {
					$thisMonth = 1;
				}
			}
			else if ($_SESSION['monthIncrements'] != 0) {
				if ($this->daysOfCongress[$_SESSION['pageCount']] == $totalDaysInThisMonth) {
					$_SESSION['monthIncrements']--;
				} 
			}
			$thisMonth = $thisMonth + $_SESSION['monthIncrements'];
			$thisYear = $thisYear + $_SESSION['yearIncrements'];
			if ($this->canIncrementYear($thisYear, $thisMonth)) {
				$_SESSION['yearIncrements']++;
				$thisYear = (integer) $thisYear + $_SESSION['yearIncrements'];
				$thisMonth = 1;
				$_SESSION['monthIncrements'] = 0;
			}
			else if ($this->canIncrementMonth($thisYear, $thisMonth)) {
				$_SESSION['monthIncrements']++;
				$thisMonth = (integer) $thisMonth + $_SESSION['monthIncrements'];
			}
			if ($thisMonth < 10) {
				$thisMonth = '0'. $thisMonth;
			}
			
			if ($this->daysOfCongress[$_SESSION['pageCount']] < 10) {
				$currentDate = $thisYear. '-'. $thisMonth. '-0'. $this->daysOfCongress[$_SESSION['pageCount']];
			}
			else {
				$currentDate = $thisYear. '-'. $thisMonth. '-'. $this->daysOfCongress[$_SESSION['pageCount']];
			}
			return $currentDate;
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
		
		private function canIncrementYear($thisYear, $thisMonth) {
			$thisYear = (integer) $thisYear + 1;
			$dateWithOneYearIncrement = new DateTime ($thisYear . '-' . '01' . '-'. '01');
			$congressEndDate = new DateTime($this->dates['ENDDATE']);
			return $dateWithOneYearIncrement < $congressEndDate  && $_SESSION['pageCount'] != 0 && $thisMonth == 12;
		}
		
		private function canIncrementMonth($thisYear, $thisMonth) {
			$totalDaysInThisMonth = cal_days_in_month(CAL_GREGORIAN, $thisMonth, $thisYear);		
			$thisMonth = (integer) $thisMonth + 1;
			if ($thisMonth > 12) {
				$thisMonth = 1;
			}
			if ($thisMonth < 10) {
				$thisMonth = '0'. $thisMonth;
			}
			$dateWithOneMonthIncrement = new DateTime( $thisYear . '-' . $thisMonth  . '-' . '01' );
			$congressEndDate = new DateTime($this->dates['ENDDATE']);
			return $dateWithOneMonthIncrement < $congressEndDate && 
				   $_SESSION['pageCount'] != 0 				     && 
				   $this->daysOfCongress[$_SESSION['pageCount'] - 1] == $totalDaysInThisMonth;
		}
			
		private function getDayPartOfCongressDate($congressDate) {
			$dayOfCongressDate = (integer) substr($congressDate, 8);
			return $dayOfCongressDate;
		}
		
		
	}
?>