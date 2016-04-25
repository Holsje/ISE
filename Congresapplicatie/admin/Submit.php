<?php
/**
 * Created by PhpStorm.
 * User: erike
 * Date: 25-4-2016
 * Time: 14:09
 */

    class Submit extends ScreenObject{
        public function __construct($value, $label, $name, $classes)
        {
            parent::__construct($value, $label, $name, $classes);
        }

        public function getObjectCode(){
            $string = '<div class="col-md-4 pull-right">';
            $string .= '<button type="submit" name="' . $this->name .'" class="btn btn-default form-control ' . $this->classes . '">'. $this->value .'</button>';
            $string .= '</div>';
            return $string;
        }
    }
?>