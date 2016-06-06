var buildingGMTable, roomTable;
var selectedBuilding;
$(document).ready(function () {
	buildingGMTable = $('#BuildingGMListBox').DataTable();
	$('#dataTables_length').css('display', 'none');
    $('#BuildingGMListBox_length').css('display', 'none');
    $('#BuildingGMListBox_paginate').css('display', 'none');
    $('#BuildingGMListBox_info').css('display', 'none');
	
	
	$("[name=previousScreenButton]").on("click", function(event) {
		window.location.href = 'manageLocationGeneralManager.php';
	})
	
	$('#BuildingGMListBox tbody').on("click", "tr", function(event) {
		if (buildingGMTable.rows(".selected").data().length > 1) {
			$("[name=buttonEditBuildingGM]").prop("disabled", true);
		}
		else if (buildingGMTable.rows(".selected").data().length == 1) {
			$("[name=buttonEditBuildingGM]").prop("disabled", false);
		}
	})
	
	$("[name=buttonDeleteBuildingGM]").on("click", function(event) {
		if (confirm("Weet u zeker dat u deze rij(en) wilt verwijderen?")) {
			var dataArray = buildingGMTable.rows(".selected");
				var result = [];
				for(var i = 0; i < dataArray.data().length; i++) {
					result.push(dataArray.data()[i][0]);
				}				
				$.ajax({
					url: window.location.href,
					type: 'POST',
					data: {
						deleteBuildings: 'deleteBuildings',
						selectedBuildingValues: result
					},
					success: function(data) {
					},
					error: function (request, status, error) {
						alert(request.responseText);
					}
				})
			dataArray.remove().draw(false);
		}
	})
	
	roomTable = $("#ZalenListBox").DataTable({
		 "scrollY":        "500px",
        "scrollCollapse": true,
        "paging":         false	
	});
	$('#dataTables_length').css('display', 'none');
    $('#ZalenListBox_length').css('display', 'none');
    $('#ZalenListBox_paginate').css('display', 'none');
    $('#ZalenListBox_info').css('display', 'none');
	
	$('#ZalenListBox tbody').on('click', 'tr', function () {
		$(this).toggleClass('selected');
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
	document.forms['formAddBuildingGM'].onsubmit = isValidBuildingInput;
	document.forms['formCreateBuildingGM']['buttonEditBuildingGM'].onclick = getRooms;
	document.forms['formUpdateBuildingGM']['buttonAddZalen'].onclick = updateCreateRoom;
	document.forms['formUpdateBuildingGM']['buttonEditZalen'].onclick = getRoomInfo;
	document.forms['formUpdateBuildingGM']['buttonDeleteZalen'].onclick = deleteRooms;
	
	document.forms['formAddZalen'].onsubmit = function() {
		$("#errMsgCreateRoom").text("");
		if(!isValidSmallInt(document.forms['formAddZalen']['roomCapacity'].value)) {
			$("#errMsgCreateRoom").text("Capaciteit mag niet groter zijn dan 32767");
			return false;
		}	
		createRoom();
		return false;	
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
	
	document.forms['formEditLocation'].onsubmit = function() {
		var form = document.forms["formEditLocation"];
		if (!isValidLocationName(form['locationName'].value)) {
			$('#errMsgEditLocationValues').html("* Locatienaam is niet geldig.");
			return false;
		}
		else if (!isValidCityName(form['locationCity'].value)) {
			$("#errMsgEditLocationValues").html("* Plaatsnaam is niet geldig. Plaatsnamen mogen maximaal 20 karakters bevatten.");
			return false;
		}
		else {
			form.submit();
		}
	}
});

function isValidBuildingInput() {
		var form = document.forms['formAddBuildingGM'];
		if (!isValidLocationName(form['buildingName'].value)) {
			$('#errMsgCreateBuilding').html("Gebouwnaam is niet geldig.");
			return false;
		}
		else if (!isValidLocationName(form['streetName'].value)) {
			$("#errMsgCreateBuilding").html("Straatnaam is niet geldig.");
			return false;
		}
		else if (!isValidSmallInt(form['houseNo'].value)) {
			$('#errMsgCreateBuilding').html("Huisnummer is niet geldig");
			return false;
		}
		else if (!isValidPostalCode(form['postalCode'].value)) {
			$('#errMsgCreateBuilding').html("Postcode is niet geldig.");
			return false;
		}
		else {
			form.submit();
		}
}

function getRooms() {
	roomTable.rows().remove().draw(false);
	roomTable.row.add(["Loading","Loading","Loading","Loading"]).draw(false);
	
	selectedBuilding = buildingGMTable.row(".selected").data()[0];
	document.forms['formUpdateBuildingGM']['BName'].value = selectedBuilding;
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
	
	for(var i = 0;i<rooms.length;i++) {
		roomTable.row.add([rooms[i]["RName"],rooms[i]["Description"],rooms[i]["MaxNumberOfParticipants"]]);
	}
	roomTable.row().draw();
}

function updateCreateRoom() {
	document.forms['formAddZalen']['BName'].value = document.forms['formUpdateBuildingGM']['BName'].value;	
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
					$("#errMsgCreateRoom").text(data);
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
					$("#errMsgUpdateRoom").text(data);
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
					
				},
				error: function (request, status, error) {
					alert(request.responseText);
				}
			});
		}
		
		selectedRows.remove().draw(false);
	}
}