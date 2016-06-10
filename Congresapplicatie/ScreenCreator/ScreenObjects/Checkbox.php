<?php
/**
 * Created by PhpStorm.
 * User: erike
 * Date: 25-4-2016
 * Time: 13:52
 */

    class Checkbox extends ScreenObject{
        private $checked;

        /**
         * Text constructor.
         * @param $value
         * @param $label
         * @param $name
         * @param $classes
         * @param $required
         */
        public function __construct($value, $label, $name, $classes, $startRow, $endRow, $checked){
            parent::__construct($value, $label, $name, $classes, $startRow, $endRow);
            $this->checked = $checked;
        }

        /**
         * @return string
         */
        public function getObjectCode(){
            $string = "";
            if ($this->label != null) {
                $string .= '<label class="control-label col-xs-8 col-sm-4 col-md-4">' . $this->label . ':</label>';
            }
            $string .= '<input type="checkbox" value="' . $this->value . '" name="'. $this->name .'" class="';
			if($this->classes != null) {
				$string.= $this->classes;
			}
			else {
				$string.= $this->classDictionary["Checkbox"];
			}
			$string .= '"';
            if($this->checked){
                $string .= ' checked'; 
            }
            $string .= '>';
            return $string;
        }
    }
?>