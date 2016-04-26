<?php
/**
 * Created by PhpStorm.
 * User: erike
 * Date: 25-4-2016
 * Time: 13:52
 */


    class Text extends ScreenObject{
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
                $string .= '<label class="control-label col-xs-8 col-sm-4 col-md-4">' . $this->label . ':</label>';
            }
            $string .= '<input type="text" value="' . $this->value . '" name="'. $this->name .'" class="'. $this->classes .'"';
            if ($this->required) {
                $string .= 'required';
            }
            $string .= '>';
            return $string;
        }
    }
?>