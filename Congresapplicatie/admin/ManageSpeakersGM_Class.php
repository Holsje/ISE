<?php
    require_once('Management.php');
    class ManageSpeakersGM extends Management{

        public function __construct(){
            parent::__construct();
        }

        public function createManagementScreen() {			
			$columnList = array("Nummer","Voornaam","Achternaam","Email");
			$valueList = $this->getSpeakers();
			parent::createManagementScreen($columnList, $valueList, "", null);
        }
		
		
		public function getSpeakers() {
			 $result = parent::getDatabase()->sendQuery("SELECT P.personNo,P.FirstName, P.LastName, P.MailAddress ".
														"FROM Speaker S ".
														"INNER JOIN Person P ON P.PersonNo = S.PersonNo " ,null);																											
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
			$phoneNumberObject = new Text(null, "Telefoonnr", "phoneNumber", null, true, true, false);
			$descriptionObject = new Text(null, "Omschrijving", "description", null, true, true, false);
			$uploadFile = new Upload(null,'Foto',"uploadCreateSpeaker",null,true,true,null,"image");
			$submitObject = new Submit("toevoegen","createSpeaker","toevoegen",null, true, true);		
			
			global $emailOfSpeakerIsWrong;
			if(isset($emailOfSpeakerIsWrong)) {
				$errMsg = new Span('Email staat al in de database.',null,'errMsgAanmakenSpreker','errorMsg',true,true,null);
			}else {
				$errMsg = new Span('',null,'errMsgAanmakenSpreker','errorMsg',true,true,null);
			}
			
			
			$this->createScreen->createPopup(array($errMsg,$speakerNameObject,$speakerLastNameObject,$emailObject,$phoneNumberObject,$descriptionObject,$uploadFile,$submitObject),"Spreker aanmaken","Add",null,null,false,"");
	
		
		}
		
		
		public function createSpeaker($storedProcName, $params,$mailAddress) {
			$this->addRecord($storedProcName, $params);
			$sqlStmnt = 'SELECT PersonNo FROM Person WHERE MailAddress = ?';
			$params = array($mailAddress);
			if($this->database->getError())
				return $this->database->getError();
			
			$result = $this->database->sendQuery($sqlStmnt, $params);
			if ($result){				
                while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
                    return $row['PersonNo'];
                }
				
            }		
		}
		
		
		public function createEditSpeakerScreen() {
			$uploadFile = new Upload(null,'Foto',"uploadEditSpeaker",null,true,true,null,"image");
			$submitObject = new Submit("aanpassen","updateSpeaker","aanpassen",null, true, true);	
			
			global $editSpeakerError;
			if(isset($editSpeakerError)) {
				$speakerNumberObject = new Identifier($_POST['personNo'],"ID","personNo",null, true, true, true);
				$speakerNameObject = new Text($_POST['speakerName'],"Voornaam","speakerName",null, true, true, true);
				$speakerLastNameObject = new Text($_POST['LastName'],"Achternaam","LastName",null, true, true, true);
				$emailObject = new Text($_POST['mailAddress'], "Mailadres", "mailAddress", null, true, true, true);
				$phoneNumberObject = new Text($_POST['phoneNumber'], "Telefoonnr", "phoneNumber", null, true, true, false);
				$descriptionObject = new Text($_POST['description'], "Omschrijving", "description", null, true, true, false);
				$errMsg = new Span($editSpeakerError,null,'errMsgBewerkenSpreker','errorMsg',true,true,null);
				$this->createScreen->createPopup(array($errMsg,$speakerNumberObject,$speakerNameObject,$speakerLastNameObject,$emailObject,$phoneNumberObject,$descriptionObject,$uploadFile,$submitObject),"Spreker aanpassen","Update",null,null,'show',"#spreker");
			}else {
				$speakerNumberObject = new Identifier(null,"ID","personNo",null, true, true, true);
				$speakerNameObject = new Text(null,"Voornaam","speakerName",null, true, true, true);
				$speakerLastNameObject = new Text(null,"Achternaam","LastName",null, true, true, true);
				$emailObject = new Text(null, "Mailadres", "mailAddress", null, true, true, true);
				$phoneNumberObject = new Text(null, "Telefoonnr", "phoneNumber", null, true, true, false);
				$descriptionObject = new Text(null, "Omschrijving", "description", null, true, true, false);
				$errMsg = new Span('',null,'errMsgBewerkenSpreker','errorMsg',true,true,null);
				$this->createScreen->createPopup(array($errMsg,$speakerNumberObject,$speakerNameObject,$speakerLastNameObject,$emailObject,$phoneNumberObject,$descriptionObject,$uploadFile,$submitObject),"Spreker aanpassen","Update",null,null,false,"#spreker");
			}

			

		}
		
		
		public function getSpeakerInfo($personNo) {
			$sqlSpeakers = "SELECT P.personNo,P.FirstName, P.LastName, P.MailAddress, P.phonenumber,s.Description,s.PicturePath ".
														"FROM Person P " .
														"INNER JOIN Speaker S ON S.PersonNo = P.PersonNo ".
														"WHERE p.PersonNo = ?";														
            $params = array($personNo);
            $resultSpeakersObject = $this->database->sendQuery($sqlSpeakers, $params);
            $arraySpeakers = array();
            if ($resultSpeakersObject){
                while($row = sqlsrv_fetch_array($resultSpeakersObject, SQLSRV_FETCH_ASSOC)){
                    array_push($arraySpeakers,$row);
                }
				
            }else {
				$arraySpeakers = $this->database->getError();
			}
			
            return json_encode($arraySpeakers, JSON_FORCE_OBJECT);
         
        }
		
		public function editSpeaker($storedProcName, $params) {
			$this->addRecord($storedProcName, $params);
			if($this->database->getError()) {
				return $this->database->getError();	
			}			
			return null;
		}
	}
?>
