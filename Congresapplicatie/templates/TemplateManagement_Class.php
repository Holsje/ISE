<?php
    require_once('Management.php');
    class Manage(Naam) extends Management{

        public function __construct(){
            parent::__construct();
        }

        public function (getDataWaardes())> {

        }

        public function createManagementScreen($columnList, $valueList) {
            //Voeg extra buttons toe in $buttonArray
            $buttonArray=array(buttons)
            parent::createManagementScreen($columnList, $valueList, $buttonArray);
        }
        
		public function createCreate(Naam)Screen() {
            //Maak screen objecten aan.
			$this->createScreen->createPopup(array(ScreenObjecten),"(Naam) aanmaken","PopUpID",extra classen);

		}
		
		public function createEdit(Naam)Screen() {
			//Zie bovenstaande functie
		}
        
         public function changeRecord($storedProcName,$params){
           //Overide indien nodig anders verwijderen.
            $result = parent::changeRecord($storedProcName,$params);
        }
		
    }
