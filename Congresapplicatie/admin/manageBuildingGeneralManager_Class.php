<?php 
require_once('Management.php');
class ManageBuildingGeneralManager extends Management {
		
	private $columnList;
	private $currentLocationName;
	private $currentLocationCity;
	private $allBuildingsByLocation;

	public function __construct($columnList, $currentLocationName, $currentLocationCity) {
		parent::__construct();
		$this->currentLocationName = $currentLocationName;
		$this->currentLocationCity = $currentLocationCity;
		$this->columnList = $columnList;
		$this->allBuildingsByLocation = $this->getBuildingsByLocation($this->currentLocationName, $this->currentLocationCity);
	}
	
	public function createManageBuildingScreenGM() {
		echo '<div class="buildingContent">';
			$this->createEditLocationScreen();
			$this->createManagementScreen($this->columnList, $this->allBuildingsByLocation, "BuildingGM", null);
		echo '</div>';
	}
	
	public function createCreateBuildingPopUp() {
		$errMsg = new Span(null,null,'errMsgCreateBuilding','errorMsg',true,true,null);
		$nameTextField = new Text(null, "Naam", "buildingName", null, true, true, true);
		$street = new Text(null, "Straat + Huisnr", "streetName", "form-control col-xs-7 col-sm-7 col-md-7", true, false, true);
		$houseNo = new Text(null, null, "houseNo", "form-control col-xs-1 col-sm-1 col-md-1", false, true, true);
		$postalCode = new Text(null, "Postcode", "postalCode", null, true, true, true);
		$saveButton = new Submit("Opslaan", null, "saveBuildingButton", null, true, true);
		$this->getCreateScreen()->createPopUp(array($errMsg, $nameTextField, $street, $houseNo, $postalCode, $saveButton),"Gebouw toevoegen","AddBuildingGM",null,null,null, "#Locatie");
	}
		
	public function createEditLocationPopUp() {
		$columnList = array("Naam","Omschrijving","Capaciteit");
		$valueList = null;
		
		$screenName = "Zalen";
		$errMsg = new Span(null,null,'errMsgUpdateBuilding','errorMsg',true,true,null);
		$locationName = new Identifier(null, "Locatie", "LocationName", null, true, true, true);
		$cityName = new Identifier(null, "Plaats", "cityName", null, true, true, true);
		$buildingName = new Text(null, "Gebouw", "buildingName", null, true, true, true);
		$street = new Text(null, "Straat + Huisnr", "streetName", "form-control col-xs-7 col-sm-7 col-md-7", true, false, false);
		$houseNo = new Text(null, null, "houseNo", "form-control col-xs-1 col-sm-1 col-md-1", false, true, false);
		$postalCode = new Text(null, "Postcode", "postalCode", null, true, true, false);
		$listBox = new Listbox(null, null, null, "col-xs-3 col-md-3 col-sm-3 singleSelect", true, true, $columnList, $valueList, $screenName . "ListBox");
		$buttonAdd = new Button("Toevoegen", null, "buttonAdd" . $screenName , "form-control btn btn-default col-xs-3 col-md-3 col-sm-3 popupButton", true, false, "#popUpAdd" . $screenName);
		$buttonChange = new Button("Aanpassen", null, "buttonEdit" . $screenName, "form-control btn btn-default col-xs-3 col-md-3 col-sm-3 popupButton onSelected", false, false, "#popUpUpdate" . $screenName);
		$buttonDelete = new Button("Verwijderen", null, "buttonDelete" . $screenName, "form-control btn btn-default col-xs-3 col-md-3 col-sm-3 popupButton onSelected", false, true, "#popUpDelete" . $screenName);
		$saveButton = new Submit("Opslaan", null, "buttonSaveUpdateBuilding", null, true, true);
		$this->getCreateScreen()->createPopUp(array($errMsg, $locationName, $cityName, $buildingName, $street, $houseNo, $postalCode, $listBox, $buttonAdd, $buttonChange,$buttonDelete, $saveButton),"Zaal beheren","UpdateBuildingGM",null,null,null, "");
	
	}
	
