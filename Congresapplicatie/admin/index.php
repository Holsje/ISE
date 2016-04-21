<?PHP
require_once('../pageConfig.php');

topLayoutManagement('Index','','');
?>

<div class="row">
    <div class="container col-sm-12 col-md-12 col-xs-12">
        <div class="content col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-xs-8 col-xs-offset-2">
             <div class="row">
                 <div class="popupWindow col-md-offset-3 col-md-6 col-sm-offset-3 col-sm-6 col-xs-offset-3 col-xs-6">
                     <h1>Inloggen</h1>
                     <form class="form-horizontal col-md-offset-1 col-sm-offset-1 col-xs-offset-1 col-xs-8 col-sm-10 col-md-10">
                         <div class="form-group">
                             <label class="control-label col-xs-8 col-sm-4 col-md-4">Gebruikersnaam</label>
                             <div class="col-xs-12 col-sm-8 col-md-8">
                                 <input type="text" class="form-control"/>
                             </div>
                         </div>
                         <div class="form-group">
                             <label class="control-label col-xs-8 col-sm-4 col-md-4">Wachtwoord</label>
                             <div class="col-xs-12 col-sm-8 col-md-8">
                                 <input type="text" class="form-control"/>
                             </div>
                         </div>
                         <div class="form-group">
                             <div class="col-md-4 col-sm-4 col-xs-4 pull-right ">
                                 <button type="submit" class="btn btn-default form-control">Inloggen</button>
                             </div>
                         </div>
                     </form>
                 </div>
             </div>

        </div>

    </div>
</div>
<?php
bottomLayout();
?>