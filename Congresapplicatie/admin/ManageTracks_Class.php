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
        $nameObject = new Text('', 'Naam', 'trackName', '', true, true, true);
        $descriptionObject = new Text('', 'Omschrijving', 'trackDescription', '', true, true, false);
        $submitObject = new Submit('Opslaan', '', 'createTrack', '', true, true);
        $this->createScreen->createPopup(array($nameObject, $descriptionObject, $submitObject), 'Track toevoegen', 'AddTracks', null, true, true, true, '#Tracks');
    }

    public function createEditTrackScreen(){
        $nameObject = new Text('', 'Naam', 'trackName', '', true, true, true);
        $descriptionObject = new Text('', 'Omschrijving', 'trackDescription', '', true, true, false);
        $errMsg = new Span('',null,'errMsgTrack','errorMsg',true,true,null);
        $submitObject = new Button('Opslaan', '', 'editTrack', '', true, true, '#popUpUpdateTracks');
        $this->createScreen->createPopup(array($nameObject, $descriptionObject, $errMsg, $submitObject), 'Track aanpassen', 'UpdateTracks', null, true, true, true, '#Tracks');
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

    public function changeRecord($params){
        $sqlUpdateTrack = "UPDATE Track SET TName = ? AND Description = ? WHERE CongressNo = ? AND TrackNo = ?";
        $result = $this->database->sendQuery($sqlUpdateTrack, $params);
        if ($result != null){
            $error['err'] = $result;
            return json_encode($error);
        }
    }

    public function addRecord($params){
        $sqlGetTrackNo = "SELECT MAX(TrackNo) AS TrackNo FROM Track WHERE CongressNo = ?";
        $resultGetTrackNo = $this->database->sendQuery($sqlGetTrackNo, array($params[0]));
        if ($resultGetTrackNo){
            while ($row = sqlsrv_fetch_array($resultGetTrackNo, SQLSRV_FETCH_ASSOC)){
                $trackNo = $row['TrackNo'];
            }
        }

        $paramsInsertTrack = $params;
        array_push($paramsInsertTrack, $trackNo);

        $sqlInsertTrack = "INSERT INTO Track(CongressNo, TName, Description, TrackNo) VALUES (?, ?, ?, ?)";
        $resultInsertTrack = $this->database->sendQuery($sqlInsertTrack, $paramsInsertTrack);
        if ($resultInsertTrack != null){
            $error['err'] = $resultInsertTrack;
            return json_encode($error);
        }
    }
}
?>