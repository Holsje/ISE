var managerTable;

$(document).ready(function () {
	managerTable =  $('#manageManagersListBox').DataTable({
        "scrollY":        "500px",
        "scrollCollapse": true,
        "paging":         false,
        "language": {
           "emptyTable": "Geen data beschikbaar"
        },
        "columnDefs": [{
				"targets": [0],
				"visible": false,
				"searchable": false			
        }] 
    });
    if(document.forms['formAddmanageManagers']){
        document.forms['formAddmanageManagers'].onsubmit = function () {
            if(!isValidName(document.forms['formAddmanageManagers']['FirstName'].value)){
                $('#errMsgAddManager').text ('Voornaam is onjuist.');
                return false;
            }
            if(!isValidName(document.forms['formAddmanageManagers']['LastName'].value)){
                $('#errMsgAddManager').text ('Achternaam is onjuist.');
                return false;
            }
            if(!isValidEmailAddress(document.forms['formAddmanageManagers']['mailAddress'].value)){
                $('#errMsgAddManager').text ('Mailadres is onjuist.');
                return false;
            }
            if(!isValidPassword(document.forms['formAddmanageManagers']['password'].value)){
                $('#errMsgAddManager').text ('Wachtwoord is onjuist.');
                return false;
            }
            if(!isValidTelephoneNumber(document.forms['formAddmanageManagers']['phoneNumber'].value)){
                $('#errMsgAddManager').text ('Telefoonnummer is onjuist.');
                return false;
            }
        }
    }
    
    if(document.forms['formUpdatemanageManagers']){
        document.forms['formUpdatemanageManagers'].onsubmit = function () {
            if(!isValidName(document.forms['formUpdatemanageManagers']['FirstName'].value)){
                $('#errMsgEditManager').text ('Voornaam is onjuist.');
                return false;
            }
            if(!isValidName(document.forms['formUpdatemanageManagers']['LastName'].value)){
                $('#errMsgEditManager').text ('Achternaam is onjuist.');
                return false;
            }
            if(!isValidEmailAddress(document.forms['formUpdatemanageManagers']['mailAddress'].value)){
                $('#errMsgEditManager').text ('Mailadres is onjuist.');
                return false;
            }
            if(!isValidPassword(document.forms['formUpdatemanageManagers']['password'].value)){
                $('#errMsgEditManager').text ('Wachtwoord is onjuist.');
                return false;
            }
            if(!isValidTelephoneNumber(document.forms['formUpdatemanageManagers']['phoneNumber'].value)){
                $('#errMsgEditManager').text ('Telefoonnummer is onjuist.');
                return false;
            }
        }
    }
    document.forms['formCreatemanageManagers']['buttonEditmanageManagers'].onclick = getManagerInfo;
    document.forms['formCreatemanageManagers']['buttonDeletemanageManagers'].onclick = deleteManager;
});

function getManagerInfo(){
    var data = managerTable.row('.selected').data();
    document.forms['formUpdatemanageManagers']['FirstName'].value = data[1];
    document.forms['formUpdatemanageManagers']['LastName'].value = data[2];
    document.forms['formUpdatemanageManagers']['mailAddress'].value = data[3];
    document.forms['formUpdatemanageManagers']['phoneNumber'].value = data[4];
    document.forms['formUpdatemanageManagers']['AanpassenManager'].value = data[0];
    
    switch(data[5]){
        case 'Beide':
            document.forms['formUpdatemanageManagers']['isGM'].checked = true;
            document.forms['formUpdatemanageManagers']['isCM'].checked = true;
            break;
        case 'Congresbeheerder':
            document.forms['formUpdatemanageManagers']['isGM'].checked = false;
            document.forms['formUpdatemanageManagers']['isCM'].checked = true;
            break;
        case 'Algemene beheerder':
            document.forms['formUpdatemanageManagers']['isGM'].checked = true;
            document.forms['formUpdatemanageManagers']['isCM'].checked = false;
            break;
    }
}

function deleteManager(){
    var personNo = managerTable.row('.selected').data()[0];
    var type = managerTable.row('.selected').data()[5];
    if(confirm('Weet u zeker dat u deze Beheerder wilt verwijderen?'))
    $.ajax({
        url: window.location.href,
        type: 'POST',
        data: {
            VerwijderManager: 'action',
            personNo: personNo,
            personType: type 
        },
        success: function(data){
            managerTable.row('.selected').remove().draw();
        }
    });
}















