<?php 

	class manageCongressPlanning extends Management {
		
		private $inschrijven;
		private $tracks;
		
		public function __construct($inschrijven) {
			parent::__construct();
			$this->inschrijven = $inschrijven;
			$this->tracks = $this->inschrijven->getTracks();
		}
		
		public function createManageCongressPlanningScreen() {
			echo '<div class="col-xs-9 col-sm-9 col-md-9 trackBox">';
			
			echo '</div>';
			echo '<div class="col-xs-3 col-sm-3 col-md-3 eventBox">';
			
			echo '</div>';
			
		}
		
		
		
	}
?>