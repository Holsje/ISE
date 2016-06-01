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
