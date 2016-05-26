var eventTest;

$(document).ready(function () {
    $(".popupButton").on('click', function (event) {
        $(event.target.attributes.getNamedItem("data-file").value).fadeToggle();
        $("#wrapper").css("overflow", "hidden");
        $("body").css("overflow", "hidden");

    });

    $(".closePopup").on("click", function (event) {
        eventTest = event;
        $(event.target.attributes.getNamedItem("data-file").value).fadeToggle();
        if(document.getElementById(eventTest.target.attributes.getNamedItem('data-file').value.substring(1)).classList.contains('show')){
            $(event.target.attributes.getNamedItem("data-file").value).removeClass('show');
            
        }
        window.location.href = window.location.href;

    });
    $(".first").on("click", function (event) {
        $("#wrapper").css("overflow", "auto");
        $("body").css("overflow", "auto");
    });
});
