<?php
require_once('Management.php');
class manageLocationGeneralManager extends Management {
	
	private $allLocations;
	private $columnList;
	
	public function __construct($columnList) {
		parent::__construct();
		$this->allLocations = $this->getAllLocations();
		
		$this->columnList = $columnList;
	}
	
	public function createManageLocationScreenGM() {
		echo '<div class="locationContent">';
		$this->createManagementScreen($this->columnList, $this->allLocations, "LocatieGM", null);
		echo '</div>';
	}
	
	public function createCreateLocationPopUp() {
		$errMsg = new Span(null, null, 'errMsgCreateLocation', 'errorMsg', true, true, null);
		$locationNameText = new Text(null, "Locatienaam", "locationNameText", null, true, true, true);
		$locationCityText = new Text(null, "Plaats", "locationCityText", null, true, true, true);
		$submitButton = new Submit("Toevoegen", null, "saveLocationGMButton", null, true, true);
		$this->getCreateScreen()->createPopUp(array($errMsg, $locationNameText, $locationCityText, $submitButton),"Locatie toevoegen","AddLocatieGM",null,null,null, "#LocatieGM");
	}
	
	public function createDeleteLocationPopUp() {
		$text = new Span("Wilt u zeker dat u de selectie wilt verwijderen?" ,null, "confirmationText", null, true, true);
		$cancelButton = new Button("Annuleren", "cancelDeleteLocation", "cancelButton", "form-control btn btn-default popupButton col-xs-6 col-sm-6 col-md-6", true, false, "#popUpDeleteLocatieGM");
		$confirmButton = new Submit("Bevestigen", "confirmDeleteLocation", "confirmDeleteLocationButton", "form-control btn btn-default popupButton col-xs-6 col-sm-6 col-md-6", false, true);
		$this->getCreateScreen()->createPopUp(array($text, $cancelButton, $confirmButton), "Locatie verwijderen", "DeleteLocatieGM", null, null, null, "#LocatieGM");
	}

	private function getAllLocations() {
		$queryLocations = "SELECT L.LocationName, City FROM Location L";
		$result = $this->database->sendQuery($queryLocations, array());
		$resultArray = array();
		if ($result) {
			$i = 0;
			while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
				$resultArray[$i] = array($row['LocationName'], $row['City']);
				$i++;
			}
		}
		return $resultArray;
	}
}

?>