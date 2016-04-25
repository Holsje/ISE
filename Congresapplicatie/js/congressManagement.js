$(document).ready( function () {
    var table = $('#congresListBox').DataTable();
	
	$('#congresListBox_wrapper').addClass('col-xs-6 col-xs-offset-2 col-sm-6 col-sm-offset-2 col-md-6 col-md-offset-2');
	$('#dataTables_length').css('display', 'none');
	$('#congresListBox_filter').css('display', 'none');
	$('#congresListBox_length').css('display', 'none');
	$('#congresListBox_paginate').css('display', 'none');
	$('#congresListBox_info').css('display', 'none');
	
    $('#congresListBox tbody').on('click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    });

	$('#deleteButton').click( function () {
		var selectedRow = table.row('.selected');
		selectedRow.remove().draw(false);
    });
	
	
	$('html').keyup(function(e){
		if(e.keyCode == 46) {
			alert('Delete Key Pressed');
			var selectedRow = table.row('.selected');
			selectedRow.remove().draw(false);
		}
	});
});