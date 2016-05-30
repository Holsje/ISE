<?php

require_once('Management.php');
class ManageLocations extends Management {
	
	private $columnList;
	private $valueList;
	private $buttonArray;
	private $currentLocationName;
	private $currentLocationCity;
	
	public function __construct($currentLocationName, $currentLocationCity, $columnList, $buttonArray){
		parent::__construct();
		$this->currentLocationName = $currentLocationName;
		$this->currentLocationCity = $currentLocationCity;
		$this->columnList = $columnList;
		$_SESSION['locationValueList'] = $this->getBuildingsByLocation($this->currentLocationName, $this->currentLocationCity);
		$this->buttonArray = $buttonArray;
    }
	
	public function createManagementScreen($columnList, $valueList, $buttonArray) {
		parent::createManagementScreen($columnList, $valueList, $buttonArray);
    }
	
	public function createLocationScreen() {
		echo '<div class="locationContent">';
		$locationNamesWithCity = array();
		$allLocations = $this->getAllLocations();
		for($i = 0; $i < sizeof($allLocations); $i++) {
			array_push($locationNamesWithCity, $allLocations[$i][0] . ' - ' . $allLocations[$i][1]);
		}
		$selectLocations = new Select($_SESSION['selectedLocation'], "Locatie", null, "form-control col-xs-10 col-sm-10 col-md-10 locationSelect", true, true, $locationNamesWithCity, null);
		echo '<div class="col-md-offset-1 col-sm-offset-1 col-xs-offset-1 col-xs-10 col-sm-10 col-md-10">';
			echo $selectLocations->getObjectCode();
		echo '</div>';
		$this->createManagementScreen($this->columnList, $_SESSION['locationValueList'], null);
		echo '</div>';
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