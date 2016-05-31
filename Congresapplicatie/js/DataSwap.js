var dataSwapTables = new Array();

$(document).ready(function () {

	$(".goToLeftButton").click(function() { 
		goLeft(event);
	});
	$(".goToRightButton").click(function() {
		goRight(event);
	});
	
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
	
	//$(".buttonSaveSwapList").click(function() {
//		saveDataSwap(event);
//	});
});



function buttonSaveSwapList(event) {
	console.log(event);
}

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