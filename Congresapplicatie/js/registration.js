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
            console.log(data);
            alert('Registreren is gelukt, Login met u email en wachtwoord.');
            $('#popUpRegistration').find('.closePopup').trigger("click");
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

function isValidEmailAddress(email) {
    return /^[a-zA-Z0-9\._-]+@[a-zA-Z0-9]+\.+[a-zA-Z]{2,7}$/.test(email);
}

function isValidName(name) {
	return /^[a-zA-Z ]{3,50}$/.test(name);
}

function isValidTelephoneNumber(telnr) {
	return /^[+0-9-\.() ]{6,25}$/.test(telnr);
}

function isValidPassword(password) {
	return /^[a-zA-Z0-9-_\.@#$%^&*()]{8,64}$/.test(password);
}
