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
			
			$tableLeft = new Listbox(null, null, null, "col-xs-3 col-md-3 col-sm-3 listBoxDataSwap ", true, false, $columnList, $valueListLeft, "listBoxSpeakerLeft");
			$tableRight = new Listbox(null, null, null, "col-xs-3 col-md-3 col-sm-3 listBoxDataSwap", false, true, $columnList, $valueListRight, "listBoxSpeakerRight");
			$buttonAddSpeaker = new Button("Toevoegen", null, "buttonAddSpeakerOfCongress", "form-control btn btn-default col-xs-3 col-md-3 col-sm-3 popupButton", false, false, "#popUpAddSpeaker");
			$buttonEditSpeakerOfCongress = new Button("Aanpassen", null, "buttonEditSpeakerOfCongress", "form-control btn btn-default col-xs-3 col-md-3 col-sm-3 onSelected", false, false, "#popUpUpdateSpeakerOfCongress");
			
			
			$buttonEditSpeaker = new Button("Aanpassen", null, "buttonEditSpeaker", "form-control btn btn-default col-xs-3 col-md-3 col-sm-3 onSelected ", false, false, "#popUpUpdateSpeaker");
			
			$buttonRemoveSpeaker = new Button("Verwijderen", null, "buttonDeleteSpeaker", "form-control btn btn-default col-xs-3 col-md-3 col-sm-3 onSelected", false, false, "#popUpDeleteSpeaker");
			echo $this->createScreen->createDataSwapList($tableLeft,"listBoxSpeakerLeft","Sprekers Congres",$tableRight,"listBoxSpeakerRight","Sprekers",false,false,array($buttonAddSpeaker,$buttonEditSpeakerOfCongress),array($buttonRemoveSpeaker,$buttonEditSpeaker),"spreker");

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
		
		public function addRecord($storedProcName, $params) {
			parent::addRecord($storedProcName, $params);
		}
		
		public function updateSpeakers($speakersToDelete,$speakersToAdd) {
		
			if(sizeof($speakersToDelete) >= 1) {
			
				$sqlStmnt = 'DELETE FROM SpeakerOfCongress ' . 
							'WHERE personNo = ? ';
				for($i = 1;$i<sizeof($speakersToDelete);$i++) {
					$sqlStmnt .= 'OR personNo = ? ';
				}
				$result = parent::getDatabase()->sendQuery($sqlStmnt,$speakersToDelete);

			}
				
			$numSpeakersToAdd = sizeof($speakersToAdd);
			if($numSpeakersToAdd >= 1) {
				$sqlStmnt = 'INSERT INTO SpeakerOfCongress ' . 
							'VALUES(?,?,null)';
				for($i = 0;$i<$numSpeakersToAdd;$i++) {
					if($i == 0) {
						array_splice($speakersToAdd, (($i*2)+1),0,$this->congressNo);
					}else {
						$sqlStmnt .= ",(?,?,null)";
						array_splice($speakersToAdd, (($i*2)+1),0,$this->congressNo);
					}
					
					
				}
				$result = parent::getDatabase()->sendQuery($sqlStmnt,$speakersToAdd);
			
			}
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
		
		
		public function editSpeaker($storedProcName, $params) {
			$this->addRecord($storedProcName, $params);
			if($this->database->getError()) {
				return $this->database->getError();	
			}
			
			return null;
		}
		
		public function createCreateSpeakerScreen() {			
			
			
			$uploadFile = new Upload(null,'Foto',"uploadCreateSpeaker",null,true,true,null,"image");
			$submitObject = new Submit("toevoegen","createSpeaker","toevoegen",null, true, true);			
			
			
			global $errorOnCreateSpeaker;
			if(isset($errorOnCreateSpeaker)) {
				$speakerNameObject = new Text($_POST['speakerName'],"Voornaam","speakerName",null, true, true, true);
				$speakerLastNameObject = new Text($_POST['LastName'],"Achternaam","LastName",null, true, true, true);
				$emailObject = new Text($_POST['mailAddress'], "Mailadres", "mailAddress", null, true, true, true);
				$phoneNumberObject = new Text($_POST['phoneNumber'], "Telefoonnr", "phoneNumber", null, true, true, false);
				$descriptionObject = new Text($_POST['description'], "Omschrijving", "description", null, true, true, false);
				$agreementObject = new Text($_POST['agreement'], "Overeenkomst", "agreement", null, true, true, false);
				$errMsg = new Span($errorOnCreateSpeaker,null,'errMsgAanmakenSpreker','errorMsg',true,true,null);
				$this->createScreen->createPopup(array($speakerNameObject,$speakerLastNameObject,$emailObject,$phoneNumberObject,$descriptionObject,$agreementObject,$errMsg,$uploadFile,$submitObject),"Spreker aanmaken","AddSpeaker",null,null,'show',"#spreker");
			}else {
				$speakerNameObject = new Text(null,"Voornaam","speakerName",null, true, true, true);
				$speakerLastNameObject = new Text(null,"Achternaam","LastName",null, true, true, true);
				$emailObject = new Text(null, "Mailadres", "mailAddress", null, true, true, true);
				$phoneNumberObject = new Text(null, "Telefoonnr", "phoneNumber", null, true, true, false);
				$descriptionObject = new Text(null, "Omschrijving", "description", null, true, true, false);
				$agreementObject = new Text(null, "Overeenkomst", "agreement", null, true, true, false);
				$errMsg = new Span('',null,'errMsgAanmakenSpreker','errorMsg',true,true,null);
				$this->createScreen->createPopup(array($errMsg,$speakerNameObject,$speakerLastNameObject,$emailObject,$phoneNumberObject,$descriptionObject,$agreementObject,$uploadFile,$submitObject),"Spreker aanmaken","AddSpeaker",null,null,false,"#spreker");
			}
			
		}
		
		public function createEditSpeakerScreen() {
			$speakerNumberObject = new Identifier(null,"ID","personNo",null, true, true, true);
			$speakerNameObject = new Text(null,"Voornaam","speakerName",null, true, true, true);
			$speakerLastNameObject = new Text(null,"Achternaam","LastName",null, true, true, true);
			$emailObject = new Text(null, "Mailadres", "mailAddress", null, true, true, true);
			$phoneNumberObject = new Text(null, "Telefoonnr", "phoneNumber", null, true, true, false);
			$descriptionObject = new Text(null, "Omschrijving", "description", null, true, true, false);
			$errMsg = new Span('',null,'errMsgBewerkenSpreker','errorMsg',true,true,null);
			$uploadFile = new Upload(null,'Foto',"uploadEditSpeaker",null,true,true,null,"image");
			$submitObject = new Submit("aanpassen","updateSpeaker","aanpassen",null, true, true);			

			$this->createScreen->createPopup(array($errMsg,$speakerNumberObject,$speakerNameObject,$speakerLastNameObject,$emailObject,$phoneNumberObject,$descriptionObject,$uploadFile,$submitObject),"Spreker aanpassen","UpdateSpeaker",null,null,false,"#spreker");

		}
		
		public function createEditSpeakerOfCongressScreen() {
			
			$uploadFile = new Upload(null,'Foto',"uploadEditSpeakerOfCongress",null,true,true,null,"image");
			$submitObject = new Submit("aanpassen","updateSpeakerOfCongress","aanpassen",null, true, true);	
			
			global $editSpeakersError;
			if(isset($editSpeakersError)) {
				$speakerNumberObject = new Identifier($_POST['personNo'],"ID","personNo",null, true, true, true);
				$speakerNameObject = new Text($_POST['speakerName'],"Voornaam","speakerName",null, true, true, true);
				$speakerLastNameObject = new Text($_POST['LastName'],"Achternaam","LastName",null, true, true, true);
				$emailObject = new Text($_POST['mailAddress'], "Mailadres", "mailAddress", null, true, true, true);
				$phoneNumberObject = new Text($_POST['phoneNumber'], "Telefoonnr", "phoneNumber", null, true, true, false);
				$descriptionObject = new Text($_POST['description'], "Omschrijving", "description", null, true, true, false);
				$agreementObject = new Text($_POST['agreement'], "Overeenkomst", "agreement", null, true, true, false);
				$errMsg = new Span($editSpeakersError,null,'errMsgUpdateSpeakerOfCongress','errorMsg',true,true,null);
				$this->createScreen->createPopup(array($speakerNumberObject,$speakerNameObject,$speakerLastNameObject,$emailObject,$phoneNumberObject,$descriptionObject,$agreementObject,$errMsg,$uploadFile,$submitObject),"Spreker aanpassen","UpdateSpeakerOfCongress",null,null,'show',"#spreker");	
			}else {
				$speakerNumberObject = new Identifier(null,"ID","personNo",null, true, true, true);
				$speakerNameObject = new Text(null,"Voornaam","speakerName",null, true, true, true);
				$speakerLastNameObject = new Text(null,"Achternaam","LastName",null, true, true, true);
				$emailObject = new Text(null, "Mailadres", "mailAddress", null, true, true, true);
				$phoneNumberObject = new Text(null, "Telefoonnr", "phoneNumber", null, true, true, false);
				$descriptionObject = new Text(null, "Omschrijving", "description", null, true, true, false);
				$agreementObject = new Text(null, "Overeenkomst", "agreement", null, true, true, false);
				$errMsg = new Span('',null,'errMsgUpdateSpeakerOfCongress','errorMsg',true,true,null);
				$this->createScreen->createPopup(array($errMsg,$speakerNumberObject,$speakerNameObject,$speakerLastNameObject,$emailObject,$phoneNumberObject,$descriptionObject,$agreementObject,$uploadFile,$submitObject),"Spreker aanpassen","UpdateSpeakerOfCongress",null,null,false,"#spreker");	
			}


		}
		
		public function getSpeakerOfCongressInfo($personNo,$user) {
			$sqlSpeakers = "SELECT P.personNo,P.FirstName, P.LastName, P.MailAddress, P.phonenumber,SOC.agreement,s.Description,s.PicturePath 
														FROM SpeakerOfCongress SOC 
														INNER JOIN Person P ON P.PersonNo = SOC.PersonNo 
														INNER JOIN Speaker S ON S.PersonNo = SOC.PersonNo 
														WHERE SOC.PersonNo = ? AND S.owner = ?";														
            $params = array($personNo,$user);
            $resultSpeakersObject = $this->database->sendQuery($sqlSpeakers, $params);
            $arraySpeakers = array();
            if ($resultSpeakersObject){
                while($row = sqlsrv_fetch_array($resultSpeakersObject, SQLSRV_FETCH_ASSOC)){
                    array_push($arraySpeakers,$row);
                }
				
            }
			
			if(!$arraySpeakers) {			
				$sqlSpeakers = "SELECT P.MailAddress FROM Speaker S
								INNER JOIN Person P ON P.PersonNo = S.Owner
								WHERE s.PersonNo = ?";	
				$params = array($personNo);
				
				$ownerMailAddress = $this->database->sendQuery($sqlSpeakers, $params);
				if($ownerMailAddress) {
					while($row = sqlsrv_fetch_array($ownerMailAddress, SQLSRV_FETCH_ASSOC)){
						$arraySpeakers['error'] =  $row['MailAddress'];
					}
				}
				
				
			}
			
            return json_encode($arraySpeakers, JSON_FORCE_OBJECT);
         
        }
		
		public function getSpeakerInfo($personNo,$user) {
			$sqlSpeakers = "SELECT P.personNo,P.FirstName, P.LastName, P.MailAddress, P.phonenumber, S.Description, S.PicturePath
														FROM Person P 
														INNER JOIN Speaker S ON S.PersonNo = P.PersonNo 
														WHERE p.PersonNo = ? AND S.owner = ?";
            $params = array($personNo, $user);
            $resultSpeakersObject = $this->database->sendQuery($sqlSpeakers, $params);
            $arraySpeakers = array();
            if ($resultSpeakersObject){
                while($row = sqlsrv_fetch_array($resultSpeakersObject, SQLSRV_FETCH_ASSOC)){
                    array_push($arraySpeakers,$row);
                }
				
            }
			
			
			if(!$arraySpeakers) {			
				$sqlSpeakers = "SELECT P.MailAddress FROM Speaker S
								INNER JOIN Person P ON P.PersonNo = S.Owner
								WHERE s.PersonNo = ?";	
				$params = array($personNo);
				
				$ownerMailAddress = $this->database->sendQuery($sqlSpeakers, $params);
				if($ownerMailAddress) {
					while($row = sqlsrv_fetch_array($ownerMailAddress, SQLSRV_FETCH_ASSOC)){
						$arraySpeakers['error'] =  $row['MailAddress'];
					}
				}
				
				
			}
            return json_encode($arraySpeakers, JSON_FORCE_OBJECT);
         
        }
 } 
	
?>
