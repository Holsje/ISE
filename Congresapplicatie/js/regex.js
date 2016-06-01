function isValidCongressName(name) {
    return /^[a-zA-Z0-9 ]{3,50}$/.test(name);
}

function isValidDate(date){
    return /^(\d{4})-(\d{2})-(\d{2})$/.test(date);
}

function isValidPrice(price){
    return (/^(\d{1,})[.](\d{1,})$/.test(price) || /^([0-9]{1,})$/.test(price));
}
