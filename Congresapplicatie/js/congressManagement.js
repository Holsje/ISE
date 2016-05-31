var congressNo,
    oldCongressName,
    oldCongressStartDate,
    oldCongressEndDate,
    oldCongressPrice,
    oldCongressBanner,
    oldCongressPublic,
    oldCongressSubjects,
    subjectTableAdd;


$(document).ready(function () {
    //Create
    subjectTableAdd = $('#subjectListBoxAdd').DataTable( {
        "sScrollY": "500px",
        "bPaginate": false,
        "bInfo": false
    });




//Create
    document.forms["formCreateCongress"]["buttonDelete"].onclick = deleteCongress;
    document.forms["formCreateCongress"]["buttonEdit"].onclick = goToEditCongress;
    document.forms['formAddSubjectFromAdd']['Toevoegen'].onclick = submitAddSubjectAdd;
    document.forms['formAdd']['Toevoegen'].onclick = onCreateCongress;


    //Create
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


    //Create
    $('html').keyup(function (e) {
        if (e.keyCode == 46) {
            deleteCongress();
        }
    });

});

//Create
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

//Both
function getSelectedSubjects(){
    var selectedSubjects = subjectTableAdd.rows(".selected");
    var size = selectedSubjects.data().length;
    var array = [];
    for (var i = 0; i < size; i++){
        array.push(selectedSubjects.data()[i][0]);
    }
    return array;
}


//Create
function onCreateCongress() {
    $.ajax({
        url: window.location.href,
        type: 'POST',
        data: {
            toevoegen: 'action',

            congressName: document.forms['formAdd']["congressName"].value,
            startDate: document.forms['formAdd']["congressStartDate"].value,
            endDate: document.forms['formAdd']["congressEndDate"].value,
            Price: document.forms['formAdd']["congressPrice"].value,
            Public: "0",
            selectedSubjects: getSelectedSubjects("Add")

        },
        success: function (data) {
            console.log(data);
            alert("Uw congres is succesvol toegevoegd.");

        }

    });
}


//Create
function submitAddSubjectAdd(){
    $('#popUpAddSubjectFromAdd .closePopUp').click();
    var newSubject = document.forms['formAddSubjectFromAdd']['subjectName'].value;
    console.log([newSubject]);
    subjectTableAdd.row.add([newSubject]).draw(true).nodes().to$().addClass('selected');
}

function goToEditCongress(){
    var selectedRow = table.row('.selected');
    var congressNo = selectedRow.data()[0];

    if (selectedRow.data()) {
        alert("Test");
        $.ajax({
            url: window.location.href,
            type: 'POST',
            data: {
                goToEdit: 'action',
                congressNo: congressNo
            },
            success: function (data) {
                alert("Hoi");
                window.location.href = "manage.php";
            },
            error: function (request, status, error) {
                alert(request.responseText);
            }
        });
    } else{
        alert("Er is geen selectie gemaakt");
        return false;
    }
}