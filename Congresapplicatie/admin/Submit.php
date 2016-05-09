<?php
/**
 * Created by PhpStorm.
 * User: erike
 * Date: 25-4-2016
 * Time: 14:09
 */

    class Submit extends ScreenObject{

        /**
         * Submit constructor.
         * @param $value
         * @param $label
         * @param $name
         * @param $classes
         */
        public function __construct($value, $label, $name, $classes, $startRow, $endRow)
        {
            parent::__construct($value, $label, $name, $classes, $startRow, $endRow);
        }


        /**
         * @return string
         */
        public function getObjectCode(){
            $string = '<button value="' . $this->label . '" type="submit" name="' . $this->name .'" class="';
			if($this->classes != null) {
				$string.= $this->classes;
			}
			else {
				$string.= $this->classDictionary["Submit"];
			}
			$string .= '">';
			
			$string .= $this->value .'</button>';
            return $string;
        }
    }
?>