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

        /**
         * Listbox constructor.
         * @param $value
         * @param $label
         * @param $name
         * @param $classes
         * @param $columnList
         * @param $valueList
         * @param $tableid
         */
        public function __construct($value, $label, $name, $classes, $startRow, $endRow, $columnList, $valueList, $tableid)
        {
            if($classes == null) {
                $classes = $this->classDictionary['Listbox'];
            }
            parent::__construct($value, $label, $name, $classes, $startRow, $endRow);
            $this->columnList = $columnList;
            $this->valueList = $valueList;
            $this->tableid  = $tableid;
        }

        /**
         * @return string
         */
        public function getObjectCode(){
            $string = '<table id="'. $this->tableid . '" class="display ' . $this->classes . '">';
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

        public function setColumnList($columnList){
            $this->Listbox->columnList = $columnList;
        }

        public function setValueList($valueList){
            $this->Listbox->valueList = $valueList;
        }

    }
?>