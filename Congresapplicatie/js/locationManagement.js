var table;
$(document).ready(function () {
    table = $('#congresListBox').DataTable( {
		"sScrollY": "500px",
		"bPaginate": false
	});
	$('.onSelected').length;
    //$('.onSelected').prop('disabled', true);
    $('#dataTables_length').css('display', 'none');
    $('#congresListBox_length').css('display', 'none');
    //$('#congresListBox_paginate').css('display', 'none');
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

    $(".popupButton").on("click", function (event) {
        $(event.target.attributes.getNamedItem("data-file").value).fadeToggle();
        $("body").css("overflow", "hidden");
    });
    $(".closePopup").on("click", function (event) {
        $(event.target.attributes.getNamedItem("data-file").value).fadeToggle();
        $("body").css("overflow", "auto");
    });
});