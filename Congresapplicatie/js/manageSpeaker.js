var oldSpeakersOfCongress = new Array();
var oldFirstName,oldLastName,oldMailAddress,oldPhoneNumber,oldDescription,oldAgreement,personNo;
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
	
	document.forms["formspreker"]["buttonEditSpeakerOfCongress"].onclick = getSpeakerInfo;
	document.forms["formUpdateSpeaker"]["aanpassen"].onclick = editSpeaker;
	document.forms["formspreker"]["buttonDeleteSpeaker"].onclick = deleteSpeakers;
});


function refreshSpeaker(){
	if(window.location.hash == "#spreker") {
		window.location.reload();
	}
	else {
		window.location.href = window.location.href.replace(window.location.hash,"") + "#spreker";
		window.location.reload();
	}
}
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
				refreshSpeaker();
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
   var selectedRow = dataSwapTables['listBoxSpeakerLeft'].row('.selected');
    if(selectedRow.data()) {
        $.ajax({
            url: window.location.href,
            type: 'POST',
            data: {
                getSpeakerInfo: 'GetSpeakerInfo',
                personNo: selectedRow.data()[0]
            },
            success: function (data) {
				data=JSON.parse(data);
                personNo = data[0]['personNo'];
				oldFirstName = data[0]['FirstName'];
				oldLastName = data[0]['LastName'];
				oldMailAddress = data[0]['MailAddress'];
				oldPhoneNumber = data[0]['phonenumber'];
				oldDescription = data[0]['Description'];
				oldAgreement = data[0]['agreement'];
                updateSpeakerInfo();
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

function updateSpeakerInfo(){
	document.forms["formUpdateSpeaker"]["speakerName"].value = oldFirstName;
    document.forms["formUpdateSpeaker"]["LastName"].value = oldLastName;
    document.forms["formUpdateSpeaker"]["mailAddress"].value = oldMailAddress;
    document.forms["formUpdateSpeaker"]["phoneNumber"].value = oldPhoneNumber;
    document.forms["formUpdateSpeaker"]["description"].value = oldDescription;
    document.forms["formUpdateSpeaker"]["agreement"].value = oldAgreement;
}

function editSpeaker(){
	$.ajax({
		url: window.location.href,
		type: 'POST',
		data: {
			updateSpeakerInfo: 'updateSpeakerInfo',
			personNo: personNo,
			
			oldFirstName: oldFirstName,
			oldLastName: oldLastName,
			oldMailAddress: oldMailAddress,
			oldPhoneNumber: oldPhoneNumber,
			oldDescription: oldDescription,
			oldAgreement: oldAgreement,
			
			newFirstName: document.forms["formUpdateSpeaker"]["speakerName"].value,
			newLastName: document.forms["formUpdateSpeaker"]["LastName"].value,
			newMailAddress: document.forms["formUpdateSpeaker"]["mailAddress"].value,
			newPhoneNumber: document.forms["formUpdateSpeaker"]["phoneNumber"].value,
			newDescription: document.forms["formUpdateSpeaker"]["description"].value,
			newAgreement: document.forms["formUpdateSpeaker"]["agreement"].value
			
		},
		success: function (data) {
			if(data) {
				$("#errMsgBewerkenSpreker").text(data);
			}
			else{
				refreshSpeaker();
			}
		},
		error: function (request, status, error) {
			alert(request.responseText);
		}
	});
}

function deleteSpeakers() {
	var selectedRows = dataSwapTables["listBoxSpeakerRight"].rows(".selected");
	
	for(var i = 0;i<selectedRows.data().length;i++) {
		$.ajax({
			url: window.location.href,
			type: 'POST',
			data: {
				deleteSpeaker: 'deleteSpeaker',
				personNo: selectedRows.data()[i][0]			
			},
			success: function (data) {
				
			},
			error: function (request, status, error) {
				alert(request.responseText);
			}
		});
	}
	
	selectedRows.remove().draw(false);
	
	

}
