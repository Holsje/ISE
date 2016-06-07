<?php
	require_once('ScreenCreator/CreateScreen.php');
	require_once('connectDatabase.php');
	require_once('database.php');
	class Inschrijven {
		public $createScreen;
		public $dates;
		public $congressName;
		
		public $yearsOfCongress;
		public $monthsOfCongress;
		public $daysOfCongress;
		
		private $thisYear;
		private $thisMonth;
		private $thisDay;
		
		private $dataBase;
		private $tracks;
		private $congress;
		private $congressNo;

		public function __construct($__congressNo) {
			global $server, $databaseName, $uid, $password;		
			$this->dataBase = new Database($server,$databaseName,$uid,$password);
			$this->createScreen = new CreateScreen();
			$this->congressNo = $__congressNo;
			$this->tracks = $this->getTracks();
			$this->dates =$this->getDays($this->congressNo);
			$this->calculateAllCongressDays($this->dates['STARTDATE'], $this->dates['ENDDATE']);
			$this->handleMonthAndYearCounts();
			$this->congress = $this->getCongress();
			$this->congressName = $this->getCongressName();
			if (!isset($_SESSION['pageCount']) || !isset($_SESSION['monthCount']) || !isset($_SESSION['yearCount'])) {
				$_SESSION['pageCount'] = 0;
				$_SESSION['monthCount'] = 0;
				$_SESSION['yearCount'] = 0;
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
			$hourHeight = 250;
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
	
		public function getTracks() {
				$result  = $this->dataBase->sendQuery("SELECT TRACKNO,DESCRIPTION,TNAME " . 
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
		
		public function getCongress() {
			$result  = $this->dataBase->sendQuery("SELECT T.TRACKNO,E.EVENTNO,T.CONGRESSNO,E.ENAME,E.TYPE,EIT.START,EIT.[END],E.PRICE,E.FILEDIRECTORY " .
													"FROM TRACK T " .
													"INNER JOIN EVENTINTRACK EIT " .
														"ON T.TRACKNO = EIT.TRACKNO " .
														"AND T.CongressNo = EIT.CongressNo " .
													"INNER JOIN EVENT E " .
														"ON E.EVENTNO = EIT.EVENTNO " .
														"AND E.CongressNo = T.CongressNo " .
													"WHERE T.CONGRESSNO = ? " . 
													"ORDER BY EIT.START",array($this->congressNo));
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
		
		public function calculateAllCongressDays($startDate, $endDate) {
			$congressStartDay = $this->getDayPartOfCongressDate($startDate);
			$congressEndDay = $this->getDayPartOfCongressDate($endDate);
			$congressYearsArray = array();
			$congressMonthsArray = array();
			$congressDaysArray = array();
			$monthInStartDate = (integer)substr($startDate, 5, 2);
			$yearInStartDate = (integer)substr($startDate, 0, 4);
			$monthInEndDate = (integer)substr($endDate, 5, 2);
			$yearInEndDate = (integer)substr($endDate, 0, 4);
			
			$yearIterations = $yearInEndDate - $yearInStartDate;
			$numberOfMonths = abs(($yearInEndDate - $yearInStartDate)*12 + ($monthInEndDate - $monthInStartDate));
		
			$currentMonth = $monthInStartDate;
			$currentYear = $yearInStartDate;
			
			for($i = 0; $i <= $yearIterations; $i++) {
				array_push($congressYearsArray, $currentYear + $i);
			}
			
			$monthCount = 1;
			for($j = 0; $j <= $numberOfMonths; $j++) {
				if ($currentMonth + $j > 12) {
					if ($monthCount > 12) {
						$monthCount = 1;
					}
					array_push($congressMonthsArray, $monthCount);
					$monthCount++;
				}
				else {
					array_push($congressMonthsArray, $currentMonth + $j);
				}
			}
			$yearCount = 0;
			$dayCount = 1;
			$monthCount = 0;
			$firstMonth = true;
			$lastMonth = false;

			if ($numberOfMonths == 0) {
				array_push($congressDaysArray, $congressStartDay);
			}
			//First month
			$amountOfMonths = sizeof($congressMonthsArray);
			for($j = 0; $j < $amountOfMonths - 1; $j++) {
				if ($congressMonthsArray[$j] == 1 && $j != 0) {
					$yearCount++;
				}
				$totalDaysInThisMonth = cal_days_in_month(CAL_GREGORIAN, $congressMonthsArray[$j], $congressYearsArray[$yearCount]);
				for($i = 0; $i <= $totalDaysInThisMonth; $i++) {
					if ($firstMonth) {
						if ($congressStartDay + $i > $totalDaysInThisMonth) {
							$firstMonth = false;
						}
						else {
							array_push($congressDaysArray, $congressStartDay + $i);
						}
					}
					else {
						if ($j + 1 == sizeof($congressMonthsArray) - 1) {
							$lastMonth = true;
						}
						if (!$lastMonth) {
							if ($dayCount > $totalDaysInThisMonth) {
								$dayCount = 1;
							}
							array_push($congressDaysArray, $dayCount);
							$dayCount++;
						}
						else {
							if (sizeof($congressMonthsArray) >  2) {
								if ($dayCount > $totalDaysInThisMonth) {
									$dayCount = 1;
								}
								array_push($congressDaysArray, $dayCount);
								$dayCount++;
							}
							else {
								while($dayCount <= $congressEndDay) {
									array_push($congressDaysArray, $dayCount);
									$dayCount++;
								}
							}
						}
					}
				}
			}
			if (sizeof($congressMonthsArray) >  2) {
				array_pop($congressDaysArray);
			}
			
			if ($numberOfMonths == 0) {
				array_push($congressDaysArray, $congressEndDay);
			}
			$this->yearsOfCongress = $congressYearsArray;
			$this->monthsOfCongress = $congressMonthsArray;
			$this->daysOfCongress = $congressDaysArray;
		}
		
		public function writeOutCurrentDate() {
			$thisYear = $this->yearsOfCongress[$_SESSION['yearCount']];
			$thisMonth = $this->monthsOfCongress[$_SESSION['monthCount']];
			$thisDay = $this->daysOfCongress[$_SESSION['pageCount']];
			
			$totalDaysInThisMonth = cal_days_in_month(CAL_GREGORIAN, $thisMonth, $thisYear);
			
			if ($thisMonth < 10) {
				$thisMonth = '0' . $thisMonth;
			}
			if ($thisDay < 10) {
				$thisDay = '0' . $thisDay;
			}
			return $thisYear . '-'. $thisMonth . '-'. $thisDay;
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
				echo '<button value="nextDay" type="submit" name="nextDayButton" class="btn btn-default nextDayButton">Volgende dag</button>';
			}
		}
		
		public function createPreviousDayButton() {
			if ($_SESSION['pageCount'] > 0) {
				echo '<button value="previous" type="submit" name="previousDayButton" class="btn btn-default previousDayButton">Vorige dag</button>';
			}
		}
		
		private function getDayPartOfCongressDate($congressDate) {
			$dayOfCongressDate = (integer) substr($congressDate, 8);
			return $dayOfCongressDate;
		}
		
		private function handleMonthAndYearCounts() {
			$this->thisYear = $this->yearsOfCongress[$_SESSION['yearCount']];
			$this->thisMonth = $this->monthsOfCongress[$_SESSION['monthCount']];
			$this->thisDay = $this->daysOfCongress[$_SESSION['pageCount']];	
			
			$totalDaysInThisMonth = cal_days_in_month(CAL_GREGORIAN, $this->thisMonth, $this->thisYear);
			
			if (isset($_POST['nextDayButton'])) {
				$_SESSION['pageCount']++;
				if ($this->daysOfCongress[$_SESSION['pageCount']] == 1) {
					$_SESSION['monthCount']++;
				}
				if($this->monthsOfCongress[$_SESSION['monthCount']] == 1 && $this->daysOfCongress[$_SESSION['pageCount']] == 1) {
					$_SESSION['yearCount']++;
				}
			}
			if (isset($_POST['previousDayButton'])) {
				$_SESSION['pageCount']--;
				if ($_SESSION['monthCount'] > 0) {
					$totalDaysInPrevMonth = cal_days_in_month(CAL_GREGORIAN, $this->monthsOfCongress[$_SESSION['monthCount']-1], $this->thisYear);
				}
				if (isset($totalDaysInPrevMonth)) {
					if ($this->daysOfCongress[$_SESSION['pageCount']] == $totalDaysInPrevMonth) {
						$_SESSION['monthCount']--;
					}
				}
				else {
					
				}
				if($this->monthsOfCongress[$_SESSION['monthCount']] == 12 && $this->daysOfCongress[$_SESSION['pageCount']] == $totalDaysInThisMonth) {
					$_SESSION['yearCount']--;
				}
			}
		}			
	}
?>