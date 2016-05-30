var speakerTable;
$(document).ready(function () {

	speakerTable = $('#speakerListBox').DataTable( {
		"sScrollY": "500px",
		"bPaginate": false
	});
    $('.onSelected').prop('disabled', true);
    $('#dataTables_length').css('display', 'none');
    $('#speakerListBox_length').css('display', 'none');
    //$('#congresListBox_paginate').css('display', 'none');
    $('#speakerListBox_info').css('display', 'none');
	
    $('.dataTable tbody').on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
            $('.onSelected').prop('disabled', true);
        } else {
            speakerTable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            $('.onSelected').prop('disabled', false);
        }
    });
	   
	document.forms["formCreatespeaker"]["buttonEditspeaker"].onclick = getSpeakerInfo;
	//document.forms["formCreatespeaker"]["buttonAddspeaker"].onclick = getSpeakerInfo;
});


function getSpeakerInfo() {
   var selectedRow = speakerTable.row('.selected');
    if(selectedRow.data()) {
        $.ajax({
            url: window.location.href,
            type: 'POST',
            data: {
                getSpeakerInfo: 'GetSpeakerInfo',
                personNo: selectedRow.data()[0],
            },
            success: function (data) {
                data = JSON.parse(data);
				console.log(data);
            //    congressNo = data['congressNo'];
                //oldCongressName = data['CName'];
                //oldCongressStartDate = parseDate(data['Startdate']['date']);
                //oldCongressEndDate = parseDate(data['Enddate']['date']);
                //oldCongressPrice = data['Price'];
                //oldCongressBanner = data['Banner'];
                //oldCongressPublic = data['Public'];
                //oldCongressSubjects = data['subjects']
                //updateCongressInfo(oldCongressName, oldCongressStartDate, oldCongressEndDate, oldCongressPrice, oldCongressBanner, oldCongressPublic, oldCongressSubjects);
            },
            error: function (request, status, error) {
                alert(request.responseText);
            }
        });
    }
    else{
        alert("Er is geen selectie gemaakt");
        return false;
    }
}
