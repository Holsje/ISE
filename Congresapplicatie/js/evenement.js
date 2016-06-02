var eventListBox;
var eventSubjectAddListBox;
var eventSelect;
var selectedCounter = 0;

$(document).ready(function () {
	$('.file-Caller').on("click",function(event){
        $(event.target.attributes.getNamedItem('data-file').value).trigger('click');
    });
    $('.file-Holder').change(function(){
        readURL(this);
    });
    eventSubjectAddListBox = $('#EvenementenSubjectListBoxAdd').DataTable( {
			         "sScrollY": "500px",
			         "bPaginate": false
    });

    eventListBox =  $('#EvenementenListBox').DataTable( {
			         "sScrollY": "500px",
			         "bPaginate": false
    });
    
    $('#EvenementenSubjectListBoxAdd tbody').on('click','tr',function(){
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
    $('#eventType').change(function(event){
        if(event.target.value == 'Lezing'){
            document.getElementsByName('eventPrice')[0].parentElement.style.display = 'None';
        }else{
            document.getElementsByName('eventPrice')[0].parentElement.style.display = 'block';
        }
    });
    
    
});

function readURL(input) {
    "use strict";
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $(input.attributes.getNamedItem("data-file").value).css("background-image", "url(" + e.target.result + ")");
        };
        reader.readAsDataURL(input.files[0]);
    }
}