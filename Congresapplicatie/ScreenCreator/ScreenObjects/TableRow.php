<?php
    class TableRow extends ScreenObject{
		private $tds;
        /**
         * Button constructor.
         * @param $value
         * @param $label
         * @param $name
         * @param $classes
         * @param $datafile
         */
        public function __construct($tds,$classes){
            $this->classes = $classes;
			$this->tds = $tds;
        }

        /**
         * @return string
         */
        public function getObjectCode(){
			if($this->classes != null) {
				$string = '<tr class="' . $this->classes . '">';
			}
			else {
				$string = '<tr>';
			}
			
			foreach($this->tds as $td) {
				$string .= $td->getObjectCode();
			}
			
			$string .= '</tr>';			
			return $string;
        }
    }
?>