<?PHP
require_once('../pageConfig.php');

topLayoutManagement('Index','','');
?>

<div class="row">
    <div class="container col-sm-12 col-md-12">
        <div class="content col-sm-8 col-sm-offset-2">
             <div class="row">
                 <h1 class="col-sm-offset-1 managementTitle">hallo</h1>
                 <button class="popupButton" data-file="#testPop">BUTTON</button>
             </div>

        </div>

    </div>
</div>
<div id="testPop" class="popup col-sm-12 col-md-12">
    <div class="popupWindow col-md-offset-3 col-md-6">
        <h1 class="col-md-8">popup</h1>
        <button class="closePopup glyphicon glyphicon-remove" data-file="#testPop"></button>
    </div>
</div>
<?php
bottomLayout();
?>