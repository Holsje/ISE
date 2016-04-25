<?php
/**
 * Created by PhpStorm.
 * User: erike
 * Date: 25-4-2016
 * Time: 15:15
 */
    class Listbox extends ScreenObject{
        private $columnList;
        private $valueList;
        private $tableid;


        public function __construct($value, $label, $name, $classes, $columnList, $valueList, $tableid)
        {
            parent::__construct($value, $label, $name, $classes);
            $this->columnList = $columnList;
            $this->valueList = $valueList;
            $this->tableid  = $tableid;
        }

        public function getObjectCode(){
            $string = '<table id="'. $this->tableid . '">';
            $string .= '<thead>';
            $string .= '<tr>';

            $size = sizeof($this->columnList);

            for($i=0; $i<$size; $i++){
                $string .= '<th>' . $this->columnList[$i] . '</th>';
            }
            $string .= '</tr>';
            $string .= '</thead>';
            $string .= '<tbody>';

            foreach($this->valueList as $columnName => $value){
                $size = sizeof($value);
                $string .= '<tr>';

                for ($i=0; $i<$size; $i++) {
                    $string .= '<td>' . $value[$i] . '</td>';
                }
                $string .= '</tr>';
            }
            $string .= '</tbody>';
            $string .= '</table>';

            return $string;
        }



    }
?>