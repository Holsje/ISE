<?php
require_once('Management(Naam)_Class.php');
$manage(Naam) = new ManageNaam();
include('Manage(Naam)Submits.php');

//$css voeg css variable toe
//$js voeg js variable toe
topLayoutManagement('Beheren (Naam)',$css,$js);
?>
    <div class="row">
        <div class="container   col-md-12 col-xs-12">
            <div class="content col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-xs-8 col-xs-offset-2">
                <?php
                    $manage(Naam)->createManagementScreen(>>array van kolomnamen in datatable<<, $manage(Naam)->get(DataWaardes());
                ?>
            </div>
        </div>
    </div>

    <?php 
        $manage(Naam)->createCreate(Naam)Screen(); 
        $manage(Naam)->createEdit(Naam)Screen(); 
    
    bottomLayout();

?>
