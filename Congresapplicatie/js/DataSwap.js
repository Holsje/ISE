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
	
	var numDataListBoxes =  $('.listBoxDataSwap').length;
	var listBoxes = $('.listBoxDataSwap');
	for(var i = 0;i<numDataListBoxes;i++) {
		dataSwapTables[listBoxes[i].id] =  $('#' + listBoxes[i].id).DataTable( {
			"sScrollY": "500px",
			"bPaginate": false
		});
	}
	
    $('#dataTables_length').css('display', 'none');
    $('#congresListBox_length').css('display', 'none');
    $('#congresListBox_info').css('display', 'none');
	
    $('.listBoxDataSwap tbody').on('click', 'tr', function () {
        $(this).toggleClass('selected');
    });
});

function goRight(event) {
	var selectedRows = dataSwapTables[event.target.attributes.getNamedItem("left").value].rows(".selected");
	
	$("." + event.target.attributes.getNamedItem("left").value + " .onSelected").attr("disabled",true);
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

	$("." + event.target.attributes.getNamedItem("right").value + " .onSelected").attr("disabled",true);
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