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

                    $array[$row['CongressNo']] = array($row['CongressNo'], $row['Name'], $row['Subject'], $row['LOCATION'], $row['StartDate']->format('Y-m-d'), $row['EndDate']->format('Y-m-d'));
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
					array_push($array,$row['Subject']);
                }
				return $array;
            }
			return false;
		}		
		
		public function createCreateCongressScreen() {
			$congressNameObject = new Text(null,"Naam","congressName",null, true, true, true);
			$locationObject = new Text(null,"Locatie","congressLocation",null, true, true, true);
			
			$addSubjectObject = new ListAddButton("+",null,"addSubjectButton","form-control btn btn-default popupButton", true, true, "#PopupAddSubject");
			$subjectList = $this->getSubjects();
			$subjectObject = new Select(null,"Onderwerp","congressSubject",null, true, true, $subjectList,$addSubjectObject);
			
			
			$startDateObject = new Date(null,"Startdatum","startDate","form-control col-xs-12 col-sm-8 col-md-8", true, true, true);
			$endDateObject = new Date(null,"Einddatum","endDate","form-control col-xs-12 col-sm-8 col-md-8", true, true, true);
			$submitObject = new Submit("toevoegen","createCongress","toevoegen",null, true, true);			
			$this->createScreen->createPopup(array($congressNameObject,$locationObject,$subjectObject,$startDateObject,$endDateObject,$submitObject),"Congres aanmaken","PopupAdd",null);
			
			$subjectNameObject = new Text(null,"Onderwerp","subjectName",null, true, true, false);
			$buttonAddSubjectObject = new Button("toevoegen","toevoegen","toevoegen","form-control col-md-4 pull-right btn btn-default", true, true,null);
			$this->createScreen->createPopup(array($subjectNameObject,$buttonAddSubjectObject),"Onderwerp toevoegen","PopupAddSubject",null);
		}
		
		public function createEditCongressScreen() {
			$congressNumberObject = new Identifier(null,null,"congressIdentifier",null, false, false, true);
			$congressNameObject = new Text(null,"Naam","congressName",null, true, true, true);
			$locationObject = new Text(null,"Locatie","congressLocation",null, true, true, true);
			
			$addSubjectObject = new ListAddButton("+",null,"addSubjectButton","form-control btn btn-default popupButton", true, true, "#PopupAddSubjectFromEdit");
			$subjectList = $this->getSubjects();
			$subjectObject = new Select(null,"Onderwerp","congressSubject",null, true, true, $subjectList,$addSubjectObject);
			
			
			$startDateObject = new Date(null,"Startdatum","startDate","form-control col-xs-12 col-sm-8 col-md-8", true, true, true);
			$endDateObject = new Date(null,"Einddatum","endDate","form-control col-xs-12 col-sm-8 col-md-8", true, true, true);
			$submitObject = new Submit("Bewerken","updateCongress","Bewerken",null, true, true);			
			$this->createScreen->createPopup(array($congressNumberObject,$congressNameObject,$locationObject,$subjectObject,$startDateObject,$endDateObject,$submitObject),"Congres bewerken","PopupChange",null);
			
			$subjectNameObject = new Text(null,"Onderwerp","subjectName",null, true, true, false);
			$buttonAddSubjectObject = new Button("Bewerken","Bewerken","Bewerken","form-control col-md-4 pull-right btn btn-default", true, true,null);
			$this->createScreen->createPopup(array($subjectNameObject,$buttonAddSubjectObject),"Onderwerp toevoegen","PopupAddSubjectFromEdit",null);
		}
    }