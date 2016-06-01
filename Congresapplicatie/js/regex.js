
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

function isValidCongressName(name) {
    return /^[a-zA-Z0-9 ]{3,50}$/.test(name);
}

function isValidDate(date){
    return /^(\d{4})-(\d{2})-(\d{2})$/.test(date);
}

function isValidPrice(price){
    return (/^(\d{1,})[.](\d{1,})$/.test(price) || /^([0-9]{1,})$/.test(price));
}
