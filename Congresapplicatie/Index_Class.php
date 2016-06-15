<?php
    require_once('pageConfig.php');
    require_once('database.php');
    require_once('ScreenCreator/CreateScreen.php');
    require_once('connectDatabasePublic.php');

    class Index{
        protected $createScreen;
        protected $database;
        protected $databaseTranslations;
        
        public function __construct(){
            global $server, $databaseName, $uid, $password,$databaseHeader;
            $this->createScreen = new CreateScreen();
            $this->database = new Database($server,$databaseName,$uid,$password);
            $this->databaseTranslations = new Database("groep1.ise.icaprojecten.nl", "MeertaligheidDB", "sa", "wachtwoord");
        }
        
        public function getEventInfo($eventNo,$congresNo){
            $sqlEvent = 'SELECT EName, FileDirectory, Description
                             FROM Event
                             WHERE EventNo = ? AND CongressNo = ?';
            $sqlSpeakers = 'SELECT P.PersonNo, S.PicturePath, P.FirstName, P.LastName
                                     FROM SpeakerOfEvent SE INNER JOIN Speaker S 
                                         ON SE.PersonNo = S.PersonNo INNER JOIN Person P 
                                             ON P.PersonNo = S.PersonNo
                                     WHERE SE.EventNo = ? AND SE.CongressNo = ?';
            $sqlSubjects = 'SELECT Subject 
                            FROM SubjectOfEvent
                            WHERE EventNo = ? AND CongressNo = ?';
            $params = array($eventNo,$congresNo);
            $resultSpeakers = $this->database->sendQuery($sqlSpeakers, $params);
            $arraySpeakers = array();
            if($resultSpeakers){
                while($row = sqlsrv_fetch_array($resultSpeakers,SQLSRV_FETCH_ASSOC)){
                    array_push($arraySpeakers,$row);
                }
            }
            $resultSubjects = $this->database->sendQuery($sqlSubjects,$params);
            $arraySubjects = array();
            if($resultSubjects){
                while($row = sqlsrv_fetch_array($resultSubjects,SQLSRV_FETCH_ASSOC)){
                    array_push($arraySubjects,$row);
                }
            }
            $result = $this->database->sendQuery($sqlEvent,$params);
            if($result){
                if($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
                    $row['speakers'] = $arraySpeakers;
                    $row['subjects'] = $arraySubjects;
                    return json_encode($row, JSON_FORCE_OBJECT);
                }
            }
        }
        
        public function getSpeakerInfo($personId){
            $sqlSpeaker = ' SELECT P.FirstName, P.LastName, S.PicturePath, S.Description
                            FROM Speaker S INNER JOIN Person P
                                ON S.PersonNo = P.PersonNo
                            WHERE P.PersonNo = ?';
            
            $param = array($personId);
            $result = $this->database->sendQuery($sqlSpeaker,$param);
            if($result){
                if($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
                    return json_encode($row,JSON_FORCE_OBJECT);
                }
            }
        }
        
        public function getEventsBySubject($subject){
            $paramsEvent = array($subject,$_SESSION['congressNo']);
            $sqlEvents ='SELECT E.EventNo
                     FROM Event E INNER JOIN SubjectOfEvent SOE
                        ON E.EventNo = SOE.EventNo AND E.CongressNo = SOE.CongressNo AND SOE.Subject = ?
                     WHERE E.CongressNo = ?';
            $result = $this->database->sendQuery($sqlEvents,$paramsEvent);
            $returnArray = array();
            if($result){
                while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
                    array_push($returnArray,$row['EventNo']);
                }
                return json_encode($returnArray);
            }
        }
        
        public function createCongressOverview(){
            $sqlCongress = 'SELECT LocationName, City, CName, Startdate, Enddate,Price, Description
                        FROM Congress
                        WHERE CongressNo = ? ';
            $params = array($_SESSION['congressNo']);
            $resultCongress = $this->database->sendQuery($sqlCongress,$params);

            $sqlCongressSubjects = 'SELECT Subject
                                    FROM SubjectOfCongress
                                    WHERE CongressNo = ?';
            $paramsCongressSubjects = array($_SESSION['congressNo']);
            $resultCongressSubjects = $this->database->sendQuery($sqlCongressSubjects, $paramsCongressSubjects);
            
            $paramsEvent = array($_SESSION['congressNo']);
            $sqlEvents = 'SELECT EventNo, EName, FileDirectory, Description, Price,Type
                             FROM Event
                             WHERE CongressNo = ?';
            $resultEvents = $this->database->sendQuery($sqlEvents,$paramsEvent);
            if($resultCongress){
                if($congressResults = sqlsrv_fetch_array($resultCongress, SQLSRV_FETCH_ASSOC)){
                    if($resultEvents){
                        echo '<h1 class="col-md-9 col-sm-12 col-xs-12">' . $congressResults['CName'] . '</h1>';
                        echo '<div class="col-md-9 eventContent" >';
                        while($row = sqlsrv_fetch_array($resultEvents,SQLSRV_FETCH_ASSOC)){
                            
                            $sqlSubjectEvents = 'SELECT Subject 
                                 FROM SubjectOfEvent
                                 WHERE EventNo = ? AND CongressNo = ?';
                            $paramEvents = array($row['EventNo'],$_SESSION['congressNo']);
                            $resultSubjectEvents = $this->database->sendQuery($sqlSubjectEvents,$paramEvents);
                            $subjectsEvent = array();
                            if($resultSubjectEvents){
                                while($rowSub = sqlsrv_fetch_array($resultSubjectEvents,SQLSRV_FETCH_ASSOC)){
                                    array_push($subjectsEvent,$rowSub['Subject']);
                                }
                            }
                            $this->createScreen->createEventInfo($row['EName'],$subjectsEvent,$row['Price'],$row['Type'],$row['EventNo'],'','#popUpeventInfo','col-sm-3 col-md-3 col-xs-3','margin-right:50px; margin-bottom:50px; ',$row['FileDirectory'] . 'thumbnail.png','');
                        }
                        echo '</div>';
                    }
                    //Info box
                    echo '<div class="col-md-3">';
                    echo '<h3 class="col-md-12" name="congressInfo">'.$_SESSION['translations']['congressInfo'].'</h3>';
                    echo '<div class="col-md-12 congresInfo">';
                    $objects = array();
                    array_push($objects,new Span($congressResults['CName'],$_SESSION['translations']['ConName'],'ConName','col-md-8 col-sm-8',true,true));
                    array_push($objects,new Span($congressResults['Description'],$_SESSION['translations']['ConDescription'],'ConDiscription','col-md-8',true,true));
                    $startDate = $congressResults['Startdate'];
                    $startDate = $startDate->format('Y-m-d');
                    $endDate = $congressResults['Enddate'];
                    $endDate = $endDate->format('Y-m-d');
                    array_push($objects,new Span($startDate,$_SESSION['translations']['ConStart'],'ConStart','col-md-8',true,true));
                    array_push($objects,new Span($endDate,$_SESSION['translations']['ConEnd'],'ConEnd','col-md-8',true,true));
                    array_push($objects,new Span($congressResults['LocationName'],$_SESSION['translations']['ConLocation'],'ConLocation','col-md-8',true,true));
                    array_push($objects,new Span($congressResults['City'],$_SESSION['translations']['ConCity'],'ConCity','col-md-8',true,true));
                    array_push($objects,new Span("€".number_format($congressResults['Price'],2,',','.'),$_SESSION['translations']['ConPrice'],'ConPrice','col-md-8',true,true));

                    $stringSubjects = "";
                    while ($row = sqlsrv_fetch_array($resultCongressSubjects, SQLSRV_FETCH_ASSOC)){
                        $stringSubjects .= $row['Subject'].", ";
                    }
                    $stringSubjects = substr($stringSubjects, 0, strlen($stringSubjects) - 2);
                    array_push($objects,new Span($stringSubjects,$_SESSION['translations']['ConSubjects'],'ConSubjects','col-md-8',true,true));
                    foreach($objects as $object){
                        echo '<div class="row" >';
                        echo $object->getObjectCode();
                        echo '</div>';
                    }
                    echo '</div>';
                    //Subject Box
                    echo '<h3 class="col-md-12" name="eventSubjects">'.$_SESSION['translations']['eventSubjects'].'</h3>';
                    echo '<div class="col-md-12 congresInfo subjects">';
                    $sqlSubjects = 'SELECT SOE.Subject, (COUNT(E.EventNo)*100)/(SELECT COUNT(*)
                                                                                FROM SubjectOfEvent SOE INNER JOIN Event E
                                                                                    ON SOE.CongressNo = E.CongressNo AND SOE.EventNo = E.EventNo
                                                                                WHERE SOE.CongressNo = 1)   as Amount
                                    FROM SubjectOfEvent SOE INNER JOIN Event E
                                        ON SOE.CongressNo = E.CongressNo AND SOE.EventNo = E.EventNo
                                    WHERE SOE.CongressNo = ?
                                    GROUP BY SOE.Subject';
                    $resultSubjects = $this->database->sendQuery($sqlSubjects,$params);
                    if($resultSubjects){
                        echo '<p class="subjectText">';
                        while($row = sqlsrv_fetch_array($resultSubjects,SQLSRV_FETCH_ASSOC)){
                            $size = intval(($row['Amount']) *16) /100;
            
                            echo '<a class="subjectClick"><font size='.$size.'>'.$row['Subject'].' </font></a>';
                        }
                        echo '</p>';
                    }
                    echo '</div>';
                    echo '<button type="button" name="planCongress" class="btn btn-default plan" onClick="location.href=&quot;inschrijven.php&quot;">'.$_SESSION['translations']['planCongress'].'</button>';
                    echo '</div>';
                }
            }
        }
        
        public function createEventInfoPopup(){
            $image = new Img('','','thumbnail','col-md-3 col-sm-4 col-xs-8',true,false);
            if (isset($_SESSION['translations'])){
				$spanDescription = new Span('',$_SESSION['translations']['eventDescription'],'eventDescription','col-md-8 col-sm-6 col-xs-12',false,true);
				$spanSubjects = new Span('',$_SESSION['translations']['subjects'],'subjects','col-md-12 col-sm-12',true,true);
				$spanSpeakers = new Span('',$_SESSION['translations']['speakers'],'speakers','',true,true);
			}else{
				$spanDescription = new Span('','Over evenement','eventDescription','col-md-8 col-sm-6 col-xs-12',false,true);
				$spanSubjects = new Span('','Onderwerp(en)','subjects','col-md-12 col-sm-12',true,true);
				$spanSpeakers = new Span('','Spreker(s)','speakers','',true,true);
			}
            $this->createScreen->createPopup(array($image,$spanDescription,$spanSubjects,$spanSpeakers),"","eventInfo",'bigPop','first','','');
        }
        public function createSpeakerInfoPopup(){
            $image = new Img('','','thumbnail','col-md-3',true,false);
            $spanDescription = new Span('','Over spreker','speakerDescription','col-md-8 col-sm-6 col-xs-12',false,true);
            $this->createScreen->createPopup(array($image,$spanDescription),"","speaker","smallPop",'','','');
        }

        public function congressPublic($congressNo){
            $sqlStmt = "SELECT [Public] FROM Congress WHERE CongressNo = ?";
            $params = array($congressNo);

            $result = $this->database->sendQuery($sqlStmt, $params);

            if ($result){
                if ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
                    if ($row['Public'] == 1){
                        return true;
                    }
                    else{
                        return false;
                    }
                }
            }else{
                return false;
            }
        }

        public function getTranslations(){
            if (isset($_GET['lang'])){
                if ($_GET['lang'] == 'NL' || $_GET['lang'] == 'EN' || $_GET['lang'] == 'DE'){
                    $_SESSION['lang'] = $_GET['lang'];
                }
                else{
                    die("De gekozen taal is niet beschikbaar.");
                }

            }else{
                $_SESSION['lang'] = 'NL';
            }

            $sqlStmt = "SELECT Name, Value
                        FROM ScreenObject
                        WHERE Language = ?";
            $params = array($_SESSION['lang']);
            $translationsArray = array();

            $result = $this->databaseTranslations->sendQuery($sqlStmt, $params);
            if ($result){
                while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
                    $translationsArray[$row['Name']] = $row['Value'];
                }
            }

            return $translationsArray;
        }
    }
?>
