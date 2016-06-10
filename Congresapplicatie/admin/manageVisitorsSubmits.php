<?php

$manageVisitors = new ManageVisitors();

    if (isset($_POST['getVisitorInfo'])){
        echo $manageVisitors->getEventsOfVisitor($_POST['personNo']);
        die();
    }

    if (isset($_POST['saveVisitor'])){
        $sqlUpdataHasPaid = "UPDATE VisitorOfCongress
                             SET HasPaid = ?
                             WHERE PersonNo = ? AND CongressNo = ?";
        if ($_POST['visitorHasPaid'] == "Ja"){
            $hasPaid = 1;
        }
        else if ($_POST['visitorHasPaid'] == "Nee"){
            $hasPaid = 0;
        }
        $params = array($hasPaid, $_POST['visitorPersonNo'], $_SESSION['congressNo']);

        $manageVisitors->getDatabase()->sendQuery($sqlUpdataHasPaid, $params);
    }

?>