	public function createCreateRoomPopUp() {
		$buildingName = new Identifier(null, "Gebouw", "BName", null, true, true, true);
		$roomName = new Text(null, "Naam", "roomName", null, true, true, true);
		$roomDescription = new Text(null, "Omschrijving", "roomDescription", null, true, true, true);
		$roomCapacity = new Text(null, "Capacity", "roomCapacity", null, true, true, true);
		$errMsg = new Span('',null,'errMsgCreateRoom','errorMsg',true,true,null);
		$saveButton = new Submit("Opslaan", null, "saveRoomButton", null, true, true);
	
		$this->getCreateScreen()->createPopUp(array($buildingName,$roomName, $roomDescription, $roomCapacity,$errMsg,$saveButton),"Zaal toevoegen","AddZalen",null,null,null, "#Locatie");
	}
	
	
	public function createEditRoomPopUp() {
		$buildingName = new Identifier(null, "Gebouw", "BName", null, true, true, true);
		$roomName = new Text(null, "Naam", "roomName", null, true, true, true);
		$roomDescription = new Text(null, "Omschrijving", "roomDescription", null, true, true, true);
		$roomCapacity = new Text(null, "Capacity", "roomCapacity", null, true, true, true);
		$errMsg = new Span('',null,'errMsgUpdateRoom','errorMsg',true,true,null);
		$saveButton = new Submit("Opslaan", null, "saveRoomButton", null, true, true);
	
		$this->getCreateScreen()->createPopUp(array($buildingName,$roomName, $roomDescription, $roomCapacity,$errMsg,$saveButton),"Zaal aanpassen","UpdateZalen",null,null,null, "#Locatie");
	}
		
	public function createDeleteBuildingPopUp() {
		$text = new Span("Wilt u zeker dat u de selectie wilt verwijderen?" ,null, "confirmationText", null, true, true);
		$cancelButton = new Button("Annuleren", "cancelDeleteBuilding", "cancelButton", "form-control btn btn-default popupButton col-xs-6 col-sm-6 col-md-6", true, false, "#popUpDeleteLocatie");
		$confirmButton = new Submit("Bevestigen", "confirmDeleteBuilding", "confirmButton", "form-control btn btn-default popupButton col-xs-6 col-sm-6 col-md-6", false, true);
		$this->getCreateScreen()->createPopUp(array($text, $cancelButton, $confirmButton), "Gebouw verwijderen", "DeleteBuildingGM", null, null, null, "#Locatie");
	}
		
	public function createEditLocationScreen() {
		echo '<form name="formEditLocation" class="form-horizontal col-md-offset-1 col-sm-offset-1 col-xs-offset-1 col-xs-10 col-sm-10 col-md-10 formEditLocation" method="POST" action="'. $_SERVER['PHP_SELF'] .'">';
			$errMsg = new Span(null, null, 'errMsgEditLocationValues', 'errorMsg', true, true, null);
			echo $errMsg->getObjectCode();
			echo '<div class="form-group">';
				echo '<label class="col-xs-3 col-sm-3 col-md-3">Locatienaam:</label>';
				echo '<input type="text" value="'. $_SESSION['chosenLocationName'] . '" name="locationName" class="form-control col-xs-9 col-sm-9 col-md-9">';
			echo '</div>';
			echo '<div class="form-group">';
				echo '<label class="col-xs-3 col-sm-3 col-md-3">Plaats:</label>';
				echo '<input type="text" value="'. $_SESSION['chosenLocationCity'] . '" name="locationCity" class="form-control col-xs-9 col-sm-9 col-md-9">';
			echo '</div>';
			echo '<div class="form-group">';
				echo '<button value="confirmEdit" type="submit" name="confirmEditLocationButton" class="form-control btn btn-default col-md-2 col-sm-2 col-xs-2 pull-right">Opslaan</button>';	
				echo '<button type="button" name="previousScreenButton" class="btn btn-default pull-right">Terug naar locatie beheren</button>';
			echo '</div>';
		echo '</form>';
	}
	
	private function getBuildingsByLocation($locationName, $city) {
		$queryGetBuildingsByLocation = "SELECT BName, Street, HouseNo, PostalCode
										FROM Building
										WHERE LocationName = ? AND City = ?";
		$params = array($locationName, $city);
		$result = $this->database->sendQuery($queryGetBuildingsByLocation, $params);
		$resultArray = array();
		if ($result) {
			$i = 0;
			while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
				$resultArray[$i] = array($row['BName'], $row['Street'], $row['HouseNo'], $row['PostalCode']);
				$i++;
			}
		}
		return $resultArray;
	}
}
?>