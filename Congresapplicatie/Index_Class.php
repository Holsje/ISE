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
            $sqlStatement = 'SELECT ENAME, SUBJECT, FILEDIRECTORY
                             FROM EVENT
                             WHERE EVENTNO = ? AND CONGRESSNO = ?';
            $params = array($eventNo,$congresNo);
            $result = $this->database->sendQuery($sqlStatement,$params);
            if($result){
                if($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
                    return json_encode($row);
                }
            }
        }
        
        public function createCongresOverzicht(){
            //($eventName,$description,$eventId,$dataFile,$classes,$extraStyle,$image,$timeString)
            $this->createScreen->createEventInfo('TestEvent1','Het is al geruime tijd een bekend gegeven dat een lezer, tijdens het bekijken van de layout van een pagina, afgeleid wordt door de tekstuele inhoud. ...','1','#popUpeventInfo','col-sm-3 col-md-3 col-xs-3','margin-right:50px; margin-bottom:50px; ','img/thumbnail.png','12:00 - 15:00');
        }
        
        public function createEventInfoPopup(){
            $image = new Img('','','thumbnail','col-md-4 col-sm-6',true,false);
            $spanDescription = new Span('','','eventDescription','col-md-8 col-sm-6',false,true);
            $spanSubjects = new Span('','Onderwerp(en)','subjects','col-md-12 col-sm-12',true,true);
            $spanSpeakers = new Span('','Spreker(s)','speakers','',true,true);
            $this->createScreen->createPopup(array($image,$spanDescription,$spanSubjects,$spanSpeakers),"","eventInfo",null);
        }
    }
?>
