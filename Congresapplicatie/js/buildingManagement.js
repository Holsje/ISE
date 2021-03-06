var buildingGMTable, roomTable;
var selectedBuilding;
$(document).ready(function () {
	buildingGMTable = $('#BuildingGMListBox').DataTable({
        "sScrollY": "500px",
        "bPaginate": false,
        "bInfo": false,
        "language": {
            "emptyTable": "Geen data beschikbaar",
            "sSearch": "Zoeken:"
        }
    });
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
					}
				})
			dataArray.remove().draw(false);
		}
	})
	
	roomTable = $("#ZalenListBox").DataTable({
        "scrollY":        "500px",
        "scrollCollapse": true,
        "paging":         false,
        "bInfo": false,
        "language": {
            "emptyTable": "Geen data beschikbaar",
            "sSearch": "Zoeken:"
        }
	});
	$('#dataTables_length').css('display', 'none');
    $('#ZalenListBox_length').css('display', 'none');
    $('#ZalenListBox_paginate').css('display', 'none');
    $('#ZalenListBox_info').css('display', 'none');
	







	if(document.forms["formAddBuildingGM"]) {
		document.forms['formAddBuildingGM'].onsubmit = function () {
			if (isValidBuildingInput('formAddBuildingGM', '#errMsgCreateBuilding')){
				return true;
			}
			else{
				return false;
			}
		};
	}

	if(document.forms["formUpdateBuildingGM"]) {
		document.forms['formUpdateBuildingGM'].onsubmit = function () {
			if (isValidBuildingInput('formUpdateBuildingGM', '#errMsgUpdateBuilding')){
				return true;
			}
			else{
				return false;
			}
		};
	}
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

function isValidBuildingInput(formName, errMsg) {
		var form = document.forms[formName];
		if (!isValidLocationName(form['buildingName'].value)) {
			$(errMsg).text("Gebouwnaam is niet geldig.");
			return false;
		}
		else if (!isValidLocationName(form['streetName'].value)) {
			$(errMsg).text("Straatnaam is niet geldig.");
			return false;
		}
		else if (!isValidSmallInt(form['houseNo'].value)) {
			$(errMsg).text("Huisnummer is niet geldig");
			return false;
		}
		else if (!isValidPostalCode(form['postalCode'].value)) {
			$(errMsg).text("Postcode is niet geldig.");
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
	document.forms['formUpdateBuildingGM']['buildingName'].value = selectedBuilding;


	 $.ajax({
			url: window.location.href,
			type: 'POST',
			data: {
				getRooms: 'rooms',
				building: selectedBuilding
			},
			success: function (data) {
				data = JSON.parse(data);
				document.forms['formUpdateBuildingGM']['LocationName'].value = data[0]['LocationName'];
				document.forms['formUpdateBuildingGM']['cityName'].value = data[0]['City'];
				document.forms['formUpdateBuildingGM']['buildingName'].value = data[0]['BName'];
				document.forms['formUpdateBuildingGM']['streetName'].value = data[0]['Street'];
				document.forms['formUpdateBuildingGM']['houseNo'].value = data[0]['HouseNo'];
				document.forms['formUpdateBuildingGM']['postalCode'].value = data[0]['PostalCode'];
				refreshRoom(data);
			}
		});
}

function refreshRoom(rooms) {
	roomTable.rows().remove().draw(false);
	
	for(var i = 1;i<rooms.length;i++) {
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
				}
			});
		}

	}
}