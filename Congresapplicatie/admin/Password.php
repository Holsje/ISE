<?php
/**
 * Created by PhpStorm.
 * User: erike
 * Date: 25-4-2016
 * Time: 13:42
 */

    class Password extends ScreenObject{

        public function __construct($value, $label, $name, $classes){
            parent::__construct($value, $label, $name, $classes);
        }

        public function getObjectCode(){
            $string = '<label class="control-label col-xs-8 col-sm-4 col-md-4">'. $this->label .':</label>';
            $string .= '<div class="col-xs-12 col-sm-8 col-md-8">';
            $string .= '<input type="password" name="'. $this->name .'" class="form-control '. $this->classes .'" required>';
            $string .= '</div>';
            return $string;
        }
    }
?>