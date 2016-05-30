<?php
    require_once('Management.php');
    class ManageSpeakers extends Management{
		private $congressNo;
        public function __construct($congressNo){
			$this->congressNo = $congressNo;
            parent::__construct();
        }

        public function createManagementScreen() {
			$columnList = array("Nummer","Voornaam","Achternaam","Email");
			$valueListLeft = $this->getSpeakersOfCongress($this->congressNo);
			$valueListRight = $this->getSpeakersNotInCongress($this->congressNo);
			
			$tableLeft = new Listbox(null, null, null, "col-xs-3 col-md-3 col-sm-3 listBox", false, false, $columnList, $valueListLeft, "listBoxSpeakerLeft", "listBoxSpeakerLeft");
			$tableRight = new Listbox(null, null, null, "col-xs-3 col-md-3 col-sm-3 listBox", false, false, $columnList, $valueListRight, "listBoxSpeakerRight", "listBoxSpeakerRight");
			$buttonAddSpeaker = new Button("Toevoegen", null, "buttonAddSpeaker", "form-control btn btn-default col-xs-3 col-md-3 col-sm-3 popupButton", false, false, "#popUpAddSpeaker");
			$buttonEditSpeaker = new Button("Aanpassen", null, "buttonEditSpeaker", "form-control btn btn-default col-xs-3 col-md-3 col-sm-3 popupButton onSelected", false, false, "#popUpUpdateSpeaker");
			$buttonRemoveSpeaker = new Button("Verwijderen", null, "buttonDeleteSpeaker", "form-control btn btn-default col-xs-3 col-md-3 col-sm-3 popupButton onSelected", false, false, "#popUpDeleteSpeaker");
			
			$this->createScreen->createDataSwapList($tableLeft,"listBoxSpeakerLeft","Sprekers Congres",$tableRight,"listBoxSpeakerRight","Sprekers",false,false,array($buttonAddSpeaker,$buttonEditSpeaker,$buttonRemoveSpeaker),array($buttonAddSpeaker,$buttonEditSpeaker,$buttonRemoveSpeaker));
			
			
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
		
		public function getSpeakersNotInCongress($congressNo) {
			 $result = parent::getDatabase()->sendQuery("SELECT P.personNo,P.FirstName, P.LastName, P.MailAddress ".
														"FROM Speaker S ".
														"INNER JOIN Person P ON P.PersonNo = S.PersonNo ".
														"WHERE NOT EXISTS(SELECT 1 " .
														"FROM SpeakerOfCongress SOC " .
														"WHERE SOC.PersonNo = S.PersonNo AND SOC.CongressNo = ?)",array($congressNo));																											
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
		
		public function createCreateSpeakerScreen() {
			
			$speakerNameObject = new Text(null,"Voornaam","speakerName",null, true, true, true);
			$speakerLastNameObject = new Text(null,"Achternaam","LastName",null, true, true, true);
			$emailObject = new Text(null, "Mailadres", "mailAddress", null, true, true, true);
			$phoneNumberObject = new Text(null, "Telefoonnr", "phoneNumber", null, true, true, true);
			$descriptionObject = new Text(null, "Description", "description", null, true, true, true);
			$submitObject = new Submit("toevoegen","createSpeaker","toevoegen",null, true, true);			

			$this->createScreen->createPopup(array($speakerNameObject,$speakerLastNameObject,$emailObject,$phoneNumberObject,$descriptionObject,$submitObject),"Spreker aanmaken","AddSpeaker",null,null,false,"#spreker");
		}
		
		public function createEditSpeakerScreen() {
			$speakerNameObject = new Text(null,"Voornaam","speakerName",null, true, true, true);
			$speakerLastNameObject = new Text(null,"Achternaam","LastName",null, true, true, true);
			$emailObject = new Text(null, "Mailadres", "mailAddress", null, true, true, true);
			$phoneNumberObject = new Text(null, "Telefoonnr", "phoneNumber", null, true, true, true);
			$descriptionObject = new Text(null, "Description", "description", null, true, true, true);
			$submitObject = new Submit("aanpassen","updateSpeaker","aanpassen",null, true, true);			

			$this->createScreen->createPopup(array($speakerNameObject,$speakerLastNameObject,$emailObject,$phoneNumberObject,$descriptionObject,$submitObject),"Spreker aanpassen","UpdateSpeaker",null,null,false,"#spreker");

		}
		
		public function getSpeakerInfo($personNo) {
			$sqlSpeakers = "SELECT P.personNo,P.FirstName, P.LastName, P.MailAddress, P.phonenumber,SOC.description ".
														"FROM SpeakerOfCongress SOC " .
														"INNER JOIN Person P ON P.PersonNo = SOC.PersonNo " .
														"WHERE SOC.CongressNo = ?";
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
    } 
	
?>
