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


$input1 = new Text(null,"DikkeShit","dikkeShitName", $management->classDictionary["Text"],true);
$input2 = new Text(null,"DikkeShit","dikkeShitName", $management->classDictionary["Text"],true);
$input3 = new Text(null,"DikkeShit","dikkeShitName", $management->classDictionary["Text"],true);
$input4 = new Text(null,"DikkeShit","dikkeShitName", $management->classDictionary["Text"],true);
$input5 = new Text(null,"DikkeShit","dikkeShitName", $management->classDictionary["Text"],true);
$input6 = new Text(null,"DikkeShit","dikkeShitName", $management->classDictionary["Text"],true);
$input7 = new Text(null,"DikkeShit","dikkeShitName", $management->classDictionary["Text"],true);
$input8 = new Text(null,"DikkeShit","dikkeShitName", $management->classDictionary["Text"],true);
$input9 = new Button("Button", null, "Button",  $management->classDictionary["Button"], null);
$input10 = new Submit("Button2", null, "Button2",  $management->classDictionary["Submit"]);
$input11 = new Password("Password", "Password", "Password",  $management->classDictionary["Password"]);
$input12 = new Select("Onno4", "Select", "Select",  $management->classDictionary["SelectWithoutButton"], array("Onno1", "Onno2", "Onno3", "Onno4"), null);
$input14 = new ListAddButton("+", null, "ListAddButton", $management->classDictionary["ListAddButton"], "DANIELZNSHIT");
$input13 = new Select("Select", "Select", "Select",  $management->classDictionary["SelectWithButton"], array("Onno1", "Onno2", "Onno3", "Onno4"), $input14);
$input15 = new Listbox(null, null, null, null, array("Naam", "Achternaam", "Leeftijd"), array(1=>array("Niels", "Bergervoet", "21"), 2=>array("Erik", "Evers", "19"), 3=>array("Daniel", "de Jong", "23")), "congresListBox");

topLayoutManagement('TestForm','','<script type="text/javascript" src="../js/congressManagement.js"></script>');
?>



<div class="row">
    <div class="container col-sm-12 col-md-12 col-xs-12">
        <div class="content col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-xs-8 col-xs-offset-2">
<?php
$management->createForm(array($input1,$input2,$input3,$input4,$input5,$input6,$input7,$input8,$input9, $input10, $input11, $input12,$input13, $input15))
?>

        </div>
    </div>
</div>
<?php
bottomLayout();
?>