var buildingGMTable;
$(document).ready(function () {
	buildingGMTable = $('#BuildingGMListBox').DataTable();
	
	$('#dataTables_length').css('display', 'none');
    $('#BuildingGMListBox_length').css('display', 'none');
    $('#BuildingGMListBox_paginate').css('display', 'none');
    $('#BuildingGMListBox_info').css('display', 'none');
	
	$('#BuildingGMListBox tbody').on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
            $('.onSelected').prop('disabled', true);
        } else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            $('.onSelected').prop('disabled', false);
        }
    });
	
	$("[name=previousScreenButton]").on("click", function(event) {
		window.location.href = 'manageLocationGeneralManager.php';
	})
	
	$('#BuildingGMListBox tbody').on("click", "tr", function(event) {
		if (buildingGMTable.rows(".selected").data().length > 1) {
			$("[name=buttonEditBuildingGM]").prop("disabled", true);
		}
		else if (buildingGMTable.rows(".selected").data().length == 1) {
			$("[name=buttonEditBuildingGM]").prop("disabled", false);
		}
	})
	
	$("[name=buttonDeleteBuildingGM]").on("click", function(event) {
		var dataArray = buildingGMTable.rows(".selected");
		var result = [];
		for(var i = 0; i < dataArray.data().length; i++) {
			result.push(dataArray.data()[i][0]);
		}
		console.log(result);
		
		$.ajax({
			url: window.location.href,
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
});