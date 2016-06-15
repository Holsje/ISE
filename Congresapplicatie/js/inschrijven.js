var eventTestTop;
$(document).ready(function () {
    
    resize();
    
    $('.eventBoxSignup').on('click',function(event){
        eventTestTop = event;
        var topEvent = parseInt(event.target.style['top']);
        var heightEvent = parseInt(event.target.style['height']);
        var myself = $(event.target).children('input').val();
        var array = $('.eventBoxSignup');
        for(i=0; i<array.length;i++)
        {
            if(myself != $(array[i]).children('input').val()){
                var innerTop = parseInt(array[i].style['top']);
                var innerHeight = parseInt(array[i].style['height']);

                if((topEvent > innerTop && topEvent < innerTop + innerHeight) || (topEvent + heightEvent > innerTop && topEvent + heightEvent < innerTop + innerHeight) || (topEvent <= innerTop && topEvent + heightEvent >= innerTop + innerHeight)){
                    if($(array[i]).hasClass('selected')){
                        $(array[i]).removeClass('selected');
                        $(array[i]).find("input")[0].name = "eventNo[]";
                    }
                }
            }
        }
    });
});

function resize() {
	if (fileName == "inschrijven.php") {
		$('.moreInfoButton').removeClass('pull-right');
	}
	else {
		$('.eventImage').css("display", "block");
		$('.eventText').removeClass("col-xs-12");
		$('.eventText').addClass("col-xs-6");
		$('.eventImage').removeClass("col-xs-6");
		$('.eventImage').addClass("col-xs-5");
		$('.eventImage').addClass("pull-right");
	}
	if (window.matchMedia("(max-width: 768px)").matches) {
		if (fileName == "inschrijven.php") {
			$('.moreInfoButton').removeClass('btn btn-default pull-right');
			$('.moreInfoButton').addClass('infoGlyph glyphicon glyphicon-info-sign');
			$('.moreInfoButton').html('');
		}
		$.ajax({
			url: 'inschrijven.php',
			type: 'POST',
			data: {
				tracksPerCarouselSlide: 2
			}
		});
	}
	else if (window.matchMedia("(min-width: 800px)").matches) {
		if (fileName == "inschrijven.php") {
			$('.moreInfoButton').removeClass('infoGlyph glyphicon glyphicon-info-sign');
			$('.moreInfoButton').addClass('btn btn-default pull-right');
			
		}
			$.ajax({
				url: 'inschrijven.php',
				type: 'POST',
				data: {
					tracksPerCarouselSlide: 3
				},
				success: function(data) {
			         $('.moreInfoButton').html(data);
				}
			});
	}
}