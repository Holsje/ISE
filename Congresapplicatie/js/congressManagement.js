var startDate;
var congressNo,
    oldCongressName,
    oldCongressLocation,
    oldCongressSubject,
    oldCongressStartDate,
    oldCongressEndDate;

$(document).ready(function () {
    var table = $('#congresListBox').DataTable();
    $('.onSelected').prop('disabled', true);

    //$('#congresListBox_wrapper').addClass('col-xs-6 col-xs-offset-2 col-sm-6 col-sm-offset-2 col-md-6 col-md-offset-2');
    $('#dataTables_length').css('display', 'none');
    $('#congresListBox_length').css('display', 'none');
    $('#congresListBox_paginate').css('display', 'none');
    $('#congresListBox_info').css('display', 'none');

    $('.dataTable tbody').on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
            $('.onSelected').prop('disabled', true);
        } else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            $('.onSelected').prop('disabled', false);
        }
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
    document.forms["formCreateCongress"]["buttonDelete"].onclick = deleteCongress;



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

    document.forms["formCreateCongress"]["buttonEdit"].onclick = fillUpdateCongressInfo;

    $('html').keyup(function (e) {
        if (e.keyCode == 46) {
            alert('Delete Key Pressed');
            var selectedRow = table.row('.selected');
            selectedRow.remove().draw(false);
        }
    });

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
                if (data != null) {
                    data = JSON.parse(data);
                    document.getElementsByName('errMsgBewerken')[0].innerHTML = '*' + data['err'];
                    var confirmBox = confirm(data['err']);
                    if (confirmBox) {

                    } else {
                        //(congressName, congressSubject, congressLocation, congressStartDate, congressEndDate)
                        startDate = parseDate(data['StartDate']['date']);
                        endDate = parseDate(data['EndDate']['date']);
                        updateCongressInfo(data['Name'], data['Subject'], data['Location'], startDate, endDate);
                    }
                } else {
                    location.reload();
                }
            }

        });
        //$('#popUpUpdate .closePopUp').click();
    }

    document.forms["formUpdate"]["updateCongress"].onclick = onUpdateCongress;

    function parseDate(dateString) {
        return dateString.split(' ')[0];
    }

});
