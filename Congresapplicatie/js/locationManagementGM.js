var locationGMTable;

$(document).ready(function () {
	locationGMTable = $('#LocatieGMListBox').DataTable();
	$('.onSelected').prop('disabled', true);
    $('#dataTables_length').css('display', 'none');
    $('#LocatieGMListBox_length').css('display', 'none');
    $('#LocatieGMListBox_paginate').css('display', 'none');
    $('#LocatieGMListBox_info').css('display', 'none');
	
	

	$("[name=buttonEditLocatieGM]").on("click", function(event) {
		var dataArray = locationGMTable.rows(".selected");
		var result = [];
		for(var i = 0; i < dataArray.data().length; i++) {
			result.push(dataArray.data()[i][0]);
			result.push(dataArray.data()[i][1]);
		}
		$.ajax({
			url: 'manageBuildingGeneralManager.php',
			type: 'POST',
			data: {
				selectedLocationValue: result
			},
			success: function(data) {
				window.location.href = 'manageBuildingGeneralManager.php';
			}
		});
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
					if (data != null && data != '' &&  /\S/.test(data)) {
						data = JSON.parse(data);
						document.getElementById('errMsgDeleteLocation').innerHTML = '*' + data['err'];
					}else{
						dataArray.remove().draw(false);
					}
				}
			});

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


