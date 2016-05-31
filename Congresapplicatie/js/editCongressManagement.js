var oldCongressName,
    oldCongressStartDate,
    oldCongressEndDate,
    oldCongressPrice,
    oldCongressPublic,
    oldCongressBanner,
    subjectTableUpdate,
    congressNo;

$(document).ready(function () {

    // /Edit
    subjectTableUpdate = $('#subjectListBoxUpdate').DataTable( {
        "sScrollY": "500px",
        "bPaginate": false,
        "bInfo": false
    });

//Edit
    document.forms['formAddSubjectFromEdit']['Bewerken'].onclick = submitAddSubjectEdit;
    document.forms['formUpdateCongress']['updateCongress'].onclick = onUpdateCongress;

    //Edit
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


    getCongressInfo();
});

//Edit mag weg
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
    document.forms["formUpdateCongress"]["congressName"].value = congressName;
    document.forms["formUpdateCongress"]["congressStartDate"].value = congressStartDate;
    document.forms["formUpdateCongress"]["congressEndDate"].value = congressEndDate;
    document.forms["formUpdateCongress"]["congressPrice"].value = congressPrice;
    document.forms["formUpdateCongress"]["congressBanner"].value = congressBanner;
    if (congressPublic == 0) {
        document.forms["formUpdateCongress"]["congressPublic"].value = "Nee";
    }
    else if (congressPublic == 1){
        document.forms["formUpdateCongress"]["congressPublic"].value = "Ja";
    }


    arraySubjectsOld = [];

    for (var key in congressSubjects){
        var obj = congressSubjects[key];
        arraySubjectsOld.push(obj.Subject);
        $("#subjectListBoxUpdate td:contains(" + obj.Subject + ")").parent('tr').addClass("selected");
    }

    oldCongressSubjects = arraySubjectsOld;
}



//Edit
function onUpdateCongress() {
    $.ajax({
        url: window.location.href,
        type: 'POST',
        data: {
            bewerken: 'action',
            congressNo: 1,

            oldCongressName: oldCongressName,
            oldCongressStartDate: oldCongressStartDate,
            oldCongressEndDate: oldCongressEndDate,
            oldCongressPrice: oldCongressPrice,
            oldCongressPublic: oldCongressPublic,
            oldCongressBanner: oldCongressBanner,
            oldCongressSubjects: oldCongressSubjects,

            newCongressName: document.forms['formUpdateCongress']["congressName"].value,

            newCongressStartDate: document.forms['formUpdateCongress']["congressStartDate"].value,
            newCongressEndDate: document.forms['formUpdateCongress']["congressEndDate"].value,
            newCongressPrice: document.forms['formUpdateCongress']["congressPrice"].value,
            newCongressPublic: document.forms['formUpdateCongress']["congressPublic"].value,
            newCongressBanner: document.forms['formUpdateCongress']["congressBanner"].value,
            selectedSubjects: getSelectedSubjects("Update")


        },
        success: function (data) {
            alert("Test");
            if (data != null && data != '' &&  /\S/.test(data)) {
                console.log(data);
                data = JSON.parse(data);
                document.getElementsByName('errMsgBewerken')[0].innerHTML = '*' + data['err'];
                var confirmBox = confirm(data['err']);
                if (confirmBox) {

                } else {
                    var startDate = parseDate(data['newCongressStartDate']['date']);
                    var endDate = parseDate(data['newCongressEndDate']['date']);
                    updateCongressInfo(data['newCongressName'], startDate, endDate, data['newCongressPrice'], data['newCongressBanner'], data['selectedSubjects']);
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

//Both
function getSelectedSubjects(){
    var selectedSubjects = subjectTableUpdate.rows(".selected");

    var size = selectedSubjects.data().length;
    var array = [];
    for (var i = 0; i < size; i++){
        array.push(selectedSubjects.data()[i][0]);
    }
    return array;
}

//Edit
function submitAddSubjectEdit() {
    $('#popUpAddSubjectFromEdit .closePopUp').click();
    var newSubject = document.forms['formAddSubjectFromEdit']['subjectName'].value;
    console.log([newSubject]);
    subjectTableUpdate.row.add([newSubject]).draw(true).nodes().to$().addClass('selected');
}


function getCongressInfo(){
        $.ajax({
            url: window.location.href,
            type: 'POST',
            data: {
                getCongressInfo: 'GetCongressInfo',
                //Hier komt het meegegeven congresnummer
                congressNo: 1
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