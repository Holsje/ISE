$(document).ready(function () {
	$(".dataTables_scrollBody").removeAttr("style");
	$(".dataTables_scrollBody").addClass("scrollBody");
	
	$(".locationSelect").change(function() {
		var selectedValue = $(this).val();
		alert(getLocationName(selectedValue));
		$.ajax({
		url: window.location.href,
        type: 'POST',
        data: {
			LocationName: getLocationName(selectedValue),
			City: getLocationCity(selectedValue)
        },
		success: function(data) {
			console.log(data);
		}});
	})
});

function getLocationName(value) {
	locationArray = value.split(" ");
	locationArray.pop();
	locationArray.pop();
	returnString = '';
	for(var i = 0; i < locationArray.length; i++) {
		returnString += ' ' + locationArray[i]
	}
	return returnString;
}

function getLocationCity(value) {
	locationArray = value.split(" ");
	return locationArray.last();
}