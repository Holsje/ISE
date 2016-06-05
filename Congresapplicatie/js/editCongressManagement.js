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
    document.forms['formUpdateCongress']['updateCongress'].onclick = isValidInput;

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


function updateCongressInfo(congressName, congressStartDate, congressEndDate, congressPrice, congressBanner, congressPublic, congressSubjects) {
    document.forms["formUpdateCongress"]["congressName"].value = congressName;
    document.forms["formUpdateCongress"]["congressStartDate"].value = congressStartDate;
    document.forms["formUpdateCongress"]["congressEndDate"].value = congressEndDate;
    document.forms["formUpdateCongress"]["congressPrice"].value = congressPrice;

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


        $("#subjectListBoxUpdate").find('[name="' + obj.Subject + '"]').parent().addClass('selected');
    }

    oldCongressSubjects = arraySubjectsOld;
}



//Edit
function onUpdateCongress() {
    $.ajax({
        url: window.location.href,
        type: 'POST',
        data: {
            UpdateCongress: 'action',

            oldCongressName: oldCongressName,
            oldCongressStartDate: oldCongressStartDate,
            oldCongressEndDate: oldCongressEndDate,
            oldCongressPrice: oldCongressPrice,
            oldCongressPublic: oldCongressPublic,
            oldCongressSubjects: oldCongressSubjects,

            newCongressName: document.forms['formUpdateCongress']["congressName"].value,

            newCongressStartDate: document.forms['formUpdateCongress']["congressStartDate"].value,
            newCongressEndDate: document.forms['formUpdateCongress']["congressEndDate"].value,
            newCongressPrice: document.forms['formUpdateCongress']["congressPrice"].value,
            newCongressPublic: document.forms['formUpdateCongress']["congressPublic"].value,
            selectedSubjects: getSelectedSubjects("Update")


        },
        success: function (data) {

            if (data != null && data != '' &&  /\S/.test(data)) {
                data = JSON.parse(data);
                document.getElementById('errMsgUpdateCongress').innerHTML = '*' + data['err'];
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


function getCongressInfo(){
        $.ajax({
            url: window.location.href,
            type: 'POST',
            data: {
                getCongressInfo: 'GetCongressInfo',
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
                oldCongressSubjects = data['subjects'];
                $('#bannerPicBtn').css("background-image", 'url(../' + oldCongressBanner + ')');
                updateCongressInfo(oldCongressName, oldCongressStartDate, oldCongressEndDate, oldCongressPrice, oldCongressBanner, oldCongressPublic, oldCongressSubjects);
            },
            error: function (request, status, error) {
                alert(request.responseText);
            }
        });
}

function isValidInput(){
    var form = document.forms["formUpdateCongress"];
    if (!isValidCongressName(form['congressName'].value)) {
        alert('Congresnaam is niet geldig');
        return;
    }
    else if (!isValidDate(form['congressStartDate'].value)) {
        alert('Startdatum is niet geldig');
        return;
    }
    else if (!isValidDate(form['congressEndDate'].value)) {
        alert('Einddatum is niet geldig');
        return;
    }
    else if (!isValidPrice(form['congressPrice'].value)) {
        alert('Prijs is niet geldig. Let op een prijs moet met een punt ingevuld worden niet met een komma.');
        return;
    }
    else {
        onUpdateCongress();
    }
}