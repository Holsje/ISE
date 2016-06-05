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
require_once('ScreenObjects/File.php');
require_once('ScreenObjects/Submit.php');
require_once('ScreenObjects/Select.php');
require_once('ScreenObjects/Password.php');
require_once('ScreenObjects/ListAddButton.php');
require_once('ScreenObjects/Listbox.php');
require_once('ScreenObjects/Date.php');
require_once('ScreenObjects/Span.php');
require_once('ScreenObjects/Img.php');
require_once('ScreenObjects/TableRow.php');
require_once('ScreenObjects/TableData.php');
require_once('ScreenObjects/Upload.php');
require_once('ScreenObjects/Identifier.php');
require_once('ScreenObjects/TextArea.php');

    class CreateScreen{

        public function __construct(){
        }
	
        public function createForm($screenObjects,$formName, $extraCssClasses, $extraLocation){
            echo '<form name="form'. $formName . '" class="form-horizontal col-md-offset-1 col-sm-offset-1 col-xs-offset-1 col-xs-10 col-sm-10 col-md-10 ' . $extraCssClasses . '" method="POST" action="'.$_SERVER['PHP_SELF']. $extraLocation . '" enctype="multipart/form-data" >';
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

        public function createPopup($screenObjects,$title,$popupId,$extraCssClasses,$firstWindow,$forceShow,$extraLocation){
            echo '<div id="popUp' . $popupId . '"  class="popup col-sm-12 col-md-12 col-xs-12 '. $forceShow .'">';
				echo '<div class="popupWindow col-md-offset-3 col-md-6 col-sm-offset-3 col-sm-6 col-xs-offset-3 col-xs-10 ' . $extraCssClasses. '">';
					echo '<div class="popupTitle col-md-6 col-xs-10">';
						echo '<h1 class="col-md-8 col-xs-8 col-sm-8">' . $title . '</h1>';
						echo '<button type="button" class="closePopup '.$firstWindow.' glyphicon glyphicon-remove" data-file="#popUp' . $popupId . '"></button>';
					echo '</div>';
			$this->createForm($screenObjects, $popupId ,"formPopup",$extraLocation);
				echo '</div>';
            echo '</div>';
        }
        
        public function createPopupByHtml($html, $title, $popupId, $extraCssClasses,$firstWindow,$forceShow){
            echo '<div id="popUp' . $popupId . '"  class="popup col-sm-12 col-md-12 col-xs-12 '. $forceShow .'">';
				echo '<div class="popupWindow col-md-offset-3 col-md-6 col-sm-offset-3 col-sm-6 col-xs-offset-3 col-xs-10 ' . $extraCssClasses. '">';
					echo '<div class="popupTitle col-md-6 col-xs-10">';
						echo '<h1 class="col-md-8 col-xs-8 col-sm-8">' . $title . '</h1>';
						echo '<button type="button" class="closePopup '.$firstWindow.' glyphicon glyphicon-remove" data-file="#popUp' . $popupId . '"></button>';
					echo '</div>';
			         echo $html;
				echo '</div>';
            echo '</div>';
        }
		
		public function createEventInfo($eventName,$subjects,$price,$type,$eventId, $trackno, $dataFile,$classes,$extraStyle,$image,$timeString) {
			if($classes != null) {
				echo '<div value="'.$eventId.'" class="' . $classes . ' eventInfoBox col-xs-12 col-sm-12 col-md-12"';
			}else {
				echo '<div class="col-sm-3 col-md-3 col-xs-3 eventInfoBox"';
			}
			if($extraStyle != null) {
				echo "style='" . $extraStyle . "'";
			}
			echo ">";
				echo '<input type="hidden" value="'. $trackno . '-'. $eventId . '" name="eventno[]">';
				
				echo '<h3 class="eventName col-xs-12 col-sm-12 col-md-12">' . $eventName ;
				echo'</h3>';
				echo '<div class="row">';
					echo '<div class="eventText col-md-7 col-xs-12 col-sm-7">';
					echo '<p>';
					for($i = 0;$i<sizeof($subjects)-1;$i++) {
						echo $subjects[$i] . " - ";
					}
					if($subjects != null) {
						echo $subjects[sizeof($subjects)-1];
					}
					echo'</p>';
					echo '<p>' . $type . '</p>';
					echo '<p>' . $timeString . '</p>';
					if($price!=null or $price != 0) {
						echo '<p class="eventPrice">Prijs:' . number_format($price,2,',','.') . '</p>';
					}
					echo '</div>';
					if($image != null) {
						echo '<div class="eventImage col-xs-6 col-sm-4 col-md-4">';
							echo '<img class="eventImageSignUp img-responsive pull-right" src="' . $image . '">';
						echo '</div>';
					}
				echo '</div>';
				$button = new Button("Meer Info", null, null, "btn btn-default moreInfoButton popupButton pull-right", true, true, $dataFile);
				echo $button->getObjectCode();
			echo '</div>';
		}
		
		public function createDataSwapList($tableLeft,$tableLeftId,$titleLeft,$tableRight,$tableRightId,$titleRight,$keepRight,$removeLeft,$buttonsLeft,$buttonsRight,$pageName) {
            
			$string = '<form name="form' . $pageName . '" method="post"  class="formPopup row col-sm-12 col-xs-12 col-md-12"  action="'.$_SERVER['PHP_SELF']. '#' . $pageName . '">';
				$string .= '<div class="col-sm-5 col-xs-5 col-md-5 dataSwapList ' . $tableLeftId . '"> ';
				$string .= '<h2>' . $titleLeft . '</h2>';
				$string .= $tableLeft->getObjectCode();
					$size = sizeof($buttonsLeft);
					 for($i=0; $i < $size; $i++){
						$string .= $buttonsLeft[$i]->getObjectCode();
					}
				$string .= '</div>';
				$string .= '<div class="col-sm-2 col-xs-2 col-md-2 dataSwapListMiddle"> ';
					$string .= '<button type="button" class="form-control btn btn-default goToLeftButton dataSwapButton" left="' . $tableLeftId . '" right="' . $tableRightId . '" keep=' . $keepRight . '><</button>';
					$string .= '<button type="button" class="form-control btn btn-default goToRightButton dataSwapButton" left="' . $tableLeftId . '" right="' . $tableRightId . '" remove=' . $removeLeft . '>></button>';
				$string .= '</div>';
				$string .= '<div class="col-sm-5 col-xs-5 col-md-5 dataSwapList ' . $tableRightId . '">';
				$string .= '<h2>' . $titleRight . '</h2>';
				$string .= $tableRight->getObjectCode();
					$size = sizeof($buttonsRight);
					 for($i=0; $i < $size; $i++){
						$string .=  $buttonsRight[$i]->getObjectCode();
					}
				$string .= '</div>';
				
				$buttonSave = new Button("Opslaan", $pageName, "buttonSaveSwapList" . $pageName, "form-control btn btn-default col-xs-3 col-md-3 col-sm-3 buttonSaveSwapList", false, false, null);
				$string .= $buttonSave->getObjectCode();
			$string .= '</form>';
            return $string;
		}
        
    }

?>
