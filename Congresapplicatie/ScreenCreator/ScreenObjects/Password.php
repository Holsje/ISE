<?php
/**
 * Created by PhpStorm.
 * User: erike
 * Date: 25-4-2016
 * Time: 13:42
 */

    class Password extends ScreenObject{
        private $required;
        /**
         * Password constructor.
         * @param $value
         * @param $label
         * @param $name
         * @param $classes
         */
        public function __construct($value, $label, $name, $classes, $startRow, $endRow, $required){
        parent::__construct($value, $label, $name, $classes, $startRow, $endRow);
            $this->required = $required;
        }

        /**
         * @return string
         */
        public function getObjectCode(){
            $string = "";
            if ($this->label != null) {
                $string .= '<label class="control-label col-xs-8 col-sm-4 col-md-4">' . $this->label . ':</label>';
            }
            $string .= '<input type="password" name="'. $this->name .'" class="';
			if($this->classes != null) {
				$string.= $this->classes;
			}
			else {
				$string.= $this->classDictionary["Password"];
			}
            $string .= '"';
            if ($this->required) {
                $string .= 'required';
            }
            $string .= '>';
            return $string;
        }
    }
?>