<?php
    require_once('Management.php');

    class ManageEvents extends Management{

        public function __construct(){
            parent::__construct();
        }
        
        public function getEventsByCongress(){
            $sql= ' SELECT EventNo, EName, Type, Price, MaxVisitors, Description
                    FROM Event
                    WHERE CongressNo = ?';
            $params = array($_SESSION['congressNo']);
            $result = $this->database->sendQuery($sql, $params);
            $returnArray = array();
            if($result){
                while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
                    array_push($returnArray,array($row['EventNo'], $row['EName'], $row['Type'], number_format($row['Price'],2,',','.'), $row['MaxVisitors'], $row['Description']));
                }
            }
            return $returnArray;   
        }
        
        public function getSpeakersOfEvent($congressNo, $eventNo){
            $sqlEvent = "SELECT P.PersonNo,P.FirstName, P.LastName, P.MailAddress 
				         FROM SpeakerOfEvent SOE INNER JOIN Person P 
                            ON P.PersonNo = SOE.PersonNo 
				         WHERE SOE.CongressNo = ? AND SOE.EventNo = ?";
            $paramsEvent =  array($congressNo,$eventNo);
            $result = $this->database->sendQuery($sqlEvent,$paramsEvent);
            if ($result){
				$array = array();
				while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
				{
					array_push($array,array($row['PersonNo'],$row['FirstName'],$row['LastName'],$row['MailAddress']));
				}
				return json_encode( $array);
			}
			return false;	
        }
        
        public function getSpeakersOfCongress($congressNo) {
			 $result = parent::getDatabase()->sendQuery("SELECT P.personNo,P.FirstName, P.LastName, P.MailAddress ".
														"FROM SpeakerOfCongress SOC " .
														"INNER JOIN Person P ON P.PersonNo = SOC.PersonNo " .
														"WHERE SOC.CongressNo = ?",array($congressNo));
														
			 if ($result){
				$array = array();
				while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
				{
					array_push($array,array($row['personNo'],$row['FirstName'],$row['LastName'],$row['MailAddress']));
				}
				return $array;
			}
			return false;		
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
        
        public function handleSpeakerEdit($congressNo, $eventNo){
            $sqlInsertEvent = 'INSERT INTO SpeakerOfEvent(PersonNo,CongressNo,EventNo) VALUES';
            $params = array();
            foreach($_POST['addingSpeakers'] as $value){
                $sqlInsertEvent .= 'VALUES(?,?,?)';
                array_push($congressNo, $eventNo,$value);
            }
            dhsajdhasjkdhajkhdajkshdajkhdsajkhdsjkhakdj;
        }
        
        public function handleSubmitAdd(){
            $sqlGetEventNo = '  SELECT MAX(eventNo) as eventNo
                                FROM Event
                                WHERE congressNo = ?';
            $eventNo = null;
            $params = array($_SESSION['congressNo']);
            $resultEventNo = $this->database->sendQuery($sqlGetEventNo,$params);
            if($resultEventNo){
                if($row = sqlsrv_fetch_array($resultEventNo,SQLSRV_FETCH_ASSOC)){
                    $eventNo = $row['eventNo'];
                }
            }
            $eventNo++;
            if(!file_exists('../Events/Event'.$eventNo)){
                mkdir('../Events/Event'.$eventNo);
            }
            
            if(isset($_FILES['eventPicture'])){
                if($_FILES['eventPicture']['error'] == UPLOAD_ERR_OK){
                    require_once('fileUploadHandler.php');
                    if(handleFile('Events/Event'.$eventNo . '/','eventPicture','thumbnail') == false){
                        echo 'upload ging fout';
                        return;
                    }
                }
            }
            $eventPrice = $_POST['eventPrice'];
            if($_POST['eventType'] == 'Lezing'){
                $eventPrice = null;
            }
            $sqlEvents = '  INSERT INTO Event(CongressNo,EventNo, EName,Type,MaxVisitors,Price,Description,FileDirectory)
                        VALUES(?,?,?,?,?,?,?,?)';
            $paramsEvent = array($_SESSION['congressNo'],$eventNo,$_POST['eventName'],$_POST['eventType'],$_POST['eventMaxVis'],$eventPrice, $_POST['eventDescription'],'Events/Event' . $eventNo . '/');
            $result = $this->database->sendQuery($sqlEvents,$paramsEvent);
            if($result !== true){
                if( ($errors = sqlsrv_errors() ) != null) {
                    foreach( $errors as $error ) {
                        echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
                        echo "code: ".$error[ 'code']."<br />";
                        echo "message: ".$error[ 'message']."<br />";
                     }
                }   
            }
            if(isset($_POST['subjects'])){
                foreach($_POST['subjects'] as $value){
                    parent::changeRecord("addSubjectToEvent", array(array($value, SQLSRV_PARAM_IN), array($_SESSION['congressNo'], SQLSRV_PARAM_IN),array($eventNo,SQLSRV_PARAM_IN)));
                }
            }
            
        }
        
        public function handleSubmitEdit($eventNo){
            if(!file_exists('../Events/Event'.$eventNo)){
                mkdir('../Events/Event'.$eventNo);
            }
            
            if(isset($_FILES['editEventPicture'])){
                if($_FILES['editEventPicture']['error'] == UPLOAD_ERR_OK){
                    require_once('fileUploadHandler.php');
                    if(handleFile('Events/Event'.$eventNo . '/','editEventPicture','thumbnail') == false){
                        echo 'upload ging fout';
                        return;
                    }
                }
            }
            $sqlUpdate = 'UPDATE Event
                          SET EName = ? , Type = ?, MaxVisitors = ?, Price = ?, FileDirectory = ?, Description = ?
                          WHERE EventNo = ? AND CongressNo = ?';
            $eventPrice = $_POST['eventPrice'];
            if($_POST['eventType'] == 'Lezing'){
                $eventPrice = null;
            }
            $paramsUpdate = array($_POST['eventName'], $_POST['eventType'], $_POST['eventMaxVis'], $eventPrice, 'Events/Event' . $eventNo.'/', $_POST['eventDescription'], $eventNo, $_SESSION['congressNo']);
            $result = $this->database->sendQuery($sqlUpdate,$paramsUpdate);
            $sqlSubjectsEvent ='SELECT Subject
                                FROM SubjectOfEvent
                                WHERE EventNo = ? AND CongressNo = ?'; 
            $paramsSubject = array($eventNo, $_SESSION['congressNo']);
            $resultSubjectEvents = $this->database->sendQuery($sqlSubjectsEvent, $paramsSubject);
            $subjectArray = array();
            if($resultSubjectEvents){
                while($row = sqlsrv_fetch_array($resultSubjectEvents,SQLSRV_FETCH_ASSOC)){
                    array_push($subjectArray,$row['Subject']);
                }
            }
            $arrayDeletes = array();
            $arrayInsert = array();
            if(sizeof($subjectArray) == 0){
                $arrayInsert = $_POST['subjects'];
            }
            for($i = 0; $i<sizeof($subjectArray);$i++){
                $continueDel = true;
                for($j = 0; $j <sizeof($_POST['subjects']); $j++){
                    $continueIn = true;
                    if($continueDel){
                        if($subjectArray[$i] == $_POST['subjects'][$j]){
                            $continueDel = false;
                        }else{
                            for($k = 0; $k <sizeof($subjectArray); $k++){
                                if($continueIn){
                                    if($subjectArray[$k] == $_POST['subjects'][$j]){
                                        $continueIn = false;
                                    }
                                }
                            }
                            if($continueIn){
                                array_push($arrayInsert,$_POST['subjects'][$j]);
                            }
                        }
                    }
                }
                if($continueDel){
                    array_push($arrayDeletes,$subjectArray[$i]);
                }
            }
            $arrayDeletes = array_unique($arrayDeletes);
            $arrayInsert = array_unique($arrayInsert);
            
            $sqlDeleteSubjects = '  DELETE FROM SubjectOfEvent
                                    WHERE ';
            $deleteParams = array();
            foreach($arrayDeletes as $value){
                $sqlDeleteSubjects .= 'Subject = ?';
                $sqlDeleteSubjects .= ' OR ';
                array_push($deleteParams,$value);
            }
            $sqlDeleteSubjects = substr($sqlDeleteSubjects,0,-4);
            
            $insertParams = array();
            foreach($arrayInsert as $value){
                parent::changeRecord("addSubjectToEvent", array(array($value, SQLSRV_PARAM_IN), array($_SESSION['congressNo'], SQLSRV_PARAM_IN),array($eventNo,SQLSRV_PARAM_IN)));
            }
            
            $result = $this->database->sendQuery($sqlDeleteSubjects,$deleteParams);
            
        }
        
        public function getSelectedEventInfo($eventNo){
            $sqlEvent = 'SELECT EName, Type, MaxVisitors, Price, FileDirectory, Description
                        FROM Event
                        WHERE EventNo = ? AND CongressNo = ? ';
            $params = array($eventNo, $_SESSION['congressNo']);
            $resultEvent = $this->database->sendQuery($sqlEvent, $params);
            $resultArray = array();
            if($resultEvent){
                while($row = sqlsrv_fetch_array($resultEvent,SQLSRV_FETCH_ASSOC)){
                    array_push($resultArray,$row['EName'], $row['Type'], $row['MaxVisitors'], $row['Price'], $row['FileDirectory'],$row['Description']);
                }
            }
            $sqlSubjectsEvent ='SELECT Subject
                                FROM SubjectOfEvent
                                WHERE EventNo = ? AND CongressNo = ?';
            $subjectEvents = array();
            $paramsSubjects = array($eventNo,$_SESSION['congressNo']);
            $resultSubjects = $this->database->sendQuery($sqlSubjectsEvent,$paramsSubjects);
            if($resultSubjects){
                while($row = sqlsrv_fetch_array($resultSubjects,SQLSRV_FETCH_ASSOC)){
                    array_push($subjectEvents,$row['Subject']);
                }
            }
            $resultArray['Subjects'] = $subjectEvents;
            return json_encode($resultArray);
            
        }

        public function createManagementScreen($columnList, $valueList) {
            //Voeg extra buttons toe in $buttonArray
            //($value, $label, $name, $classes, $startRow, $endRow, $datafile)
            $SpeakerToEvent = new Button('Spreker koppelen','','speakerToEvent','form-control btn btn-default col-xs-3 col-md-3 col-sm-3 popupButton onSelected',false,false,'#popUpSpeakerToEvent');
            $buttonArray=array($SpeakerToEvent);
            parent::createManagementScreen($columnList, $valueList, 'Evenementen' ,$buttonArray);
        }
        
		public function createCreateEventsScreen() {
            //($value, $label, $name, $classes, $startRow, $endRow, $required)
            //$congressName = new Span('Komt nog een session','Congress','congres','',true,true);
            $eventName = new Text('','Naam','eventName','',true,true,true);
            //($value, $label, $name, $classes, $startRow, $endRow, $list, $button, $firstRowEmpty, $id)
            $eventType = new Select('', 'Type','eventType','',true,true,array('Workshop','Lezing'),false,false,'eventType');
            $eventPrice = new Text('', 'Prijs', 'eventPrice','form-control col-xs-10 col-sm-8 col-md-8 eventPrice',true,true,false);
            $eventMaxVis = new Text('', 'Max bezoekers','eventMaxVis','',true,true,false);
            $eventDescription = new TextArea('', 'Omschrijving','eventDescription','form-control col-xs-12 col-sm-8 col-md-8 description',true,true,false);
            $eventFileUpload = new Upload('','Afbeelding','eventPicture','form-control col-xs-12 col-sm-8 col-md-8','true',true,'','image');
            $columnList = array("Onderwerp");
            $valueList = $this->getSubjects();
            $eventSubject = new Listbox(null, null, null, "col-xs-3 col-md-3 col-sm-3 subjectListBox", true, true, $columnList, $valueList, "EvenementenSubjectListBoxAdd");
            $addSubjectObject = new Button("+",null,"addSubjectEventButton","form-control btn btn-default popupButton subjectAdd", true, true, "#popUpEvenementenSubjectListBoxAdd");
            $eventToevoegenBtn = new Submit("Toevoegen","createEvent","ToevoegenEvent","form-control col-md-4 pull-right btn btn-default", true, true, '#popUpAddEvenementen');
            $screenObjecten = array($eventName,$eventDescription,$eventType,$eventPrice,$eventMaxVis,$eventFileUpload,$eventSubject,$addSubjectObject,$eventToevoegenBtn);
            //Maak screen objecten aan.
			$this->createScreen->createPopup($screenObjecten,"Evenement aanmaken","AddEvenementen",'smallPop','first','','#Evenementen');
		}
        
        public function createAddSubjectScreen(){
            $buttonAddSubjectObject = new Button("Toevoegen","","ToevoegenSubject","form-control col-md-4 pull-right btn btn-default", true, true,'#EvenementenSubjectListBoxAdd');
            $subjectNameObject = new Text(null,"Onderwerp","subjectName",null, true, true, false);
            $this->createScreen->createPopup(array($subjectNameObject,$buttonAddSubjectObject),"Onderwerp toevoegen","EvenementenSubjectListBoxAdd",null, true, true,"");
        }
        
        public function createEditEventsScreen() {
            $eventName = new Text('','Naam','eventName','',true,true,true);
            $eventType = new Select('', 'Type','eventType','',true,true,array('Workshop','Lezing'),false,false,'eventType');
            $eventPrice = new Text('', 'Prijs', 'eventPrice','form-control col-xs-10 col-sm-8 col-md-8 eventPrice',true,true,false);
            $eventMaxVis = new Text('', 'Max bezoekers','eventMaxVis','',true,true,false);
            $eventDescription = new TextArea('', 'Omschrijving','eventDescription','form-control col-xs-12 col-sm-8 col-md-8 description',true,true,false);
            $eventFileUpload = new Upload('','Afbeelding','editEventPicture','form-control col-xs-12 col-sm-8 col-md-8','true',true,'','image');
            $columnList = array("Onderwerp");
            $valueList = $this->getSubjects();
            $eventSubject = new Listbox(null, null, null, "col-xs-3 col-md-3 col-sm-3 subjectListBox", true, true, $columnList, $valueList, "EvenementenSubjectListBoxEdit");
            $eventToevoegenBtn = new Submit("Aanpassen","editEvent","AanpassenEvent","form-control col-md-4 pull-right btn btn-default", true, true, '#popUpAddEvenementen');
            $addSubjectObject = new Button("+",null,"addSubjectEventButton","form-control btn btn-default popupButton subjectAdd", true, true, "#popUpEvenementenSubjectListBoxAdd");
            $screenObjecten = array($eventName,$eventDescription,$eventType,$eventPrice,$eventMaxVis,$eventFileUpload,$eventSubject,$addSubjectObject,$eventToevoegenBtn);
            //Maak screen objecten aan.
			$this->createScreen->createPopup($screenObjecten,"Evenement aanpassen","UpdateEvenementen",'smallPop','first','','#Evenementen');

		}
        
         public function changeRecord($storedProcName,$params){
           //Overide indien nodig anders verwijderen.
            $result = parent::changeRecord($storedProcName,$params);
        }
        
        public function createAddSpeakerToEvent(){
            $columnList = array("PersonNo","Voornaam","Achternaam","Email");
			$valueListLeft = array();
			$valueListRight = $this->getSpeakersOfCongress($_SESSION['congressNo']);
			
			$tableLeft = new Listbox(null, null, null, "col-xs-3 col-md-3 col-sm-3 listBoxDataSwap", true, false, $columnList, $valueListLeft, "listBoxSpeakerEventLeft");
			$tableRight = new Listbox(null, null, null, "col-xs-3 col-md-3 col-sm-3 listBoxDataSwap", false, true, $columnList, $valueListRight, "listBoxSpeakerEventRight");
			$buttonAddSpeaker = new Button("Toevoegen", null, "buttonAddSpeakerOfCongress", "form-control btn btn-default col-xs-3 col-md-3 col-sm-3 popupButton", false, false, "#popUpAddSpeaker");
			$buttonEditSpeakerOfCongress = new Button("Aanpassen", null, "buttonEditSpeakerOfCongress", "form-control btn btn-default col-xs-3 col-md-3 col-sm-3 popupButton onSelected", false, false, "#popUpUpdateSpeakerOfCongress");
			
			$buttonEditSpeaker = new Button("Aanpassen", null, "buttonEditSpeaker", "form-control btn btn-default col-xs-3 col-md-3 col-sm-3 popupButton onSelected", false, false, "#popUpUpdateSpeaker");
			$buttonRemoveSpeaker = new Button("Verwijderen", null, "buttonDeleteSpeaker", "form-control btn btn-default col-xs-3 col-md-3 col-sm-3 onSelected", false, false, "#popUpDeleteSpeaker");
			$dataSwapList = $this->createScreen->createDataSwapList($tableLeft,"listBoxSpeakerEventLeft","Sprekers Evenement",$tableRight,"listBoxSpeakerEventRight","Sprekers",false,false,array($buttonAddSpeaker,$buttonEditSpeakerOfCongress),array($buttonRemoveSpeaker,$buttonEditSpeaker),"sprekerEvent");
            $this->createScreen->createPopupByHtml($dataSwapList,'Sprekers Koppelen','SpeakerToEvent','bigPop','first','');
        }
		
    }
