<?php
/**
 * Created by PhpStorm.
 * User: erike
 * Date: 26-4-2016
 * Time: 13:48
 */
require_once('ScreenObjects/ScreenObject.php');
require_once('ScreenObjects/Text.php');
require_once('ScreenObjects/Button.php');
require_once('ScreenObjects/Submit.php');
require_once('ScreenObjects/Select.php');
require_once('ScreenObjects/Password.php');
require_once('ScreenObjects/ListAddButton.php');
require_once('ScreenObjects/Listbox.php');
require_once('ScreenObjects/Date.php');
require_once('ScreenObjects/Span.php');
require_once('ScreenObjects/Img.php');

    class CreateScreen{

        public function __construct(){
        }

        public function createForm($screenObjects,$formName, $extraCssClasses){
            echo '<form name="form'. $formName . '" class="form-horizontal col-md-offset-1 col-sm-offset-1 col-xs-offset-1 col-xs-10 col-sm-10 col-md-10 ' . $extraCssClasses . '" method="POST" action="'.$_SERVER['PHP_SELF']. '">';
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

        public function createPopup($screenObjects,$title,$popupId,$extraCssClasses,$firstWindow){
            echo '<div id="popUp' . $popupId . '"  class="popup col-sm-12 col-md-12 col-xs-12">';
				echo '<div class="popupWindow col-md-offset-3 col-md-6 col-sm-offset-3 col-sm-6 col-xs-offset-3 col-xs-10 ' . $extraCssClasses. '">';
					echo '<div class="popupTitle col-md-6 col-xs-10">';
						echo '<h1 class="col-md-8 col-xs-8 col-sm-8">' . $title . '</h1>';
						echo '<button type="button" class="closePopup '.$firstWindow.' glyphicon glyphicon-remove" data-file="#popUp' . $popupId . '"></button>';
					echo '</div>';
			$this->createForm($screenObjects, $popupId ,"formPopup");
				echo '</div>';
            echo '</div>';
        }
		
		public function createEventInfo($eventName,$subjects,$price,$type,$eventId, $trackno, $dataFile,$classes,$extraStyle,$image,$timeString) {
			if($classes != null) {
				echo '<div value="'.$eventId.'" class="' . $classes . ' eventInfoBox"';
			}else {
				echo '<div class="col-sm-3 col-md-3 col-xs-3 eventInfoBox"';
			}
			if($extraStyle != null) {
				echo "style='" . $extraStyle . "'";
			}
			echo ">";
				echo '<input type="hidden" value="'. $trackno . '-'. $eventId . '" name="eventno[]">';
				echo '<h3>' . $eventName ;
				if($image != null) {
					echo '<img class="eventImage" src="' . $image . '">';
				}				
				echo'</h3>';
				echo '<p>';
				for($i = 0;$i<sizeof($subjects)-1;$i++) {
					echo $subjects[$i] . " - ";
				}
				if($subjects != null) {
					echo $subjects[sizeof($subjects)-1];
				}
				echo'</p>';
				echo '<p>' . $type . '</p>';
				echo '<p class="eventTime">' . $timeString . '</p>';
				if($price!=null or $price != 0) {
					echo '<p class="eventPrice">Prijs:' . number_format($price,2,',','.') . '</p>';
				}
				$button = new Button("Meer Info", null, null, "btn btn-default moreInfoButton popupButton", true, true, $dataFile);
				echo $button->getObjectCode();
			echo '</div>';
		}
    }

?>
