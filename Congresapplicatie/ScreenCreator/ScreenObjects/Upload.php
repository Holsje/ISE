<?php

    class Upload extends ScreenObject{
        protected $classesInput;
        
        public function __construct($value, $label, $name, $classes, $startRow, $endRow, $classesInput){
            parent::__construct($value, $label, $name, $classes, $startRow, $endRow);
            $this->classesInput  = $classesInput;
        }

        public function getObjectCode(){
            $string = '<input type="file" id="' . $this->name . 'InputFile" data-file="#' . $this->name . 'Btn" class="file-Holder ' . $this->classesInput . '" accept="image/*">';
            $string .= '<button type="button" id="' . $this->name . 'Btn" data-file="#' . $this->name . 'InputFile" class="file-Caller ' . $this->classes . '">+</button>';
            return $string;
        }  
    }
?>
