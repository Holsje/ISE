var eventListBox;
var eventSubjectAddListBox;
var eventSubjectEditListBox;
var selectedTable;
var oldSpeakers = [];
var hiddenMade = false;
var setDataTable = false;
var eventNoSubmit = 0;
$(document).ready(function () {
    $('#popUpSpeakerToEvent .closePopup').on('click',function(){
        $('#listBoxSpeakerEventLeft').DataTable().clear();
        $('#listBoxSpeakerEventRight').DataTable().clear();
    });
   
    eventSubjectAddListBox = $('#EvenementenSubjectListBoxAdd').DataTable( {
			         "sScrollY": "500px",
			         "bPaginate": false,
                     "retrieve": true,
                     "bInfo": false,
                     "language": {
                        "emptyTable": "Geen data beschikbaar",
                        "sSearch": "Zoeken:"
                     }
    });

    eventListBox =  $('#EvenementenListBox').DataTable( {
			        "sScrollY": "500px",
			        "bPaginate": false,
                    "bInfo": false,
                    "language": {
                        "emptyTable": "Geen data beschikbaar",
                        "sSearch": "Zoeken:"
                    }
    });
    eventSubjectEditListBox =  $('#EvenementenSubjectListBoxEdit').DataTable( {
			         "sScrollY": "500px",
			         "bPaginate": false,
                     "bInfo": false,
                     "language": {
                        "emptyTable": "Geen data beschikbaar",
                        "sSearch": "Zoeken:"
                     }
    });
    
    if($('#listBoxSpeakerEventRight')) {
		$('#listBoxSpeakerEventRight').on('click', 'tr', function () {
			var numSelectedRows = dataSwapTables['listBoxSpeakerEventRight'].rows(".selected").data().length;
			if (numSelectedRows == 0) {
				$("[name=buttonEditSpeaker]").prop("disabled", true);
				$("[name=buttonDeleteSpeaker]").prop("disabled", true);
			}
			else if (numSelectedRows == 1) {
				$("[name=buttonEditSpeaker]").prop("disabled", false);
				$("[name=buttonDeleteSpeaker]").prop("disabled", false);
			}
			else {
				$("[name=buttonEditSpeaker]").prop("disabled", true);
				$("[name=buttonDeleteSpeaker]").prop("disabled", true);
			}
		});
	}
    
    if($('#listBoxSpeakerEventLeft')) {
		$('#listBoxSpeakerEventLeft').on('click', 'tr', function () {
			var numSelectedRows = dataSwapTables['listBoxSpeakerEventLeft'].rows(".selected").data().length;
			if (numSelectedRows == 0) {
				$("[name=buttonEditSpeakerOfCongress]").prop("disabled", true);
			}
			else if (numSelectedRows == 1) {
				$("[name=buttonEditSpeakerOfCongress]").prop("disabled", false);
			}
			else {
				$("[name=buttonEditSpeakerOfCongress]").prop("disabled", true);
			}
		});	
	}
    $('#EvenementenSubjectListBoxAdd tbody').on('click','tr',function(){
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
            this.children[0].removeChild(this.children[0].childNodes[1]);
        } else {
            $(this).addClass('selected');
            var child = document.createElement('input');
            child.setAttribute('value',this.children[0].innerHTML);
            child.setAttribute('name','subjects[]');
            child.style.visibility ='hidden';
            this.children[0].appendChild(child);
        }
    });
    
    $('#EvenementenSubjectListBoxEdit tbody').on('click','tr',function(){
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
            $('.onSelected').prop('disabled', true);
            this.children[0].removeChild(this.children[0].childNodes[1]);
        } else {
            $(this).addClass('selected');
            $('.onSelected').prop('disabled', false);
            var child = document.createElement('input');
            child.setAttribute('value',this.children[0].innerHTML);
            child.setAttribute('name','subjects[]');
            child.style.visibility ='hidden';
            this.children[0].appendChild(child);
        }
    });
    $('.subjectInput').change(function(event){
        if(event.target.value == 'Lezing'){
            $('.eventPrice').parent().css('display','none');
        }else{
            $('.eventPrice').parent().css('display','block');
        }
    });
    
    document.forms['formCreateEvenementen']['buttonDeleteEvenementen'].onclick = deleteEvent;
    document.forms['formCreateEvenementen']['buttonEditEvenementen'].onclick = getSelectedEventInfo;
    document.forms['formCreateEvenementen']['speakerToEvent'].onclick = fillSpeakersOfEvent;
    
    if(document.forms["formsprekerEvent"]) {
		document.forms["formsprekerEvent"]["buttonEditSpeakerOfCongress"].onclick = function() {getSpeakerInfo(0,event)};
		document.forms["formsprekerEvent"]["buttonEditSpeaker"].onclick = function() {getSpeakerInfo(1,event)};
        document.forms["formsprekerEvent"]["buttonAddSpeakerOfCongress"].onclick = function(event){
            setLocation(event);
             if(!hiddenMade){
                document.forms['formAddSpeaker'].appendChild(createHiddenEvent());
                document.forms['formUpdateSpeaker'].appendChild(createHiddenEvent());
                document.forms['formUpdateSpeakerOfCongress'].appendChild(createHiddenEvent());
            }
        }
	}

    
    document.forms['formsprekerEvent']['buttonSaveSwapListsprekerEvent'].onclick = addNewSpeakers;
    
    $(".listBoxSpeakerEventLeft .dataTables_scrollBody").removeAttr("style");
	$(".listBoxSpeakerEventRight .dataTables_scrollBody").removeAttr("style");
	$(".listBoxSpeakerEventRight .dataTables_scrollBody").addClass("noScrollBody");
	$(".listBoxSpeakerEventLeft .dataTables_scrollBody").addClass("noScrollBody");
    
    

    if(setDataTable){
        getRow('EvenementenListBox',eventNoSubmit).addClass('selected');
        $('.onSelected').prop('disabled',false);
        fillSpeakersOfEvent();
    }
    if(document.forms['formAddEvenementen']){
        document.forms['formAddEvenementen'].onsubmit = function () {
            if(!isValidLocationName(document.forms['formAddEvenementen']['eventName'].value)){
                $('#errMsgInsertEvent').text ('Eventnaam is onjuist.');
                return false;
            }
            if(!isValidDescription(document.forms['formAddEvenementen']['eventDescription'].value)){
                $('#errMsgInsertEvent').text ('Omschrijving is onjuist.');
                return false;
            }
            if(!isValidPrice(document.forms['formAddEvenementen']['eventPrice'].value)){
                $('#errMsgInsertEvent').text ('Prijs is onjuist.');
                return false;
            }
            if(!isValidSmallIntNullable(document.forms['formAddEvenementen']['eventMaxVis'].value)){
                $('#errMsgInsertEvent').text ('Maximaal aantal bezoekers is onjuist.');
                return false;
            }
        }
    }
    if(document.forms['formUpdateEvenementen']){
        document.forms['formUpdateEvenementen'].onsubmit = function () {
            if(!isValidLocationName(document.forms['formUpdateEvenementen']['eventName'].value)){
                $('#errMsgUpdateEvent').text ('Eventnaam is onjuist.');
                return false;
            }
            if(!isValidDescription(document.forms['formUpdateEvenementen']['eventDescription'].value)){
                $('#errMsgUpdateEvent').text ('Omschrijving is onjuist.');
                return false;
            }
            if(!isValidPrice(document.forms['formUpdateEvenementen']['eventPrice'].value)){
                $('#errMsgUpdateEvent').text ('Prijs is onjuist.');
                return false;
            }
            if(!isValidSmallIntNullable(document.forms['formUpdateEvenementen']['eventMaxVis'].value)){
                $('#errMsgUpdateEvent').text ('Maximaal aantal bezoekers is onjuist.');
                return false;
            }
        }
    }
});

