<?php
	require_once('pageConfig.php');
	$css = '<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">';
	$javaScript = '<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>';
	$javaScript .= '<script src="js/globalFunctions.js"></script>';
	$javaScript .= '<script src="js/CreateCongress.js"></script>';
	
	topLayoutMannagement('createCongress',$css,$javaScript);
?>

	<form name="CreateCongressForm" class="content col-xs-12 col-sm-12 col-md-12" action="#" onsubmit="return validateForm()">
		<div class="row">
			<label class="col-xs-offset-0 col-xs-6 col-sm-offset-2 col-sm-4 col-md-offset-2 col-md-4">Congressname:</label> <input type="text" id="CongressName" name="CongressName" class="col-xs-6  col-sm-4 col-md-4" required>
		</div>
		<div class="row">
			<label class="col-xs-offset-0 col-xs-6 col-sm-offset-2 col-sm-4 col-md-offset-2 col-md-4">Location:</label> <input type="text" id="Location" name="Location" class="col-xs-6  col-sm-4 col-md-4" required>
		</div>
		<div class="row">
			<label class="col-xs-offset-0 col-xs-6 col-sm-offset-2 col-sm-4 col-md-offset-2 col-md-4">Subject:</label>	<!--<input type="text" id="Subject" name="Subject" class="col-xs-6  col-sm-4 col-md-4" list="Subjects" required>			-->
				<select id="Subject" class="col-xs-5 col-sm-4 col-md-4">
					<?php 
						foreach($CongressApplicationDB->getSubjects() as $array) {
							echo"<option value='" . $array . "'>" . $array . "</option>";
						}
					?>
				</select>
				<button type="button" onclick="console.log('hai');">+</button>
		</div>
		<div class="row">
			<label class="col-xs-offset-0 col-xs-6 col-sm-offset-2 col-sm-4 col-md-offset-2 col-md-4">StartDate:</label> <input type="text" id="StartDate" name="StartDate" class="col-xs-6  col-sm-4 col-md-4 datepicker" required>
		</div>
		<div class="row">
			<label class="col-xs-offset-0 col-xs-6 col-sm-offset-2 col-sm-4 col-md-offset-2 col-md-4">EndDate:</label> <input type="text" id="EndDate" name="EndDate" class="col-xs-6  col-sm-4 col-md-4 datepicker" required>
		</div>
		<div class="row">
			<label class="col-xs-offset-0 col-xs-12 col-sm-offset-2 col-sm-8 col-md-offset-2 col-md-8 error" style="display:none; color:red;" id="errorMsg">* error</label>
		</div>
		<div class="row" style="margin-top:20px;">
			<input type="submit" value="Toevoegen" class="col-xs-offset-10 col-xs-2 col-sm-offset-8 col-sm-2 col-md-offset-8 col-md-2">
		</div>
</div>

<?php	

	bottomLayout();
?>