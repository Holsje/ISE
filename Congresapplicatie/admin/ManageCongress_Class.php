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

		
		public function createCreateCongressScreen() {
			$congressNameObject = new Text(null,"Naam","congressName",null, true, true, true);
			
			$addSubjectObject = new Button("+",null,"addSubjectButton","form-control btn btn-default popupButton", true, true, "#popUpAddSubjectFromAdd");

            $columnList = array("Onderwerp");
            $valueList = $this->getSubjects();
            $subjectObject = new Listbox(null, null, null, "col-xs-3 col-md-3 col-sm-3", true, true, $columnList, $valueList, "subjectListBoxAdd");


            $startDateObject = new Date(null,"Startdatum","congressStartDate","form-control col-xs-12 col-sm-8 col-md-8", true, true, true);
			$endDateObject = new Date(null,"Einddatum","congressEndDate","form-control col-xs-12 col-sm-8 col-md-8", true, true, true);
            $priceObject = new Text(null,"Prijs","congressPrice","form-control col-xs-12 col-sm-8 col-md-8",true,true,false);
            $publicObject = new Text(null,"Publiek","congressPublic","form-control col-xs-12 col-sm-8 col-md-8",true,true,false);
            $bannerObject = new File(null,"Banner","congressBanner","form-control col-xs-12 col-sm-8 col-md-8",true,true,false);

			$submitObject = new Button("Toevoegen","createCongress","Toevoegen","form-control col-md-4 pull-right btn btn-default", true, true, '#popUpAdd');
			$this->createScreen->createPopup(array($congressNameObject,$startDateObject,$endDateObject,$priceObject,$publicObject,$bannerObject,$subjectObject,$addSubjectObject,$submitObject),"Congres aanmaken","Add", "",true, false);
			
			$subjectNameObject = new Text(null,"Onderwerp","subjectName",null, true, true, false);
			$buttonAddSubjectObject = new Button("Toevoegen","Toevoegen","Toevoegen","form-control col-md-4 pull-right btn btn-default", true, true,'');
			$this->createScreen->createPopup(array($subjectNameObject,$buttonAddSubjectObject),"Onderwerp toevoegen","AddSubjectFromAdd",null, true, true);
		}

		public function createEditCongressScreen() {
			$congressNameObject = new Text(null,"Naam","congressName",null, true, true, true);

			$addSubjectObject = new Button("+",null,"addSubjectButton","form-control btn btn-default popupButton", true, true, "#popUpAddSubjectFromEdit");

            $columnList = array("Onderwerp");
            $valueList = $this->getSubjects();
            $subjectObject = new Listbox(null, null, null, "col-xs-3 col-md-3 col-sm-3", true, true, $columnList, $valueList, "subjectListBoxUpdate");

			$startDateObject = new Date(null,"Startdatum","congressStartDate","form-control col-xs-12 col-sm-8 col-md-8", true, true, true);
			$endDateObject = new Date(null,"Einddatum","congressEndDate","form-control col-xs-12 col-sm-8 col-md-8", true, true, true);
            $priceObject = new Text(null,"Prijs","congressPrice","form-control col-xs-12 col-sm-8 col-md-8",true,true,false);
            $publicObject = new Text(null,"Publiek","congressPublic","form-control col-xs-12 col-sm-8 col-md-8",true,true,false);
            $bannerObject = new Text(null,"Banner","congressBanner","form-control col-xs-12 col-sm-8 col-md-8",true,true,false);

            $errMsg = new Span('',null,'errMsgBewerken','errorMsg',true,true,null);
			$submitObject = new Button("Bewerken","Bewerken","updateCongress","form-control col-md-4 pull-right btn btn-default",true, true, '#popUpUpdate');
			//$this->createScreen->createPopup(array($congressNameObject,$startDateObject,$endDateObject,$subjectObject,$addSubjectObject,$errMsg,$submitObject),"Congres bewerken","Update",null, "", true, false);
			$this->createScreen->createForm(array($congressNameObject,$startDateObject,$endDateObject,$priceObject,$publicObject,$bannerObject,$subjectObject,$addSubjectObject,$errMsg,$submitObject),"UpdateCongress", null);

			$subjectNameObject = new Text(null,"Onderwerp","subjectName",null, true, true, false);
			$buttonAddSubjectObject = new Button("Bewerken","Bewerken","Bewerken","form-control col-md-4 pull-right btn btn-default", true, true, '');
			$this->createScreen->createPopup(array($subjectNameObject,$buttonAddSubjectObject),"Onderwerp toevoegen","AddSubjectFromEdit",null, "", true, false);
		}

        public function addRecord($paramsCongress, $paramsSubjects,$congressBanner)
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

            if($resultCongress && $resultCongressSubjects && $resultCongressNo) {
                sqlsrv_commit($this->database->getConn());
                return "Transaction committed.<br />";
            } else {
                sqlsrv_rollback($this->database->getConn());
                return "Transaction rolled back.<br />";
            }

            //Upload Banner
            $target_file =  basename($_FILES["Banner"]["name"]);
            $uploadOk = true;
            $imageFileType = pathinfo($target_file,PATHINFO_EXTENSTION);

            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif"){
                $uploadOk = false;
            }

            if ($uploadOk){
                $filename = "img/Banners/Congress".$congressNo;
                switch($imageFileType){
                    case "jpg": $filename .= ".jpg";
                    case "png": $filename .= ".png";
                    case "jpeg": $filename .= ".jpeg";
                    case "gif": $filename .= ".gif";
                }
                if (rename($target_file, $filename )){
                    echo "Succesfull";
                }
            }
        }

        public function changeRecord($storedProcName,$params, $oldSubjects, $newSubjects){
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
                return "Transaction committed.<br />";
            } else {
                sqlsrv_rollback($this->database->getConn());
                return "Transaction rolled back.<br />";
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
