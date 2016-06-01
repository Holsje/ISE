var locationTable;

$(document).ready(function () {
	locationTable =  $('#LocatieListBox').DataTable();
	
	$('#dataTables_length').css('display', 'none');
    $('#LocatieListBox_length').css('display', 'none');
    $('#LocatieListBox_paginate').css('display', 'none');
    $('#LocatieListBox_info').css('display', 'none');
	
	$('.dataTable tbody').on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
            $('.onSelected').prop('disabled', true);
        } else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            $('.onSelected').prop('disabled', false);
        }
    });
	
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
