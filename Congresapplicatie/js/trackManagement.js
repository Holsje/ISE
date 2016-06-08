var oldTrackName,
    oldTrackDescription,
    trackNo,
    tableTracks;

$(document).ready(function () {

    tableTracks = $('#TracksListBox').DataTable( {
        "sScrollY": "500px",
        "bPaginate": false,
        "bInfo": false,
        "columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false,
                "searchable": false
            }
        ],
        "language": {
            "emptyTable": "Geen data beschikbaar",
            "sSearch": "Zoeken:"
        }
    });

    document.forms["formCreateTracks"]["buttonDeleteTracks"].onclick = deleteTrack;
    document.forms["formCreateTracks"]["buttonEditTracks"].onclick = getTrackInfo;
    //document.forms["formUpdateTracks"]["editTrack"].onclick = onUpdateTrack;
    //Create
    $('html').keyup(function (e) {
        if (e.keyCode == 46) {
            deleteTrack();
        }
    });


    if(document.forms["formAddTracks"]) {
        document.forms["formAddTracks"].onsubmit = function() {
            if(!isValidLocationName(document.forms["formAddTracks"]["trackName"].value)) {
                $("#errMsgInsertTrack").text("Tracknaam is onjuist.");
                return false;
            }
            if(!isValidDescription(document.forms["formAddTracks"]["trackDescription"].value)) {
                $("#errMsgInsertTrack").text("Omschrijving is onjuist.");
                return false;
            }
            return true;
        }
    }

    if(document.forms["formUpdateTracks"]) {
        document.forms["formUpdateTracks"].onsubmit = function() {
            if(!isValidName(document.forms["formUpdateTracks"]["trackName"].value)) {
                $("#errMsgUpdateTrack").text("Tracknaam is onjuist.");
                return false;
            }
            if(!isValidDescription(document.forms["formUpdateTracks"]["trackDescription"].value)) {
                $("#errMsgUpdateTrack").text("Omschrijving is onjuist.");
                return false;
            }
            return true;
        }
    }


});


function deleteTrack() {
    var selectedRow = tableTracks.row('.selected');
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
    var selectedRow = tableTracks.row('.selected');
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




/*function onUpdateTrack() {
    $.ajax({
        url: window.location.href,
        type: 'POST',
        data: {
            editTrack: 'action',
            trackNo: trackNo,
            newTrackName: document.forms['formUpdateTracks']["trackName"].value,
            newTrackDescription: document.forms['formUpdateTracks']["trackDescription"].value
        },
        success: function (data) {
            if (data != null && data != '' &&  /\S/.test(data)) {
                console.log(data);
                data = JSON.parse(data);
                document.getElementById('errMsgTrack').innerHTML = '*' + data['err'];
            }
            else{
                $('#popUpUpdateTracks button')[0].click();
                //location.reload();
            }

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



        }

    });

}
 */