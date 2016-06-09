 var track;
 var eventOfTrack;
 var hourHeight = 100;
 var asdfds;
 var offSetTop;
var usingPopup;
var roomTable;
var newEvent;
var startHours;
 $(document).ready(function(){
	 usingPopup = $(".eventPlanningPopUp");
	usingPopup.find("input[name=startTimeEvent]").on("change", function() {
		var hoursTring = this.value.split(":");
		var hours =  parseInt(hoursTring[0]) +(hoursTring[1]/60)+(hoursTring[2]/3600);
		
		//newEvent.css("top",hours*hourHeight);
		newEvent.animate({top:hours*hourHeight},1000);
		var topOffsetPopUp = hours*hourHeight + parseFloat(newEvent.css("height"));
		
		usingPopup.animate({top:topOffsetPopUp},1000)
	});
	
	usingPopup.find("input[name=endTimeEvent]").on("change", function() {
		var hoursTring = this.value.split(":");
		var hours =  parseInt(hoursTring[0]) +(hoursTring[1]/60)+(hoursTring[2]/3600);
		hoursTring = usingPopup.find("input[name=startTimeEvent]").val().split(":");
		var startHours = parseInt(hoursTring[0]) +(hoursTring[1]/60)+(hoursTring[2]/3600);
		newEvent.animate({height:(hours-startHours)*hourHeight},1000);
		
		
		var topOffsetPopUp = parseFloat(newEvent.css("top")) + (hours-startHours)*hourHeight;
		usingPopup.animate({top:topOffsetPopUp},1000);
	//	usingPopup.css("top",(((startHours-hours)*hourHeight) + parseInt(newEvent.css("height"))));	
	});
	
	
	 roomTable = $("#roomListBox").DataTable( {
		"sScrollY": "500px",
		"bPaginate": false
	 });
	$('#roomListBox_length').css('display', 'none');
    $('#roomListBox_info').css('display', 'none');
	$(".dataTables_scrollBody").removeAttr("style");
	$(".dataTables_scrollBody").addClass("scrollBody");
	 
	 $('#roomListBox tbody').on('click', 'tr', function () {
        $(this).toggleClass("selected");
    });
	 
	$(".eventBoxPlanning").droppable({ 
		drop: function(event,ui) { 
			track = $(this);  
			eventOfTrack = $(ui.draggable); 
			offSetTop = eventOfTrack.offset().top - track.offset().top;
			offSetTop = Math.round(offSetTop/(hourHeight/4))*(hourHeight/4);			
			$(".eventPlanningPopUp").hide();
			$(".beingAdded").remove();			
			newEvent = eventOfTrack.clone().appendTo(track);
			newEvent.css("left",0);
			newEvent.css("width", "");
			newEvent.css("top",offSetTop);
			newEvent.css("height",100);
			newEvent.addClass("beingAdded");
	
			console.log(usingPopup);
			var d = new Date(Math.round((offSetTop-100)/hourHeight*4)*900*1000);
			usingPopup.find("input[name=startTimeEvent]").val(d.toTimeString().split(' ')[0]);
			d.setHours(d.getHours()+1);
			usingPopup.find("input[name=endTimeEvent]").val(d.toTimeString().split(' ')[0]);
			usingPopup.css("top", (offSetTop+100));
			usingPopup.appendTo(track);
			$("#errMsgEventPlanning").text('');
			usingPopup.show();
		}
	});
	
	$(".eventPlanningBox .eventInEventBox ").draggable({
		revert: true
	});
	
	document.forms['formEditEventInfo']['saveEventPlanningButton'].onclick = function() {
		
		sendEventPopUpData();
	};
	
	document.forms['formEditEventInfo']['buildingSelect'].onchange = function() {
		var selectedValue = usingPopup.find("select[name=buildingSelect]").val();
		
		$.ajax( {
			url: window.location.href,
			type: 'POST',
			data: {
				changeRoomsOnSelectChange: 'true',
				buildingName: selectedValue
			},
			success: function(data) {
				dataJSON = JSON.parse(data);
				refreshRoomTable(dataJSON);
			}
		})
	}
 });
 
 function refreshRoomTable(data) {
	 console.log(data);
	 roomTable.rows().remove().draw(false);
	for(var i = 0;i < data.length; i++) {
		roomTable.row.add([data[i]]);
	}
	roomTable.row().draw();
 }
 
function sendEventPopUpData() {
	var rooms = new Array();
	
	var selectedValues = roomTable.rows(".selected").data();
	
	for(var i = 0; i < selectedValues.length;i++) {
		rooms[i] = selectedValues[i][0];
	}
	if (rooms.length == 0) {
		rooms = '';
	}
	$.ajax({
		url: window.location.href,
		type: 'POST',
		data: {
			startTime: usingPopup.find("input[name=startTimeEvent]").val(),
			endTime: usingPopup.find("input[name=endTimeEvent]").val(),
			buildingName: usingPopup.find("select[name=buildingSelect]").val(),
			rooms: rooms,
			trackNo: track.attr("id"),
			eventNo: eventOfTrack.attr("id")
		},
		success: function(data) {
			if (data != null && data != '' &&  /\S/.test(data)) {
				$("#errMsgEventPlanning").text(data);
			}
			else {
				usingPopup.hide();
				newEvent.removeClass("beingAdded");
			}
		}
	})
}
