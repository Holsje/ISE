<?php
	require_once('database.php');
	require_once('ScreenCreator/CreateScreen.php');
	require_once('connectDatabase.php');
	require_once('pageConfig.php');
	//@TODO: Make show empty tracks
	
	class Inschrijven {
		protected $dataBase;
		public $createScreen;
		public function __construct() {
			global $server, $databaseName, $uid, $password;
			$this->dataBase = new Database($server,$databaseName,$uid,$password);
			$this->CreateScreen = new CreateScreen();
		}
		

	
		public function createSchedule() {
		
			$result  = $this->dataBase->sendQuery("SELECT TRACKNO,DESCRIPTION,TNAME " . 
													"FROM TRACK " . 
													"WHERE CongressNo = ?",array(1));
			$tracks = array();
			if($result) {
				while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
					$tracks[$row['TRACKNO']] = $row;
				}
			}else {
				return;
			}
			
			$dates;
			$result  = $this->dataBase->sendQuery("SELECT STARTDATE,ENDDATE FROM Congress WHERE CONGRESSNO = ?",array(1));
			if($result) {
				while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
					$dates['STARTDATE'] = $row['STARTDATE']->format('Y-m-d');
					$dates['ENDDATE'] = $row['ENDDATE']->format('Y-m-d');
				}
			}else {
				return;
			}
			
			
			$result  = $this->dataBase->sendQuery("SELECT T.TRACKNO,E.EVENTNO,T.CONGRESSNO,E.ENAME,E.TYPE,EIT.START,EIT.[END],E.PRICE,E.FILEDIRECTORY " .
													"FROM TRACK T " .
													"INNER JOIN EVENTINTRACK EIT " .
														"ON T.TRACKNO = EIT.TRACKNO " .
														"AND T.CongressNo = EIT.CongressNo " .
														"AND T.CongressNo = EIT.TRA_CongressNo " .
													"INNER JOIN EVENT E " .
														"ON E.EVENTNO = EIT.EVENTNO " .
														"AND E.CongressNo = T.CongressNo " .
													"WHERE T.CONGRESSNO = ? " . 
													"ORDER BY EIT.START",array(1));
			$congress = array();
			if($result) {
				while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
				{
					if(!isset($congress[$row['TRACKNO']]['INFO'])) {
						$congress[$row['TRACKNO']]['INFO'] = $tracks[$row['TRACKNO']];
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
			}else {
				return;
			}
			
			$hourHeight = 150;
			$firstTrack = true;
			
			echo"<script>var json = JSON.parse('". json_encode($congress) ."'); console.log(json);</script>";
			echo"<script>var json = JSON.parse('". json_encode($tracks) ."'); console.log(json);</script>";
			$day = 0;
			
			foreach($dates as $dayKey) {
				$day++;
				//$tracks = $congress[$track['TRACKNO']];
				echo '<div id="day' . $day . '">';
				foreach($tracks as $track) {						
						$trackKey = $track['TRACKNO'];
						if($trackKey=="TIMES") {
							continue;
						}
						
						echo '<div class="day' . $day . '">';
						if($firstTrack) {
							echo '<div id="timeBar' . $day . '" style="height:' . (($congress['TIMES'][$dayKey]['ENDTIME']-$congress['TIMES'][$dayKey]['STARTTIME'])*$hourHeight) . 'px; top:100px;" class="col-sm-1 col-md-1 col-xs-1">';
							for($i = 0;$i<($congress['TIMES'][$dayKey]['ENDTIME']-$congress['TIMES'][$dayKey]['STARTTIME'])+1;$i++) {
								echo '<div style="height:' . $hourHeight . ';">';
								if($congress['TIMES'][$dayKey]['STARTTIME']+$i < 10) {
									echo '0' . $congress['TIMES'][$dayKey]['STARTTIME']+$i;
								}
								else {
									echo $congress['TIMES'][$dayKey]['STARTTIME']+$i;
								}
								echo ':00</div>';
							}
							echo '</div>';
							$firstTrack = false;
						}
						
						echo '<div class="col-sm-3 col-md-3 col-xs-3">';
						echo '<div class="col-sm-12 col-md-12 col-xs-12" style="height:100px"><h1>' . $track['TNAME'] . '</h1></div>';
						echo '<div class="col-sm-12 col-md-12 col-xs-12" style="margin-left:10px; margin-top:0px; border-style:solid; border-width:1px; background-color:#F0F0F0; height:' . (($congress['TIMES'][$dayKey]['ENDTIME']-$congress['TIMES'][$dayKey]['STARTTIME'])*$hourHeight) . ';">';
						
						if(!isset($congress[$track['TRACKNO']]["DAYS"][$dayKey])) {
							echo '</div>';
							echo '</div>';
							echo '</div>';
							continue;
						}					
						$eventDates = $congress[$track['TRACKNO']]["DAYS"][$dayKey];
						//foreach($tracks["DAYS"] AS $dayKey => $eventDates) {
						echo"<script>var json = JSON.parse('". json_encode($eventDates) ."'); console.log(json);</script>";
						foreach($eventDates["EVENTS"] AS $event) {
							$time = split(':',$event['START']);
							$startTimeInHours = $time[0] + $time[1]/60 + $time[2]/3600;
							$time = split(':',$event['END']);
							$endTimeInHours = $time[0] + $time[1]/60 + $time[2]/3600;
							
							$distanceFromTop = ($startTimeInHours - ($congress['TIMES'][$dayKey]['STARTTIME']))*$hourHeight;
							$height = ($endTimeInHours-$startTimeInHours)*$hourHeight;
							echo $this->CreateScreen->createEventInfo($event['ENAME'],$event["SUBJECTS"],$event["PRICE"],$event["TYPE"],$event["EVENTNO"],"#popUpeventInfo","col-sm-12 col-md-12 col-xs-12","position:absolute; top:" . $distanceFromTop . "px; height:" . $height . "px; width:90%; left:5%; background-color:#FFF;",$event['FILEDIRECTORY'],$event['START'] . " -  " . $event['END']);
						}
						echo '</div>';
						echo '</div>';
						echo '</div>';
						
					}
				$firstTrack = true;
				echo '</div>';
			}
			
		}
		
		
	}
	
	function sortFunction( $a, $b ) {
		return strtotime($a['startdate']) - strtotime($b['startdate']);
	}

?>