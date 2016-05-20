$(document).ready(function () {
    $(".moreInfoButton").on("click", function (event) {
        getEventInfo($(this).closest("div").attr("value"));
    });

    $("#popUpeventInfo .closePopup").on("click", function (event) {
        $('.speaker').remove();
    });
});


function getEventInfo(eventNo) {
    $.ajax({
        url: window.location.href,
        type: 'POST',
        data: {
            getInfo: 'GetInfo',
            eventNo: eventNo
        },
        success: function (data) {
            data = JSON.parse(data);
            $('.popupTitle h1').html(data['ENAME']);
            $('#thumbnail').attr('src', data['FILEDIRECTORY'] + 'thumbnail.png');
            $('#subjects').html(data['SUBJECT']);
            $('#eventDescription').html(data['DESCRIPTION']);
            var sizeSpeaker = 0;
            for (var value in data['speakers']) {
                sizeSpeaker++;
            }

            for (i = 0; i < sizeSpeaker; i++) {
                console.log(data['speakers'][i]['FIRSTNAME']);
                var formGroup = document.createElement('DIV');
                formGroup.className += 'form-group';
                formGroup.className += ' speaker';
                formGroup.className += ' col-md-2';
                var img = document.createElement('IMG');
                img.className += 'col-md-12';
                img.src = data['speakers'][i]['PICTUREPATH'];
                formGroup.appendChild(img);
                var name = document.createElement('SPAN');
                name.innerHTML = data['speakers'][i]['FIRSTNAME'] + ' ' + data['speakers'][i]['LASTNAME'];
                name.className += 'col-md-10';
                formGroup.appendChild(name);
                document.forms['formeventInfo'].appendChild(formGroup);
            }

        },
        error: function (request, status, error) {
            alert(request.responseText);
        }
    });
}

function createSpeakerElement(index) {
    console.log(index);
}
