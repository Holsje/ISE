var eventTest;

$(document).ready(function () {
    $(".popupButton").on('click', function (event) {
        $(event.target.attributes.getNamedItem("data-file").value).fadeToggle();
        $("#wrapper").css("overflow", "hidden");
        $("body").css("overflow", "hidden");
        $("body").css("position", "relative");
        $("body").css("height", "100%");
    });

    $(".closePopup").on("click", function (event) {
        $(".successMsg").empty()

        $(event.target.attributes.getNamedItem("data-file").value).fadeToggle();
        if(event.target.attributes.getNamedItem("data-file").value == '#popUpLogin'){
            window.location.href = window.location;
        }

        if(document.getElementById(event.target.attributes.getNamedItem('data-file').value.substring(1)).classList.contains('show')){
            $(event.target.attributes.getNamedItem("data-file").value).removeClass('show');

        }


    });
    $(".first").on("click", function (event) {
        $("#wrapper").css("overflow", "auto");
        $("body").css("overflow", "auto");
    });
});
