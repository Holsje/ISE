var congressNo,
    oldCongressName,
    oldCongressLocation,
    oldCongressSubject,
    oldCongressStartDate,
    oldCongressEndDate;

$(document).ready(function () {
    document.forms["formCreateCongress"]["buttonDelete"].onclick = deleteCongress;
    document.forms["formCreateCongress"]["buttonEdit"].onclick = fillUpdateCongressInfo;
    document.forms['formUpdate']['updateCongress'].onclick = onUpdateCongress;
    document.forms['formAddSubjectFromEdit']['Bewerken'].onclick = submitAddSubject;

    $('html').keyup(function (e) {
        if (e.keyCode == 46) {
            deleteCongress();
        }
    });
});

function deleteCongress() {
    var selectedRow = table.row('.selected');
    if (selectedRow.data()) {
        if (confirm("Weet u zeker dat u deze rij wilt verwijderen?")) {
            $.ajax({
                url: window.location.href,
                type: 'POST',
                data: {
                    verwijderen: 'Verwijderen',
                    congressNo: selectedRow.data()[0]
                }
            });
            selectedRow.remove().draw(false);
        }
    } else {
        alert("Er is geen selectie gemaakt");
    }
}

function fillUpdateCongressInfo() {
    var selectedRow = table.row('.selected');
    if (selectedRow.data()) {
        congressNo = selectedRow.data()[0];
        oldCongressName = selectedRow.data()[1];
        oldCongressSubject = selectedRow.data()[2];
        oldCongressLocation = selectedRow.data()[3];
        oldCongressStartDate = selectedRow.data()[4];
        oldCongressEndDate = selectedRow.data()[5];
        updateCongressInfo(oldCongressName, oldCongressSubject, oldCongressLocation, oldCongressStartDate, oldCongressEndDate);
    } else {
        alert("Er is geen selectie gemaakt");
        return false;
    }
}

function updateCongressInfo(congressName, congressSubject, congressLocation, congressStartDate, congressEndDate) {
    document.forms["formUpdate"]["congressName"].value = congressName;
    document.forms["formUpdate"]["congressSubject"].value = congressSubject;
    document.forms["formUpdate"]["congressLocation"].value = congressLocation;
    document.forms["formUpdate"]["congressStartDate"].value = congressStartDate;
    document.forms["formUpdate"]["congressEndDate"].value = congressEndDate;
}

function onUpdateCongress() {
    $.ajax({
        url: window.location.href,
        type: 'POST',
        data: {
            bewerken: 'action',
            congressNo: congressNo,

            oldCongressName: oldCongressName,
            oldCongressSubject: oldCongressSubject,
            oldCongressLocation: oldCongressLocation,
            oldCongressStartDate: oldCongressStartDate,
            oldCongressEndDate: oldCongressEndDate,

            newCongressName: document.forms['formUpdate']["congressName"].value,
            newCongressSubject: document.forms['formUpdate']["congressSubject"].value,
            newCongressLocation: document.forms['formUpdate']["congressLocation"].value,
            newCongressStartDate: document.forms['formUpdate']["congressStartDate"].value,
            newCongressEndDate: document.forms['formUpdate']["congressEndDate"].value
        },
        success: function (data) {

            if (data != null && data != '') {
                data = JSON.parse(data);
                document.getElementsByName('errMsgBewerken')[0].innerHTML = '*' + data['err'];
                var confirmBox = confirm(data['err']);
                if (confirmBox) {

                } else {
                    var startDate = parseDate(data['StartDate']['date']);
                    var endDate = parseDate(data['EndDate']['date']);
                    updateCongressInfo(data['Name'], data['Subject'], data['Location'], startDate, endDate);
                }
            } else {
                location.reload();
            }
        }

    });
}

function submitAddSubject() {
    $('#popUpAddSubjectFromEdit .closePopUp').click();
    var selector = document.forms['formUpdate']['congressSubject'];
    var newSubject = document.forms['formAddSubjectFromEdit']['subjectName'].value;
    for (i = 0; i < selector.options.length; i++) {
        if (selector.options[i].value == newSubject) {
            selector.options[i].selected = 'true';
            document.forms['formAddSubjectFromEdit']['subjectName'].value = '';
            return;
        }
    }
    var option = document.createElement('option');
    option.value = newSubject;
    option.innerHTML = newSubject;
    option.selected = 'true';
    document.forms['formAddSubjectFromEdit']['subjectName'].value = '';
    selector.add(option);
}