function deleteEvent(){
    if(confirm('Weet u zeker dat u deze rij wilt verwijderen?')){
        var eventNo = $('#EvenementenListBox tbody .selected td').html();
        $.ajax({
            url: window.location.href,
            type: 'POST',
            data: {
                deleteEvent: 'Action',
                eventNo: eventNo
            },
            success: function(data){
                $('#EvenementenListBox tbody .selected').remove();
            }
        });
    }
}
function getSelectedEventInfo(){
    var eventNo = $('#EvenementenListBox tbody .selected td').html();
    $.ajax({
        url: window.location.href,
        type: 'POST',
        data: {
            updateEvent: 'Action',
            eventNo: eventNo
        },
        success: function(data){
            data = JSON.parse(data);
            document.forms['formUpdateEvenementen']['eventName'].value = data[0];
            document.forms['formUpdateEvenementen']['eventDescription'].value = data[5];
            document.forms['formUpdateEvenementen']['eventType'].value = data[1];
            if(data[1] == 'Lezing'){
                document.forms['formUpdateEvenementen']['eventPrice'].parentElement.style.display = 'None';
            }else{
                document.forms['formUpdateEvenementen']['eventPrice'].value = data[3];
            }
            document.forms['formUpdateEvenementen']['eventMaxVis'].value = data[2];
            $('#editEventPictureBtn').css("background-image", "url(../" + data[4] + 'thumbnail.png' + ")");
            for(var i = 0; i<data['Subjects'].length;i++){
               $("#EvenementenSubjectListBoxEdit").find('[name="' + data['Subjects'][i] + '"]').parent().addClass('selected');
                var child = document.createElement('input');
                child.setAttribute('value',data['Subjects'][i]);
                child.setAttribute('name','subjects[]');
                child.style.visibility ='hidden';
                $("#EvenementenSubjectListBoxEdit").find("[name='" + data['Subjects'][i] + "']").append(child);
            }
            document.forms['formUpdateEvenementen']['AanpassenEvent'].value = eventNo;
        }
    });
}

