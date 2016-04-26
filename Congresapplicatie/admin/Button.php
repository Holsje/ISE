<?php
/**
 * Created by PhpStorm.
 * User: erike
 * Date: 25-4-2016
 * Time: 14:14
 */
    class Button extends ScreenObject{
        private $datafile;

        public function __construct($value, $label, $name, $classes, $datafile){
            parent::__construct($value, $label, $name, $classes);
            $this->datafile = $datafile;
        }

        public function getObjectCode(){
            $string = '<button type="button" name="' . $this->name .'" class="' . $this->classes . '"';
            if ($this->datafile != null){
                $string .= 'data-file="'. $this->datafile .'"';
            }
                $string.= '>'. $this->value .'</button>';
            return $string;
        }
    }
?>