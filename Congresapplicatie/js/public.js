var parents;

$(document).ready(function () {
    $(".moreInfoButton").on("click", function (event) {
        parents = $(this).closest("div");
        alert(parents);
    });
});
