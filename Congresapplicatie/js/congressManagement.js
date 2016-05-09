$(document).ready( function () {
    var table = $('#congresListBox').DataTable();
	
	//$('#congresListBox_wrapper').addClass('col-xs-6 col-xs-offset-2 col-sm-6 col-sm-offset-2 col-md-6 col-md-offset-2');
	$('#dataTables_length').css('display', 'none');
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


	function deleteCongress() {
		var selectedRow = table.row('.selected');
		if(selectedRow.data()) {
			if(confirm("Weet u zeker dat u deze rij wilt verwijderen?")) {
				selectedRow.remove().draw(false);
			}
		}
		else {
			alert("Er is geen selectie gemaakt");
		}
    }
	document.forms["formCreateCongress"]["buttonDelete"].onclick = deleteCongress;
	
	
	
	function updateCongress() {
		var selectedRow = table.row('.selected');
		if(selectedRow.data()) {
			document.forms["PopupChangeform"]["congressIdentifier"].value = selectedRow.data()[0];			
			document.forms["PopupChangeform"]["congressName"].value = selectedRow.data()[1];			
			document.forms["PopupChangeform"]["congressSubject"].value = selectedRow.data()[2];
			document.forms["PopupChangeform"]["congressLocation"].value = selectedRow.data()[3];
			document.forms["PopupChangeform"]["startDate"].value = selectedRow.data()[4];
			document.forms["PopupChangeform"]["endDate"].value = selectedRow.data()[5];
		}
		else {
			alert("Er is geen selectie gemaakt");
			return false;
		}
	}
	document.forms["formCreateCongress"]["buttonEdit"].onclick = updateCongress;
	
	$('html').keyup(function(e){
		if(e.keyCode == 46) {
			alert('Delete Key Pressed');
			var selectedRow = table.row('.selected');
			selectedRow.remove().draw(false);
		}
	});
});