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

		if (!isset($_SESSION['currentLocationName']) && !isset($_SESSION['currentLocationCity'])) {
			$location = $this->getCongressLocation();
			if ($location['LocationName'] == null) {
				$this->currentLocationName = null;
			} else {
				$this->currentLocationName = $location['LocationName'];
				$_SESSION['currentLocationName'] = $this->currentLocationName;
			}

			if ($location['City'] == null) {
				$this->currentLocationCity = null;
			} else {
				$this->currentLocationCity = $location['City'];
				$_SESSION['currentLocationCity'] = $this->currentLocationCity;
			}
		}

		$this->allLocations = $this->getAllLocations();




		$this->columnList = $columnList;
		if (isset($_SESSION['currentLocationName']) && isset($_SESSION['currentLocationCity'])) {
			$_SESSION['locationValueList'] = $this->getBuildingsByLocation($_SESSION['currentLocationName'], $_SESSION['currentLocationCity']);
		}
    }
	
	public function createManagementScreen($columnList, $valueList, $screenName, $buttonArray) {
		parent::createManagementScreen($columnList, $valueList, $screenName, $buttonArray);
    }
	
	public function createLocationScreen() {
		echo '<div class="locationContent">';
		if (isset($_SESSION['errorMsgLinkToCongress'])){
			$errMsg = new Span($_SESSION['errorMsgLinkToCongress'],null,'errMsgLinkToCongress','errorMsg',true,true,null);
			$errMsg->getObjectCode();
		}
		$buttonLinkToCongress = new Submit("Deze locatie koppelen aan dit congres", null, "buttonLinkToCongress" ,"btn btn-default pull-right buttonLinkToCongress", false, false, null);
		$locationNamesWithCity = array();
		for($i = 0; $i < sizeof($this->allLocations); $i++) {
			array_push($locationNamesWithCity, $this->allLocations[$i][0] . ' - ' . $this->allLocations[$i][1]);
		}
		if (isset($_SESSION['currentLocationName']) && isset($_SESSION['currentLocationCity'])) {
			$selectLocations = new Select($_SESSION['currentLocationName'] . ' - ' . $_SESSION['currentLocationCity'], "Locatie", null, "form-control col-xs-12 col-sm-8 col-md-8 locationSelect", true, true, $locationNamesWithCity, null, true, null);
		}
		else{
			$selectLocations = new Select(null, "Locatie", null, "form-control col-xs-12 col-sm-8 col-md-8 locationSelect", true, true, $locationNamesWithCity, null, true, null);
		}
		echo '<div class="col-md-offset-1 col-sm-offset-1 col-xs-offset-1 col-xs-10 col-sm-10 col-md-10">';
		echo $selectLocations->getObjectCode();
		echo '<form name="formCoupleLocatie" method="POST" action="'. $_SERVER['PHP_SELF'] . '#Locatie">';
			echo $buttonLinkToCongress->getObjectCode();
		echo '</form>';
		echo '</div>';
		if (isset($_SESSION['currentLocationName']) && isset($_SESSION['currentLocationCity'])) {
			$this->createManagementScreen($this->columnList, $_SESSION['locationValueList'], "Locatie", null);
		}
		else {
			$this->createManagementScreen($this->columnList, null, "Locatie", null);
		}
		echo '</div>';
	}
	
	public function createAddBuildingPopUp() {
		$saveButton = new Submit("Toevoegen", null, "saveBuildingButton", null, true, true);

		if (isset($_SESSION['errorMsgInsertBuilding'])) {
			$errMsg = new Span($_SESSION['errorMsgInsertBuilding'],null,'errMsgInsertBuilding','errorMsg',true,true,null);
			$nameTextField = new Text($_POST['buildingName'], "Naam", "buildingName", null, true, true, true);
			$street = new Text($_POST['streetName'], "Straat + Huisnr", "streetName", "form-control col-xs-7 col-sm-7 col-md-7", true, false, false);
			$houseNo = new Text($_POST['houseNo'], null, "houseNo", "form-control col-xs-1 col-sm-1 col-md-1", false, true, false);
			$postalCode = new Text($_POST['postalCode'], "Postcode", "postalCode", null, true, true, false);
			unset($_SESSION['errorMsgInsertBuilding']);
			$this->getCreateScreen()->createPopUp(array($errMsg, $nameTextField, $street, $houseNo, $postalCode, $saveButton), "Gebouw toevoegen", "AddLocatie", null, null, null, "#Locatie");
		}
		else{
			$errMsg = new Span('',null,'errMsgInsertBuilding','errorMsg',true,true,null);
			$nameTextField = new Text(null, "Naam", "buildingName", null, true, true, true);
			$street = new Text(null, "Straat + Huisnr", "streetName", "form-control col-xs-7 col-sm-7 col-md-7", true, false, false);
			$houseNo = new Text(null, null, "houseNo", "form-control col-xs-1 col-sm-1 col-md-1", false, true, false);
			$postalCode = new Text(null, "Postcode", "postalCode", null, true, true, false);
			$this->getCreateScreen()->createPopUp(array($errMsg, $nameTextField, $street, $houseNo, $postalCode, $saveButton), "Gebouw toevoegen", "AddLocatie", null, null, null, "#Locatie");
		}
	}
	
	public function createEditLocationPopUp() {
		$columnList = array("Naam","Omschrijving","Capaciteit");
		$valueList = null;
		
		$screenName = "Zalen";
		$errMsg = new Span('',null,'errMsgDeleteRoom','errorMsg',true,true,null);
		$locationName = new Identifier(null, "Locatie", "LocationName", null, true, true, true);
		$cityName = new Identifier(null, "Plaats", "cityName", null, true, true, true);
		$buildingName = new Identifier(null, "Gebouw", "BName", null, true, true, true);
		$listBox = new Listbox(null, null, null, "col-xs-3 col-md-3 col-sm-3 singleSelect", false, false, $columnList, $valueList, $screenName . "ListBox");
		$buttonAdd = new Button("Toevoegen", null, "buttonAdd" . $screenName , "form-control btn btn-default col-xs-3 col-md-3 col-sm-3 popupButton", false, false, "#popUpAdd" . $screenName);
		$buttonChange = new Button("Aanpassen", null, "buttonEdit" . $screenName, "form-control btn btn-default col-xs-3 col-md-3 col-sm-3 popupButton onSelected", false, false, "#popUpUpdate" . $screenName);
		$buttonDelete = new Button("Verwijderen", null, "buttonDelete" . $screenName, "form-control btn btn-default col-xs-3 col-md-3 col-sm-3 popupButton onSelected", false, false, "#popUpDelete" . $screenName);
		
				
		$this->getCreateScreen()->createPopUp(array($errMsg, $locationName, $cityName, $buildingName,$listBox, $buttonAdd, $buttonChange,$buttonDelete),"Zaal beheren","UpdateLocatie",null,null,null, "#Locatie");
	
	}
	
	public function createCreateRoomPopUp() {
		$buildingName = new Identifier(null, "Gebouw", "BName", null, true, true, true);
		$roomName = new Text(null, "Naam", "roomName", null, true, true, true);
		$roomDescription = new Text(null, "Omschrijving", "roomDescription", null, true, true, true);
		$roomCapacity = new Text(null, "Capacity", "roomCapacity", null, true, true, true);
		$errMsg = new Span('',null,'errMsgCreateRoom','errorMsg',true,true,null);
		$saveButton = new Submit("Opslaan", null, "saveRoomButton", null, true, true);

		$this->getCreateScreen()->createPopUp(array($errMsg,$buildingName,$roomName, $roomDescription, $roomCapacity,$saveButton),"Zaal toevoegen","AddZalen",null,null,null, "#Locatie");
	}
	
	
	public function createEditRoomPopUp() {
		$errMsg = new Span('',null,'errMsgUpdateRoom','errorMsg',true,true,null);
		$locationName = new Identifier(null, "Locatie", "LocationName", null, true, true, true);
		$cityName = new Identifier(null, "Plaats", "cityName", null, true, true, true);
		$buildingName = new Identifier(null, "Gebouw", "BName", null, true, true, true);
		$roomName = new Text(null, "Naam", "roomName", null, true, true, true);
		$roomDescription = new Text(null, "Omschrijving", "roomDescription", null, true, true, true);
		$roomCapacity = new Text(null, "Capacity", "roomCapacity", null, true, true, true);
		$saveButton = new Submit("Opslaan", null, "saveRoomButton", null, true, true);
	
		$this->getCreateScreen()->createPopUp(array($errMsg, $locationName, $cityName, $buildingName, $roomName, $roomDescription, $roomCapacity,$saveButton),"Zaal aanpassen","UpdateZalen",null,null,null, "#Locatie");
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


	public function getCongressLocation(){
		$queryGetLocation = "SELECT LocationName, City
							 FROM Congress
							 WHERE CongressNo = ?";
		$params = array($_SESSION['congressNo']);
		$result = $this->database->sendQuery($queryGetLocation, $params);
		if ($result){
			if ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
				return $row;
			}
		}
	}


}

?>