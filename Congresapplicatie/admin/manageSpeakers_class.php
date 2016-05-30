<?php
    require_once('Management.php');
    class ManageSpeakers extends Management{

        public function __construct(){
            parent::__construct();
        }

        public function createManagementScreen($columnList, $valueList) {
            $button = new Submit("Test", null, null, "form-control btn btn-default col-xs-3 col-md-3 col-sm-3", false, false, "DATAFILE");
            //$buttonArray = array($button);
            parent::createManagementScreen($columnList, $valueList, null);
        }
        
        public function changeRecord($storedProcName,$params){
            $sqlStmt = 'SELECT CongressNo, Subject, Name, Location, StartDate,EndDate
                        FROM Congress
                        WHERE CongressNo = ?';
            $result = parent::changeRecord($storedProcName,$params);
            if($result != null){  
                $selectedResult = $this->database->sendQuery($sqlStmt,array($params[0][0]));
                if($selectedResult){
                    if($row = sqlsrv_fetch_array($selectedResult,SQLSRV_FETCH_ASSOC)){
                        $row['err']= $result;
                        return json_encode($row);
                    }
                }
            }
        }
		
		public function createCreateSpeakersScreen() {
			
			$congressNameObject = new Text(null,"Naam","congressName",null, true, true, true);
			$locationObject = new Text(null,"Locatie","congressLocation",null, true, true, true);
			
			$addSubjectObject = new ListAddButton("+",null,"addSubjectButton","form-control btn btn-default popupButton", true, true, "#popUpAddSubject");
			$subjectList = $this->getSubjects();
			$subjectObject = new Select(null,"Onderwerp","congressSubject",null, true, true, $subjectList,$addSubjectObject);
			
			
			$startDateObject = new Date(null,"Startdatum","startDate","form-control col-xs-12 col-sm-8 col-md-8", true, true, true);
			$endDateObject = new Date(null,"Einddatum","endDate","form-control col-xs-12 col-sm-8 col-md-8", true, true, true);
			$submitObject = new Submit("toevoegen","createCongress","toevoegen",null, true, true);			
			$this->createScreen->createPopup(array($congressNameObject,$locationObject,$subjectObject,$startDateObject,$endDateObject,$submitObject),"Congres aanmaken","Add",null);
			
			$subjectNameObject = new Text(null,"Onderwerp","subjectName",null, true, true, false);
			$buttonAddSubjectObject = new Button("toevoegen","toevoegen","toevoegen","form-control col-md-4 pull-right btn btn-default", true, true,null);
			$this->createScreen->createPopup(array($subjectNameObject,$buttonAddSubjectObject),"Onderwerp toevoegen","AddSubject",null);
		}
		
		public function createEditCongressScreen() {
			$congressNameObject = new Text(null,"Naam","congressName",null, true, true, true);
			$locationObject = new Text(null,"Locatie","congressLocation",null, true, true, true);
			
			$addSubjectObject = new ListAddButton("+",null,"addSubjectButton","form-control btn btn-default popupButton", true, true, "#popUpAddSubjectFromEdit");
			$subjectList = $this->getSubjects();
			$subjectObject = new Select(null,"Onderwerp","congressSubject",null, true, true, $subjectList,$addSubjectObject);
			
			
			$startDateObject = new Date(null,"Startdatum","congressStartDate","form-control col-xs-12 col-sm-8 col-md-8", true, true, true);
			$endDateObject = new Date(null,"Einddatum","congressEndDate","form-control col-xs-12 col-sm-8 col-md-8", true, true, true);
            //($value, $label, $name, $classes, $startRow, $endRow, $datafile){
            $errMsg = new Span('',null,'errMsgBewerken','errorMsg',true,true,null);
			$submitObject = new Button("Bewerken","Bewerken","updateCongress","form-control col-md-4 pull-right btn btn-default",true, true, '#popUpUpdate');			
			$this->createScreen->createPopup(array($congressNameObject,$locationObject,$subjectObject,$startDateObject,$endDateObject,$errMsg,$submitObject),"Congres bewerken","Update",null);
			
			$subjectNameObject = new Text(null,"Onderwerp","subjectName",null, true, true, false);
			$buttonAddSubjectObject = new Button("Bewerken","Bewerken","Bewerken","form-control col-md-4 pull-right btn btn-default", true, true,null);
			$this->createScreen->createPopup(array($subjectNameObject,$buttonAddSubjectObject),"Onderwerp toevoegen","AddSubjectFromEdit",null);
		}
    } 
	
?>
