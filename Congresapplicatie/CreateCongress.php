<?php
	require_once('pageConfig.php');
	$css = '<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">';
	$javaScript = '<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>';
	$javaScript .= '<script src="js/CreateCongress.js"></script>';
	
	topLayoutMannagement('createCongress',$css,$javaScript);
?>

<div class="container col-xs-12 col-sm-12 col-md-12">
	<form name="CreateCongressForm" class="content col-xs-8 col-sm-8 col-md-8" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return validateForm()">
		<div class="row">
			<label class="col-xs-6  col-sm-6 col-md-6">Congressname:</label> <input type="text" id="CongressName" name="CongressName" class="col-xs-6  col-sm-6 col-md-6" required>
		</div>
		<div class="row">
			<label class="col-xs-6  col-sm-6 col-md-6">Location:</label> <input type="text" id="Location" name="Location" class="col-xs-6  col-sm-6 col-md-6" required>
		</div>
		<div class="row">
			<label class="col-xs-6  col-sm-6 col-md-6">Subject:</label>	<input type="text" id="Subject" name="Subject" class="col-xs-6  col-sm-6 col-md-6" list="Subjects" required>			
				<datalist id="Subjects">
					<?php 
						foreach($CongressApplicationDB->getSubjects() as $array) {
							echo"<option value='" . $array . "'>" . $array . "</option>";
						}
					?>
				</datalist>
		</div>
		<div class="row">
			<label class="col-xs-6  col-sm-6 col-md-6">StartDate:</label> <input type="text" id="StartDate" name="StartDate" class="col-xs-6  col-sm-6 col-md-6 datepicker" required>
		</div>
		<div class="row">
			<label class="col-xs-6  col-sm-6 col-md-6">EndDate:</label> <input type="text" id="EndDate" name="EndDate" class="col-xs-6  col-sm-6 col-md-6 datepicker" required>
		</div>
		<div class="row">
			<div class="col-xs-6  col-sm-6 col-md-6"> </div> <input type="submit" value="submit" class="col-xs-6  col-sm-6 col-md-6">
		</div>
	</form>
</div>

<?php	

	bottomLayout();
?>