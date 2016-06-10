<?php
require_once('Management.php');

class ManageVisitors extends Management{

    public function __construct(){
        parent::__construct();
    }

    public function getVisitorsOfCongress(){
        $sql= ' SELECT P.PersonNo, C.Price, P.FirstName, P.LastName, P.MailAddress, VOC.HasPaid, (C.Price + SUM(E.Price)) AS TotalPrice
                FROM Person P INNER JOIN EventOfVisitorOfCongress EVC
                        ON P.PersonNo = EVC.PersonNo INNER JOIN Congress C
                            ON EVC.CongressNo = C.CongressNo INNER JOIN Event E
                                ON EVC.CongressNo = E.CongressNo AND EVC.EventNo = E.EventNo INNER JOIN VisitorOfCongress VOC
                                    ON EVC.PersonNo = VOC.PersonNo AND EVC.CongressNo = VOC.CongressNo
                WHERE C.CongressNo = ?
                GROUP BY P.PersonNo, P.FirstName, P.LastName, P.MailAddress, C.Price, VOC.HasPaid';
        $params = array($_SESSION['congressNo']);
        $result = $this->database->sendQuery($sql, $params);
        $returnArray = array();
        if($result){
            while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
                if ($row['HasPaid'] == 0){
                    $hasPaid = "Nee";
                }
                else{
                    $hasPaid = "Ja";
                }
                array_push($returnArray,array($row['PersonNo'], number_format($row['Price'],2,',','.'), $row['FirstName'], $row['LastName'], $row['MailAddress'], $hasPaid ,number_format($row['TotalPrice'],2,',','.')));
            }
        }
        return $returnArray;
    }

    public function getEventsOfVisitor($personNo){
        $sql= ' SELECT P.PersonNo, E.EName, E.Price
                FROM Event E INNER JOIN EventOfVisitorOfCongress EVC
                    ON E.CongressNo = EVC.CongressNo AND E.EventNo = EVC.EventNo INNER JOIN Person P
                        ON P.PersonNo = EVC.PersonNo
                WHERE P.PersonNo = ? AND E.CongressNo = ?';
        $params = array($personNo, $_SESSION['congressNo']);
        $result = $this->database->sendQuery($sql, $params);
        $returnArray = array();
        if($result){
            while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
                array_push($returnArray,array($row['EName'], number_format($row['Price'],2,',','.')));
            }
        }
        return json_encode($returnArray);
    }

    public function createVisitorsOfCongressScreen(){
        $columnList = array("PersonNo", "Prijs", "Voornaam", "Achternaam", "Mailadres", "Betaald", "Prijs");
        $valueList = $this->getVisitorsOfCongress();
        $listboxVisitors = new Listbox(null, null, null, "", true, true, $columnList, $valueList, "VisitorsListBox");
        $buttonChangeVisitor = new Button("Aanpassen", "", "buttonEditVisitor", "form-control btn btn-default col-xs-3 col-md-3 col-sm-3 onSelected popUpButton", true, true, "#popUpUpdateVisitor");
        $this->createScreen->createForm(array($listboxVisitors, $buttonChangeVisitor), "VisitorsOfCongress", "", "Bezoekers");
    }

    public function createPopUpUpdateVisitor(){
        $columnList = array("Evenement", "Prijs");
        $valueList = null;
        $personNoObject = new Identifier("", "Persoonnummer", "visitorPersonNo", "", true, true, false);
        $firstNameObject = new Identifier("", "Voornaam", "visitorFirstName", "", true, true, false);
        $lastNameObject = new Identifier("", "Achternaam", "visitorLastName", "", true, true, false);
        $mailObject = new Identifier("", "Mailadres", "visitorMailAddress", "", true, true, false);
        $selectHasPaid = new Select("", "Betaald", "visitorHasPaid", "", true, true, array("Ja", "Nee"), null, false, "");
        $listboxEvents = new Listbox(null, null, null, "", true, true, $columnList, $valueList, "EventsOfVisitorListbox");
        $priceObject = new Identifier("", "Congresprijs", "congressPriceVisitor", "form-control pull-right priceObject", true, true, false);
        $totalPriceObject = new Identifier("", "Totaalprijs", "totalPrice", "form-control pull-right priceObject", true, true, false);
        $saveVisitor = new Submit("Opslaan", "", "saveVisitor", "", true, true);
        $this->createScreen->createPopup(array($personNoObject, $firstNameObject, $lastNameObject, $mailObject, $selectHasPaid, $listboxEvents, $priceObject, $totalPriceObject, $saveVisitor), "Bezoeker aanpassen", "UpdateVisitor", "", "", "", "#Bezoekers");
    }

}
