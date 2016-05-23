<?php
	require_once('database.php');
	require_once('ScreenCreator/CreateScreen.php');
	require_once('connectDatabase.php');
	require_once('pageConfig.php');
	
	
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
					$tracks[$row['TRACKNO']] = array("DESCRIPTION" => $row['DESCRIPTION'], "TRACKNAME" => $row['TNAME']);
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
			$events = array();
			if($result) {
				while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
				{
					if(!isset($events[$row['TRACKNO']]['INFO'])) {
						$events[$row['TRACKNO']]['INFO'] = $tracks[$row['TRACKNO']];
					}				
					
					if(!isset($events[$row['TRACKNO']]['DAYS'][$row['START']->format('Y-m-d')]['EVENTS'])) {
						$events[$row['TRACKNO']]['DAYS'][$row['START']->format('Y-m-d')]['EVENTS'] = array();
					}
					
					if(!isset($events[$row['TRACKNO']]['DAYS'][$row['START']->format('Y-m-d')]['STARTTIME'])) {
						$events[$row['TRACKNO']]['DAYS'][$row['START']->format('Y-m-d')]['STARTTIME'] = intval($row['START']->format('H'));
					}
					$events[$row['TRACKNO']]['DAYS'][$row['START']->format('Y-m-d')]['ENDTIME']  = intval($row['END']->format('H')+1);
					$subjects = array();
					$subjectsResult = $this->dataBase->sendQuery("SELECT SUBJECT ".
																	"FROM SubjectOfEvent SOE ".
																	"WHERE EventNo = ? AND CongressNo = ?",array($row['EVENTNO'],$row['CONGRESSNO']));
					while ($subjectsRow = sqlsrv_fetch_array($subjectsResult,SQLSRV_FETCH_ASSOC)) {
						array_push($subjects,$subjectsRow["SUBJECT"]);
					}
					array_push($events[$row['TRACKNO']]['DAYS'][$row['START']->format('Y-m-d')]['EVENTS'],array("ENAME"=>$row['ENAME'],"TYPE"=>$row['TYPE'],"START"=>$row['START']->format('H:i:s'),"END"=>$row['END']->format('H:i:s'),"PRICE"=>$row['PRICE'],"FILEDIRECTORY"=>$row['FILEDIRECTORY'],"SUBJECTS"=>$subjects));
				}
			}else {
				return;
			}
			
			$hourHeight = 200;
			foreach($events as $tracks) {
					echo"<script>var json = JSON.parse('". json_encode($tracks) ."'); console.log(json);</script>";
					foreach($tracks["DAYS"] AS $eventDates) {
						echo '<div id="timeBar" style="height:' . (($eventDates['ENDTIME']-$eventDates['STARTTIME'])*$hourHeight) . 'px; top:100px;" class="col-sm-1 col-md-1 col-xs-1">';
							for($i = 0;$i<($eventDates['ENDTIME']-$eventDates['STARTTIME'])+1;$i++) {
								echo '<div style="height:' . $hourHeight . ';">';
								if($eventDates['STARTTIME']+$i < 10) {
									echo '0' . $eventDates['STARTTIME']+$i;
								}
								else {
									echo $eventDates['STARTTIME']+$i;
								}
								echo ':00</div>';
							}
						echo '</div>';
						
						echo '<div class="col-sm-4 col-md-4 col-xs-4">';
						echo '<div class="col-sm-12 col-md-12 col-xs-12" style="height:100px"><h1>' . $tracks['INFO']['TRACKNAME'] . '</h1></div>';
						echo '<div class="col-sm-12 col-md-12 col-xs-12" style="margin-left:10px; margin-top:0px; border-style:solid; border-width:1px; background-color:#F0F0F0; height:' . (($eventDates['ENDTIME']-$eventDates['STARTTIME'])*$hourHeight) . ';">';
						
						
						foreach($eventDates["EVENTS"] AS $event) {
							$time = split(':',$event['START']);
							$startTimeInHours = $time[0] + $time[1]/60 + $time[2]/3600;
							$time = split(':',$event['END']);
							$endTimeInHours = $time[0] + $time[1]/60 + $time[2]/3600;
							
							$distanceFromTop = ($startTimeInHours - ($eventDates['STARTTIME']))*$hourHeight;
							$height = ($endTimeInHours-$startTimeInHours)*$hourHeight;
							echo $this->CreateScreen->createEventInfo($event['ENAME'],$event["SUBJECTS"],$event["PRICE"],$event["TYPE"],"id","#popupNaam","col-sm-12 col-md-12 col-xs-12","position:absolute; top:" . $distanceFromTop . "px; height:" . $height . "px; width:90%; left:5%; background-color:#FFF;",$event['FILEDIRECTORY'],$event['START'] . " -  " . $event['END']);
						}
						echo '</div>';
						echo '</div>';
						return;
					}
			
			}
			
			
			//$event[0][0][0][0];
			//$event[0][0][0][1];
			
			//foreach($events = $event)//
//				$event['START'];
//			}
			
			
			//var_dump($tracks[0][0][4]);

			
		
		
			//$result = $this->dataBase->sendQuery("SELECT DESCRIPTION,TRACKNAME FROM TRACK WHERE CongressNo = ?",array(1));
			
			//$result = $this->dataBase->sendQuery("SELECT ENAME,TYPE,START,[END],Subject,Price,filedirectory,description FROM EVENT WHERE CongressNo=? AND START IS NOT NULL AND [END] IS NOT NULL ORDER BY START",array(1));
			//$eventInformation = array();
			 
//			 if ($result){
                ///while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
                //{
//					array_push($eventInformation,$row);
                //}
            //}
			
			
			//$oldDay = 0;
			//foreach($eventInformation as $event) {
				
				//strtotime($event["START"]->format("d"));
			//	var_dump($event["START"]->format("d"));
			
			//}
						//DAY 0 TRACK 0 EVENT 0 Starttime
			
			
		}
		
		
	}
	
	function sortFunction( $a, $b ) {
		return strtotime($a['startdate']) - strtotime($b['startdate']);
	}

?>