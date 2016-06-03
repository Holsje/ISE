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
			"bPaginate": false,
			"columnDefs": [ {
				"targets": [0],
				"visible": false,
				"searchable": false			
			},{
				"targets": [4],
				"visible": false,
				"searchable": false	
			}]
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
	var rowsError = '';
	
	var selectedRow = dataSwapTables[event.target.attributes.getNamedItem("left").value].row(".selected");	
	while(selectedRow.length > 0) {	
		if(selectedRow.data()[4] == 'False') {
			rowsError += selectedRow.data()[3] + "\n";
			selectedRow.node().classList.remove("selected");
			selectedRow = dataSwapTables[event.target.attributes.getNamedItem("left").value].row(".selected");	
			continue;
		}
		dataSwapTables[event.target.attributes.getNamedItem("right").value].row.add(selectedRow.data());
		selectedRow.remove();
		selectedRow = dataSwapTables[event.target.attributes.getNamedItem("left").value].row(".selected");	
	}
	
	dataSwapTables[event.target.attributes.getNamedItem("left").value].rows().draw();
	dataSwapTables[event.target.attributes.getNamedItem("right").value].rows().draw();
	
	if(rowsError != '') {
		alert('Kan de rij(en) met de volgende mail(s) niet verwijderen: \n' + rowsError);
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