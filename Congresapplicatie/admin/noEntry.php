<?php
    require_once('SessionHandler.php');
    sessionHandler(false, false);
    require_once('../pageConfig.php');
    topLayoutManagement("Geen Toegang", "", "");
?>

<div class="row">
    <div class="container col-sm-12 col-md-12 col-xs-12">
        <div class="content col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-xs-8 col-xs-offset-2">
            <h4>U heeft geen toegang tot of rechten voor deze pagina!</h4>
        </div>
    </div>
</div>
<?php
bottomLayout();
?>
