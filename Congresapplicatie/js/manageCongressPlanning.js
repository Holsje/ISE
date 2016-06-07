 var track;
 var eventOfTrack;
 var hourHeight = 100;
 var asdfds;
 var offSetTop;
 $(document).ready(function(){
	$(".eventBoxPlanning").droppable({ 
		drop: function(event,ui) { 
			track = $(this);  
			eventOfTrack = $(ui.draggable); 
			offSetTop = eventOfTrack.offset().top - track.offset().top;
			offSetTop = Math.round(offSetTop/(hourHeight/4))*(hourHeight/4);			
			$(".eventPlanningPopUp").hide();
			$(".beingAdded").remove();			
			var newEvent = eventOfTrack.clone().appendTo(track);
			newEvent.css("left",0);
			newEvent.css("top",offSetTop);
			newEvent.addClass("beingAdded");
			var usingPopup = track.find(".eventPlanningPopUp");
			var d = new Date(Math.round((offSetTop-100)/hourHeight*4)*900*1000);
			usingPopup.find("input[name=startTimeEvent]").val(d.toTimeString().split(' ')[0]);
			d.setHours(d.getHours()+1);
			usingPopup.find("input[name=endTimeEvent]").val(d.toTimeString().split(' ')[0]);
			usingPopup.css("top", (offSetTop+100));
			usingPopup.show();
		}
	});
	
	$(".eventPlanningBox .eventInEventBox ").draggable({
		revert: true
	});
	 
 });
 
function sendEventPopUpData() {
	
}
