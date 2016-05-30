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
			$valueList = $this->getSpeakers($this->congressNo);
			
            parent::createManagementScreen($columnList, $valueList, null);
        }
        
        public function getSpeakers($congressNo) {
			 $result = parent::getDatabase()->sendQuery("SELECT P.personNo,P.FirstName, P.LastName, P.MailAddress FROM SpeakerOfCongress SOC " .
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
		
		public function createCreateSpeakerScreen() {
			
			$speakerNameObject = new Text(null,"Voornaam","speakerName",null, true, true, true);
			$speakerLastNameObject = new Text(null,"Achternaam","LastName",null, true, true, true);
			$emailObject = new Text(null, "Mailadres", "mailAddress", null, true, true, true);
			$phoneNumberObject = new Text(null, "Telefoonnr", "phoneNumber", null, true, true, true);
			$descriptionObject = new Text(null, "Description", "description", null, true, true, true);
			$submitObject = new Submit("toevoegen","createSpeaker","toevoegen",null, true, true);			

			$this->createScreen->createPopup(array($speakerNameObject,$speakerLastNameObject,$emailObject,$phoneNumberObject,$descriptionObject,$submitObject),"Spreker aanmaken","Add",null,null,false);
		}
		
		public function createEditSpeakerScreen() {
			$speakerNameObject = new Text(null,"Voornaam","speakerName",null, true, true, true);
			$speakerLastNameObject = new Text(null,"Achternaam","LastName",null, true, true, true);
			$emailObject = new Text(null, "Mailadres", "mailAddress", null, true, true, true);
			$phoneNumberObject = new Text(null, "Telefoonnr", "phoneNumber", null, true, true, true);
			$descriptionObject = new Text(null, "Description", "description", null, true, true, true);
			$submitObject = new Submit("aanpassen","updateSpeaker","aanpassen",null, true, true);			

			$this->createScreen->createPopup(array($speakerNameObject,$speakerLastNameObject,$emailObject,$phoneNumberObject,$descriptionObject,$submitObject),"Spreker aanpassen","Update",null,null,false);

		}
    } 
	
?>
