<?php
	include('admin/sessionHandler.php');
	sessionHandlerWeb(false);
	require_once('pageConfig.php');	
	require_once('database.php');
	require_once('connectDatabase.php');
	require_once('confirm_Class.php');
	global $server, $databaseName, $uid, $password;
	$dataBase = new Database($server,$databaseName,$uid,$password);
	$confirm = new Confirmation($_SESSION['congressNo'], $dataBase);
	topLayout('Bevestiging',"css/confirm.css", "js/confirm.js");
?>

<div class="row">
	<div class="container col-sm-12 col-md-12 col-xs-12">
		<div class="content col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
			<div class="row">
				<div id="Content" style="background-color:#FFF;" class="col-sm-12 col-md-12 col-xs-12">	
					<?php $confirm->createConfirmationScreen(); 
						  require_once('confirm_Submit.php');
					?>
				</div>
			</div>
		</div>
	</div>
</div>


