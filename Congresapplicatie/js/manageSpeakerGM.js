 	var table;
$(document).ready(function () {	
	
	table = $('#ListBox').DataTable( {
        "sScrollY": "500px",
        "bPaginate": false,
        "bInfo": false,
		"columnDefs": [ {
				"targets": [0],
				"visible": false,
				"searchable": false			
			}]
	});
	
	
	 $('.singleSelect.dataTable tbody').on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
            $('.onSelected').prop('disabled', true);
        } else {
            $('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            $('.onSelected').prop('disabled', false);
        }
    });
	
	
	document.forms["formCreate"]["buttonEdit"].onclick = getSpeakerInfo;
	document.forms["formCreate"]["buttonDelete"].onclick = deleteSpeakers;
	
	
	
	if(document.forms["formAdd"]) {
		document.forms["formAdd"].onsubmit = function() {
			if(!isValidName(document.forms["formAdd"]["speakerName"].value)) {
				$("#errMsgAanmakenSpreker").text("Voornaam onjuist.");
				return false;
			}
			if(!isValidName(document.forms["formAdd"]["LastName"].value)) {
				$("#errMsgAanmakenSpreker").text("Achternaam onjuist.");
				return false;
			}			
			if(!isValidEmailAddress(document.forms["formAdd"]["mailAddress"].value)) {
				$("#errMsgAanmakenSpreker").text("Email onjuist.");
				return false;
			}			
			if(!isValidTelephoneNumber(document.forms["formAdd"]["phoneNumber"].value)) {
				$("#errMsgAanmakenSpreker").text("Telefoonnummer onjuist.");
				return false;
			}			
			
			return true;
		}
	}



	if(document.forms["formUpdate"]) {
		document.forms["formUpdate"].onsubmit = function() {
			if(!isValidName(document.forms["formUpdate"]["speakerName"].value)) {
				$("#errMsgBewerkenSpreker").text("Voornaam onjuist.");
				return false;
			}
			if(!isValidName(document.forms["formUpdate"]["LastName"].value)) {
				$("#errMsgBewerkenSpreker").text("Achternaam onjuist.");
				return false;
			}			
			if(!isValidEmailAddress(document.forms["formUpdate"]["mailAddress"].value)) {
				$("#errMsgBewerkenSpreker").text("Email onjuist.");
				return false;
			}			
			if(!isValidTelephoneNumber(document.forms["formUpdate"]["phoneNumber"].value)) {
				$("#errMsgBewerkenSpreker").text("Telefoonnummer onjuist.");
				return false;
			}			
			return true;
		}
	}

});



function getSpeakerInfo() {
	var selectedRow = table.row('.selected');
    if(selectedRow.data()) {
        $.ajax({
            url: window.location.href,
            type: 'POST',
            data: {
                getSpeakerInfo: 'getSpeakerInfo',
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
				document.getElementById("uploadEditSpeakerBtn").style.backgroundImage= "url('../" + data[0]['PicturePath'] + "')";
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
	document.forms["formUpdate"]["personNo"].value = personNo;
	document.forms["formUpdate"]["speakerName"].value = oldFirstName;
	document.forms["formUpdate"]["LastName"].value = oldLastName;
	document.forms["formUpdate"]["mailAddress"].value = oldMailAddress;
	document.forms["formUpdate"]["phoneNumber"].value = oldPhoneNumber;
	document.forms["formUpdate"]["description"].value = oldDescription;
}


function deleteSpeakers() {
	if (confirm("Weet u zeker dat u deze rij wilt verwijderen?")) {
		var selectedRows = table.rows(".selected");
		
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



