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
