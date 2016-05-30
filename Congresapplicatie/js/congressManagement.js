var congressNo,
    oldCongressName,
    oldCongressStartDate,
    oldCongressEndDate,
    oldCongressPrice,
    oldCongressBanner,
    oldCongressPublic,
    oldCongressSubjects,
    subjectTableAdd,
    subjectTableUpdate;
/*
var amountOfSelects = 1;
*/

$(document).ready(function () {

    subjectTableAdd = $('#subjectListBoxAdd').DataTable( {
        "sScrollY": "500px",
        "bPaginate": false,
        "bInfo": false
    });

    subjectTableUpdate = $('#subjectListBoxUpdate').DataTable( {
        "sScrollY": "500px",
        "bPaginate": false,
        "bInfo": false
    });

    document.forms["formCreateCongress"]["buttonDelete"].onclick = deleteCongress;
    document.forms["formCreateCongress"]["buttonEdit"].onclick = getCongressInfo;
    document.forms['formUpdate']['updateCongress'].onclick = onUpdateCongress;
    document.forms['formAddSubjectFromEdit']['Bewerken'].onclick = submitAddSubject;
    document.forms['formAdd']['Toevoegen'].onclick = onCreateCongress;

    $('#subjectListBoxAdd tbody').on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
            $('.onSelected').prop('disabled', true);
        } else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            $('.onSelected').prop('disabled', false);
        }
    });

    $('#subjectListBoxUpdate tbody').on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
            $('.onSelected').prop('disabled', true);
        } else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            $('.onSelected').prop('disabled', false);
        }
    });


    $('html').keyup(function (e) {
        if (e.keyCode == 46) {
            deleteCongress();
        }
    });

    /*
    $("#selectSubject1").bind('change',function(event){
        addSelect();
    });
    */
});

function getCongressInfo(congressNo){
    var selectedRow = table.row('.selected');
    if(selectedRow.data()) {
        $.ajax({
            url: window.location.href,
            type: 'POST',
            data: {
                getCongressInfo: 'GetCongressInfo',
                congressNo: selectedRow.data()[0]
            },
            success: function (data) {
                data = JSON.parse(data);
                congressNo = data['congressNo'];
                oldCongressName = data['CName'];
                oldCongressStartDate = parseDate(data['Startdate']['date']);
                oldCongressEndDate = parseDate(data['Enddate']['date']);
                oldCongressPrice = data['Price'];
                oldCongressBanner = data['Banner'];
                oldCongressPublic = data['Public'];
                oldCongressSubjects = data['subjects']
                updateCongressInfo(oldCongressName, oldCongressStartDate, oldCongressEndDate, oldCongressPrice, oldCongressBanner, oldCongressPublic, oldCongressSubjects);
            },
            error: function (request, status, error) {
                alert(request.responseText);
            }
        });
    }
    else{
        alert("Er is geen selectie gemaakt");
        return false;
    }
}

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
        oldCongressStartDate = selectedRow.data()[2];
        oldCongressEndDate = selectedRow.data()[3];
        updateCongressInfo(oldCongressName, oldCongressStartDate, oldCongressEndDate);
    } else {
        alert("Er is geen selectie gemaakt");
        return false;
    }
}


function updateCongressInfo(congressName, congressStartDate, congressEndDate, congressPrice, congressBanner, congressPublic, congressSubjects) {
    document.forms["formUpdate"]["congressName"].value = congressName;
    document.forms["formUpdate"]["congressStartDate"].value = congressStartDate;
    document.forms["formUpdate"]["congressEndDate"].value = congressEndDate;


    for (var key in congressSubjects){
        var obj = congressSubjects[key];
        console.log(obj.Subject);
        $("#subjectListBoxUpdate td:contains(" + obj.Subject + ")").parent('tr').addClass("selected");
    }
}


function getSelectedSubjects(){

    var selectedSubjects = subjectTableAdd.rows(".selected");
    var size = selectedSubjects.data().length;
    var array = [];
    console.log(selectedSubjects);
    for (var i = 0; i < size; i++){
        console.log(selectedSubjects.data()[i][0]);
        array.push(selectedSubjects.data()[i][0]);
    }
    return array;
}


function onUpdateCongress() {
    $.ajax({
        url: window.location.href,
        type: 'POST',
        data: {
            bewerken: 'action',
            congressNo: congressNo,

            oldCongressName: oldCongressName,
            oldCongressStartDate: oldCongressStartDate,
            oldCongressEndDate: oldCongressEndDate,
            oldCongressSubjects: oldCongressSubjects,

            newCongressName: document.forms['formUpdate']["congressName"].value,

            newCongressStartDate: document.forms['formUpdate']["congressStartDate"].value,
            newCongressEndDate: document.forms['formUpdate']["congressEndDate"].value,

            selectedSubjects: getSelectedSubjects()


        },
        success: function (data) {

            if (data != null && data != '' &&  /\S/.test(data)) {
                data = JSON.parse(data);
                document.getElementsByName('errMsgBewerken')[0].innerHTML = '*' + data['err'];
                var confirmBox = confirm(data['err']);
                if (confirmBox) {

                } else {
                    var startDate = parseDate(data['StartDate']['date']);
                    var endDate = parseDate(data['EndDate']['date']);
                    updateCongressInfo(data['Name'], data['Subject'], data['Location'], startDate, endDate);
					oldCongressName = data['Name'];
					oldCongressStartDate = startDate;
					oldCongressEndDate = endDate;
                }
            } else {
                location.reload();
            }
        }

    });
}


function onCreateCongress() {
    $.ajax({
        url: window.location.href,
        type: 'POST',
        data: {
            toevoegen: 'action',

            congressName: document.forms['formAdd']["congressName"].value,
            startDate: document.forms['formAdd']["startDate"].value,
            endDate: document.forms['formAdd']["endDate"].value,
            Price: "1",
            Public: "0",
            Banner: "banner.jpg",
            selectedSubjects: getSelectedSubjects()

        },
        success: function (data) {
            console.log(data);
            alert("Uw congres is succesvol toegevoegd.");

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

/*
function addSelect() {
    amountOfSelects++;
    var nameSelect = "congressSubject"+(amountOfSelects-1);
    var lastSelect = $('select[name^="congressSubject"]:last');
    console.log(lastSelect);
    document.forms["formAdd"].appendChild;
    lastSelect.clone(true).prop('id', 'selectSubject'+amountOfSelects).prop('name', 'congressSubject'+amountOfSelects).appendTo('[name="formAdd"]');
    var newSelect = document.getElementById("#selectSubject"+amountOfSelects);
    console.log(newSelect);
    newSelect.removeChild(lastSelect[lastSelect.selectedIndex]);
    $("#selectSubject"+(amountOfSelects-1)).unbind();

    $("#selectSubject"+(amountOfSelects)).bind('change',function(event){
        addSelect();
    });

}
*/
