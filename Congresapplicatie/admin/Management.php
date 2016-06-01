<?php
require_once('../database.php');
require_once('../ScreenCreator/CreateScreen.php');
require_once('../connectDatabase.php');
require_once('../pageConfig.php');


    class Management{

        protected $database;
        protected $createScreen;


        /**
         * Management constructor.
         * @param $server
         * @param $databaseName
         * @param $uid
         * @param $password
         */
        public function __construct(){
            global $server, $databaseName, $uid, $password;
            $this->database = new Database($server, $databaseName, $uid, $password);
            $this->createScreen = new CreateScreen();
        }

        /**
         * @param $storedProcName
         * @param $params
         */
        public function addRecord($storedProcName, $params){
			$result = $this->sendStoredProc($storedProcName, $params);
            if ($result){
				return $result;
            }
			return $this->database->getError();
        }

        /**
         * @param $queryString
         * @param $params
         */
        public function changeRecord($storedProcName, $params){
			$result = $this->sendStoredProc($storedProcName, $params);
            if ($result){
                return $result;
            }
			return $this->database->getError();
        }
        
        private function sendStoredProc($storedProcName, $params){
            $execString = "{call " . $storedProcName . "(";
			for($i = 0;$i<sizeof($params)-1;$i++) {
				$execString .= " ?,";
			}
			$execString .= "?)}";

			return $this->database->sendQuery($execString,$params);
            
        }

        /**
         * @param $queryString
         * @param $params
         */
        public function deleteRecord($queryString, $params){
			$result = $this->database->sendQuery($queryString, $params);
            if($result) {
                return $result;
            }
            return false;
        }

        public function createManagementScreen($columnList, $valueList,$screenName, $buttonArray){


            $listBox = new Listbox(null, null, null, "col-xs-3 col-md-3 col-sm-3 singleSelect", false, false, $columnList, $valueList, $screenName . "ListBox");
            $buttonAdd = new Button("Toevoegen", null, "buttonAdd" . $screenName , "form-control btn btn-default col-xs-3 col-md-3 col-sm-3 popupButton", false, false, "#popUpAdd" . $screenName);
            $buttonChange = new Button("Aanpassen", null, "buttonEdit" . $screenName, "form-control btn btn-default col-xs-3 col-md-3 col-sm-3 popupButton onSelected", false, false, "#popUpUpdate" . $screenName);
            $array = array($listBox, $buttonAdd, $buttonChange);

            if ($buttonArray != null) {
                foreach ($buttonArray as $button) {
                    array_push($array, $button);
                }
            }
            array_push($array, $buttonDelete = new Button("Verwijderen", null, "buttonDelete" . $screenName, "form-control btn btn-default col-xs-3 col-md-3 col-sm-3 popupButton onSelected", false, false, "#popUpDelete" . $screenName));

            $this->createScreen->createForm($array, "Create" . $screenName, null,"#" . $screenName);
        }

        /**
         * @return Database
         */
        public function getDatabase(){
            return $this->database;
        }
		
		public function getCreateScreen() {
			return $this->createScreen;
		}



    }
?>
