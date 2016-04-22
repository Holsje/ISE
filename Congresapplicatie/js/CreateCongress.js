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
			
		makeError("Not all fields are filled in.");
		return false;
	}
	
	if(!isValidDate(startDate) || !isValidDate(endDate)) {
		makeError("Invalid start or end date.");
		return false;
	}
	
	var d1 = new Date(startDate);
	var d2 = new Date(endDate);
	if(d1 > d2) {
		makeError("Start date is before end date.");
		return false;
	}
	
	return true;
	
}

function validateDate() {
	var startDate = document.forms["CreateCongressForm"]["StartDate"].value;
	var endDate = document.forms["CreateCongressForm"]["EndDate"].value;
	if(startDate == "" || endDate == "") {
		return;
	}
	if(validateForm()) {
		removeError();
	}
}

function addSubject() {
	var newSubject = document.forms["addSubjectForm"]["newSubject"];
	if(newSubject.value == "" || newSubject == null) {
	
		return;
	}else {
		var newSubjectOption = document.createElement("option");
		var text = document.createTextNode(newSubject.value);
		newSubjectOption.value = newSubject.value;
		newSubjectOption.appendChild(text);
		
		document.forms["CreateCongressForm"]["Subject"].appendChild(newSubjectOption);
		
	
	}
}


