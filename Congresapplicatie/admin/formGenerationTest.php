<?php
require_once('Management.php');
require_once('ScreenObject.php');
require_once('Text.php');
require_once('Button.php');
require_once('Submit.php');
require_once('Select.php');
require_once('Password.php');
require_once('ListAddButton.php');
require_once('Listbox.php');

require_once('../connectDatabase.php');

require_once('../pageConfig.php');



$management = new Management($server,
    $database,
    $uid,
    $password);



$input1 = new Text(null,"DikkeShit","dikkeShitName",null, true, false, true);
$input2 = new Text(null,null,"dikkeShitName", "form-control col-xs-1 col-sm-1 col-md-1", false, true, true);
$input3 = new Text(null,"DikkeShit","dikkeShitName", null, true, true, true);
$input4 = new Text(null,"DikkeShit","dikkeShitName", null, true, true, true);
$input5 = new Text(null,"DikkeShit","dikkeShitName", null, true, true, true);
$input6 = new Text(null,"DikkeShit","dikkeShitName", null, true, true, true);
$input7 = new Text(null,"DikkeShit","dikkeShitName", null, true, true, true);
$input8 = new Text(null,"DikkeShit","dikkeShitName", null, true, true, true);
$input9 = new Button("Button", null, "Button",  null, true, true, null);
$input10 = new Submit("Button2", null, "Button2",  null, true, true);
$input11 = new Password("Password", "Password", "Password",  null, true, true);
$input12 = new Select("Onno4", "Select", "Select",  null, true, true, array("Onno1", "Onno2", "Onno3", "Onno4"), null);
$input14 = new ListAddButton("+", null, "ListAddButton", null, true, true, "DANIELZNSHIT");
$input13 = new Select("Select", "Select", "Select",  null, true, true, array("Onno1", "Onno2", "Onno3", "Onno4"), $input14);
$input15 = new Listbox(null, null, null, null, true, true, array("Naam", "Achternaam", "Leeftijd"), array(1=>array("Niels", "Bergervoet", "21"), 2=>array("Erik", "Evers", "19"), 3=>array("Daniel", "de Jong", "23")), "congresListBox");


topLayoutManagement('TestForm','','<script type="text/javascript" src="../js/congressManagement.js"></script>');


$management->createPopup(array($input1,$input2,$input3,$input4,$input5,$input6,$input7,$input8,$input9, $input10, $input11, $input12,$input13, $input15),"Dikke title","dikkeTitle",null);
?>



<div class="row">
    <div class="container col-sm-12 col-md-12 col-xs-12">
        <div class="content col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-xs-8 col-xs-offset-2">
<?php
//$management->createForm(array($input1,$input2,$input3,$input4,$input5,$input6,$input7,$input8,$input9, $input10, $input11, $input12,$input13, $input15));
?>

        </div>
    </div>
</div>
<?php
bottomLayout();
?>