 	var table;
$(document).ready(function () {	
	
	table = $('#ListBox').DataTable( {
        "sScrollY": "500px",
        "bPaginate": false,
        "bInfo": false,
		"columnDefs": [ {
				"targets": [0],
				"visible": false,
				"searchable": false			
			}]
	});
	
	
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
});