function fillSpeakersOfEvent(){
    var eventNo = $('#EvenementenListBox tbody .selected td').html();
    $.ajax({
        url: window.location.href,
        type: 'POST',
        data: {
            speakerOfEvent: 'Action',
            eventNo: eventNo
        },
        success: function(data){
            data = JSON.parse(data);
            var tableLeft = $('#listBoxSpeakerEventLeft').DataTable();
            for(i = 0; i< data['event'].length; i++){
                oldSpeakers.push(data['event'][i]);
                tableLeft.row.add([data['event'][i][0],data['event'][i][1],data['event'][i][2],data['event'][i][3]]);
            }
            var tableRight = $('#listBoxSpeakerEventRight').DataTable();
            for(i = 0; i< data['congres'].length; i++){
                tableRight.row.add([data['congres'][i][0],data['congres'][i][1],data['congres'][i][2],data['congres'][i][3]]);
            }
            if(!hiddenMade){
                document.forms['formAddSpeaker'].appendChild(createHiddenEvent());
                document.forms['formUpdateSpeaker'].appendChild(createHiddenEvent());
                document.forms['formUpdateSpeakerOfCongress'].appendChild(createHiddenEvent());
            }
            
            tableLeft.rows().draw();
            tableRight.rows().draw();
            
            
        }
        
    });
}

function createHiddenEvent(){
    var hiddenEventNo = document.createElement('input');
    hiddenEventNo.name = 'eventNoReload';
    hiddenEventNo.style.visibility = 'hidden';
    hiddenEventNo.setAttribute('value',$('#EvenementenListBox tbody .selected td').html());
    hiddenMade = true;
    return hiddenEventNo;
}

function addNewSpeakers(){
    var table = $('#listBoxSpeakerEventLeft').DataTable();
    var eventNo = $('#EvenementenListBox tbody .selected td').html();
    var newSpeakers = calcNewSpeakers(table);
    var deleteSpeakers = calcDeleteSpeakers(table);
    $.ajax({
        url: window.location.href,
        type: 'POST',
        data: {
            addSpeakerOfEvent: 'Action',
            addingSpeakers: newSpeakers,
            deletingSpeakers: deleteSpeakers,
            eventNo: eventNo
        },
        success: function(data){
            window.location.href = window.location.protocol +'//'+ window.location.host + window.location.pathname;
        }
    });
}

function calcNewSpeakers(dataTable){
    var returnArray =[];
    for(i =0; i< dataTable.rows().data().length; i++){
        var continueLoop = true;
        for(j = 0; j < oldSpeakers.length; j++){
            if(continueLoop){
                if(dataTable.rows().data()[i][0] == oldSpeakers[j][0]){
                    continueLoop = false;
                }
            }
        }if(continueLoop){
            returnArray.push(dataTable.rows().data()[i][0]);
        }
    }
    return returnArray;
}

function calcDeleteSpeakers(dataTable){
    var returnArray =[];
    for(i =0; i< oldSpeakers.length; i++){
        var continueLoop = true;
        for(j = 0; j < dataTable.rows().data().length; j++){
            if(continueLoop){
                if(dataTable.rows().data()[j][0] == oldSpeakers[i][0]){
                    continueLoop = false;
                }
            }
        }if(continueLoop){
            returnArray.push(oldSpeakers[i][0]);
        }
    }
    return returnArray;
}

function getRow(table_id, arg1) {
    var oTable = $('#' + table_id).dataTable(),
        data = oTable.fnGetData(),
        row, i, l = data.length;

    for ( i = 0; i < l; i++ ) {
        row = data[i];

        // columns to search are hard-coded, but you could easily pass this in as well
        if ( data[i][0] == arg1) {
            return $('#' + table_id + ' tr').eq(i+1);
        }
    }
    return false;
}