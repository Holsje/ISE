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
		$("#dialog").title = title;
		$("#dialogMessage").innerHTML = message;
		
	}
	
	
	$(function() {
		$( "#dialog" ).dialog();
	  });
 }