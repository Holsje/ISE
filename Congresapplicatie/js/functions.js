$(document).ready(function () {
    $(".popupButton").on('click', function (event) {
        $(event.target.attributes.getNamedItem("data-file").value).fadeToggle();
        $("#wrapper").css("overflow", "hidden");
        $("body").css("overflow", "hidden");

    });

    $(".closePopup").on("click", function (event) {
        $(event.target.attributes.getNamedItem("data-file").value).fadeToggle();

    });
    $(".first").on("click", function (event) {
        $("#wrapper").css("overflow", "auto");
        $("body").css("overflow", "auto");
    });
});
