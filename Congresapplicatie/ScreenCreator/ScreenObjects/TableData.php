<?php
    class TableData extends ScreenObject{
        private $data;

        /**
         * Button constructor.
         * @param $value
         * @param $label
         * @param $name
         * @param $classes
         * @param $datafile
         */
        public function __construct($data,$classes){
            $this->classes = $classes;
			$this->data = $data;
        }

        /**
         * @return string
         */
        public function getObjectCode(){
			if($this->classes != null) {
				$string = '<td class="' . $this->classes . '">';
			}
			else {
				$string = '<td>';
			}
				$string .= $this->data;
			
			$string .= '</td>';			
			return $string;
        }
    }
?>