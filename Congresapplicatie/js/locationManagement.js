var locationTable,roomTable;
var selectedBuilding,roomName,roomDescription,roomCapacity;

$(document).ready(function () {
	locationTable =  $('#LocatieListBox').DataTable({
        "scrollY":        "500px",
        "scrollCollapse": true,
        "paging":         false,
        "language": {
           "emptyTable": "Geen data beschikbaar"
        } 
    });
	$('#dataTables_length').css('display', 'none');
    $('#LocatieListBox_length').css('display', 'none');
    $('#LocatieListBox_paginate').css('display', 'none');
    $('#LocatieListBox_info').css('display', 'none');
	$('#LocatieListBox tbody').on('click', 'tr', function () {
		var numSelectedRows = locationTable.rows(".selected").data().length;
		if (numSelectedRows == 0) {
			$("[name=buttonEditLocatie]").prop("disabled", true);
			$("[name=buttonDeleteLocatie]").prop("disabled", true);
		}
		else if (numSelectedRows == 1) {
			$("[name=buttonEditLocatie]").prop("disabled", false);
			$("[name=buttonDeleteLocatie]").prop("disabled", false);
		}
		else {
			$("[name=buttonEditLocatie]").prop("disabled", true);
			$("[name=buttonDeleteLocatie]").prop("disabled", false);
		}
    });
	
	roomTable = $("#ZalenListBox").DataTable({
        "scrollY":        "500px",
        "scrollCollapse": true,
        "paging":         false,
        "language": {
           "emptyTable": "Geen data beschikbaar"
        }
	});
	$('#dataTables_length').css('display', 'none');
    $('#ZalenListBox_length').css('display', 'none');
    $('#ZalenListBox_paginate').css('display', 'none');
    $('#ZalenListBox_info').css('display', 'none');
	
	$('#ZalenListBox tbody').on('click', 'tr', function () {
		var numSelectedRows = roomTable.rows(".selected").data().length;
		if (numSelectedRows == 0) {
			$("[name=buttonEditZalen]").prop("disabled", true);
			$("[name=buttonDeleteZalen]").prop("disabled", true);
		}
		else if (numSelectedRows == 1) {
			$("[name=buttonEditZalen]").prop("disabled", false);
			$("[name=buttonDeleteZalen]").prop("disabled", false);
		}
		else {
			$("[name=buttonEditZalen]").prop("disabled", true);
			$("[name=buttonDeleteZalen]").prop("disabled", false);
		}
    });
	
	document.forms['formCreateLocatie']['buttonEditLocatie'].onclick = getRooms;
	document.forms['formUpdateLocatie']['buttonAddZalen'].onclick = updateCreateRoom;
	document.forms['formUpdateLocatie']['buttonEditZalen'].onclick = getRoomInfo;
	document.forms['formUpdateLocatie']['buttonDeleteZalen'].onclick = deleteRooms;
	
	document.forms['formAddZalen'].onsubmit = function() {
		$("#errMsgCreateRoom").text("");
		if(!isValidSmallInt(document.forms['formAddZalen']['roomCapacity'].value)) {
			$("#errMsgCreateRoom").text("Capaciteit mag niet groter zijn dan 32767");
			return false;
		}
        if(!isValidLocationName(document.forms['formAddZalen']['roomName'].value)) {
			$("#errMsgCreateRoom").text("Zaalnaam is onjuist.");
			return false;
		}	
		createRoom();
		return false;	
	}
    
    document.forms['formAddLocatie'].onsubmit = function () {
        if(!isValidLocationName(document.forms['formAddLocatie']['buildingName'].value)){
            $('#errMsgInsertBuilding').text('Gebouwnaam is onjuist.');
            return false;
        }
        if(!isValidLocationName(document.forms['formAddLocatie']['streetName'].value)){
            $('#errMsgInsertBuilding').text('Straatnaam is onjuist.');
            return false;
        }
        if(!isValidSmallInt(document.forms['formAddLocatie']['houseNo'].value)){
            $('#errMsgInsertBuilding').text('Huisnummer is onjuist.');
            return false;
        }
        if(!isValidPostalCode(document.forms['formAddLocatie']['postalCode'].value)){
            $('#errMsgInsertBuilding').text('Postcode is onjuist.');
            return false;
        }else{
            form.submit();
        }
    }
	
	document.forms['formUpdateZalen'].onsubmit = function() {
		
		$("#errMsgUpdateRoom").text("");
		if(!isValidSmallInt(document.forms['formUpdateZalen']['roomCapacity'].value)) {
			$("#errMsgUpdateRoom").text("Capaciteit moet kleiner zijn dan 32767");
			return false;
		}	
		editRoom();
		return false;	
	}
	
	
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

	$("[name=buttonDeleteLocatie]").on("click", function(){
		alert("U kunt vanaf hier geen gebouw verwijderen.");
	});
});


function getRooms() {
	roomTable.rows().remove().draw(false);
	roomTable.row.add(["Loading","Loading","Loading","Loading"]).draw(false);
	
	selectedBuilding = locationTable.row(".selected").data()[0];
	document.forms['formUpdateLocatie']['BName'].value = selectedBuilding;
	 $.ajax({
			url: window.location.href,
			type: 'POST',
			data: {
				getRooms: 'rooms',
				building: selectedBuilding
			},
			success: function (data) {
				data = JSON.parse(data);
				refreshRoom(data);
			},
			error: function (request, status, error) {
				alert(request.responseText);
			}
		});
}

