<?php
class ManageLocations extends Management {
	
	private $columnList;
	private $valueList;
	private $buttonArray;
	private $currentLocationName;
	private $currentLocationCity;
	private $allLocations;
	
	public function __construct($currentLocationName, $currentLocationCity, $columnList){
		parent::__construct();
		$this->allLocations = $this->getAllLocations();
		if ($currentLocationName != null && $currentLocationCity != null) {
			$this->currentLocationName = $currentLocationName;
			$this->currentLocationCity = $currentLocationCity;
		}
		else {
			$this->currentLocationName = $this->allLocations[0][0];
			$this->currentLocationCity = $this->allLocations[0][1];
			$_SESSION['currentLocationName'] = $this->currentLocationName;
			$_SESSION['currentLocationCity'] = $this->currentLocationCity;
		}
		$this->columnList = $columnList;
		$_SESSION['locationValueList'] = $this->getBuildingsByLocation($this->currentLocationName, $this->currentLocationCity);
    }
	
	public function createManagementScreen($columnList, $valueList, $screenName, $buttonArray) {
		parent::createManagementScreen($columnList, $valueList, $screenName, $buttonArray);
    }
	
	public function createLocationScreen() {
		echo '<div class="locationContent">';
		$buttonLinkToCongress = new Submit("Deze locatie koppelen aan dit congres", null, "buttonLinkToCongress" ,"btn btn-default pull-right buttonLinkToCongress", false, false, null);
		$locationNamesWithCity = array();
		for($i = 0; $i < sizeof($this->allLocations); $i++) {
			array_push($locationNamesWithCity, $this->allLocations[$i][0] . ' - ' . $this->allLocations[$i][1]);
		}
		if (isset($_SESSION['selectedLocation'])) {
			$selectLocations = new Select($_SESSION['selectedLocation'], "Locatie", null, "form-control col-xs-12 col-sm-8 col-md-8 locationSelect", true, true, $locationNamesWithCity, null, null, null);
		}
		else {
			$selectLocations = new Select($locationNamesWithCity[0], "Locatie", null, "form-control col-xs-12 col-sm-8 col-md-8 locationSelect", true, true, $locationNamesWithCity, null, null, null);
		}
		echo '<div class="col-md-offset-1 col-sm-offset-1 col-xs-offset-1 col-xs-10 col-sm-10 col-md-10">';
			echo $selectLocations->getObjectCode();
			echo '<form name="formCoupleLocatie" method="POST" action="'. $_SERVER['PHP_SELF'] . '#Locatie">';
				echo $buttonLinkToCongress->getObjectCode();
			echo '</form>';
		echo '</div>';
		$this->createManagementScreen($this->columnList, $_SESSION['locationValueList'], "Locatie", null);
		echo '</div>';
	}
	
	public function createAddBuildingPopUp() {
		$nameTextField = new Text(null, "Naam", "buildingName", null, true, true, true);
		$street = new Text(null, "Straat + Huisnr", "streetName", "form-control col-xs-7 col-sm-7 col-md-7", true, false, false);
		$houseNo = new Text(null, null, "houseNo", "form-control col-xs-1 col-sm-1 col-md-1", false, true, false);
		$postalCode = new Text(null, "Postcode", "postalCode", null, true, true, false);
		$saveButton = new Submit("Opslaan", null, "saveBuildingButton", null, true, true);
		$this->getCreateScreen()->createPopUp(array($nameTextField, $street, $houseNo, $postalCode, $saveButton),"Gebouw toevoegen","AddLocatie",null,null,null, "#Locatie");
	}
	
	public function createEditLocationPopUp() {
		$columnList = array("Naam","Omschrijving","Capaciteit");
		$valueList = null;
		
		$screenName = "Zalen";
		$buildingName = new Identifier(null, "Gebouw", "BName", null, true, true, true);
		$listBox = new Listbox(null, null, null, "col-xs-3 col-md-3 col-sm-3 singleSelect", false, false, $columnList, $valueList, $screenName . "ListBox");
		$buttonAdd = new Button("Toevoegen", null, "buttonAdd" . $screenName , "form-control btn btn-default col-xs-3 col-md-3 col-sm-3 popupButton", false, false, "#popUpAdd" . $screenName);
		$buttonChange = new Button("Aanpassen", null, "buttonEdit" . $screenName, "form-control btn btn-default col-xs-3 col-md-3 col-sm-3 popupButton onSelected", false, false, "#popUpUpdate" . $screenName);
		$buttonDelete = new Button("Verwijderen", null, "buttonDelete" . $screenName, "form-control btn btn-default col-xs-3 col-md-3 col-sm-3 popupButton onSelected", false, false, "#popUpDelete" . $screenName);
		
				
		$this->getCreateScreen()->createPopUp(array($buildingName,$listBox, $buttonAdd, $buttonChange,$buttonDelete),"Zaal beheren","UpdateLocatie",null,null,null, "#Locatie");
	
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
		$this->getCreateScreen()->createPopUp(array($text, $cancelButton, $confirmButton), "Gebouw verwijderen", "DeleteLocatie", null, null, null, "#Locatie");
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
	
	public function getCurrentLocationName() {
		return $this->currentLocationName;
	}
	public function getCurrentLocationCity() {
		return $this->currentLocationCity;
	}
}

?>