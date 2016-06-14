var oldCongressName,
    oldCongressStartDate,
    oldCongressEndDate,
    oldCongressPrice,
    oldCongressPublic,
    oldCongressBanner,
    subjectTableUpdate,
    congressNo;
var oldManagers = [];
$(document).ready(function () {

    // /Edit
    subjectTableUpdate = $('#subjectListBoxUpdate').DataTable( {
        "sScrollY": "500px",
        "bPaginate": false,
        "bInfo": false,
        "language": {
            "emptyTable": "Geen data beschikbaar",
            "sSearch": "Zoeken:"
        }
    });

//Edit
    document.forms['formUpdateCongress']['updateCongress'].onclick = isValidInput;
    document.forms['formUpdateCongress']['editCongressManagers'].onclick = getManagerInfo;
    document.forms['formcongresBeheerders']['buttonSaveSwapListcongresBeheerders'].onclick = updateCongressManagers;

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

    $('#popUpManagersToCongress .closePopup').on('click',function(){
        $('#listBoxCongressManagerLeft').DataTable().clear();
        $('#listBoxCongressManagerRight').DataTable().clear();
    });
    getCongressInfo();

});

function getManagerInfo(){
    $.ajax({
        url: window.location.href,
        type: 'POST',
        data: {
          getManagerInfo: 'action' 
        },
        success: function(data){
            data = JSON.parse(data);
            var tableLeft = $('#listBoxCongressManagerLeft').DataTable();
            for(i = 0; i< data['congress'].length; i++){
                oldManagers.push(data['congress'][i]);
                tableLeft.row.add([data['congress'][i][0],data['congress'][i][1],data['congress'][i][2],data['congress'][i][3]]);
            }
            var tableRight = $('#listBoxCongressManagerRight').DataTable();
            for(i = 0; i< data['all'].length; i++){
                tableRight.row.add([data['all'][i][0],data['all'][i][1],data['all'][i][2],data['all'][i][3]]);
            }
            
            tableLeft.rows().draw();
            tableRight.rows().draw();
            
            
        }
    });
}

function updateCongressManagers(){
    var table = $('#listBoxCongressManagerLeft').DataTable();
    var newManagers= calcNewManagers(table);
    var deleteManagers = calcDeleteManagers(table);
    console.log('new:' + newManagers);
    console.log('del:' + deleteManagers);
    $.ajax({
        url: window.location.href,
        type: 'POST',
        data: {
            addManagerOfCongress: 'Action',
            addingManagers: newManagers,
            deletingManagers: deleteManagers
        },
        success: function(data){
            console.log(data);
            if (data != null && data != '' &&  /\S/.test(data)) {
                if(data == 'err'){
                    alert('U kunt niet u zelf verwijderen als congres beheerder van dit congres. \nEr zijn geen wijzigingen doorgevoerd. \nNeem contact op met algemene beheerder als u deze actie toch wilt uitvoeren.');
                }
                else{
                    $('#errMsgManagersToCongress').text(data);
                }
            }
            else{
                window.location.href = window.location.protocol +'//'+ window.location.host + window.location.pathname;
            }
        }
    });
}

function updateCongressInfo(congressName, congressStartDate, congressEndDate, congressPrice, congressBanner, congressPublic, congressSubjects) {
    document.forms["formUpdateCongress"]["congressName"].value = congressName;
    document.forms["formUpdateCongress"]["congressStartDate"].value = congressStartDate;
    document.forms["formUpdateCongress"]["congressEndDate"].value = congressEndDate;
    if (congressPrice = .0000) {
        congressPrice = 0;
    }
    document.forms["formUpdateCongress"]["congressPrice"].value = parseInt(congressPrice).toFixed(2);

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
        $("#errMsgUpdateCongress").text('Congresnaam is niet geldig');
        return;
    }
    else if (!isValidDate(form['congressStartDate'].value)) {
        $("#errMsgUpdateCongress").text('Startdatum is niet geldig');
        return;
    }
    else if (!isValidDate(form['congressEndDate'].value)) {
        $("#errMsgUpdateCongress").text('Einddatum is niet geldig');
        return;
    }
    else if (!isValidPrice(form['congressPrice'].value)) {
        $("#errMsgUpdateCongress").text('Prijs is niet geldig. Let op een prijs moet met een punt ingevuld worden niet met een komma.');
        return;
    }
    else if (form['congressStartDate'].value > form['congressEndDate'].value){
        $("#errMsgUpdateCongress").text('Eind datum mag niet voor begin datum liggen.');
    }
    else {
        onUpdateCongress();
    }
}

function calcNewManagers(dataTable){
    var returnArray =[];
    for(i =0; i< dataTable.rows().data().length; i++){
        var continueLoop = true;
        for(j = 0; j < oldManagers.length; j++){
            if(continueLoop){
                if(dataTable.rows().data()[i][0] == oldManagers[j][0]){
                    continueLoop = false;
                }
            }
        }if(continueLoop){
            returnArray.push(dataTable.rows().data()[i][0]);
        }
    }
    return returnArray;
}

function calcDeleteManagers(dataTable){
    var returnArray =[];
    for(i =0; i< oldManagers.length; i++){
        var continueLoop = true;
        for(j = 0; j < dataTable.rows().data().length; j++){
            if(continueLoop){
                if(dataTable.rows().data()[j][0] == oldManagers[i][0]){
                    continueLoop = false;
                }
            }
        }if(continueLoop){
            returnArray.push(oldManagers[i][0]);
        }
    }
    return returnArray;
}