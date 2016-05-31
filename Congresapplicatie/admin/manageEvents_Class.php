<?php
    require_once('Management.php');

    class ManageEvents extends Management{

        public function __construct(){
            parent::__construct();
        }
        
        public function getEventsByCongress(){
            $sql= ' SELECT EventNo, EName, Type, MaxVisitors
                    FROM Event
                    WHERE CongressNo = 1';
            $result = $this->database->sendQuery($sql, array(null));
            $returnArray = array();
            if($result){
                while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
                    array_push($returnArray,array($row['EventNo'], $row['EName'], $row['Type'], $row['MaxVisitors']));
                }
            }
            return $returnArray;
            
        }

        public function createManagementScreen($columnList, $valueList) {
            //Voeg extra buttons toe in $buttonArray
            //$value, $label, $name, $classes, $startRow, $endRow $classesInput)
            $buttonArray=array();
            parent::createManagementScreen($columnList, $valueList, $buttonArray);
            $upload = new Upload('','','eventPic','',true,true,'',"image");
            echo '<form method="POST" action="/ISE/ISE/Congresapplicatie/admin/fileUploadHandler.php" enctype="multipart/form-data">';
            echo $upload->getObjectCode();
            echo '<input type="submit" >';
            echo '</form>';
        }
        
		public function createCreateEventsScreen() {
            //Maak screen objecten aan.
			//$this->createScreen->createPopup(array(ScreenObjecten),"(Naam) aanmaken","PopUpID",extra classen);

		}
		
		public function createEditEventsScreen() {
			//Zie bovenstaande functie
		}
        
         public function changeRecord($storedProcName,$params){
           //Overide indien nodig anders verwijderen.
            $result = parent::changeRecord($storedProcName,$params);
        }
		
    }
