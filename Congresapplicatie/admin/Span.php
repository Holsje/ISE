<?php
    class Span extends ScreenObject{

        /**
         * Text constructor.
         * @param $value
         * @param $label
         * @param $name
         * @param $classes
         */
        public function __construct($value,$label, $name, $classes, $startRow, $endRow){
            parent::__construct($value,$label, $name, $classes, $startRow, $endRow);
        }

        /**
         * @return string
         */
        public function getObjectCode(){
            $string = '<span name="' . $this->name . '" class="';
            if($this->classes != null){
                $string .= $this->classes . '"';
            }else{
                $string .= 'col-xs-12 col-sm-12 col-md-12"';
            }
            $string .= '>' . $this->value . '</span>';
            return $string;
        }
    }
?>
