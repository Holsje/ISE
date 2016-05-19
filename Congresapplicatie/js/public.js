var target;

$(document).ready(function () {
    $(".moreInfoButton").on("click", function (event) {
        target = $('.popupTitle h1');
        getEventInfo($(this).closest("div").attr("value"));
    });
});


function getEventInfo(eventNo) {
    $.ajax({
        url: window.location.href,
        type: 'POST',
        data: {
            getInfo: 'GetInfo',
            eventNo: eventNo
        },
        success: function (data) {
            data = JSON.parse(data);
            $('.popupTitle h1').html(data['ENAME']);
            $('#thumbnail').attr('src', data['FILEDIRECTORY'] + 'thumbnail.png');
            $('#subjects').html(data['SUBJECT']);
        }
    });
}
