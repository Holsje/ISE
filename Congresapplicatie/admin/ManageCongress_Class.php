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
			$congressNameObject = new Text(null,"Naam","congressName",null, "??", "??", true);
			$locationObject = new Text(null,"Locatie","congressLocation",null, "??", "??", true);
			
			$addSubjectObject = new ListAddButton("+",null,"addSubjectButton",null, "??", "??", "popupAddSubject");
			$subjectList = $this->getSubjects();
			$subjectObject = new Select(null,"Onderwerp","congressSubject",null, "??", "??", $subjectList,$addSubjectObject);
			
			
			$startDateObject = new Text(null,"DikkeShit","dikkeShitName",null, true, false, true);
			$endDateObject = new Text(null,"DikkeShit","dikkeShitName",null, true, false, true);
			$submitObject = new Text(null,"DikkeShit","dikkeShitName",null, true, false, true);
		
		}

    }