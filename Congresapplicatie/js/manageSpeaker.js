var oldSpeakersOfCongress = new Array();
var oldFirstName,oldLastName,oldMailAddress,oldPhoneNumber,oldDescription,oldAgreement,personNo;
$(document).ready(function () {	
	if($('#listBoxSpeakerLeft')) {
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
	}
		

	if($('#listBoxSpeakerRight')) {
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
	}
	if(dataSwapTables["listBoxSpeakerLeft"]) {
		for(var i = 0; i < dataSwapTables["listBoxSpeakerLeft"].rows().data().length;i++) {
			oldSpeakersOfCongress[i] = dataSwapTables["listBoxSpeakerLeft"].rows().data()[i][0];
		}
	}
	$('button[name="buttonSaveSwapListspreker"]').on("click", function() {
		updateSpeakersOfCongress();
	});
	if(document.forms["formspreker"]) {
		document.forms["formspreker"]["buttonEditSpeakerOfCongress"].onclick = function() {getSpeakerInfo(0)};
		document.forms["formspreker"]["buttonEditSpeaker"].onclick = function() {getSpeakerInfo(1)};
		document.forms["formUpdateSpeakerOfCongress"]["aanpassen"].onclick = editSpeakerOfCongress;
		//document.forms["formUpdateSpeaker"]["aanpassen"].onclick = editSpeaker;
		document.forms["formspreker"]["buttonDeleteSpeaker"].onclick = deleteSpeakers;
	}
	
	if(document.forms["formAddSpeaker"]) {
		document.forms["formAddSpeaker"].onsubmit = function() {
			alert("test");
			return false;
		}
	}
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

	
function getSpeakerInfo(speakerType) {
	if(speakerType == 0) {
		var selectedRow = dataSwapTables['listBoxSpeakerLeft'].row('.selected');
	}else {
		var selectedRow = dataSwapTables['listBoxSpeakerRight'].row('.selected');
	}
    if(selectedRow.data()) {
		if(speakerType == 0) {
			speakerName = "speakerOfCongress";
		}else {
			speakerName = "speaker";
		}
        $.ajax({
            url: window.location.href,
            type: 'POST',
            data: {
                getSpeakerInfo: speakerName,
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
				document.getElementById("uploadEditSpeakerOfCongressBtn").style.backgroundImage= "url('../" + data[0]['PicturePath'] + "')";
                updateSpeakerInfo(speakerType);
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

function updateSpeakerInfo(speakerType){
	if(speakerType == 0) {
		document.forms["formUpdateSpeakerOfCongress"]["personNo"].value = personNo;
		document.forms["formUpdateSpeakerOfCongress"]["speakerName"].value = oldFirstName;
		document.forms["formUpdateSpeakerOfCongress"]["LastName"].value = oldLastName;
		document.forms["formUpdateSpeakerOfCongress"]["mailAddress"].value = oldMailAddress;
		document.forms["formUpdateSpeakerOfCongress"]["phoneNumber"].value = oldPhoneNumber;
		document.forms["formUpdateSpeakerOfCongress"]["description"].value = oldDescription;
		document.forms["formUpdateSpeakerOfCongress"]["agreement"].value = oldAgreement;
	}else {
		document.forms["formUpdateSpeaker"]["personNo"].value = personNo;
		document.forms["formUpdateSpeaker"]["speakerName"].value = oldFirstName;
		document.forms["formUpdateSpeaker"]["LastName"].value = oldLastName;
		document.forms["formUpdateSpeaker"]["mailAddress"].value = oldMailAddress;
		document.forms["formUpdateSpeaker"]["phoneNumber"].value = oldPhoneNumber;
		document.forms["formUpdateSpeaker"]["description"].value = oldDescription;
		document.forms["formUpdateSpeaker"]["agreement"].value = oldAgreement;	
	}
}

function editSpeakerOfCongress(){
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
			
			newFirstName: document.forms["formUpdateSpeakerOfCongress"]["speakerName"].value,
			newLastName: document.forms["formUpdateSpeakerOfCongress"]["LastName"].value,
			newMailAddress: document.forms["formUpdateSpeakerOfCongress"]["mailAddress"].value,
			newPhoneNumber: document.forms["formUpdateSpeakerOfCongress"]["phoneNumber"].value,
			newDescription: document.forms["formUpdateSpeakerOfCongress"]["description"].value,
			newAgreement: document.forms["formUpdateSpeakerOfCongress"]["agreement"].value
			
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
