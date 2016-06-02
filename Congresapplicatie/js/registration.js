$(document).ready(function () {
    if(typeof document.forms['formRegistration'] !== 'undefined'){
	   document.forms["formRegistration"]["registrationSubmit"].onclick = isValidInput;
    }
});

function sendFormData() {
	var form = document.forms["formRegistration"];
	$.ajax({
		url: 'Registration.php',
		type: 'POST',
		data: {
			validInput: 'valid',
			firstName: form['firstName'].value,
			lastName: form['lastName'].value,
			mailAddress: form['mailAddress'].value,
			phoneNum: form['phoneNum'].value,
			password: form['password'].value
		},
        success: function(data){
			if ($.trim(data) == 'mailInUse') {
				$("[name=formRegistration]").append('<p class="errText">Er is al iemand geregistreerd onder dit mailadres. Probeer het met een ander mailadres.</p>');
			}
			else {
				$("[name=formRegistration]").append('<p class="successText">Registreren gelukt.</p>');
				$('#popUpRegistration').find('.closePopup').trigger("click");
			}
        }
	});
}

function isValidInput() {
	var form = document.forms["formRegistration"];
	if (!isValidName(form['firstName'].value)) {
		alert('Voornaam is niet geldig');
		return;
	}
	else if (!isValidName(form['lastName'].value)) {
		alert('Achternaam is niet geldig');
		return;
	}
	else if (!isValidEmailAddress(form['mailAddress'].value)) {
		alert('Mailadres is niet geldig');
		return;
	}
	else if (!isValidTelephoneNumber(form['phoneNum'].value)) {
		alert('Telefoonnummer is niet geldig');
		return;
	}
	else if (!isValidPassword(form['password'].value)) {
		alert('Wachtwoord is niet geldig');
		return;
	}
	else {
		sendFormData();
	}
}


