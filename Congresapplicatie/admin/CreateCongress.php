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
		<div class="popupWindow col-md-offset-3 col-md-6 col-sm-offset-3 col-sm-6 col-xs-offset-3 col-xs-6">
			<h1 class="col-md-8">Create congress</h1>
			<button type="button" class="closePopup glyphicon glyphicon-remove" data-file="#createCongress"></button>
	   
					<form name="CreateCongressForm" class="form-horizontal col-md-offset-1 col-sm-offset-1 col-xs-offset-1 col-xs-8 col-sm-10 col-md-10" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return validateForm()">
						<div class="form-group">
							<label class="control-label col-xs-8 col-sm-4 col-md-4">Congressname:</label> 
							<div class="col-xs-12 col-sm-8 col-md-8">
								<input type="text" id="CongressName" name="CongressName" class="form-control" required>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-xs-8 col-sm-4 col-md-4">Location:</label> 
							<div class="col-xs-12 col-sm-8 col-md-8">
								<input type="text" id="Location" name="Location" class="form-control" required>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-xs-8 col-sm-4 col-md-4">Subject:</label>
							<div class="col-xs-12 col-sm-8 col-md-8">
								<select id="Subject" name="Subject" class="form-control">
									<?php 
										foreach($CongressApplicationDB->getSubjects() as $array) {
											echo"<option value='" . $array . "'>" . $array . "</option>";
										}
									?>
								</select>
							</div>
							<div class="col-xs-1 col-sm-1 col-md-1">
								<button type="button" class="popupButton" data-file="#addSubject">+</button>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-xs-8 col-sm-4 col-md-4">StartDate:</label> 
							<div class="col-xs-12 col-sm-8 col-md-8">
								<input type="text" id="StartDate" name="StartDate" class="form-control datepicker" required onchange="validateDate()">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-xs-8 col-sm-4 col-md-4">EndDate:</label> 
							<div class="col-xs-12 col-sm-8 col-md-8">
								<input type="text" id="EndDate" name="EndDate" class="form-control datepicker" required onchange="validateDate()">
							</div>
						</div>
						<div class="form-group">
							<div class="control-label col-xs-8 col-sm-4 col-md-4">
								<label class="control-label col-xs-12 col-sm-12 col-md-12" style="display:none; color:red;" id="errorMsg">* error</label>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-4 pull-right">
								<button type="submit" value="Toevoegen" name="Submit" class="btn btn-default form-control">Submit form</button>
							</div>
						</div>
					</form>
				
		 </div>
	</div>
	<div id="addSubject" class="popup col-sm-12 col-md-12 col-xs-12">
		<div class="popupWindow col-md-offset-3 col-md-6 col-sm-offset-3 col-sm-6 col-xs-offset-3 col-xs-6">
			<h1 class="col-md-8">Create new subject</h1>
			<button type="button" class="closePopup glyphicon glyphicon-remove" data-file="#addSubject"></button>
			
			<form name="addSubjectForm" class="form-horizontal col-md-offset-1 col-sm-offset-1 col-xs-offset-1 col-xs-8 col-sm-10 col-md-10" method="#" action="#" onsubmit="return false">
				<div class="form-group">
					<label class="control-label col-xs-8 col-sm-4 col-md-4">Subject:</label> 
					<div class="col-xs-12 col-sm-8 col-md-8">
						<input type="text" id="newSubject" name="newSubject" class="form-control" required>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-4 pull-right">
						<button type="button" value="Toevoegen" name="Submit" class="btn btn-default form-control" onclick="addSubject()">Submit form</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<?php	
	}
	bottomLayout();
?>