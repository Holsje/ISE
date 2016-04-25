<?php
/**
 * Created by PhpStorm.
 * User: erike
 * Date: 25-4-2016
 * Time: 13:39
 */

    class ScreenObject{

        protected $value;
        protected $label;
        protected $name;
        protected $classes;

        public function __construct($value, $label, $name, $classes){
            $this->value = $value;
            $this->label = $label;
            $this->name = $name;
            $this->classes = $classes;
        }

        public function getObjectCode(){

        }
    }
?>