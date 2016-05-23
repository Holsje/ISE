<?php
    require_once('pageConfig.php');
    require_once('database.php');
    require_once('ScreenCreator/CreateScreen.php');
    require_once('connectDatabase.php');
    //if(session_status() === PHP_SESSION_NONE){
    //    session_start();
    //}
    
    if(isset($_GET['congresNo'])){
        $_SESSION['congresNo'] = $_GET['congresNo'];
    }

    class Index{
        protected $createScreen;
        protected $database;
        
        public function __construct(){
            global $server, $databaseName, $uid, $password;
            $this->createScreen = new CreateScreen();
            $this->database = new Database($server, $databaseName, $uid, $password);
        }
        
        public function getEventInfo($eventNo,$congresNo){
            $sqlStatement = 'SELECT ENAME, FILEDIRECTORY, DESCRIPTION
                             FROM EVENT
                             WHERE EVENTNO = ? AND CONGRESSNO = ?';
            $sqlStatementSpeakers = 'SELECT P.PERSONNO, S.PICTUREPATH, P.FIRSTNAME, P.LASTNAME
                                     FROM SPEAKEROFEVENT SE INNER JOIN SPEAKER S 
                                         ON SE.PERSONNO = S.PERSONNO INNER JOIN PERSON P 
                                             ON P.PERSONNO = S.PERSONNO
                                     WHERE SE.EVENTNO = ? AND SE.CONGRESSNO = ?';
            $sqlSubjects = 'SELECT Subject 
                            FROM SubjectOfEvent
                            WHERE EventNO = ? AND CongressNo = ?';
            $params = array($eventNo,$congresNo);
            $resultSpeakers = $this->database->sendQuery($sqlStatementSpeakers, $params);
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
            $result = $this->database->sendQuery($sqlStatement,$params);
            if($result){
                if($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
                    $row['speakers'] = $arraySpeakers;
                    $row['subjects'] = $arraySubjects;
                    return json_encode($row, JSON_FORCE_OBJECT);
                }
            }
        }
        
        public function createCongresOverzicht(){
            $sqlStmt = 'SELECT LocationName, City, CName, Startdate, Enddate,Price, Description
                        FROM Congress
                        WHERE CongressNo = ? ';
            $params = array($_SESSION['congresNo']);
            $resultCong = $this->database->sendQuery($sqlStmt,$params);
            
            $sqlStatement = 'SELECT EVENTNO, ENAME, FILEDIRECTORY, DESCRIPTION
                             FROM EVENT
                             WHERE CONGRESSNO = ?';
            $result = $this->database->sendQuery($sqlStatement,$params);
            if($resultCong){
                if($congressResults = sqlsrv_fetch_array($resultCong, SQLSRV_FETCH_ASSOC)){
                    if($result){
                        echo '<div class="col-md-9" >';
                        while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
                            $this->createScreen->createEventInfo($row['ENAME'],$row['DESCRIPTION'],$row['EVENTNO'],'#popUpeventInfo','col-sm-2 col-md-3 col-xs-2','margin-right:50px; margin-bottom:50px; ',$row['FILEDIRECTORY'] . 'thumbnail.png','');
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
                    $subjectSql = ' SELECT Subject 
                                    FROM SubjectOfCongress
                                    WHERE CongressNo = ?';
                    $resultSubject = $this->database->sendQuery($subjectSql,$params);
                    if($resultSubject){
                        while($row = sqlsrv_fetch_array($resultSubject,SQLSRV_FETCH_ASSOC)){
                            $subject = new Span($row['Subject'].' ','','',' ',false,false);
                            echo $subject->getObjectCode();
                        }
                    }
                    echo '</div>';
                    echo '<button type="button" class="btn btn-default plan" onClick="location.href=&quot;inschrijven.php&quot;">Plan je Congres</button>';
                    echo '</div>';
                }
            }
        }
        
        public function createEventInfoPopup(){
            $image = new Img('','','thumbnail','col-md-3 col-sm-4',true,false);
            $spanDescription = new Span('','Over evenement','eventDescription','col-md-8 col-sm-6',false,true);
            $spanSubjects = new Span('','Onderwerp(en)','subjects','col-md-12 col-sm-12',true,true);
            $spanSpeakers = new Span('','Spreker(s)','speakers','',true,true);
            $this->createScreen->createPopup(array($image,$spanDescription,$spanSubjects,$spanSpeakers),"","eventInfo",'bigPop');
        }
    }
?>
