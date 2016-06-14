
function isValidEmailAddress(email) {
    return /^[a-zA-Z0-9\._-]+@[a-zA-Z0-9]+\.+[a-zA-Z]{2,7}$/.test(email);
}

function isValidName(name) {
	return /^[a-zA-Z ]{1,50}$/.test(name);
}

function isValidTelephoneNumber(telnr) {
    if(telnr == ""){
        return true;
    }
	return /^[+0-9-\.() ]{6,25}$/.test(telnr);
}

function isValidPassword(password) {
	return /^[a-zA-Z0-9-_\.!@#$%^&*()]{8,64}$/.test(password);
}

function isValidCongressName(name) {
    return /^[a-zA-Z0-9 ]{3,50}$/.test(name);
}

function isValidDate(date){
    if(date == ""){
        return true;
    }
    return /^(\d{4})-(\d{2})-(\d{2})$/.test(date);
}

function isValidPrice(price){
    if(price == ""){
        return true;
    }
    return (/^(\d{1,})[.](\d{1,})$/.test(price) || /^([0-9]{1,})$/.test(price));
}

function isValidLocationName(locationName) {
	return /^[a-zA-Z0-9-, ()./ ]{1,50}$/.test(locationName);
}

function isValidCityName(cityName) {
	return /^[a-zA-Z0-9-,()./\ ]{1,20}$/.test(cityName);
}

function isValidSmallInt(capacity) {
    if(capacity == parseInt(capacity)) {
		if((capacity > -32768) && (capacity < 32767)) {
			return true;
		}
	}
		return false;
}


function isValidStartAndEndTime(startTime, endTime) {
	if (isNaN(startTime) || isNaN(endTime)) {
		return false;
	}
	return startTime < endTime;
}

function isValidSmallIntNullable(capacity) {
    if(capacity == ""){
        return true;
    }
    if(capacity == parseInt(capacity)) {
		if((capacity > -32768) && (capacity < 32767)) {
			return true;
		}
	}
		return false;
}

function isValidPostalCode(postalCode) {
	return /^[a-zA-Z0-9]{1,6}$/.test(postalCode);
} 

function isValidDescription(description){
    if(description == ""){
        return true;
    }
    return (/^[a-zA-Z0-9-_+/-\=.,!@#$%^&*();\/\\|<>"' ~`€{}\[\]?’‘ÆÐƎƏƐƔĲŊŒẞÞǷȜæðǝəɛɣĳŋœĸſßþƿȝĄƁÇĐƊĘĦĮƘŁØƠŞȘŢȚŦŲƯY̨Ƴąɓçđɗęħįƙłøơşșţțŧųưy̨ƴÁÀÂÄǍĂĀÃÅǺĄÆǼǢƁĆĊĈČÇĎḌĐƊÐÉÈĖÊËĚĔĒĘẸƎƏƐĠĜǦĞĢƔáàâäǎăāãåǻąæǽǣɓćċĉčçďḍđɗðéèėêëěĕēęẹǝəɛġĝǧğģɣĤḤĦIÍÌİÎÏǏĬĪĨĮỊĲĴĶƘĹĻŁĽĿʼNŃN̈ŇÑŅŊÓÒÔÖǑŎŌÕŐỌØǾƠŒĥḥħıíìiîïǐĭīĩįịĳĵķƙĸĺļłľŀŉńn̈ňñņŋóòôöǒŏōõőọøǿơœŔŘŖŚŜŠŞȘṢẞŤŢṬŦÞÚÙÛÜǓŬŪŨŰŮŲỤƯẂẀŴẄǷÝỲŶŸȲỸƳŹŻŽẒŕřŗſśŝšşșṣßťţṭŧþúùûüǔŭūũűůųụưẃẁŵẅƿýỳŷÿȳỹƴźżžẓ]{1,1000}$/.test(description));
}