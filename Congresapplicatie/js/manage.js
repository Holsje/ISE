var speakerTable;
var locationTable = $('#LocatieListBox').DataTable();
$(document).ready(function () {
	locationTable =  $('#LocatieListBox').DataTable();
	$(".dataTables_scrollBody").removeAttr("style");
	$(".dataTables_scrollBody").addClass("scrollBody");
	
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

function getSpeakerInfo() {
   var selectedRow = speakerTable.row('.selected');
    if(selectedRow.data()) {
        $.ajax({
            url: window.location.href,
            type: 'POST',
            data: {
                getSpeakerInfo: 'GetSpeakerInfo',
                personNo: selectedRow.data()[0],
            },
            success: function (data) {
                data = JSON.parse(data);
				console.log(data);
            //    congressNo = data['congressNo'];
                //oldCongressName = data['CName'];
                //oldCongressStartDate = parseDate(data['Startdate']['date']);
                //oldCongressEndDate = parseDate(data['Enddate']['date']);
                //oldCongressPrice = data['Price'];
                //oldCongressBanner = data['Banner'];
                //oldCongressPublic = data['Public'];
                //oldCongressSubjects = data['subjects']
                //updateCongressInfo(oldCongressName, oldCongressStartDate, oldCongressEndDate, oldCongressPrice, oldCongressBanner, oldCongressPublic, oldCongressSubjects);
            },
            error: function (request, status, error) {
                alert(request.responseText);
            }
        });
    }
    else{
        alert("Er is geen selectie gemaakt");
        return false;
    }
}
