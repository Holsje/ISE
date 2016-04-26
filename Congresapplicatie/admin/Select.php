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

        public function __construct($value, $label, $name, $classes, $list, $button){
            parent::__construct($value, $label, $name, $classes);
            $this->list = $list;
            $this->button = $button;
        }

        public function getObjectCode(){
            $string = '<label class="control-label col-xs-8 col-sm-4 col-md-4">'. $this->label .':</label>';
            $string .= '<select value="' . $this->value . '" name="' . $this->name .'" class="' . $this->classes .'">';
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