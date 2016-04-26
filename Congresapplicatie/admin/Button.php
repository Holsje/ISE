<?php
/**
 * Created by PhpStorm.
 * User: erike
 * Date: 25-4-2016
 * Time: 14:14
 */
    class Button extends ScreenObject{
        private $datafile;

        /**
         * Button constructor.
         * @param $value
         * @param $label
         * @param $name
         * @param $classes
         * @param $datafile
         */
        public function __construct($value, $label, $name, $classes, $startRow, $endRow, $datafile){
            parent::__construct($value, $label, $name, $classes, $startRow, $endRow);
            $this->datafile = $datafile;
        }

        /**
         * @return string
         */
        public function getObjectCode(){
            $string = '<button type="button" name="' . $this->name .'" class="';
			if($this->classes != null) {
				$string.= $this->classes;
			}
			else {
				$string.= $this->classDictionary["Button"];
			}
			$string .= '"';
			
			
            if ($this->datafile != null){
                $string .= 'data-file="'. $this->datafile .'"';
            }
                $string.= '>'. $this->value .'</button>';
            return $string;
        }
    }
?>