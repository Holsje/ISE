 $(function() {
    $( ".datepicker" ).datepicker();
  });
  
  
function validateForm() {
	var congressName = document.forms["CreateCongressForm"]["CongressName"].value;
	var location = document.forms["CreateCongressForm"]["Location"].value;
	var subject = document.forms["CreateCongressForm"]["Subject"].value;
	var startDate = document.forms["CreateCongressForm"]["StartDate"].value;
	var endDate = document.forms["CreateCongressForm"]["EndDate"].value;
	if(congressName == null || congressName == "" || location == null || location == "" ||
		subject == null || subject == "" || startDate == null || startDate == "" || 
		endDate == null || endDate == "") {
			
		alert("Not all fields are filled in.");
		return false;
	}
	
	if(!isValidDate(startDate) || !isValidDate(endDate)) {
		alert("Invalid start or end date.");
		return false;
	}
	
	var d1 = new Date(startDate);
	var d2 = new Date(endDate);
	if(d1 > d2) {
		alert("Start date is before end date.");
		return false;
	}
}