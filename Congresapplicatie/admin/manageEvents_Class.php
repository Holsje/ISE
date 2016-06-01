<?php
    require_once('Management.php');

    class ManageEvents extends Management{

        public function __construct(){
            parent::__construct();
        }
        
        public function getEventsByCongress(){
            $sql= ' SELECT EventNo, EName, Type, MaxVisitors
                    FROM Event
                    WHERE CongressNo = ?';
            $params = array($_SESSION['congressNo']);
            $result = $this->database->sendQuery($sql, $params);
            $returnArray = array();
            if($result){
                while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
                    array_push($returnArray,array($row['EventNo'], $row['EName'], $row['Type'], $row['MaxVisitors']));
                }
            }
            return $returnArray;
            
        }
        
        public function getSubjects() {

            $result = parent::getDatabase()->sendQuery("SELECT Subject FROM Subject",null);

            if ($result){
				$array = array();
				while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
                {
					array_push($array,array($row['Subject']));
                }
				return $array;
            }
			return false;
		}
        
        public function handleSubmitAdd(){
            $sqlEvents = '  INSERT INTO Event(CongressNo, EName,Type,MaxVisitors,Price)
                        VALUES(?,?,?,?,?)';
            $params = array($_SESSION['congressNo'],$_POST['eventName'],$_POST['eventType'],$_POST['eventPrice'],$_POST['eventMaxVis']);
            $result = $this->database->sendQuery($sql,$params);
            if($result){
                echo 'ging goed';
            }
            
            $sqlGetEventNo = '  SELECT @@IDENTITY as eventNo
                                FROM Event';
            $eventNo = null;
            $resultEventNo = $this->database->sendQuery($sql,array());
            if($resultEventNo){
                if($row = sqlsrv_fetch_array($resultEventNo,SQLSRV_FETCH_ASSOC)){
                    $eventNo = $row['eventNo'];
                }
            }
            echo 'eventNo = '. $eventNo;

            //if(isset($_POST['eventPicture'])){
            //    require_once('fileUploadHandler.php');
            //    mkdir('../Events/')
            //    handleFile()
            //}
        }

        public function createManagementScreen($columnList, $valueList) {
            //Voeg extra buttons toe in $buttonArray
            //($value, $label, $name, $classes, $startRow, $endRow, $datafile)
            $SpeakerToEvent = new Button('Spreker koppelen','','','form-control btn btn-default col-xs-3 col-md-3 col-sm-3 popupButton onSelected',false,false,'#something');
            $buttonArray=array($SpeakerToEvent);
            parent::createManagementScreen($columnList, $valueList, 'Evenementen' ,$buttonArray);
        }
        
		public function createCreateEventsScreen() {
            //($value, $label, $name, $classes, $startRow, $endRow, $required)
            //$congressName = new Span('Komt nog een session','Congress','congres','',true,true);
            $eventName = new Text('','Naam','eventName','',true,true,true);
            //($value, $label, $name, $classes, $startRow, $endRow, $list, $button, $firstRowEmpty, $id)
            $eventType = new Select('', 'Type','eventType','',true,true,array('Workshop','Lezing'),false,false,'eventType');
            $eventPrice = new Text('', 'Prijs', 'eventPrice','',true,true,true);
            $eventMaxVis = new Text('', 'Max bezoekers','eventMaxVis','',true,true,true);
            //$value, $label, $name, $classes, $startRow, $endRow, $classesInput
            $eventFileUpload = new Upload('','Afbeelding','eventPicture','form-control col-xs-12 col-sm-8 col-md-8','true',true,'','image');
            $columnList = array("Onderwerp");
            $valueList = $this->getSubjects();
            $eventSubject = new Listbox(null, null, null, "col-xs-3 col-md-3 col-sm-3", true, true, $columnList, $valueList, "EvenementenSubjectListBoxAdd");
            $eventToevoegenBtn = new Submit("Toevoegen","createCongress","Toevoegen","form-control col-md-4 pull-right btn btn-default", true, true, '#popUpAddEvenementen');
            $screenObjecten = array($eventName,$eventType,$eventPrice,$eventMaxVis,$eventFileUpload,$eventSubject,$eventToevoegenBtn);
            //Maak screen objecten aan.
			$this->createScreen->createPopup($screenObjecten,"Evenement aanmaken","AddEvenementen",'smallPop','first','','#Evenementen');

		}
		
		public function createEditEventsScreen() {
			//Zie bovenstaande functie
		}
        
         public function changeRecord($storedProcName,$params){
           //Overide indien nodig anders verwijderen.
            $result = parent::changeRecord($storedProcName,$params);
        }
		
    }
