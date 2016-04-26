<?php
/**
 * Created by PhpStorm.
 * User: erike
 * Date: 25-4-2016
 * Time: 13:30
 */

require('../database.php');

    class Management extends Database{
        public $classDictionary = array("Button"=>"form-control btn btn-default",
                                        "ListAddButton"=>"form-control btn btn-default",
                                        "Listbox"=>"",
                                        "Password"=>"form-control col-xs-12 col-sm-8 col-md-8",
                                        "SelectWithButton"=>"form-control col-xs-10 col-sm-7 col-md-7 subjectInput",
                                        "SelectWithoutButton"=>"form-control col-xs-10 col-sm-8 col-md-8 subjectInput",
                                        "Submit"=>"form-control col-md-4 pull-right btn btn-default",
                                        "Text"=>"form-control col-xs-12 col-sm-4 col-md-4");

        public function __construct($server, $database, $uid, $password){
            parent::__construct($server, $database, $uid, $password);
        }

        public function addRecord($storedProcName, $params){
            $sql = "EXEC dbo." . $storedProcName . " ?";
            for ($i = 0; $i < sizeof($params)-1; $i++){
                $sql .= ", ?";
            }
            $this->sendQuery($sql, $params);
        }

        public function changeRecord($queryString, $params){
            $this->sendQuery();
        }

        public function deleteRecord($queryString, $params){
            $this->sendQuery();
        }

        public function createForm($screenObjects){
            echo '<form class="form-horizontal col-md-offset-1 col-sm-offset-1 col-xs-offset-1 col-xs-8 col-sm-10 col-md-10" method="POST" action="'.$_SERVER['PHP_SELF']. '">';
            $size = sizeof($screenObjects);
            for($i=0; $i < $size; $i++){
                echo '<div class="form-group"> ';
                echo  $screenObjects[$i]->getObjectCode();
                echo '</div>';
            }
            echo '</form>';
        }

        public function createPopup($screenObjects){

        }


    }
?>