var congressNo,
    oldCongressName,
    oldCongressStartDate,
    oldCongressEndDate,
    oldCongressPrice,
    oldCongressBanner,
    oldCongressPublic,
    oldCongressSubjects,
    subjectTableAdd,
    file;

var table;
$(document).ready(function () {
    //Create
    subjectTableAdd = $('#subjectListBoxAdd').DataTable( {
        "sScrollY": "500px",
        "bPaginate": false,
        "bInfo": false
    });

	table = $('#ListBox').DataTable( {
        "sScrollY": "500px",
        "bPaginate": false,
        "bInfo": false
    });


//Create
    document.forms["formCreate"]["buttonDelete"].onclick = deleteCongress;
    document.forms["formCreate"]["buttonEdit"].onclick = goToEditCongress;
    document.forms['formAdd']['Toevoegen'].onclick = isValidInput;


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
    var selectedRow = $('#ListBox').DataTable().row('.selected');
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
            if (data != null && data != '' &&  /\S/.test(data)) {
                data = JSON.parse(data);
                document.getElementById('errMsgInsertCongress').innerHTML = '*' + data['err'];
            }else {
                window.location.href = 'manage.php';
            }
        }

    });
}

function goToEditCongress(){
    var selectedRow = $('#ListBox').DataTable().row('.selected');
    var congressNo = selectedRow.data()[0];
    var congressName = selectedRow.data()[1];

    if (selectedRow.data()) {
        $.ajax({
            url: window.location.href,
            type: 'POST',
            data: {
                goToEdit: 'action',
                congressNo: congressNo,
                congressName: congressName
            },
            success: function (data) {
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

function isValidInput() {
    var form = document.forms["formAdd"];
    if (!isValidCongressName(form['congressName'].value)) {
        $("#errMsgInsertCongress").text("Congresnaam is niet geldig.");
        return;
    }
    else if (!isValidDate(form['congressStartDate'].value)) {
        $("#errMsgInsertCongress").text("Startdatum is niet geldig.");
        return;
    }
    else if (!isValidDate(form['congressEndDate'].value)) {
        $("#errMsgInsertCongress").text("Einddatum is niet geldig.");
        return;
    }
    else if (!isValidPrice(form['congressPrice'].value)) {
        $("#errMsgInsertCongress").text("Prijs is niet geldig. Let op een prijs moet met een punt ingevuld worden niet met een komma.");
        return;
    }
    else if (form['congressStartDate'].value > form['congressEndDate'].value){
        $("#errMsgInsertCongress").text('Eind datum mag niet voor begin datum liggen.');
    }
    else {
        onCreateCongress();
    }
}

