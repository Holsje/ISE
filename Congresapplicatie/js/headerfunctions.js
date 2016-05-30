var scrollTop;
$(document).ready(function () {
    $(document).scroll(function () {
        "use strict";
        scrollTop = $(window).scrollTop();
        if (scrollTop >= 155) {
            $(".navbar").addClass("navbar-fixed-top");
            $(".content").css('top','51px');
        } else {
            if ($(".navbar-fixed-top") != undefined) {
                $(".content").css('top', '0px');
                $(".navbar").removeClass("navbar-fixed-top");
            }
        }
    });
})
