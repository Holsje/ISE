<?php

    class Identifier extends ScreenObject{
        private $required;

        /**
         * Text constructor.
         * @param $value
         * @param $label
         * @param $name
         * @param $classes
         * @param $required
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
                $string .= '<label class="control-label col-xs-8 col-sm-4 col-md-4" style="display:none;">' . $this->label . ':</label>';
            }
            $string .= '<input  style="display:none;" type="text" value="' . $this->value . '" name="'. $this->name .'" class="';
			if($this->classes != null) {
				$string.= $this->classes;
			}
			else {
				$string.= $this->classDictionary["Text"];
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
