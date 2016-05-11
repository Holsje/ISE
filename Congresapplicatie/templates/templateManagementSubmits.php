<?php
	if(isset($_POST['toevoegen'])) {
		if($_POST['toevoegen'] == "create(Naam)") {
			$params = array( 
                array($_POST[(naamVeld)],SQLSRV_PARAM_IN),etc
               );
			$manage(Naam)->addRecord("spInsert(Naam)",$params);
		}
	}	
	else if(isset($_POST['bewerken'])) {
	   	//Zie bovenstaande functie + Die()
        $manage(Naam)->changeRecord("spInsert(Naam)",$params);
        die();
	}	
	
	else if(isset($_POST['verwijderen'])) {
		//Zie bovenstaande functie + Die()
        echo $manageCongress->deleteRecord(Query,Params)
		die();
	}
	
?>
