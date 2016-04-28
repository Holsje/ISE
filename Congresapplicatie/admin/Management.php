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
        }

        /**
         * @param $queryString
         * @param $params
         */
        public function changeRecord($queryString, $params){
        }

        /**
         * @param $queryString
         * @param $params
         */
        public function deleteRecord($queryString, $params){
        }

        public function createManagementScreen($columnList, $valueList, $buttonArray){


            $listBox = new Listbox(null, null, null, "col-xs-3 col-md-3 col-sm-3", false, false, $columnList, $valueList, "congresListBox");
            $buttonAdd = new Button("Toevoegen", null, null, "form-control btn btn-default col-xs-3 col-md-3 col-sm-3", false, false, "PopupAdd");
            $buttonChange = new Button("Aanpassen", null, null, "form-control btn btn-default col-xs-3 col-md-3 col-sm-3", false, false, "PopupChange");

            $array = array($listBox, $buttonAdd, $buttonChange);

            if ($buttonArray != null) {
                foreach ($buttonArray as $button) {
                    array_push($array, $button);
                }
            }
            array_push($array, $buttonDelete = new Button("Verwijderen", null, null, "form-control btn btn-default col-xs-3 col-md-3 col-sm-3", false, false, "PopupDelete"));

            $this->createScreen->createForm($array, null);
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