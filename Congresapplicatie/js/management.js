var table;
$(document).ready(function () {
    table = $('#congresListBox').DataTable( {
		"sScrollY": "500px",
		"bPaginate": false
	});
	$('.onSelected').length;
    $('.onSelected').prop('disabled', true);
    $('#dataTables_length').css('display', 'none');
    $('#congresListBox_length').css('display', 'none');
    //$('#congresListBox_paginate').css('display', 'none');
    $('#congresListBox_info').css('display', 'none');
	
    $('.singleSelect.dataTable tbody').on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
            $('.onSelected').prop('disabled', true);
        } else {
            $('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            $('.onSelected').prop('disabled', false);
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
		case "#Congresgegevens":
		default:
			var activeTab = 0;
		break;
	}
	 $(function() {
		$( "#tabs" ).tabs({active:activeTab});
	  });
});

function parseDate(dateString) {
    return dateString.split(' ')[0];
}
