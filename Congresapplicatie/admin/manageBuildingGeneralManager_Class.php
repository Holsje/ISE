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
				echo '<div>';
					$this->createPreviousScreenButton();
				echo '</div>';
				$this->createEditLocationScreen();
				$this->createManagementScreen($this->columnList, $this->allBuildingsByLocation, "BuildingGM", null);
			echo '</div>';
		}
		
		public function createCreateBuildingPopUp() {
			$nameTextField = new Text(null, "Naam", "buildingName", null, true, true, true);
			$street = new Text(null, "Straat + Huisnr", "streetName", "form-control col-xs-7 col-sm-7 col-md-7", true, false, false);
			$houseNo = new Text(null, null, "houseNo", "form-control col-xs-1 col-sm-1 col-md-1", false, true, false);
			$postalCode = new Text(null, "Postcode", "postalCode", null, true, true, false);
			$saveButton = new Submit("Opslaan", null, "saveBuildingButton", null, true, true);
			$this->getCreateScreen()->createPopUp(array($nameTextField, $street, $houseNo, $postalCode, $saveButton),"Gebouw toevoegen","AddBuildingGM",null,null,null, "#Locatie");
		}
		
		public function createDeleteBuildingPopUp() {
			$text = new Span("Wilt u zeker dat u de selectie wilt verwijderen?" ,null, "confirmationText", null, true, true);
			$cancelButton = new Button("Annuleren", "cancelDeleteBuilding", "cancelButton", "form-control btn btn-default popupButton col-xs-6 col-sm-6 col-md-6", true, false, "#popUpDeleteLocatie");
			$confirmButton = new Submit("Bevestigen", "confirmDeleteBuilding", "confirmButton", "form-control btn btn-default popupButton col-xs-6 col-sm-6 col-md-6", false, true);
			$this->getCreateScreen()->createPopUp(array($text, $cancelButton, $confirmButton), "Gebouw verwijderen", "DeleteBuildingGM", null, null, null, "#Locatie");
		}
		
		public function createEditLocationScreen() {
			echo '<form name="formEditLocation" class="form-horizontal col-md-offset-1 col-sm-offset-1 col-xs-offset-1 col-xs-10 col-sm-10 col-md-10 formEditLocation" method="POST" action="'. $_SERVER['PHP_SELF'] .'">';
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
		
		private function createPreviousScreenButton() {
		}
	}
?>