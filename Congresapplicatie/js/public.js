$(document).ready(function () {
    $(".moreInfoButton").on("click", function (event) {
        getEventInfo($(this).closest("div").attr("value"));
    });

    $("#popUpeventInfo .closePopup").on("click", function (event) {
        $('.speaker').remove();
    });
	
	$(".eventInfoBox").on("click", function (event) {
		if ($(this).hasClass("selected")) {
			$(this).removeClass("selected");
			$(this).find("input")[0].name = "eventNo[]";
		}
		else {
			$(this).addClass("selected");
			$(this).find("input")[0].name = "eventNoSelected[]";
			$(".signUpForCongressButton").prop("disabled", false);
		}
	});
	
	
	
	$(".signUpForCongressButton").on("click", function (event){
		editRunningFormData();
	});
});


function editRunningFormData() {
	var selectedEventsOnLastPage = $("[name='eventNoSelected[]']");
	var resultArray = [];

	for(i = 0; i < selectedEventsOnLastPage.length; i++) {
		resultArray.push(selectedEventsOnLastPage[i].value);
	}
	$.ajax({
		url: window.location.href,
		type: 'POST',
		data: {
			eventNoSelected: resultArray,
			ajaxRequest: 'inschrijven'
		},
		success: function (data) {
			console.log(data);
			if (data == 'logged in') {
				location.reload();
			}
			
		},
		error: function (request, status, error) {
			console.log(status);
		}
	});
}

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
			alert(data);
            $('#popUpeventInfo .popupTitle h1').html(data['ENAME']);
            $('#thumbnail').attr('src', data['FILEDIRECTORY'] + 'thumbnail.png');
            $('#eventDescription').html(data['DESCRIPTION']);
            var size = 0;
            for (var value in data['speakers']) {
                size++;
            }

            for (i = 0; i < size; i++) {
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
            size = 0;
            for (var value in data['subjects']) {
                size++;
            }
            var subjects = "";
            for (i = 0; i < size; i++) {
                subjects += data['subjects'][i]['Subject'];
                if (i != size - 1) {
                    subjects += ", ";
                }
            }
            $('#subjects').html(subjects);

        },
        error: function (request, status, error) {
            alert(request.responseText);
        }
    });
}

function createSpeakerElement(index) {
    console.log(index);
}
