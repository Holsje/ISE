<?php
/**
 * Created by PhpStorm.
 * User: erike
 * Date: 25-4-2016
 * Time: 13:39
 */

    class ScreenObject{

        /**
         * @var
         */
        protected $value;
        /**
         * @var
         */
        protected $label;
        /**
         * @var
         */
        protected $name;
        /**
         * @var
         */
        protected $classes;
        /**
         * @var
         */
        protected $startRow;
        /**
         * @var
         */
        protected $endRow;


        /**
         * ScreenObject constructor.
         * @param $value
         * @param $label
         * @param $name
         * @param $classes
         * @param $startRow
         * @param $endRow
         */
        public function __construct($value, $label, $name, $classes, $startRow, $endRow){
            $this->value = $value;
            $this->label = $label;
            $this->name = $name;
            $this->classes = $classes;
            $this->startRow = $startRow;
            $this->endRow = $endRow;
        }

        /**
         *
         */
        public function getObjectCode(){

        }

        /**
         * @return mixed
         */
        public function getEndRow()
        {
            return $this->endRow;
        }

        /**
         * @return mixed
         */
        public function getStartRow()
        {
            return $this->startRow;
        }


    }
?>