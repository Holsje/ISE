<?php
/**
 * Created by PhpStorm.
 * User: erike
 * Date: 26-4-2016
 * Time: 14:29
 */
    require_once('Management.php');
    class ManageCongress extends Management{

        public function __construct(){
            parent::__construct();
        }

        public function getCongresses() {

            $result = parent::getDatabase()->sendQuery("SELECT * FROM Congress", null);

            if ($result){
                $array = array();
                while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
                {

                    $array[$row['CongressNo']] = array($row['CongressNo'], $row['CName'], $row['Startdate']->format('Y-m-d'), $row['Enddate']->format('Y-m-d'), $row['Public']);
                }
                return $array;
            }
            return false;
        }

        public function createManagementScreen($columnList, $valueList) {
            $button = new Submit("Test", null, null, "form-control btn btn-default col-xs-3 col-md-3 col-sm-3", false, false, "DATAFILE");
            $buttonArray = array($button);
            parent::createManagementScreen($columnList, $valueList, $buttonArray);
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

        public function getCongressInfo($congressNo) {
            $sqlCongress = 'SELECT *
                            FROM Congress
                            WHERE CongressNo = ?';
            $sqlCongressSubjects = 'SELECT Subject
                                    FROM SubjectOfCongress
                                    WHERE CongressNo = ?';
            $params = array($congressNo);
            $resultCongressSubjects = $this->database->sendQuery($sqlCongressSubjects, $params);
            $arrayCongressSubjects = array();
            if ($resultCongressSubjects){
                while($row = sqlsrv_fetch_array($resultCongressSubjects, SQLSRV_FETCH_ASSOC)){
                    array_push($arrayCongressSubjects,$row);
                }
            }

            $resultCongress = $this->database->sendQuery($sqlCongress, $params);
            if ($resultCongress){
                if ($row = sqlsrv_fetch_array($resultCongress, SQLSRV_FETCH_ASSOC)){
                    $row['subjects'] = $arrayCongressSubjects;
                    return json_encode($row, JSON_FORCE_OBJECT);
                }
            }
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
		
		public function createCreateCongressScreen() {
			$congressNameObject = new Text(null,"Naam","congressName",null, true, true, true);
			
			$addSubjectObject = new ListAddButton("+",null,"addSubjectButton","form-control btn btn-default popupButton", true, true, "#popUpAddSubject");
			/*
            $subjectList = $this->getSubjects();
			$subjectObject = new Select(null,"Onderwerp","congressSubject",null, true, true, $subjectList,$addSubjectObject, true, "selectSubject1");
            */
            $columnList = array("Onderwerp");
            $valueList = $this->getSubjects();
            $subjectObject = new Listbox(null, null, null, "col-xs-3 col-md-3 col-sm-3", true, true, $columnList, $valueList, "subjectListBoxAdd");


            $startDateObject = new Date(null,"Startdatum","startDate","form-control col-xs-12 col-sm-8 col-md-8", true, true, true);
			$endDateObject = new Date(null,"Einddatum","endDate","form-control col-xs-12 col-sm-8 col-md-8", true, true, true);
			$submitObject = new Button("Toevoegen","createCongress","Toevoegen","form-control col-md-4 pull-right btn btn-default", true, true, '#popUpAdd');
			$this->createScreen->createPopup(array($congressNameObject,$startDateObject,$endDateObject,$subjectObject,$submitObject),"Congres aanmaken","Add", "",true);
			
			$subjectNameObject = new Text(null,"Onderwerp","subjectName",null, true, true, false);
			$buttonAddSubjectObject = new Button("toevoegen","toevoegen","toevoegen","form-control col-md-4 pull-right btn btn-default", true, true,null,"", true);
			$this->createScreen->createPopup(array($subjectNameObject,$buttonAddSubjectObject),"Onderwerp toevoegen","AddSubject",null, "", true);
		}
		
		public function createEditCongressScreen() {
			$congressNameObject = new Text(null,"Naam","congressName",null, true, true, true);
			
			$addSubjectObject = new ListAddButton("+",null,"addSubjectButton","form-control btn btn-default popupButton", true, true, "#popUpAddSubjectFromEdit");
			/*
            $subjectList = $this->getSubjects();
			$subjectObject = new Select(null,"Onderwerp","congressSubject1",null, true, true, $subjectList,$addSubjectObject, false, "select");
			*/
            $columnList = array("Onderwerp");
            $valueList = $this->getSubjects();
            $subjectObject = new Listbox(null, null, null, "col-xs-3 col-md-3 col-sm-3", true, true, $columnList, $valueList, "subjectListBoxUpdate");
			
			$startDateObject = new Date(null,"Startdatum","congressStartDate","form-control col-xs-12 col-sm-8 col-md-8", true, true, true);
			$endDateObject = new Date(null,"Einddatum","congressEndDate","form-control col-xs-12 col-sm-8 col-md-8", true, true, true);
            //($value, $label, $name, $classes, $startRow, $endRow, $datafile){
            $errMsg = new Span('',null,'errMsgBewerken','errorMsg',true,true,null);
			$submitObject = new Button("Bewerken","Bewerken","updateCongress","form-control col-md-4 pull-right btn btn-default",true, true, '#popUpUpdate');			
			$this->createScreen->createPopup(array($congressNameObject,$startDateObject,$endDateObject,$subjectObject,$errMsg,$submitObject),"Congres bewerken","Update",null, "", true);
			
			$subjectNameObject = new Text(null,"Onderwerp","subjectName",null, true, true, false);
			$buttonAddSubjectObject = new Button("Bewerken","Bewerken","Bewerken","form-control col-md-4 pull-right btn btn-default", true, true,null);
			$this->createScreen->createPopup(array($subjectNameObject,$buttonAddSubjectObject),"Onderwerp toevoegen","AddSubjectFromEdit",null, "", true);
		}

        public function addRecord($paramsCongress, $paramsSubjects)
        {
            $sqlInsertCongress = "INSERT INTO Congress (CName, Startdate, Enddate, Price, [Public], Banner) VALUES (?, ?, ?, ?, ?, ?)";
            $sqlGetCongressNo = "SELECT @@IDENTITY AS CongressNo";
            //BEGIN TRANSACTION

            if (sqlsrv_begin_transaction($this->database->getConn()) === false) {
                die(print_r(sqlsrv_errors(), true));
            }


            $congressNo = "";
            $spQueryFailed = false;
            $resultCongressSubjects = true;

            $resultCongress = $this->database->sendQuery($sqlInsertCongress, $paramsCongress);
            $resultCongressNo = $this->database->sendQuery($sqlGetCongressNo, $paramsCongress);

            if ($resultCongressNo){
                if ($row = sqlsrv_fetch_array($resultCongressNo, SQLSRV_FETCH_ASSOC)){
                    $congressNo = $row['CongressNo'];
                }
            }


            for ($i = 0; $i < sizeof($paramsSubjects); $i++){
                $params = array(
                    array($paramsSubjects[$i], SQLSRV_PARAM_IN),
                    array($congressNo, SQLSRV_PARAM_IN)
                );

               $result = parent::addRecord("spAddSubjectToCongress", $params);
                if (!$result) {
                    $resultCongressSubjects = false;
                }
            }



            var_dump($resultCongressSubjects);
            if($resultCongress && $resultCongressSubjects && $resultCongressNo) {
                sqlsrv_commit($this->database->getConn());
                return "Transaction committed.<br />";
            } else {
                sqlsrv_rollback($this->database->getConn());
                return "Transaction rolled back.<br />";
            }
        }
    }
