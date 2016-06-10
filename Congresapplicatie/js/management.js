var table;
var thisEvent;
var setDataTable = false;
var eventNo = 0;
var eventTest;
$(document).ready(function () {
    table = $('#congresListBox').DataTable( {
		"sScrollY": "500px",
		"bPaginate": false,
        "language": {
            "emptyTable": "Geen data beschikbaar",
            "sSearch": "Zoeken:"
        }
	});
    $(".dataTables_scrollBody").removeAttr("style");
	$(".dataTables_scrollBody").addClass("scrollBody");
	$('.onSelected').length;
    if(!setDataTable){
        $('.onSelected').prop('disabled', true);
        setDataTable = false;
    }
    $('#dataTables_length').css('display', 'none');
    $('#congresListBox_length').css('display', 'none');
    //$('#congresListBox_paginate').css('display', 'none');
    $('#congresListBox_info').css('display', 'none');
	
    $('.singleSelect.dataTable tbody').on('click', 'tr', function () {
        var parent = $(this).parents('form').children('.onSelected');
        console.log(parent);
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
            parent.prop('disabled', true);
        } else {
            if(!$(this.childNodes[0]).hasClass('dataTables_empty')){
               $('tr.selected').removeClass('selected');
                $(this).addClass('selected');
                parent.prop('disabled', false);
                console.log('test');
            }     
        }
    });

    $(".popupButton").on("click", function (event) {
        $(event.target.attributes.getNamedItem("data-file").value).fadeToggle();
        $("body").css("overflow", "hidden");
    });

	$(".closePopup").on("click", function (event) {
		$(".errorMsg").empty()
        $(event.target.attributes.getNamedItem("data-file").value).fadeToggle();
        $("body").css("overflow", "auto");

		if(document.getElementById(event.target.attributes.getNamedItem('data-file').value.substring(1)).classList.contains('show')){
			$(event.target.attributes.getNamedItem("data-file").value).removeClass('show');


		}
    });
	
	switch(window.location.hash) {
		case "#Locatie":
			var activeTab = 1;
		break;
		case "#Tracks":
			var activeTab = 2;
		break;
		case "#Evenementen":
			var activeTab = 3;
		break;
		case "#spreker":
			var activeTab = 4;
		break;
        case "#Bezoekers":
            var activeTab = 5;
        break;
		case "#Congresgegevens":
		default:
			var activeTab = 0;
		break;
	}
		$( "#tabs" ).tabs({
            active:activeTab
        });
});

function parseDate(dateString) {
    return dateString.split(' ')[0];
}
