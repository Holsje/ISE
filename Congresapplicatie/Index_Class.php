<?php
    require_once('pageConfig.php');
    require_once('database.php');
    require_once('ScreenCreator/CreateScreen.php');
    require_once('connectDatabase.php');

    class Index{
        protected $createScreen;
        protected $database;
        
        public function __construct(){
            global $server, $databaseName, $uid, $password,$databaseHeader;
            $this->createScreen = new CreateScreen();
            $this->database = $databaseHeader;
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
                    echo '<h3 class="col-md-12">Congres Informatie</h3>';
                    echo '<div class="col-md-12 congresInfo">';
                    $objects = array();
                    array_push($objects,new Span($congressResults['CName'],'Naam','ConName','col-md-8',true,true));
                    array_push($objects,new Span($congressResults['Description'],'Omschrijving','ConDiscription','col-md-8',true,true));
                    $startDate = $congressResults['Startdate'];
                    $startDate = $startDate->format('Y-m-d');
                    $endDate = $congressResults['Enddate'];
                    $endDate = $endDate->format('Y-m-d');
                    array_push($objects,new Span($startDate,'Begin datum','ConStart','col-md-8',true,true));
                    array_push($objects,new Span($endDate,'Eind datum','ConEnd','col-md-8',true,true));
                    array_push($objects,new Span($congressResults['LocationName'],'Locatie','ConStart','col-md-8',true,true));
                    array_push($objects,new Span($congressResults['City'],'Plaats','ConStart','col-md-8',true,true));
                    array_push($objects,new Span(number_format($congressResults['Price'],2,',','.'),'Prijs','ConPrice','col-md-8',true,true));
                    foreach($objects as $object){
                        echo '<div class="row" >';
                        echo $object->getObjectCode();
                        echo '</div>';
                    }
                    echo '</div>';
                    //Subject Box
                    echo '<h3 class="col-md-12">Onderwerpen</h3>';
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
                    echo '<button type="button" class="btn btn-default plan" onClick="location.href=&quot;inschrijven.php&quot;">Plan je Congres</button>';
                    echo '</div>';
                }
            }
        }
        
        public function createEventInfoPopup(){
            $image = new Img('','','thumbnail','col-md-3 col-sm-4 col-xs-8',true,false);
            $spanDescription = new Span('','Over evenement','eventDescription','col-md-8 col-sm-6 col-xs-12',false,true);
            $spanSubjects = new Span('','Onderwerp(en)','subjects','col-md-12 col-sm-12',true,true);
            $spanSpeakers = new Span('','Spreker(s)','speakers','',true,true);
            $this->createScreen->createPopup(array($image,$spanDescription,$spanSubjects,$spanSpeakers),"","eventInfo",'bigPop','first','');
        }
        public function createSpeakerInfoPopup(){
            $image = new Img('','','thumbnail','col-md-3',true,false);
            $spanDescription = new Span('','Over spreker','speakerDescription','col-md-8 col-sm-6 col-xs-12',false,true);
            $this->createScreen->createPopup(array($image,$spanDescription),"","speaker","smallPop",'','');
        }
    }
?>
