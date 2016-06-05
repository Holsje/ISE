var oldSpeakersOfCongress = new Array();
var oldFirstName,oldLastName,oldMailAddress,oldPhoneNumber,oldDescription,oldAgreement,personNo;
$(document).ready(function () {	
	if($('#listBoxSpeakerLeft')) {
		$('#listBoxSpeakerLeft').on('click', 'tr', function () {
			var numSelectedRows = dataSwapTables['listBoxSpeakerLeft'].rows(".selected").data().length;
			if (numSelectedRows == 0) {
				$("[name=buttonEditSpeakerOfCongress]").prop("disabled", true);
			}
			else if (numSelectedRows == 1) {
				$("[name=buttonEditSpeakerOfCongress]").prop("disabled", false);
			}
			else {
				$("[name=buttonEditSpeakerOfCongress]").prop("disabled", true);
			}
		});	
	}

	if($('#listBoxSpeakerRight')) {
		$('#listBoxSpeakerRight').on('click', 'tr', function () {
			var numSelectedRows = dataSwapTables['listBoxSpeakerRight'].rows(".selected").data().length;
			if (numSelectedRows == 0) {
				$("[name=buttonEditSpeaker]").prop("disabled", true);
				$("[name=buttonDeleteSpeaker]").prop("disabled", true);
			}
			else if (numSelectedRows == 1) {
				$("[name=buttonEditSpeaker]").prop("disabled", false);
				$("[name=buttonDeleteSpeaker]").prop("disabled", false);
			}
			else {
				$("[name=buttonEditSpeaker]").prop("disabled", true);
				$("[name=buttonDeleteSpeaker]").prop("disabled", false);
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
		document.forms["formspreker"]["buttonDeleteSpeaker"].onclick = deleteSpeakers;
	}
	
	if(document.forms["formAddSpeaker"]) {
		document.forms["formAddSpeaker"].onsubmit = function() {
			if(!isValidName(document.forms["formAddSpeaker"]["speakerName"].value)) {
				$("#errMsgAanmakenSpreker").text("Voornaam onjuist.");
				return false;
			}
			if(!isValidName(document.forms["formAddSpeaker"]["LastName"].value)) {
				$("#errMsgAanmakenSpreker").text("Achternaam onjuist.");
				return false;
			}			
			if(!isValidEmailAddress(document.forms["formAddSpeaker"]["mailAddress"].value)) {
				$("#errMsgAanmakenSpreker").text("Email onjuist.");
				return false;
			}			
			if(!isValidTelephoneNumber(document.forms["formAddSpeaker"]["phoneNumber"].value)) {
				$("#errMsgAanmakenSpreker").text("Telefoonnummer onjuist.");
				return false;
			}						
			return true;
		}
	}
	
	
	if(document.forms["formUpdateSpeakerOfCongress"]) {
		document.forms["formUpdateSpeakerOfCongress"].onsubmit = function() {
			if(!isValidName(document.forms["formUpdateSpeakerOfCongress"]["speakerName"].value)) {
				$("#errMsgUpdateSpeakerOfCongress").text("Voornaam onjuist.");
				return false;
			}
			if(!isValidName(document.forms["formUpdateSpeakerOfCongress"]["LastName"].value)) {
				$("#errMsgUpdateSpeakerOfCongress").text("Achternaam onjuist.");
				return false;
			}			
			if(!isValidEmailAddress(document.forms["formUpdateSpeakerOfCongress"]["mailAddress"].value)) {
				$("#errMsgUpdateSpeakerOfCongress").text("Email onjuist.");
				return false;
			}			
			if(!isValidTelephoneNumber(document.forms["formUpdateSpeakerOfCongress"]["phoneNumber"].value)) {
				$("#errMsgUpdateSpeakerOfCongress").text("Telefoonnummer onjuist.");
				return false;
			}			
			return true;
		}
	}
	
	
	if(document.forms["formUpdateSpeaker"]) {
		document.forms["formUpdateSpeaker"].onsubmit = function() {
			if(!isValidName(document.forms["formUpdateSpeaker"]["speakerName"].value)) {
				$("#errMsgBewerkenSpreker").text("Voornaam onjuist.");
				return false;
			}
			if(!isValidName(document.forms["formUpdateSpeaker"]["LastName"].value)) {
				$("#errMsgBewerkenSpreker").text("Achternaam onjuist.");
				return false;
			}			
			if(!isValidEmailAddress(document.forms["formUpdateSpeaker"]["mailAddress"].value)) {
				$("#errMsgBewerkenSpreker").text("Email onjuist.");
				return false;
			}			
			if(!isValidTelephoneNumber(document.forms["formUpdateSpeaker"]["phoneNumber"].value)) {
				$("#errMsgBewerkenSpreker").text("Telefoonnummer onjuist.");
				return false;
			}			
			return true;
		}
	}
	
	
	$(".listBoxSpeakerLeft .dataTables_scrollBody").removeAttr("style");
	$(".listBoxSpeakerRight .dataTables_scrollBody").removeAttr("style");
	$(".listBoxSpeakerRight .dataTables_scrollBody").addClass("noScrollBody");
	$(".listBoxSpeakerLeft .dataTables_scrollBody").addClass("noScrollBody");
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
				if(speakerType == 0) {
					document.getElementById("uploadEditSpeakerOfCongressBtn").style.backgroundImage= "url('../" + data[0]['PicturePath'] + "')";
				}else {
					document.getElementById("uploadEditSpeakerBtn").style.backgroundImage= "url('../" + data[0]['PicturePath'] + "')";
				}
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
	if (confirm("Weet u zeker dat u deze rij(en) wilt verwijderen?")) {
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
}
