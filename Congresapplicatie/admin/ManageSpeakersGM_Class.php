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
			$phoneNumberObject = new Text(null, "Telefoonnr", "phoneNumber", null, true, true, true);
			$descriptionObject = new Text(null, "Description", "description", null, true, true, true);
			$uploadFile = new Upload(null,'Spreker',"uploadCreateSpeaker",null,true,true,null,"image");
			$submitObject = new Submit("toevoegen","createSpeaker","toevoegen",null, true, true);			
			$errMsg = new Span('',null,'errMsgAanmakenSpreker','errorMsg',true,true,null);
			
			global $emailIsWrong;
			if(isset($emailIsWrong)) {
				$speakerNameObject = new Text($_POST['speakerName'],"Voornaam","speakerName",null, true, true, true);
				$speakerLastNameObject = new Text($_POST['LastName'],"Achternaam","LastName",null, true, true, true);
				$emailObject = new Text($_POST['mailAddress'], "Mailadres", "mailAddress", null, true, true, true);
				$phoneNumberObject = new Text($_POST['phoneNumber'], "Telefoonnr", "phoneNumber", null, true, true, true);
				$descriptionObject = new Text($_POST['description'], "Description", "description", null, true, true, true);
				$errMsg = new Span('Email staat al in de database.',null,'errMsgAanmakenSpreker','errorMsg',true,true,null);
				echo '<script>$(document).ready(function () {document.forms["formCreate"]["buttonAdd"].click();});</script>';
			}
			
			
			$this->createScreen->createPopup(array($speakerNameObject,$speakerLastNameObject,$emailObject,$phoneNumberObject,$descriptionObject,$errMsg,$uploadFile,$submitObject),"Spreker aanmaken","Add",null,null,false,"");
	
		
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
	}
?>
