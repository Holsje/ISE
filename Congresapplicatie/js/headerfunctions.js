var scrollTop;

$(document).ready(function() {
	$(document).scroll(function() {
		"use strict";
		scrollTop = $(window).scrollTop();
		
		if (scrollTop >= 155) {
			$(".navbar").addClass("navbar-fixed-top");
		}
		else {
			if ($(".navbar-fixed-top") != undefined) {
				$(".navbar").removeClass("navbar-fixed-top");
			}
		}
	});	
})



$(document).ready(function () {
    $(".popupButton").on("click", function (event) {
        $(event.target.attributes.getNamedItem("data-file").value).fadeToggle();
        console.log($(this).closest("div").attr("value"));
    });
});
