<?php
/**
 * Created by PhpStorm.
 * User: erike
 * Date: 25-4-2016
 * Time: 13:30
 */

require_once('../database.php');
require_once('CreateScreen.php');
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
			$execString = "{call " . $storedProcName . "(";
			for($i = 0;$i<sizeof($params)-1;$i++) {
				$execString .= " ?,";
			}
			$execString .= "?)}";
			
			$result = $this->database->sendQuery($execString,$params);
            if ($result){
				return true;
            }
			return false;
        }

        /**
         * @param $queryString
         * @param $params
         */
        public function changeRecord($queryString, $params){
			$result = $this->database->sendQuery($queryString, $params);
            if($result) {
                return $result;
            }
            return false;
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

        public function createManagementScreen($columnList, $valueList, $buttonArray){


            $listBox = new Listbox(null, null, null, "col-xs-3 col-md-3 col-sm-3", false, false, $columnList, $valueList, "congresListBox");
            $buttonAdd = new Button("Toevoegen", null, "buttonAdd", "form-control btn btn-default col-xs-3 col-md-3 col-sm-3 popupButton", false, false, "#PopupAdd");
            $buttonChange = new Button("Aanpassen", null, "buttonEdit", "form-control btn btn-default col-xs-3 col-md-3 col-sm-3 popupButton", false, false, "#PopupChange");

            $array = array($listBox, $buttonAdd, $buttonChange);

            if ($buttonArray != null) {
                foreach ($buttonArray as $button) {
                    array_push($array, $button);
                }
            }
            array_push($array, $buttonDelete = new Button("Verwijderen", null, "buttonDelete", "form-control btn btn-default col-xs-3 col-md-3 col-sm-3 popupButton", false, false, "#PopupDelete"));

            $this->createScreen->createForm($array, "formCreateCongress", null);
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

        public function getCreateScreen(){
            return $this->createScreen;
        }

    }
?>