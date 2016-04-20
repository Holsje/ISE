 function jAlert(title,message){
 
	if(!$("#dialog").length) {
		var dialog = document.createElement("div");
		dialog.id = "dialog";
		dialog.title = title;
		var p = document.createElement("P");
		p.id = "dialogMessage";
		var t = document.createTextNode(message);
		p.appendChild(t);
		dialog.appendChild(p);
		document.body.appendChild(dialog);
	}
	else {
		$("#dialog")
		$(".ui-dialog-title").text(title);
		$("#dialogMessage").text(message);
		
	}
	
	$(function() {
		$( "#dialog" ).dialog();
	  });
 }
 
 
 function getAjax(url,jsonData,handleData) {
	$.ajax({
		url: url, 
		type: 'GET',
		data : jsonData,
		contentType: 'application/json; charset=utf-8',
		success:function(data) {
			handleData(data)
		},
		error: function() {
			handleData('error')
		}
	});	
}


function makeError(errorMessage,id) {
	if(id) {	
		$("#" + id).text("* " + errorMessage);
		$("#" + id)[0].style.display = "";
	}else {
		$("#errorMsg").text("* " + errorMessage);
		$("#errorMsg")[0].style.display = "";
	}
}