var dataSwapTables = new Array();

$(document).ready(function () {

	$(".goToLeftButton").click(function() { 
		goLeft();
	});
	$(".goToRightButton").click(function() {
		goRight();
	});
	
	var numDataListBoxes =  $('.listBox').length;
	for(var i = 0;i<numDataListBoxes;i++) {
		dataSwapTables[$('.listBox')[i].id] =  $('#' + $('.listBox')[i].id).DataTable( {
		"sScrollY": "500px",
		"bPaginate": false
	});
	}
	tableLeft =
	
    $('.onSelected').prop('disabled', true);
    $('#dataTables_length').css('display', 'none');
    $('#congresListBox_length').css('display', 'none');
    $('#congresListBox_info').css('display', 'none');
	
    $('.dataTable tbody').on('click', 'tr', function () {
        $(this).toggleClass('selected');
    });
});



function goRight() {
	var selectedRows = tableLeft.rows(".selected");
	if(keepRight == true) {
		selectedRows.remove().draw(false);
	}else {
		for(var i = 0;i<selectedRows.data().length;i++) {
			tableRight.row.add(selectedRows.data()[i]).draw(false);
		}
		selectedRows.remove().draw(false);
	}
}


function goLeft() {
	var selectedRows = tableRight.rows(".selected");
	if(keepRight == true) {
		for(var i = 0;i<selectedRows.data().length;i++) {
			tableLeft.row.add(selectedRows.data()[i]).draw(false);
		}
	} else {
		for(var i = 0;i<selectedRows.data().length;i++) {
			tableLeft.row.add(selectedRows.data()[i]).draw(false);
		}
		selectedRows.remove().draw(false);
	}
	
}