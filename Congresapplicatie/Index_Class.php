<?php
    require_once('pageConfig.php');
    require_once('database.php');
    require_once('ScreenCreator/CreateScreen.php');
    require_once('connectDatabase.php');
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }
    
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
            $sqlStatement = 'SELECT ENAME, SUBJECT, FILEDIRECTORY, DESCRIPTION
                             FROM EVENT
                             WHERE EVENTNO = ? AND CONGRESSNO = ?';
            $sqlStatementSpeakers = 'SELECT P.PERSONNO, S.PICTUREPATH, P.FIRSTNAME, P.LASTNAME
                                     FROM SPEAKEROFEVENT SE INNER JOIN SPEAKER S 
                                         ON SE.PERSONNO = S.PERSONNO INNER JOIN PERSON P 
                                             ON P.PERSONNO = S.PERSONNO
                                     WHERE SE.EVENTNO = ? AND SE.CONGRESSNO = ?';
            $paramsSpeakers = array($eventNo,$congresNo);
            $resultSpeakers = $this->database->sendQuery($sqlStatementSpeakers, $paramsSpeakers);
            $arraySpeakers = array();
            if($resultSpeakers){
                while($row = sqlsrv_fetch_array($resultSpeakers,SQLSRV_FETCH_ASSOC)){
                    array_push($arraySpeakers,$row);
                }
            }
            $params = array($eventNo,$congresNo);
            $result = $this->database->sendQuery($sqlStatement,$params);
            if($result){
                if($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
                    $row['speakers'] = $arraySpeakers;
                    return json_encode($row, JSON_FORCE_OBJECT);
                }
            }
        }
        
        public function createCongresOverzicht(){
            $sqlStatement = 'SELECT EVENTNO, ENAME, FILEDIRECTORY, DESCRIPTION
                             FROM EVENT
                             WHERE CONGRESSNO = ?';
            $params = array($_SESSION['congresNo']);
            $result = $this->database->sendQuery($sqlStatement,$params);
            if($result){
                while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
                    $this->createScreen->createEventInfo($row['ENAME'],$row['DESCRIPTION'],$row['EVENTNO'],'#popUpeventInfo','col-sm-3 col-md-3 col-xs-3','margin-right:50px; margin-bottom:50px; ',$row['FILEDIRECTORY'] . 'thumbnail.png','');
                }
            }
            //($eventName,$description,$eventId,$dataFile,$classes,$extraStyle,$image,$timeString)
            
        }
        
        public function createEventInfoPopup(){
            $image = new Img('','','thumbnail','col-md-3 col-sm-4',true,false);
            $spanDescription = new Span('','Over evenement','eventDescription','col-md-8 col-sm-6',false,true);
            $spanSubjects = new Span('','Onderwerp(en)','subjects','col-md-12 col-sm-12',true,true);
            $spanSpeakers = new Span('','Spreker(s)','speakers','',true,true);
            $this->createScreen->createPopup(array($image,$spanDescription,$spanSubjects,$spanSpeakers),"","eventInfo",null);
        }
    }
?>
