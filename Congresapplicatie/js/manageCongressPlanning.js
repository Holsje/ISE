 var track;
 var eventOfTrack;
 var hourHeight = 100;
 var offSetTop;
var usingPopup;
var roomTable;
var newEvent;
var startHours;
var endHours;

var startScrollY;
var endScrollY;
var newScrollTop;

var isUpdate;

 $(document).ready(function(){
	 isUpdate = false;
	 usingPopup = $(".eventPlanningPopUp");
	usingPopup.find("input[name=startTimeEvent]").on("change", function() {
		var hoursStartTring = this.value.split(":");
		
		startHours = parseInt(hoursStartTring[0]);
		if(!isNaN(hoursStartTring[1]))
			startHours += (hoursStartTring[1]/60);
		if(!isNaN(hoursStartTring[2]))
			startHours += (hoursStartTring[2]/3600);
		
		var hoursTring = usingPopup.find("input[name=endTimeEvent]").val().split(":");
		endHours = parseInt(hoursTring[0]);
		
		if(!isNaN(hoursTring[1]))
			endHours += (hoursTring[1]/60);
		if(!isNaN(hoursTring[2]))
			endHours += (hoursTring[2]/3600);
				
		newEvent.stop();
		newEvent.animate({
							top:startHours*hourHeight,
							height:(endHours-startHours)*hourHeight
							},500);
		//newEvent.animate({height:(endHours-startHours)*hourHeight},1000);
		var topOffsetPopUp = startHours*hourHeight + (endHours-startHours)*hourHeight;
		usingPopup.stop();
		usingPopup.animate({top:topOffsetPopUp},500);
	});
	
	usingPopup.find("input[name=endTimeEvent]").on("change", function() {
		var hoursTring = this.value.split(":");
		var oldEndHours = endHours;
		endHours =  parseInt(hoursTring[0]);
		if(!isNaN(hoursTring[1]))
			endHours += (hoursTring[1]/60);
		if(!isNaN(hoursTring[2]))
			endHours += (hoursTring[2]/3600);
		
		hoursTring = usingPopup.find("input[name=startTimeEvent]").val().split(":");
		
		startHours = parseInt(hoursTring[0]);
		
		if(!isNaN(hoursTring[1]))
			startHours += (hoursTring[1]/60);
		if(!isNaN(hoursTring[2]))
			startHours += (hoursTring[2]/3600);
		
		newEvent.stop()
		newEvent.animate({height:(endHours-startHours)*hourHeight},500);
		
		var topOffsetPopUp = parseFloat(newEvent.css("top")) + (endHours-startHours)*hourHeight;
		usingPopup.stop();
		if($("html, body").is(":animated")) {
			newScrollTop = newScrollTop+((endHours-oldEndHours)*hourHeight);
			$("html, body").stop();
		}else {
			newScrollTop = window.scrollY+((endHours-oldEndHours)*hourHeight);
		}
		if(topOffsetPopUp >= 1900){
            topOffsetPopUp = 1900;
        }
		usingPopup.animate({top:topOffsetPopUp},500);
		$("html, body").animate({scrollTop:(newScrollTop)},500);
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
			isUpdate = false;
			track = $(this);  
			eventOfTrack = $(ui.draggable); 
			offSetTop = eventOfTrack.offset().top - track.offset().top;
			offSetTop = Math.round(offSetTop/(hourHeight/4))*(hourHeight/4);			
			$(".eventPlanningPopUp").hide();
			$(".beingAdded").remove();			
			newEvent = eventOfTrack.clone(true).appendTo(track);
			newEvent.css("left",0);
			newEvent.css("width", "");
			newEvent.css("top",offSetTop);
			newEvent.css("height",100);
			newEvent.addClass("beingAdded");
			
			var d = new Date(Math.round((offSetTop-100)/hourHeight*4)*900*1000);
			endHours = (Math.round((offSetTop+100)/hourHeight*4)*900*1000)/3600/1000;
			startHours = (Math.round((offSetTop-100)/hourHeight*4)*900*1000)/3600/1000;
			
			usingPopup.find("input[name=startTimeEvent]").val(d.toTimeString().split(' ')[0]);
			d.setHours(d.getHours()+1);
			usingPopup.find("input[name=endTimeEvent]").val(d.toTimeString().split(' ')[0]);
			usingPopup.css("top", (offSetTop+hourHeight));
			usingPopup.appendTo(track);
			$("#errMsgEventPlanning").text('');
			usingPopup.find("select[name=buildingSelect]").val('');
			roomTable.clear();
			usingPopup.show();
			roomTable.draw(); 
		}
	});
	
	$(".eventPlanningBox .eventInEventBox ").draggable({
	//		helper:'clone'
	});
	
	$(".eventPlanningBox .eventInEventBox ").draggable({
			revert:true,
			revertDuration: 1
	});
	
	document.forms['formEditEventInfo']['saveEventPlanningButton'].onclick = function() {
		if (isValidInput()) {
			sendEventPopUpData();
		}
	};
	
	$(".closePlanningPopup").on("click", function() {
		$(".beingAdded").hide("fade",function() { $(".beingAdded").remove();});
	});
	
	$(".deleteEventFromPlanning").on("click", function() {
		$.ajax({
			url: window.location.href,
			type: 'POST',
			data : {
				deleteEventFromPlanning: 'delete',
				eventNo: $(this).parents('.eventInEventBox').attr("id"),
				trackNo: $(this).parents('.eventBoxPlanning').attr("id")
			}
		});
		$(this).parents('.eventInEventBox').hide("fade",function() { $(".beingAdded").remove();});
		eventOfTrack = $(".eventPlanningBox").children("div[value=" + $(this).parents('.eventInEventBox').attr("value") + "]");
		eventOfTrack.find(".timesPlanned").text(parseInt(eventOfTrack.find(".timesPlanned").html())-1);
		if(parseInt(eventOfTrack.find(".timesPlanned").html()) == 0)
			eventOfTrack.addClass("eventNotInTrack");
				
	});
	
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
	
	$(".editEventInPlanning").on("click", function() {
		newEvent = $(this).parents(".eventInEventBox");
		
		var thisTop = parseInt(newEvent.css("top"));
		var thisHeight = parseInt(newEvent.css("height"));
		
		var d = new Date(((thisTop-100)/hourHeight)*3600*1000);
		startHours = ((thisTop-100)/hourHeight);
		usingPopup.find("input[name=startTimeEvent]").val(d.toTimeString().split(' ')[0]);
		var d = new Date((((thisTop+thisHeight)-100)/hourHeight)*3600*1000);
		endHours = (((thisTop+thisHeight)-100)/hourHeight);
		usingPopup.find("input[name=endTimeEvent]").val(d.toTimeString().split(' ')[0]);
		$("#errMsgEventPlanning").text('');
		usingPopup .css("top",thisTop + thisHeight);
		track = $(this).parents(".eventBoxPlanning");
		usingPopup.appendTo(track);
		usingPopup.find("select[name=buildingSelect]").val(newEvent.attr("gebouw"));
		usingPopup.show();
		var selectedRooms = newEvent.attr("zaal").split(" ");
		editEventInPlanning(selectedRooms); 	
		roomTable.draw();
	});	
	
	
 });
 
 function editEventInPlanning(selectedRooms) {
	var selectedValue = usingPopup.find("select[name=buildingSelect]").val();
	isUpdate = true;
	$.ajax( {
		url: window.location.href,
		type: 'POST',
		data: {
			changeRoomsOnSelectChange: 'true',
			buildingName: selectedValue
		},
		success: function(data) {
			dataJSON = JSON.parse(data);
			refreshEditEventInPlanningRoomTable(dataJSON,selectedRooms);
		}
	});
 }
 
 function isValidInput() {
	if (!isValidStartAndEndTime(startHours, endHours)) {
		$("#errMsgEventPlanning").text("Starttijd mag niet na eindtijd zijn.");
		return false;
	}
	return true;
 }
 
 function refreshRoomTable(data) {
	 roomTable.rows().remove().draw(false);
	for(var i = 0;i < data.length; i++) {
		roomTable.row.add([data[i]]);
	}
	roomTable.row().draw();
 }
 
 
 function refreshEditEventInPlanningRoomTable(data,selectedRows) {
	 roomTable.rows().remove().draw(false);
	for(var i = 0;i < data.length; i++) {
		if($.inArray(data[i], selectedRows) != -1)
			roomTable.row.add([data[i]]).nodes().to$().addClass("selected");
		else
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
			eventUpdate: isUpdate,
			rooms: rooms,
			trackNo: track.attr("id"),
			eventNo: newEvent.attr("id")
		},
		success: function(data) {
			if (data != null && data != '' &&  /\S/.test(data)) {
				$("#errMsgEventPlanning").text(data);
			}
			else {
				usingPopup.hide();
				newEvent.find(".deleteEventFromPlanning").on("click",function() {
						$.ajax({
							url: window.location.href,
							type: 'POST',
							data : {
								deleteEventFromPlanning: 'delete',
								eventNo: $(this).parents('.eventInEventBox').attr("id"),
								trackNo: $(this).parents('.eventBoxPlanning').attr("id")
							}
						});
						$(this).parents('.eventInEventBox').hide("fade",function() { $(".beingAdded").remove();});
				});
				newEvent.removeClass("beingAdded");
				if(rooms.length == 0) {
					newEvent.addClass("eventNotInRoom");
				}else {
					newEvent.removeClass("eventNotInRoom");
				}
				if(!isUpdate) {
					eventOfTrack.removeClass("eventNotInTrack");
					eventOfTrack.find(".timesPlanned").text(parseInt(eventOfTrack.find(".timesPlanned").html())+1);
				}
				newEvent.attr("gebouw", usingPopup.find("select[name=buildingSelect]").val());
				var roomString = '';
				
				for(var i = 0; i < rooms.length; i++) {
					roomString += rooms[i] + ' ';
				}
				
				newEvent.attr("zaal", roomString);
			}
		}
	})
}
