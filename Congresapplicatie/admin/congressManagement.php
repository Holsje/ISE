<?php
	require_once('../pageConfig.php');
	$css = '<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">';
	$css .= '<link rel="stylesheet" href="../css/congressManagement/congressManagement.css">';
	$javaScript = '<script src="../js/globalFunctions.js"></script>';
	$javaScript .= '<script type="text/javascript" src="../js/congressManagement.js"></script';

//DATA TABLES
	$css .= '<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/t/dt/dt-1.10.11/datatables.min.css">';
	$css .= '<link rel="stylesheet" type="text/css" href="../css/dataTables/css/dataTables.bootstrap.min.css">';
	$javaScript .= '<script type="text/javascript" src="https://cdn.datatables.net/t/dt/dt-1.10.11/datatables.min.js"></script>';
	$javaScript .= '<script type="text/javascript" src="../js/dataTables/js/jquery.dataTables.min.js"></script>';
	$javaScript .= '<script type="text/javascript" src="../js/dataTables/js/dataTables.bootstrap.min.js"></script>';
//
	
	topLayoutManagement('createCongress',$css,$javaScript);
?>

<div class="container col-sm-12 col-md-12">
	<div class="content col-sm-8 col-sm-offset-2">
		 <div class="row">
			<h1 class="col-sm-offset-1 managementTitle">Congres beheren</h1>
		 </div>
		 <form class="congresManagementContent">
			 <div class="row">
				<!--<select class="congresListBox col-xs-offset-2 col-xs-6 col-sm-offset-2 col-sm-6 col-md-offset-2 col-md-6" size="5">
					<option>1</option>
					<option>2</option>
				</select>-->
				<table id="congresListBox" class="display">
					<thead>
						<tr>
							<th>Congresnaam</th>
							<th>Locatie</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Congres 1</td>
							<td>Locatie 1</td>
						</tr>
						<tr>
							<td>Congres 2</td>
							<td>Locatie 2</td>
						</tr>
						<tr>
							<td>Congres 3</td>
							<td>Locatie 3</td>
						</tr>
					</tbody>
				</table>
				<div class="col-xs-4 col-sm-4 col-md-4">
					<div class="form-group">
						<button class="btn btn-default managementButton col-xs-8 col-sm-8 col-md-8">Toevoegen</button>
					</div>
					<div class="form-group">
						<button class="btn btn-default managementButton col-xs-8 col-sm-8 col-md-8">Aanpassen</button>
					</div>
					<div class="form-group">
						<button class="btn btn-default managementButton col-xs-8 col-sm-8 col-md-8">Gebouw toewijzen</button>
					</div>
					<div class="form-group">
						<button type="button" id="deleteButton" class="btn btn-default managementButton col-xs-8 col-sm-8 col-md-8">Verwijderen</button>
					</div>
				</div>
			</div>
		</form>
	</div>

</div>

<?php	
	bottomLayout();
?>