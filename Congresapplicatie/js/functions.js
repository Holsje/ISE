$(document).ready(function () {
    $(".popupButton").on("click", function (event) {
        $(event.target.attributes.getNamedItem("data-file").value).fadeToggle();
        $("body").css("overflow", "hidden");
    });

    $(".closePopup").on("click", function (event) {
        $(event.target.attributes.getNamedItem("data-file").value).fadeToggle();
        $("body").css("overflow", "auto");
    });
});
