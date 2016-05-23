<?php

    class Img extends ScreenObject{

        public function __construct($value, $label, $name, $classes, $startRow, $endRow){
            parent::__construct($value, $label, $name, $classes, $startRow, $endRow);
        }

        public function getObjectCode(){
            $string = '<img id=' . $this->name .' src="'.$this->value . '" class="img ';
			if($this->classes != null) {
				$string.= $this->classes;
			}
			$string .= '" alt="empty">';
            return $string;
        }
    }
?>
