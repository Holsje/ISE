$(document).ready(function () {
	$('.file-Caller').on("click",function(event){
        $(event.target.attributes.getNamedItem('data-file').value).trigger('click');
    });
    $('.file-Holder').change(function(){
        readURL(this);
    });
	$(".dataTables_scrollBody").removeAttr("style");
	$(".dataTables_scrollBody").addClass("scrollBody");

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