function refreshRoom(rooms) {
	roomTable.rows().remove().draw(false);
	document.forms['formUpdateLocatie']['LocationName'].value = rooms[0]['LocationName'];
	document.forms['formUpdateLocatie']['cityName'].value = rooms[0]['City'];

	for(var i = 0;i<rooms.length;i++) {
        if(rooms[i]["RName"] != null){
		  roomTable.row.add([rooms[i]["RName"],rooms[i]["Description"],rooms[i]["MaxNumberOfParticipants"]]);
        }
	}
	roomTable.row().draw();
}

function updateCreateRoom() {
	document.forms['formAddZalen']['BName'].value = document.forms['formUpdateLocatie']['BName'].value;	
}

function createRoom() {
		roomName = document.forms['formAddZalen']['roomName'].value;
		roomDescription = document.forms['formAddZalen']['roomDescription'].value;
		roomCapacity = document.forms['formAddZalen']['roomCapacity'].value;
		
	$.ajax({
			url: window.location.href,
			type: 'POST',
			data: {
				createRoom: 'createRoom',
				BName: selectedBuilding,
				roomName: roomName,
				roomDescription: roomDescription,
				roomCapacity: roomCapacity
			},
			success: function (data) {
				if (data != null && data != '' &&  /\S/.test(data)) {
					data = JSON.parse(data);
					document.getElementById('errMsgCreateRoom').innerHTML = '*' + data;
				}
				else {
					$("#errMsgCreateRoom").text("");
					document.forms['formAddZalen']['roomName'].value = '';
					document.forms['formAddZalen']['roomDescription'].value = '';
					document.forms['formAddZalen']['roomCapacity'].value = '';
					roomTable.row.add([roomName,roomDescription,roomCapacity]).draw(false);
					$("#popUpAddZalen button")[0].click();
				}
			},
			error: function (request, status, error) {
				alert(request.responseText);
			}
		});
}

function getRoomInfo() {
	var selectedRow = roomTable.row('.selected');

	 $.ajax({
		url: window.location.href,
		type: 'POST',
		data: {
			getRoomInfo: 'getRoomInfo',
			building: selectedBuilding,
			RName: selectedRow.data()[0]
		},
		success: function (data) {
			data = JSON.parse(data);
			refreshEditRoom(data);
		},
		error: function (request, status, error) {
			alert(request.responseText);
		}
	});
}

function refreshEditRoom(roomInfo) {
	document.forms['formUpdateZalen']['LocationName'].value = roomInfo[0]['LocationName'];
	document.forms['formUpdateZalen']['cityName'].value = roomInfo[0]['City'];
	document.forms['formUpdateZalen']['BName'].value = roomInfo[0]['BName'];
	document.forms['formUpdateZalen']['roomName'].value = roomInfo[0]['RName'];
	document.forms['formUpdateZalen']['roomDescription'].value = roomInfo[0]['Description'];
	document.forms['formUpdateZalen']['roomCapacity'].value = roomInfo[0]['MaxNumberOfParticipants'];
	roomName = roomInfo[0]['RName'];
}


function editRoom() {
		var oldRoomName = roomName;
		newRoomName = document.forms['formUpdateZalen']['roomName'].value;
		roomDescription = document.forms['formUpdateZalen']['roomDescription'].value;
		roomCapacity = document.forms['formUpdateZalen']['roomCapacity'].value;
		
	$.ajax({
			url: window.location.href,
			type: 'POST',
			data: {
				editRoom: 'editRoom',
				BName: selectedBuilding,
				roomName: newRoomName,
				roomDescription: roomDescription,
				roomCapacity: roomCapacity,
				oldRoomName: oldRoomName
			},
			success: function (data) {
				if (data != null && data != '' &&  /\S/.test(data)) {
					data = JSON.parse(data);
					document.getElementById('errMsgUpdateRoom').innerHTML = '*' + data;
				}
				else {
					$("#errMsgUpdateRoom").text("");
					document.forms['formUpdateZalen']['roomName'].value = '';
					document.forms['formUpdateZalen']['roomDescription'].value = '';
					document.forms['formUpdateZalen']['roomCapacity'].value = '';
					roomTable.rows(".selected").remove().draw(false);
					roomTable.row.add([newRoomName,roomDescription,roomCapacity]).draw(false);
					$("[name=buttonEditZalen]").prop("disabled", true);
					$("[name=buttonDeleteZalen]").prop("disabled", true);
					$("#popUpUpdateZalen button")[0].click();
				}
			},
			error: function (request, status, error) {
				alert(request.responseText);
			}
		});
}


function deleteRooms() {
	if (confirm("Weet u zeker dat u deze rij(en) wilt verwijderen?")) {
		var selectedRows = roomTable.rows(".selected");
		
		for(var i = 0;i<selectedRows.data().length;i++) {
			$.ajax({
				url: window.location.href,
				type: 'POST',
				data: {
					deleteRoom: 'deleteRoom',
					BName: selectedBuilding,
					roomName: selectedRows.data()[i][0]			
				},
				success: function (data) {
					if (data != null && data != '' &&  /\S/.test(data)) {
						data = JSON.parse(data);
						document.getElementById('errMsgDeleteRoom').innerHTML = '*' + data;
					}else{
						selectedRows.remove().draw(false);
					}
				},
				error: function (request, status, error) {
					alert(request.responseText);
				}
			});
		}
		

	}
}

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
