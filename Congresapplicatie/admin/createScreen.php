<?php
/**
 * Created by PhpStorm.
 * User: erike
 * Date: 26-4-2016
 * Time: 13:48
 */
require_once('ScreenObject.php');
require_once('Text.php');
require_once('Button.php');
require_once('Submit.php');
require_once('Select.php');
require_once('Password.php');
require_once('ListAddButton.php');
require_once('Listbox.php');
require_once('Date.php');
require_once('Identifier.php');
require_once('Span.php');


    class CreateScreen{

        public function __construct(){
        }

        public function createForm($screenObjects,$formName, $extraCssClasses){
            echo '<form name="form'. $formName . '" class="form-horizontal col-md-offset-1 col-sm-offset-1 col-xs-offset-1 col-xs-8 col-sm-10 col-md-10 ' . $extraCssClasses . '" method="POST" action="'.$_SERVER['PHP_SELF']. '">';
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
            echo '<div id="popUp' . $popupId . '"  class="popup col-sm-12 col-md-12 col-xs-12 ' . $extraCssClasses . '">';
				echo '<div class="popupWindow col-md-offset-3 col-md-6 col-sm-offset-3 col-sm-6 col-xs-offset-3 col-xs-6">';
					echo '<div class="popupTitle">';
						echo '<h1 class="col-md-8">' . $title . '</h1>';
						echo '<button type="button" class="closePopup glyphicon glyphicon-remove" data-file="#popUp' . $popupId . '"></button>';
					echo '</div>';
			$this->createForm($screenObjects, $popupId ,"formPopup");
				echo '</div>';
            echo '</div>';
        }
    }

?>
