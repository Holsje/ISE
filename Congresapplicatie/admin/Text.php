<?php
/**
 * Created by PhpStorm.
 * User: erike
 * Date: 25-4-2016
 * Time: 13:52
 */
    class Text extends ScreenObject{
        private $required;
        public function __construct($value, $label, $name, $classes, $required){
            parent::__construct($value, $label, $name, $classes);
            $this->required = $required;
        }

        public function getObjectCode(){
            $string = '<label class="control-label col-xs-8 col-sm-4 col-md-4">'. $this->label .':</label>';
            $string .= '<div class="col-xs-12 col-sm-8 col-md-8">';
            $string .= '<input type="text" value="' . $this->value . '" name="'. $this->name .'" class="form-control '. $this->classes .'"';
            if ($this->required) {
                $string .= 'required';
            }
            $string .= '>';
            $string .= '</div>';
            return $string;
        }
    }
?>