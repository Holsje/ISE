var eventListBox;
var eventSubjectAddListBox;
var eventSubjectEditListBox;
var selectedTable;
var oldSpeakers = [];

$(document).ready(function () {
    eventSubjectAddListBox = $('#EvenementenSubjectListBoxAdd').DataTable( {
			         "sScrollY": "500px",
			         "bPaginate": false,
                     "retrieve": true
    });

    eventListBox =  $('#EvenementenListBox').DataTable( {
			         "sScrollY": "500px",
			         "bPaginate": false
    });
    eventSubjectEditListBox =  $('#EvenementenSubjectListBoxEdit').DataTable( {
			         "sScrollY": "500px",
			         "bPaginate": false
    });    
    
    $('.subjectAdd').on('click',function(event){
        selectedTable = $('#' + event.target.form.getElementsByClassName('subjectListBox')[1].getAttribute('id')).DataTable();
    });
    
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
        console.log(event.target.value);
        if(event.target.value == 'Lezing'){
            $('.eventPrice').parent().css('display','none');
        }else{
            $('.eventPrice').parent().css('display','block');
        }
    });
    
    document.forms['formCreateEvenementen']['buttonDeleteEvenementen'].onclick = deleteEvent;
    document.forms['formCreateEvenementen']['buttonEditEvenementen'].onclick = getSelectedEventInfo;
    document.forms['formCreateEvenementen']['speakerToEvent'].onclick = fillSpeakersOfEvent;
    document.forms['formEvenementenSubjectListBoxAdd']['ToevoegenSubject'].onclick = function(event){
       addTemporarySubject(event,selectedTable); 
    }
    
    document.forms['formsprekerEvent']['buttonSaveSwapListsprekerEvent'].onclick = addNewSpeakers;
    
    $(".listBoxSpeakerEventLeft .dataTables_scrollBody").removeAttr("style");
	$(".listBoxSpeakerEventRight .dataTables_scrollBody").removeAttr("style");
	$(".listBoxSpeakerEventRight .dataTables_scrollBody").addClass("noScrollBody");
	$(".listBoxSpeakerEventLeft .dataTables_scrollBody").addClass("noScrollBody");
    
    
});

function deleteEvent(){
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
            console.log(data);
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
            console.log($('#editEventPictureBtn'));
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
function addTemporarySubject(event,dataTable){
    $('#popUpEvenementenSubjectListBoxAdd .closePopUp').click();
    var newSubject = document.forms['formEvenementenSubjectListBoxAdd']['subjectName'].value;
    dataTable.row.add([newSubject]).draw(true).nodes().to$().addClass('selected');
    var newRow = dataTable.row(dataTable.data().length -1);
    newRow.node().childNodes[0].setAttribute('name',newRow.data());
    var child = document.createElement('input');
    child.setAttribute('value',document.forms['formEvenementenSubjectListBoxAdd']['subjectName'].value);
    child.setAttribute('name', 'subjects[]');
    child.style.visibility = 'hidden';
    newRow.node().childNodes[0].appendChild(child);
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
            var table = $('#listBoxSpeakerEventLeft').DataTable();
            for(i = 0; i< data.length; i++){
                oldSpeakers.push(data[i]);
                table.row.add([data[i][0],data[i][1],data[i][2],data[i][3]]);
            }
            table.rows().draw();
        }
    });
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