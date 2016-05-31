var dataSwapTables = new Array();

$(document).ready(function () {

	$(".goToLeftButton").click(function() { 
		goLeft(event);
	});
	$(".goToRightButton").click(function() {
		goRight(event);
	});

	var numDataListBoxes =  $('.listBox').length;
	var listBoxes = $('.listBox');
	for(var i = 0;i<numDataListBoxes;i++) {
		dataSwapTables[listBoxes[i].id] =  $('#' + listBoxes[i].id).DataTable( {
			"sScrollY": "500px",
			"bPaginate": false
		});
	}
	
    $('.onSelected').prop('disabled', true);
    $('#dataTables_length').css('display', 'none');
    $('#congresListBox_length').css('display', 'none');
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
});

function goRight(event) {
	var selectedRows = dataSwapTables[event.target.attributes.getNamedItem("left").value].rows(".selected");
	if(event.target.attributes.getNamedItem("remove").value == true) {
		selectedRows.remove().draw(false);
	}else {
		for(var i = 0;i<selectedRows.data().length;i++) {
			dataSwapTables[event.target.attributes.getNamedItem("right").value].row.add(selectedRows.data()[i]).draw(false);
		}
		selectedRows.remove().draw(false);
	}
}


function goLeft(event) {
	var selectedRows = dataSwapTables[event.target.attributes.getNamedItem("right").value].rows(".selected");
	if(event.target.attributes.getNamedItem("keep").value == true) {
		for(var i = 0;i<selectedRows.data().length;i++) {
			dataSwapTables[event.target.attributes.getNamedItem("left").value].row.add(selectedRows.data()[i]).draw(false);
		}
	} else {
		for(var i = 0;i<selectedRows.data().length;i++) {
			dataSwapTables[event.target.attributes.getNamedItem("left").value].row.add(selectedRows.data()[i]).draw(false);
		}
		selectedRows.remove().draw(false);
	}
	
}


function saveDataSwap(event) {
	
}