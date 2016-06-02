var oldTrackName,
    oldTrackDescription,
    trackNo;

$(document).ready(function () {

    table = $('#TracksListBox').DataTable( {
        "sScrollY": "500px",
        "bPaginate": false,
        "bInfo": false,
        "columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false,
                "searchable": false
            }
        ]
    });

    $('#TracksListBox tbody').on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
            $('.onSelected').prop('disabled', true);
        } else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            $('.onSelected').prop('disabled', false);
        }
    });

    document.forms["formCreateTracks"]["buttonDeleteTracks"].onclick = deleteTrack;
    document.forms["formCreateTracks"]["buttonEditTracks"].onclick = getTrackInfo;
    document.forms["formUpdateTracks"]["editTrack"].onclick = onUpdateTrack;
    //Create
    $('html').keyup(function (e) {
        if (e.keyCode == 46) {
            deleteTrack();
        }
    });

});


function deleteTrack() {
    var selectedRow = table.row('.selected');
    if (selectedRow.data()) {
        if (confirm("Weet u zeker dat u deze rij wilt verwijderen?")) {
            $.ajax({
                url: window.location.href,
                type: 'POST',
                data: {
                    delete: 'action',
                    trackNo: selectedRow.data()[0]
                },
                success: function(data){
                    console.log(data);
                }
            });
            selectedRow.remove().draw(false);
        }
    } else {
        alert("Er is geen selectie gemaakt");
    }
}

function getTrackInfo(){
    var selectedRow = table.row('.selected');
    trackNo = selectedRow.data()[0];
    $.ajax({
        url: window.location.href,
        type: 'POST',
        data: {
            getTrackInfo: 'GetTrackInfo',
            trackNo: trackNo
        },
        success: function (data) {
            console.log(data);
            data = JSON.parse(data);
            oldTrackName = data['TName'];
            oldTrackDescription = data['Description'];
            updateTrackInfo(oldTrackName, oldTrackDescription);
        },
        error: function (request, status, error) {
            alert(request.responseText);
        }
    });
}

function updateTrackInfo(trackName, trackDescription){
    document.forms["formUpdateTracks"]["trackName"].value = trackName;
    document.forms["formUpdateTracks"]["trackDescription"].value = trackDescription;
}


function onUpdateTrack() {
    $.ajax({
        url: window.location.href,
        type: 'POST',
        data: {
            editTrack: 'action',
            trackNo: trackNo,
            newTrackName: document.forms['formUpdateTracks']["trackName"].value,
            newTrackDescription: document.forms['formUpdateTracks']["trackDescription"].value
        },
        success: function (data, event) {
            if (data != null && data != '' &&  /\S/.test(data)) {
                console.log(data);
                data = JSON.parse(data);
                document.getElementById('errMsgTrack').innerHTML = '*' + data['err'];
                alert("WACHT");
            }
            /*
            if (data != null && data != '' &&  /\S/.test(data)) {
                data = JSON.parse(data);
                document.getElementById('errMsgTrack').innerHTML = '*' + data['err'];
                var confirmBox = confirm(data['err'] + 'De pagina wordt opnieuw geladen met de nieuwe gegevens.');
                if (confirmBox) {
                    location.reload();
                } else {
                    oldTrackName = data['oldTrackName'];
                    oldTrackDescription = data['oldTrackDescription'];
                }
            } else {
                location.reload();
            }
            */


        }

    });
}