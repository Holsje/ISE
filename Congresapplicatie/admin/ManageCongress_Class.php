<?php
/**
 * Created by PhpStorm.
 * User: erike
 * Date: 26-4-2016
 * Time: 14:29
 */
    require_once('Management.php');
    require_once('Login_Class.php');
    $login = new Login();
    require_once('fileUploadHandler.php');
    class ManageCongress extends Management{

        public function __construct(){
            parent::__construct();
        }

        public function getCongresses() {
        global $login;
            if ($_SESSION['liberties'] != 'Algemene beheerder') {
                $sqlGetCongresses = "SELECT * FROM Congress WHERE";

                $adminCongresses = $login->getAdminCongresses($_SESSION['user']);

                if ($adminCongresses != null) {

                    for ($i = 0; $i < sizeof($adminCongresses); $i++) {
                        $sqlGetCongresses .= " CongressNo = ? OR";
                    }

                    $sqlGetCongresses = substr($sqlGetCongresses, 0, sizeof($sqlGetCongresses) - 3);

                    $result = parent::getDatabase()->sendQuery($sqlGetCongresses, $adminCongresses);
                }
                else{
                    $result = false;
                }
            }
            else{
                $sqlGetCongresses = "SELECT * FROM Congress";
                $result = parent::getDatabase()->sendQuery($sqlGetCongresses, null);
            }


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
            $congressPlanningButton = new Button("Inplannen congres", null, "congressPlanningButton", 'form-control btn btn-default col-xs-3 col-md-3 col-sm-3 onSelected', false, false, null);
			parent::createManagementScreen($columnList, $valueList, "", array($congressPlanningButton));
			
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

		
		public function createCreateCongressScreen() {
            $errMsg = new Span(null, null, 'errMsgInsertCongress', 'errorMsg', true, true, null);
			$congressNameObject = new Text(null,"Naam","congressName",null, true, true, true);

            $addSubjectObject = new Button("+",null,"addSubjectButton","form-control btn btn-default popupButton subjectAdd", true, true, "#popUpSubjectListBoxAdd");
            $columnList = array("Onderwerp");
            $valueList = $this->getSubjects();
            $subjectObject = new Listbox(null, null, null, "col-xs-3 col-md-3 col-sm-3 subjectListBox", true, true, $columnList, $valueList, "subjectListBoxAdd");


            $startDateObject = new Date(null,"Startdatum","congressStartDate","form-control col-xs-12 col-sm-8 col-md-8", true, true, true);
			$endDateObject = new Date(null,"Einddatum","congressEndDate","form-control col-xs-12 col-sm-8 col-md-8", true, true, true);
            $priceObject = new Text(null,"Prijs","congressPrice","form-control col-xs-12 col-sm-8 col-md-8",true,true,false);


			$submitObject = new Button("Toevoegen","createCongress","Toevoegen","form-control col-md-4 pull-right btn btn-default", true, true, '#popUpAdd');
			$this->createScreen->createPopup(array($errMsg, $congressNameObject,$startDateObject,$endDateObject,$priceObject,$subjectObject,$addSubjectObject,$submitObject),"Congres aanmaken","Add", "",true, false,"");
			

		}

		public function createEditCongressScreen() {
            $errMsg = new Span(null, null, 'errMsgUpdateCongress', 'errorMsg', true, true, null);
			$congressNameObject = new Text(null,"Naam","congressName",null, true, true, true);

			$addSubjectObject = new Button("+",null,"addSubjectButton","form-control btn btn-default popupButton subjectAdd", true, true, "#popUpSubjectListBoxAdd");

            $columnList = array("Onderwerp");
            $valueList = $this->getSubjects();
            $subjectObject = new Listbox(null, null, null, "col-xs-3 col-md-3 col-sm-3 subjectListBox", true, true, $columnList, $valueList, "subjectListBoxUpdate");

			$startDateObject = new Date(null,"Startdatum","congressStartDate","form-control col-xs-12 col-sm-8 col-md-8", true, true, true);
			$endDateObject = new Date(null,"Einddatum","congressEndDate","form-control col-xs-12 col-sm-8 col-md-8", true, true, true);
            $priceObject = new Text(null,"Prijs","congressPrice","form-control col-xs-12 col-sm-8 col-md-8",true,true,false);
            $publicObject = new Text(null,"Publiek","congressPublic","form-control col-xs-12 col-sm-8 col-md-8",true,true,false);
            $bannerEditObject = new Button("Banner aanpassen",null,"editCongressBanner","form-control btn btn-default popupButton",true,false,'#popUpBanner');
			$submitObject = new Button("Opslaan","Bewerken","updateCongress","form-control col-md-4 pull-right btn btn-default",false, true, '#popUpUpdate');
			//$this->createScreen->createPopup(array($congressNameObject,$startDateObject,$endDateObject,$subjectObject,$addSubjectObject,$errMsg,$submitObject),"Congres bewerken","Update",null, "", true, false);
			$this->createScreen->createForm(array($errMsg,$congressNameObject,$startDateObject,$endDateObject,$priceObject,$publicObject,$subjectObject,$addSubjectObject,$bannerEditObject,$submitObject),"UpdateCongress", null,"");

            $this->createEditBannerPopUp();
		}

        public function createEditBannerPopUp(){
            $bannerObject = new Upload('','','bannerPic', 'bannerPic', true, true, '', 'image');
            $submitObject = new Submit('Opslaan', '', 'saveBanner', null, true, true);
            $this->createScreen->createPopup(array($bannerObject,$submitObject), "Banner aanpassen", "Banner", null, "", true, false);
        }

        public function addRecord($paramsCongress, $paramsSubjects)
        {
            $sqlInsertCongress = "INSERT INTO Congress (CName, Startdate, Enddate, Price, [Public]) VALUES (?, ?, ?, ?, ?)";
            $sqlGetCongressNo = "SELECT @@IDENTITY AS CongressNo";
            //BEGIN TRANSACTION
            if (sqlsrv_begin_transaction($this->database->getConn()) === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            $congressNo = "";
            $spQueryFailed = false;
            $resultCongressSubjects = true;
            $resultCongressManager = true;
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

            $paramsCongressManager = array(array($_SESSION['personNo'], SQLSRV_PARAM_IN), array($congressNo, SQLSRV_PARAM_IN));
            $result =  parent::addRecord("spAddCongressManagerToCongress", $paramsCongressManager);

            if (!$result){
                $resultCongressManager = false;
            }
             mkdir('../Congresses/Congress' .$congressNo);



            if($resultCongress && $resultCongressSubjects && $resultCongressNo && $resultCongressManager) {
                sqlsrv_commit($this->database->getConn());
                $_SESSION['congressNo'] = $congressNo;
            } else {
                sqlsrv_rollback($this->database->getConn());
                $err['err'] = "";
                if (is_string($resultCongress)){
                    $err['err'] .= $resultCongress;
                }
                else if(is_string($resultCongressSubjects)){
                    $err['err'] .= $resultCongressSubjects;
                }
                else if (is_string($resultCongressManager)){
                    $err['err'] .= $resultCongressManager;
                }
                
               
                return json_encode($err);
            }

        }

        public function changeRecord($storedProcName,$params, $oldSubjects, $newSubjects, $subjectsFromDatabase){
            $insertNewSubjectsFailed = false;
            $deleteOldSubjectsFailed = false;

            //BEGIN TRANSACTION
            if (sqlsrv_begin_transaction($this->database->getConn()) === false) {
                die(print_r(sqlsrv_errors(), true));
            }

            $sqlStmt = 'SELECT CongressNo, CName, Startdate,Enddate, Price, Description, Banner, [Public]
                        FROM Congress
                        WHERE CongressNo = ?';
            $result = parent::changeRecord($storedProcName,$params);

            $sqlGetCurrentSubjects = "SELECT Subject FROM SubjectOfCongress WHERE CongressNo = ?";
            $paramsGetCurrentSubjects = array($_SESSION['congressNo']);
            $resultGetCurrentSubjects = $this->database->sendQuery($sqlGetCurrentSubjects, $paramsGetCurrentSubjects);
            $arrayCurrentSubjects = array();
            if ($resultGetCurrentSubjects){
                while ($row = sqlsrv_fetch_array($resultGetCurrentSubjects, SQLSRV_FETCH_ASSOC)){
                    array_push($arrayCurrentSubjects, $row['Subject']);
                }
                if ($arrayCurrentSubjects == null){
                    $arrayCurrentSubjects = null;
                }
            }

            if ($arrayCurrentSubjects !== $subjectsFromDatabase){
                $err['err'] = "De onderwerpen bij het congres zijn tussendoor gewijzigd. Probeer het opnieuw.";
                return json_encode($err);
            }

            $resultArrayNewSubjects = array();
            for($i = 0; $i < sizeof($newSubjects); $i++){
                $resultNewSubject = parent::changeRecord("spAddSubjectToCongress", array(array($newSubjects[$i], SQLSRV_PARAM_IN), array($_SESSION['congressNo'], SQLSRV_PARAM_IN)));
                array_push($resultArrayNewSubjects, $resultNewSubject);
            }

            for ($i = 0; $i < sizeof($resultArrayNewSubjects); $i++){
                if ($resultArrayNewSubjects[$i]==false){
                    $insertNewSubjectsFailed = true;
                }
            }

            $resultArrayOldSubjects = array();
            $sqlDeleteSubject = "DELETE FROM SubjectOfCongress WHERE Subject = ? AND CongressNo = ?";
            for ($i = 0; $i < sizeof($oldSubjects); $i++){
                $resultOldSubject = $this->database->sendQuery($sqlDeleteSubject, array($oldSubjects[$i], $_SESSION['congressNo']));
                array_push($resultArrayOldSubjects, $resultOldSubject);
            }

            for ($i = 0; $i < sizeof($resultArrayOldSubjects); $i++){
                if ($resultArrayOldSubjects[$i]==false){
                    $deleteOldSubjectsFailed = true;
                }
            }

            //END TRANSACTION
            if($result && !$insertNewSubjectsFailed && !$deleteOldSubjectsFailed) {
                sqlsrv_commit($this->database->getConn());
            } else {
                sqlsrv_rollback($this->database->getConn());
                $err['err'] = $result;
                return json_encode($err);
            }

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

    }
