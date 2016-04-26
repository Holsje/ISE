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

        /**
         * Management constructor.
         * @param $server
         * @param $database
         * @param $uid
         * @param $password
         */
        public function __construct($server, $database, $uid, $password){
            parent::__construct($server, $database, $uid, $password);
        }

        /**
         * @param $storedProcName
         * @param $params
         */
        public function addRecord($storedProcName, $params){
            $sql = "EXEC dbo." . $storedProcName . " ?";
            for ($i = 0; $i < sizeof($params)-1; $i++){
                $sql .= ", ?";
            }
            $this->sendQuery($sql, $params);
        }

        /**
         * @param $queryString
         * @param $params
         */
        public function changeRecord($queryString, $params){
            $this->sendQuery();
        }

        /**
         * @param $queryString
         * @param $params
         */
        public function deleteRecord($queryString, $params){
            $this->sendQuery();
        }

        public function createForm($screenObjects,$extraCssClasses){
			echo '<form class="form-horizontal col-md-offset-1 col-sm-offset-1 col-xs-offset-1 col-xs-8 col-sm-10 col-md-10 ' . $extraCssClasses . '" method="POST" action="'.$_SERVER['PHP_SELF']. '">';
            $size = sizeof($screenObjects);
            for($i=0; $i < $size; $i++){
                if ($screenObjects[$i]->getStartRow()) {
                    echo '<div class="form-group"> ';
                }
                echo  $screenObjects[$i]->getObjectCode();
                if ($screenObjects[$i]->getEndRow()) {
                    echo '</div>';
                }
            }
            echo '</form>';
        }

        public function createPopup($screenObjects,$title,$popupId,$extraCssClasses){
			echo '<div id="' . $popupId . '"  class="popup col-sm-12 col-md-12 col-xs-12 ' . $extraCssClasses . '">';
				echo '<div class="popupWindow col-md-offset-3 col-md-6 col-sm-offset-3 col-sm-6 col-xs-offset-3 col-xs-6">';
					echo '<div class="popupTitle">';
					echo '<h1 class="col-md-8">' . $title . '</h1>';
					echo '<button type="button" class="closePopup glyphicon glyphicon-remove" data-file="#' . $popupId . '"></button>';
					echo '</div>';
					$this->createForm($screenObjects, "formPopup");
				echo '</div>';
			echo '</div>';
        }


    }
?>