<?php
/**
 * Created by PhpStorm.
 * User: erike
 * Date: 25-4-2016
 * Time: 14:09
 */

    class Submit extends ScreenObject{

        /**
         * Submit constructor.
         * @param $value
         * @param $label
         * @param $name
         * @param $classes
         */
        public function __construct($value, $label, $name, $classes, $startRow, $endRow)
        {
            parent::__construct($value, $label, $name, $classes, $startRow, $endRow);
        }


        /**
         * @return string
         */
        public function getObjectCode(){
            $string = '<button type="submit" name="' . $this->name .'" class="' . $this->classes . '">'. $this->value .'</button>';
            return $string;
        }
    }
?>