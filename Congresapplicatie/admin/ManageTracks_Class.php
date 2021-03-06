<?php
/**
 * Created by PhpStorm.
 * User: erike
 * Date: 1-6-2016
 * Time: 15:41
 */
require_once('Management.php');
class ManageTracks extends Management
{
    public function __construct(){
        parent::__construct();
    }

    public function createManagementScreen($columnList, $valueList){
        parent::createManagementScreen($columnList, $valueList, "Tracks", null);
    }

    public function getTracks() {
        $result = parent::getDatabase()->sendQuery("SELECT * FROM Track WHERE CongressNo = ?", array($_SESSION['congressNo']));

        if ($result){
            $array = array();
            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
            {

                $array[$row['TrackNo']] = array($row['TrackNo'], $row['TName'], $row['Description']);
            }
            return $array;
        }
        return false;
    }

    public function createCreateTrackScreen(){

        $submitObject = new Submit('Opslaan', null, 'createTrack', null, true, true);
        if (isset($_SESSION['errorMsgInsertTrack'])) {

            $nameObject = new Text($_POST['trackName'], 'Naam', 'trackName', null, true, true, true);
            $descriptionObject = new Text($_POST['trackDescription'], 'Omschrijving', 'trackDescription', null, true, true, false);
            $errMsg = new Span($_SESSION['errorMsgInsertTrack'], null, 'errMsgTrack', 'errorMsg', true, true, null);
            unset($_SESSION['errorMsgInsertTrack']);
            $this->createScreen->createPopup(array($errMsg, $nameObject, $descriptionObject, $submitObject), 'Track toevoegen', 'AddTracks', null, true, 'show', '#Tracks');
        }
        else{
            $nameObject = new Text(null, 'Naam', 'trackName', null, true, true, true);
            $descriptionObject = new Text(null, 'Omschrijving', 'trackDescription', null, true, true, false);
            $errMsg = new Span(null, null, 'errMsgInsertTrack', 'errorMsg', true, true, null);
            $this->createScreen->createPopup(array($errMsg, $nameObject, $descriptionObject, $submitObject), 'Track toevoegen', 'AddTracks', null, true, '','#Tracks');
        }

    }

    public function createEditTrackScreen(){

        $submitObject = new Submit('Opslaan', null, 'editTrack', null, true, true);
        if (isset($_SESSION['errorMsgEditTrack'])) {
            $nameObject = new Text($_POST['trackName'], 'Naam', 'trackName', null, true, true, true);
            $descriptionObject = new Text($_POST['trackDescription'], 'Omschrijving', 'trackDescription', null, true, true, false);
            $errMsg = new Span($_SESSION['errorMsgEditTrack'], null, 'errMsgTrackUpdateTrack', 'errorMsg', true, true, null);
            unset($_SESSION['errorMsgEditTrack']);
            $this->createScreen->createPopup(array($errMsg, $nameObject, $descriptionObject, $submitObject), 'Track aanpassen', 'UpdateTracks', null, true, 'show', '#Tracks');
        }
        else{
            $nameObject = new Text(null, 'Naam', 'trackName', null, true, true, true);
            $descriptionObject = new Text(null, 'Omschrijving', 'trackDescription', null, true, true, false);
            $errMsg = new Span(null, null, 'errMsgUpdateTrack', 'errorMsg', true, true, null);
            $this->createScreen->createPopup(array($errMsg, $nameObject, $descriptionObject, $submitObject), 'Track aanpassen', 'UpdateTracks', null, true, '', '#Tracks');
        }
    }


    public function getTrackInfo($congressNo, $trackNo) {
        $sqlTrack = 'SELECT *
                            FROM Track
                            WHERE CongressNo = ? AND TrackNo = ?';
        $params = array($congressNo, $trackNo);

        $resultTrack = $this->database->sendQuery($sqlTrack, $params);
        if ($resultTrack){
            if ($row = sqlsrv_fetch_array($resultTrack, SQLSRV_FETCH_ASSOC)){
                return json_encode($row, JSON_FORCE_OBJECT);
            }
        }
    }

    public function editTrack($params){
        $sqlUpdateTrack = "UPDATE Track SET TName = ?, Description = ? WHERE CongressNo = ? AND TrackNo = ?";
        $result = $this->database->sendQuery($sqlUpdateTrack, $params);
        if (is_string($result)){
            $_SESSION['errorMsgEditTrack'] = $result;
        }
    }

    public function createTrack($params){
        $sqlGetTrackNo = "SELECT MAX(TrackNo) AS TrackNo FROM Track WHERE CongressNo = ?";
        $resultGetTrackNo = $this->database->sendQuery($sqlGetTrackNo, array($params[0]));
        if ($resultGetTrackNo){
            if ($row = sqlsrv_fetch_array($resultGetTrackNo, SQLSRV_FETCH_ASSOC)){
                $trackNo = $row['TrackNo'];
            }
            $trackNo++;
        }

        $paramsInsertTrack = $params;
        array_push($paramsInsertTrack, $trackNo);

        $sqlInsertTrack = "INSERT INTO Track(CongressNo, TName, Description, TrackNo) VALUES (?, ?, ?, ?)";
        $resultInsertTrack = $this->database->sendQuery($sqlInsertTrack, $paramsInsertTrack);
        if (is_string($resultInsertTrack)){
            $_SESSION['errorMsgInsertTrack'] = $resultInsertTrack;
        }
    }
}
?>