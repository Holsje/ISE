var selectedTable;
$(document).ready(function () {

    document.forms['formSubjectListBoxAdd']['ToevoegenSubject'].onclick = function(event){
        alert("TEst");
        addTemporarySubject(event,selectedTable);
    }

    $('.subjectAdd').on('click',function(event){
        selectedTable = $('#' + event.target.form.getElementsByClassName('subjectListBox')[1].getAttribute('id')).DataTable();
    });
});
function addTemporarySubject(event,dataTable){
    $('#popUpSubjectListBoxAdd .closePopUp').click();
    var newSubject = document.forms['formSubjectListBoxAdd']['subjectName'].value;
    dataTable.row.add([newSubject]).draw(true).nodes().to$().addClass('selected');
    var newRow = dataTable.row(dataTable.data().length -1);
    newRow.node().childNodes[0].setAttribute('name',newRow.data());
    var child = document.createElement('input');
    child.setAttribute('value',document.forms['formSubjectListBoxAdd']['subjectName'].value);
    child.setAttribute('name', 'subjects[]');
    child.style.visibility = 'hidden';
    newRow.node().childNodes[0].appendChild(child);
}