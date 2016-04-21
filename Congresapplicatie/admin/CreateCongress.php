<?php
	require_once('../pageConfig.php');
	$css = '<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">';
	$javaScript = '<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>';
	$javaScript .= '<script src="../js/globalFunctions.js"></script>';
	$javaScript .= '<script src="../js/CreateCongress.js"></script>';
	
	topLayoutManagement('createCongress',$css,$javaScript);
	$succesCreateCongress = false;
	if(isset($_POST['Submit'])) {
		include("createCongressFunctions.php");
	}
	
	if($succesCreateCongress) {		
		echo "Congress has been made.";	
	}
	
	else {
	?>
	
	<div id="createCongress" class="popup col-sm-12 col-md-12 col-xs-12">
		<div class="popupWindow col-md-offset-3 col-md-6">
			<h1 class="col-md-8">Create congress</h1>
			<button type="button" class="closePopup glyphicon glyphicon-remove" data-file="#testPop" onclick="$('#createCongress').toggle('fade');"></button>
	   
					<form name="CreateCongressForm" class="content form-horizontal col-xs-8 col-sm-6 col-md-6 col-xs-offset-2 col-sm-offset-3 col-xs-offset-3" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return validateForm()">
						<div class="form-group">
							<label class="control-label col-xs-5 col-sm-4 col-md-3">Congressname:</label> 
							<div class="col-xs-7 col-sm-8 col-md-9">
								<input type="text" id="CongressName" name="CongressName" class="form-control" required>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-xs-5 col-sm-4 col-md-3">Location:</label> 
							<div class="col-xs-7 col-sm-8 col-md-9">
								<input type="text" id="Location" name="Location" class="form-control" required>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-xs-5 col-sm-4 col-md-3">Subject:</label>
							<div class="col-xs-6 col-sm-7 col-md-8">
								<select id="Subject" name="Subject" class="form-control">
									<?php 
										foreach($CongressApplicationDB->getSubjects() as $array) {
											echo"<option value='" . $array . "'>" . $array . "</option>";
										}
									?>
								</select>
							</div>
							<div class="col-xs-1 col-sm-1 col-md-1">
								<button type="button" onclick="$('#addSubject').toggle('fade');">+</button>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-xs-5 col-sm-4 col-md-3">StartDate:</label> 
							<div class="col-xs-7 col-sm-8 col-md-9">
								<input type="text" id="StartDate" name="StartDate" class="form-control datepicker" required onchange="validateDate()">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-xs-5 col-sm-4 col-md-3">EndDate:</label> 
							<div class="col-xs-7 col-sm-8 col-md-9">
								<input type="text" id="EndDate" name="EndDate" class="form-control datepicker" required onchange="validateDate()">
							</div>
						</div>
						<div class="form-group">
							<div class="col-xs-7 col-sm-8 col-md-9">
								<label class="control-label col-xs-12 col-sm-12 col-md-12" style="display:none; color:red;" id="errorMsg">* error</label>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-4 pull-right">
								<button type="submit" value="Toevoegen" name="Submit" class="btn btn-default form-control">Submit form</button>
							</div>
						</div>
				</div>
				
		 </div>
	</div>
	
	<div id="addSubject" class="popup col-sm-12 col-md-12">
		<div class="popupWindow col-md-offset-3 col-md-6">
			<h1 class="col-md-8">popup</h1>
			<button type="button" class="closePopup glyphicon glyphicon-remove" data-file="#testPop" onclick="$('#addSubject').toggle('fade');"></button>
		</div>
	</div>

	<?php	
	}
	bottomLayout();
?>