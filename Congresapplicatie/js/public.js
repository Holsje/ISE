var lastEvent;
var fileName = location.href.split("/").slice(-1);
$(document).ready(resize);
$(window).resize(resize);
$(document).ready(function () {	
	if ($('.noEventsText').length > 0) {
		$('#myCarousel a').css("display", "none");
	}
	else {
		$('#myCarousel a').css("display", "block");
	}
	
	
	$(".moreInfoButton").on("click", function (event) {
        getEventInfo($(this).closest("div").attr("value"));
    });

    $("#popUpeventInfo .closePopup").on("click", function (event) {
        $('.speaker').remove();
    });
	
	$(".eventBox .eventInfoBox").on("click", function (event) {
		if (!$(event.target).hasClass("moreInfoButton")) {
			if ($(this).hasClass("selected")) {
				$(this).removeClass("selected");
				$(this).find("input")[0].name = "eventNo[]";
			}
			else {
				$(this).addClass("selected");
				$(this).find("input")[0].name = "eventNoSelected[]";
				$(".signUpForCongressButton").prop("disabled", false);
			}
		}
	});
	
	$(".signUpForCongressButton").on("click", function (event){
		editRunningFormData();
	});
	
    $(".subjectClick").on("click", function (event){
        if(lastEvent != null){
            if(event.target == lastEvent.target){
                lastEvent = null;
            }else{
                selectEventOnSubject(event.target.innerHTML);
                lastEvent = event;
            }
        }else{
            selectEventOnSubject(event.target.innerHTML);
            lastEvent = event;
        }
        $('.selectedSubject').removeClass('selectedSubject');
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
			console.log(data);
			data = JSON.parse(data);
            $('#popUpeventInfo .popupTitle h1').html(data['EName']);
            var filename = data['FileDirectory'] + 'thumbnail.png';
            $('img#thumbnail').attr('src', data['FileDirectory'] + 'thumbnail.png');
            $('#eventDescription').html(data['Description']);
            var size = 0;
            for (var value in data['speakers']) {
                size++;
            }

            for (i = 0; i < size; i++) {
                var formGroup = document.createElement('button');
                formGroup.type = 'button';
                formGroup.className += 'speaker';
                formGroup.className += ' col-md-2';
                formGroup.className += ' popupButton';
                formGroup.onclick = speakerPopup;
                formGroup.setAttribute("data-file", '#popUpspeaker');
                var img = document.createElement('IMG');
                img.className += 'col-md-12';
                img.src = data['speakers'][i]['PicturePath'];
                img.setAttribute('id', data['speakers'][i]['PersonNo']);
                formGroup.appendChild(img);
                var name = document.createElement('SPAN');
                name.innerHTML = data['speakers'][i]['FirstName'] + ' ' + data['speakers'][i]['LastName'];
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
var test;

function speakerPopup(event) {
    $.ajax({
        url: 'index.php',
        type: 'POST',
        data: {
            speakerPop: 'GO',
            personID: event.target.attributes.getNamedItem('id').value
        },
        success: function (data) {
            console.log(data);
            data = JSON.parse(data);
            $('#popUpspeaker .popupTitle h1').html(data['FirstName'] + ' ' + data['LastName']);
            $('#thumbnail').attr('src', data['PicturePath']);
            $('#speakerDescription').html(data['Description']);
            $('#popUpspeaker').fadeToggle();
        }
    });
}

function selectEventOnSubject(subject){
    $.ajax({
        url: 'index.php',
        type: 'POST',
        data: {
            subjectClick: 'go',
            subject: subject
        },
        success: function(data){
            data = JSON.parse(data);
            for(var i = 0; i < data.length; i++){
                for(var x = 0; x<document.getElementsByClassName('eventInfoBox').length; x++){
                    if(document.getElementsByClassName('eventInfoBox')[x].getAttribute('value') ==data[i]){
                        if(!document.getElementsByClassName('eventInfoBox')[x].classList.contains('selectedSubject')){
                            document.getElementsByClassName('eventInfoBox')[x].classList +=' selectedSubject';
                        }
                    }
                }
            }
        }
    });
}

function resize() {
	if (fileName == "inschrijven.php") {
		$('.moreInfoButton').removeClass('pull-right');
		//$('.plan').html("Terug naar homepagina");
		//$('.plan').removeClass("col-md-1");
		//$('.plan').on("click", function() {
		//	window.location.href = "index.php";
		//})
	}
	else {
		$('.eventImage').css("display", "block");
		$('.eventText').removeClass("col-xs-12");
		$('.eventText').addClass("col-xs-6");
		$('.eventImage').removeClass("col-xs-6");
		$('.eventImage').addClass("col-xs-5");
		$('.eventImage').addClass("pull-right");
	}
	if (window.matchMedia("(max-width: 768px)").matches) {
		if (fileName == "inschrijven.php") {
			$('.moreInfoButton').removeClass('btn btn-default pull-right');
			$('.moreInfoButton').addClass('infoGlyph glyphicon glyphicon-info-sign');
			$('.moreInfoButton').html('');
		}
		$.ajax({
			url: 'inschrijven.php',
			type: 'POST',
			data: {
				tracksPerCarouselSlide: 2
			},
			success: function(data) {

			}
		})
	}
	else if (window.matchMedia("(min-width: 800px)").matches) {
		if (fileName == "inschrijven.php") {
			$('.moreInfoButton').removeClass('infoGlyph glyphicon glyphicon-info-sign');
			$('.moreInfoButton').addClass('btn btn-default pull-right');
			$('.moreInfoButton').html('Meer info');
		}
			$.ajax({
				url: 'inschrijven.php',
				type: 'POST',
				data: {
					tracksPerCarouselSlide: 3
				},
				success: function(data) {
			
				}
			})
	}
}

function redirect() {
	window.location.href = "index.php?congressNo=1"
}