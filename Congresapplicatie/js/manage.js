$(document).ready(function () {
	$('.file-Caller').on("click",function(event){
        $(event.target.attributes.getNamedItem('data-file').value).trigger('click');
        console.log('test?');
    });
});