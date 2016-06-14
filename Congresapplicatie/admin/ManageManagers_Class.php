<?php
    require_once('Management.php');
    class ManageManagers extends Management{

        public function __construct(){
            parent::__construct();
        }

        public function getManagers() {
            $sqlManagers = "SELECT P.PersonNo, P.FirstName, P.LastName, P.MailAddress, P.PhoneNumber, 
                                                    CASE 
													   WHEN  COUNT(PTOP.TypeName) > 1 THEN 'Beide'
													   ELSE ( SELECT PTOP2.TypeName
													   		  FROM PersonTypeOfPerson PTOP2
													   		  WHERE PTOP2.PersonNo = P.PersonNo AND (PTOP2.TypeName = 'Algemene beheerder' OR                                                                           PTOP2.TypeName = 'Congresbeheerder'))
												    END as [Type]
															
FROM Person P INNER JOIN PersonTypeOfPerson PTOP 
	ON P.PersonNo = PTOP.PersonNo
WHERE PTOP.TypeName = 'Algemene beheerder' OR PTOP.TypeName = 'Congresbeheerder'
GROUP BY P.PersonNo, P.FirstName, P.LastName, P.MailAddress, P.PhoneNumber";
            $result = $this->database->sendQuery($sqlManagers,array());
            $returnArray = array();
            if($result){
                while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
                    array_push($returnArray,array($row['PersonNo'],$row['FirstName'], $row['LastName'], $row['MailAddress'], $row['PhoneNumber'],$row['Type']));
                }
                return $returnArray;
            }
        }
        
        public function handleAddManager($manageType){
            $spParams = array(array($_POST['FirstName'],SQLSRV_PARAM_IN),
                              array($_POST['LastName'],SQLSRV_PARAM_IN),
                              array($_POST['mailAddress'],SQLSRV_PARAM_IN),
                              array(hash('sha256',$_POST['password']),SQLSRV_PARAM_IN),
                              array($_POST['phoneNumber'],SQLSRV_PARAM_IN),
                              array($manageType,SQLSRV_PARAM_IN)
                             );
            $result = $this->addRecord('spAddManager',$spParams);
            return $result;
        }
        
        public function handleUpdateManager(){
             //BEGIN TRANSACTION
            if (sqlsrv_begin_transaction($this->database->getConn()) === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            
            $sqlGetOldType = '  SELECT TypeName
                                FROM PersonTypeOfPerson
                                WHERE PersonNo = ?';
            $paramsOldType = array($_POST['AanpassenManager']);
            var_dump($paramsOldType);
            $resultOldType = $this->database->sendQuery($sqlGetOldType,$paramsOldType);
            $GM = 0;
            $PostGM = 0;
            $CM = 0;
            $PostCM = 0;
            if($resultOldType){
                while($row = sqlsrv_fetch_array($resultOldType,SQLSRV_FETCH_ASSOC)){
                    if($row['TypeName'] == 'Congresbeheerder'){
                        $CM = 1;
                    }
                    if($row['TypeName'] == 'Algemene beheerder'){
                        $GM = 1;
                    }
                }
            }
            $sqlUpdatePerson = 'UPDATE Person
                                SET FirstName = ?, LastName = ?,  MailAddress = ?, PhoneNumber = ?
                                WHERE PersonNo = ?';
            $paramsUpdatePerson = array($_POST['FirstName'], $_POST['LastName'], $_POST['mailAddress'], $_POST['phoneNumber'],$_POST['AanpassenManager']);
            $resultUpdate = $this->database->sendQuery($sqlUpdatePerson, $paramsUpdatePerson);
            
            if(isset($_POST['isGM'])){
                $PostGM = 1;
            }
            if(isset($_POST['isCM'])){
                $PostCM = 1;
            }
            $GM = $PostGM - $GM;
            $CM = $PostCM - $CM;
            $param = array($_POST['AanpassenManager'],hash('sha256',$_POST['password']));
            
            $resultInsertGM = true;
            if($GM == 1){
                $sqlInsertGM = 'INSERT INTO GeneralManager
                                VALUES(?,?)';
                $resultInsertGM = $this->database->sendQuery($sqlInsertGM,$param);
                $sqlInsertGMType = 'INSERT INTO PersonTypeOfPerson
                                    VALUES(?,?)';
                $paramsNewType = array($_POST['AanpassenManager'],'Algemene Beheerder');
                $this->database->sendQuery($sqlInsertGMType, $paramsNewType);
            }
            $resultDelGM = true;
            if ($GM == -1){
                $sqlDeleteGM =' DELETE FROM GeneralManager
                                WHERE PersonNo = ?';
                $resultDelGM = $this->database->sendQuery($sqlDeleteGM,$paramsOldType);
            }
            $resultInsertCM = true;
            if($CM == 1){
                $sqlInsertCM = 'INSERT INTO CongressManager
                                VALUES(?,?)';
                $resultInsertCM = $this->database->sendQuery($sqlInsertCM,$param);
                $sqlInsertCMType = 'INSERT INTO PersonTypeOfPerson
                                    VALUES(?,?)';
                $paramsNewType = array($_POST['AanpassenManager'],'Congresbeheerder');
                $resultTest = $this->database->sendQuery($sqlInsertCMType, $paramsNewType);
            }
            $resultDelCM = true;
            if ($CM == -1){
                $sqlDeleteCM =' DELETE FROM CongressManager
                                WHERE PersonNo = ?';
                $resultDelCM = $this->database->sendQuery($sqlDeleteCM,$paramsOldType);
            }
            
             if($resultDelCM && $resultInsertCM && $resultDelGM && $resultInsertGM && $resultUpdate) {
                sqlsrv_commit($this->database->getConn());
            } else {
                sqlsrv_rollback($this->database->getConn());
                $err['err'] = $resultUpdate;
                return $err;
            }
            return $resultUpdate;
        }
        
        public function handleDeleteManager(){
            if($_POST['personType'] == 'Beide'){
                $sqlDeleteCM =' DELETE FROM CongressManager
                                    WHERE PersonNo = ?';
                $resultDelCM = $this->database->sendQuery($sqlDeleteCM,array($_POST['personNo']));
                if(is_string($resultDelCM)){
                    return $resultDelCM;
                }
                $sqlDeleteGM =' DELETE FROM GeneralManager
                                WHERE PersonNo = ?';
                $resultDelGM = $this->database->sendQuery($sqlDeleteGM,array($_POST['personNo']));
                
            }
            if($_POST['personType'] == 'Algemene beheerder'){
                $sqlDeleteGM =' DELETE FROM GeneralManager
                                WHERE PersonNo = ?';
                $resultDelGM = $this->database->sendQuery($sqlDeleteGM,array($_POST['personNo']));
            }
            if($_POST['personType'] == 'Congresbeheerder'){
                $sqlDeleteCM =' DELETE FROM CongressManager
                                WHERE PersonNo = ?';
                $resultDelCM = $this->database->sendQuery($sqlDeleteCM,array($_POST['personNo']));
                 if(is_string($resultDelCM)){
                    return $resultDelCM;
                }
            }     
        }

        public function createManagementScreen($columnList, $valueList) {
            //Voeg extra buttons toe in $buttonArray
            //$buttonArray=array(buttons)
            parent::createManagementScreen($columnList, $valueList,'manageManagers',array());
        }
        
		public function createCreateManageMangersScreen() {
            $managerAddBtn = new Submit("Toevoegen","addManager","ToevoegenManager","form-control col-md-4 pull-right btn btn-default", true, true, '');
            if(isset($_POST['isGM'])){
                $isGM = new CheckBox(null,'Algemene Beheerder','isGM',null,true,false,true);
            }else{
                $isGM = new CheckBox(null,'Algemene Beheerder','isGM',null,true,false,false);
            }
            
            if(isset($_POST['isCM'])){
                $isCM = new CheckBox(null,'Congresbeheerder','isCM',null,false,true,true);
            }else{
                $isCM = new CheckBox(null,'Congresbeheerder','isCM',null,false,true,true);
            }
           
            if(isset($_SESSION['errMsgAddManager'])){
                $errMsg = new Span($_SESSION['errMsgAddManager'],null,'errMsgAddManager','errorMsg',true,true,null);
                $manageNameObject = new Text($_POST['FirstName'],"Voornaam","FirstName",null, true, true, true);
                $manageLastNameObject = new Text($_POST['LastName'],"Achternaam","LastName",null, true, true, true);
                $emailObject = new Text($_POST['mailAddress'], "Mailadres", "mailAddress", null, true, true, true);
                $passwordObject = new Password($_POST['password'], "Wachtwoord", "password", null, true, true, true);
                $phoneNumberObject = new Text($_POST['phoneNumber'], "Telefoonnr", "phoneNumber", null, true, true, false);
                $screenObjects = array($errMsg,$manageNameObject, $manageLastNameObject, $emailObject, $passwordObject,$phoneNumberObject, $isGM,$isCM,$managerAddBtn);
                $this->createScreen->createPopup($screenObjects,"Beheerders aanmaken","AddmanageManagers",'','','show','');
                unset($_SESSION['errMsgAddManager']);
            }else{
                $errMsg = new Span(null,null,'errMsgAddManager','errorMsgAddManager',true,true,null);
                $manageNameObject = new Text(null,"Voornaam","FirstName",null, true, true, true);
                $manageLastNameObject = new Text(null,"Achternaam","LastName",null, true, true, true);
                $emailObject = new Text(null, "Mailadres", "mailAddress", null, true, true, true);
                $passwordObject = new Password(null, "Wachtwoord", "password", null, true, true, true);
                $phoneNumberObject = new Text(null, "Telefoonnr", "phoneNumber", null, true, true, false);
                $screenObjects = array($errMsg,$manageNameObject, $manageLastNameObject, $emailObject, $passwordObject,$phoneNumberObject, $isGM,$isCM,$managerAddBtn);
                $this->createScreen->createPopup($screenObjects,"Beheerders aanmaken","AddmanageManagers",'','','','');
            }
		}
		
		public function createEditManageMangersScreen() {
			$managerAddBtn = new Submit("Aanpassen","UpdateManager","AanpassenManager","form-control col-md-4 pull-right btn btn-default", true, true, '');
            if(isset($_POST['isGM'])){
                $isGM = new CheckBox(null,'Algemene Beheerder','isGM',null,true,false,true);
            }else{
                $isGM = new CheckBox(null,'Algemene Beheerder','isGM',null,true,false,false);
            }
            
            if(isset($_POST['isCM'])){
                $isCM = new CheckBox(null,'Congresbeheerder','isCM',null,false,true,true);
            }else{
                $isCM = new CheckBox(null,'Congresbeheerder','isCM',null,false,true,false);
            }
           
            if(isset($_SESSION['errMsgEditManager'])){
                $errMsg = new Span($_SESSION['errMsgEditManager'],null,'errMsgEditManager','errorMsg',true,true,null);
                $manageNameObject = new Text($_POST['FirstName'],"Voornaam","FirstName",null, true, true, true);
                $manageLastNameObject = new Text($_POST['LastName'],"Achternaam","LastName",null, true, true, true);
                $emailObject = new Text($_POST['mailAddress'], "Mailadres", "mailAddress", null, true, true, true);
                $passwordObject = new Password($_POST['password'], "Wachtwoord", "password", null, true, true, true);
                $phoneNumberObject = new Text($_POST['phoneNumber'], "Telefoonnr", "phoneNumber", null, true, true, false);
                $screenObjects = array($errMsg,$manageNameObject, $manageLastNameObject, $emailObject, $passwordObject,$phoneNumberObject, $isGM,$isCM,$managerAddBtn);
                $this->createScreen->createPopup($screenObjects,"Beheerders aanpassen","UpdatemanageManagers",'','','show','');
                unset($_SESSION['errMsgEditManager']);
            }else{
                $errMsg = new Span(null,null,'errMsgEditManager','errorMsgEditManager',true,true,null);
                $manageNameObject = new Text(null,"Voornaam","FirstName",null, true, true, true);
                $manageLastNameObject = new Text(null,"Achternaam","LastName",null, true, true, true);
                $emailObject = new Text(null, "Mailadres", "mailAddress", null, true, true, true);
                $passwordObject = new Password(null, "Wachtwoord", "password", null, true, true, true);
                $phoneNumberObject = new Text(null, "Telefoonnr", "phoneNumber", null, true, true, false);
                $screenObjects = array($errMsg,$manageNameObject, $manageLastNameObject, $emailObject, $passwordObject,$phoneNumberObject, $isGM,$isCM,$managerAddBtn);
                $this->createScreen->createPopup($screenObjects,"Beheerders aanpassen","UpdatemanageManagers",'','','','');
            }

		}
        
         public function changeRecord($storedProcName,$params){
           //Overide indien nodig anders verwijderen.
            $result = parent::changeRecord($storedProcName,$params);
        }
		
    }
?>