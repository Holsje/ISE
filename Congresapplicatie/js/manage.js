var speakerTable;
var oldSpeakersOfCongress = new Array();
$(document).ready(function () {	
	$('#listBoxSpeakerLeft').on('click', 'tr', function () {
	   if ($('#listBoxSpeakerLeft .selected')[0] == null) {
			$("button[name='buttonEditSpeakerOfCongress']").prop("disabled",true);
		}else {
			if($('#listBoxSpeakerLeft .selected')[1] != null) {
				$("button[name='buttonEditSpeakerOfCongress']").prop("disabled",true);
			}else {
				$("button[name='buttonEditSpeakerOfCongress']").prop("disabled",false);
			}
		}
    });	
	
	$('#listBoxSpeakerRight').on('click', 'tr', function () {
	   if ($('#listBoxSpeakerRight .selected')[0] == null) {
			$("button[name='buttonEditSpeaker']").prop("disabled",true);
			$("button[name='buttonDeleteSpeaker']").prop("disabled",true);
		}else {
			if($('#listBoxSpeakerRight .selected')[1] != null) {
				$("button[name='buttonEditSpeaker']").prop("disabled",true);
			}else {
				$("button[name='buttonEditSpeaker']").prop("disabled",false);
				$("button[name='buttonDeleteSpeaker']").prop("disabled",false);
			}
		}
    });
	
	for(var i = 0; i < dataSwapTables["listBoxSpeakerLeft"].rows().data().length;i++) {
		oldSpeakersOfCongress[i] = dataSwapTables["listBoxSpeakerLeft"].rows().data()[i][0];
	}
	
	$('button[name="buttonSaveSwapListspreker"]').on("click", function() {
		updateSpeakersOfCongress();
	});
});



function updateSpeakersOfCongress() {
	 $.ajax({
			url: window.location.href,
			type: 'POST',
			data: {
				buttonSaveSwapList: 'spreker',
				oldPersons: oldSpeakersOfCongress,
				newPersons: getSpeakersOfCongress()
			},
			success: function (data) {
				data = JSON.parse(data);
				console.log(data);
			},
			error: function (request, status, error) {
				alert(request.responseText);
			}
		});
}

function getSpeakersOfCongress() {
	var newSpeakers = new Array();
	for(var i = 0; i < dataSwapTables["listBoxSpeakerLeft"].rows().data().length;i++) {
		newSpeakers[i] = dataSwapTables["listBoxSpeakerLeft"].rows().data()[i][0];
	}
	
	return newSpeakers;
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


