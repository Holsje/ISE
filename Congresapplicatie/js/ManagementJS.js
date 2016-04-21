/**
 * Created by erike on 20-4-2016.
 */
$(document).ready(function(){
   $(".popupButton").on("click",function(event){
        $(event.target.attributes.getNamedItem("data-file").value).css("display", "block");
   });

   $(".closePopup").on("click", function(event){
        $(event.target.attributes.getNamedItem("data-file").value).css("display", "none");
   });
});