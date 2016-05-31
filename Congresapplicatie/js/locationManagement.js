var locationTable;
var locationGMTable;
var buildingGMTable;
$(document).ready(function () {
	locationTable =  $('#LocatieListBox').DataTable();
	locationGMTable = $('#LocatieGMListBox').DataTable();
	$(".dataTables_scrollBody").removeAttr("style");
	$(".dataTables_scrollBody").addClass("scrollBody");
	
	 $('#LocatieListBox_info').css('display', 'none');

	$(".locationSelect").change(function() {
		var selectedValue = $(this).val();
		$.ajax({
		url: window.location.href,
        type: 'POST',
        data: {
			SelectedValue: selectedValue,
			LocationName: getLocationName(selectedValue),
			City: getLocationCity(selectedValue)
        },
		success: function(data) {
			location.reload();
			window.location.href = 'manage.php#Locatie';
		},
		error: function (request, status, error) {
            alert(request.responseText);
        }});
	})
	$('#LocatieGMListBox tbody').on("click", "tr", function(event) {
		if (locationGMTable.rows(".selected").data().length > 1) {
			$("[name=buttonEditLocatieGM]").prop("disabled", true);
		}
		else if (locationGMTable.rows(".selected").data().length == 1) {
			$("[name=buttonEditLocatieGM]").prop("disabled", false);
		}
	})

	$("[name=buttonDeleteLocatie]").on("click", function(event) {
		var dataArray = locationTable.rows(".selected");
		var result = [];
		for(var i = 0; i < dataArray.data().length; i++) {
			result.push(dataArray.data()[i][0]);
		}
		console.log(result);
		
		$.ajax({
			url: 'manage.php#Locatie',
			type: 'POST',
			data: {
				selectedBuildingValues: result
			},
			success: function(data) {
				console.log(data);
			},
			error: function (request, status, error) {
				alert(request.responseText);
			}
		})
	})
	$("[name=buttonEditLocatieGM]").on("click", function(event) {
		var dataArray = locationGMTable.rows(".selected");
		var result = [];
		for(var i = 0; i < dataArray.data().length; i++) {
			result.push(dataArray.data()[i][0]);
			result.push(dataArray.data()[i][1]);
		}
		console.log(result);
		$.ajax({
			url: 'manageBuildingGeneralManager.php',
			type: 'POST',
			data: {
				selectedLocationValue: result
			},
			success: function(data) {
				window.location.href = 'manageBuildingGeneralManager.php';
			},
			error: function (request, status, error) {
				alert(request.responseText);
			}
		})
	})
	$("[name=buttonDeleteLocatieGM]").on("click", function(event) {
		var dataArray = locationGMTable.rows(".selected");
		console.log(dataArray);
		var result = [];
		for(var i = 0; i < dataArray.data().length; i++) {
			result.push(dataArray.data()[i][0]);
			result.push(dataArray.data()[i][1]);
		}
		$.ajax({
			url: window.location.href,
			type: 'POST',
			data: {
				selectedLocationValues: result
			},
			success: function(data) {
				console.log(data);
			},
			error: function (request, status, error) {
				alert(request.responseText);
			}
		})
	})
});


function getLocationName(value) {
	locationArray = value.split(" ");
	locationArray.pop();
	locationArray.pop();
	returnString = '';
	for(var i = 0; i < locationArray.length; i++) {
		if (i == 0) {
			returnString += locationArray[i];
		}
		else {
			returnString += ' ' + locationArray[i];
		}
	}
	return returnString;
}

function getLocationCity(value) {
	locationArray = value.split(" ");
	return locationArray[locationArray.length - 1];
}
