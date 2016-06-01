<?php

    class Upload extends ScreenObject{
        protected $classesInput;
        protected $acceptFile;
        
        public function __construct($value, $label, $name, $classes, $startRow, $endRow, $classesInput, $acceptFile){
            parent::__construct($value, $label, $name, $classes, $startRow, $endRow);
            $this->classesInput  = $classesInput;
            $this->acceptFile = $acceptFile;
        }

        public function getObjectCode(){
            $string = '';
            if ($this->label != null) {
                $string .= '<label class="control-label col-xs-8 col-sm-4 col-md-4">' . $this->label . ':</label>';
            }
            $string .= '<input type="file" name="' . $this->name . '" id="' . $this->name . 'InputFile" data-file="#' . $this->name . 'Btn" class="file-Holder ' . $this->classesInput . '" accept="image/*">';
            $string .= '<button type="button" id="' . $this->name . 'Btn" data-file="#' . $this->name . 'InputFile" class="file-Caller ' . $this->classes . '">+</button>';
            return $string;
        }  
    }
?>
