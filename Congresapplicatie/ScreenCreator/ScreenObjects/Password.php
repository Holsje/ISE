<?php
/**
 * Created by PhpStorm.
 * User: erike
 * Date: 25-4-2016
 * Time: 13:42
 */

    class Password extends ScreenObject{
        /**
         * Password constructor.
         * @param $value
         * @param $label
         * @param $name
         * @param $classes
         */
        public function __construct($value, $label, $name, $classes, $startRow, $endRow){
            parent::__construct($value, $label, $name, $classes, $startRow, $endRow);
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
			$string .= '" required>';
            return $string;
        }
    }
?>