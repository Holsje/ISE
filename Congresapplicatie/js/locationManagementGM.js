var locationGMTable;

$(document).ready(function () {
	locationGMTable = $('#LocatieGMListBox').DataTable();
	$('.onSelected').prop('disabled', true);
    $('#dataTables_length').css('display', 'none');
    $('#LocatieGMListBox_length').css('display', 'none');
    $('#LocatieGMListBox_paginate').css('display', 'none');
    $('#LocatieGMListBox_info').css('display', 'none');
	
	$('#LocatieGMListBox tbody').on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
            $('.onSelected').prop('disabled', true);
        } else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            $('.onSelected').prop('disabled', false);
        }
    });
	
	$('#LocatieGMListBox tbody').on("click", "tr", function(event) {
		if (locationGMTable.rows(".selected").data().length > 1) {
			$("[name=buttonEditLocatieGM]").prop("disabled", true);
		}
		else if (locationGMTable.rows(".selected").data().length == 1) {
			$("[name=buttonEditLocatieGM]").prop("disabled", false);
		}
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
		if (confirm("Weet u zeker dat u deze rij(en) wilt verwijderen?")) {
			var dataArray = locationGMTable.rows(".selected");
			var result = [];
			for(var i = 0; i < dataArray.data().length; i++) {
				result.push(dataArray.data()[i][0]);
				result.push(dataArray.data()[i][1]);
			}
			$.ajax({
				url: window.location.href,
				type: 'POST',
				data: {
					deleteLocation: 'deleteLocation',
					selectedLocationValues: result
				},
				success: function(data) {
					console.log(data);
				},
				error: function (request, status, error) {
					alert(request.responseText);
				}
			})
			dataArray.remove().draw(false);
		}
	})
	document.forms['formAddLocatieGM'].onsubmit = isValidInput;
});

function isValidInput(){
    var form = document.forms["formAddLocatieGM"];
    if (!isValidLocationName(form['locationNameText'].value)) {
		$(".errorMsg").html("* Locatienaam is niet geldig.");
        return false;
    }
	else if (!isValidCityName(form['locationCityText'].value)) {
		$(".errorMsg").html("* Plaatsnaam is niet geldig.");
		return false;
	}
    else {
        form.submit();
    }
}


