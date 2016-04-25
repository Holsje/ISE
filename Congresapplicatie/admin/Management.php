<?php
/**
 * Created by PhpStorm.
 * User: erike
 * Date: 25-4-2016
 * Time: 13:30
 */

require('../database.php');

    class Management extends Database{

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