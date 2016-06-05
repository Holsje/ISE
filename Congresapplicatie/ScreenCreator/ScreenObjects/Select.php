<?php
/**
 * Created by PhpStorm.
 * User: erike
 * Date: 25-4-2016
 * Time: 13:57
 */

    class Select extends ScreenObject{

        private $list;
        private $button;
        private $firstRowEmpty;
        private $id;

        /**
         * Select constructor.
         * @param $value
         * @param $label
         * @param $name
         * @param $classes
         * @param $list
         * @param $button
         */
        public function __construct($value, $label, $name, $classes, $startRow, $endRow, $list, $button, $firstRowEmpty, $id){
            parent::__construct($value, $label, $name, $classes, $startRow, $endRow);
            $this->list = $list;
            $this->button = $button;
            $this->firstRowEmpty = $firstRowEmpty;
            $this->id = $id;
        }

        /**
         * @return string
         */
        public function getObjectCode(){
            $string = "";
            if ($this->label != null) {
                $string .= '<label class="control-label col-xs-8 col-sm-4 col-md-4">' . $this->label . ':</label>';
            }
            $string .= '<select id="' . $this->id . '" value="' . $this->value . '" name="' . $this->name .'" class="';
			
			if($this->classes != null) {
				$string.= $this->classes;
			}
			else if($this->button != null) {
				$string.= $this->classDictionary["SelectWithButton"];
			}
			else {
				$string.= $this->classDictionary["SelectWithoutButton"];
			}
			$string .= '">';
            if($this->firstRowEmpty){
                $string .= '<option disabled selected value>Selecteer een rij</option>';
            }
            for ($i = 0; $i < sizeof($this->list); $i++){
                if ($this->list[$i] == $this->value){
                    $string .= '<option value="' . $this->list[$i] . '" selected>' . $this->list[$i] .'</option>';
                }
                else {
                    $string .= '<option value="' . $this->list[$i] . '">' . $this->list[$i] . '</option>';
                }
            }
            $string .= '</select>';
            if ($this->button != null){
                $string .= '<div class="col-xs-1 col-sm-1 col-md-1 addSubjectBox">';
                $string .= $this->button->getObjectCode();
                $string .= '</div>';
            }

            return $string;
        }
    }
?>