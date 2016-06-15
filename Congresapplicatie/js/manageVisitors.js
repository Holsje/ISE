/**
 * Created by erike on 10-6-2016.
 */
var tableVisitors,
    tableEvents;
$(document).ready(function () {
    tableVisitors = $('#VisitorsListBox').DataTable({
        "sScrollY": "500px",
        "bPaginate": false,
        "bInfo": false,
        "columnDefs": [ {
            "targets": [0],
            "visible": false,
            "searchable": false
        },
            {
            "targets": [1],
            "visible": false,
            "searchable": false
        }],
        "language": {
            "emptyTable": "Geen data beschikbaar",
            "sSearch": "Zoeken:"
        }
    });

    tableEvents = $('#EventsOfVisitorListbox').DataTable({
        "sScrollY": "500px",
        "bPaginate": false,
        "bInfo": false,
        "language": {
            "emptyTable": "Geen data beschikbaar",
            "sSearch": "Zoeken:"
        }
    });

    tableVisitors.on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
            $('.onSelected').prop('disabled', true);
        } else {
            tableVisitors.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            $('.onSelected').prop('disabled', false);
        }
    });

    document.forms["formVisitorsOfCongress"]["buttonEditVisitor"].onclick = getVisitorInformation;

    function getVisitorInformation(){
        var selectedRow = tableVisitors.row('.selected');
        if (selectedRow.data()) {
            $.ajax({
                url: window.location.href,
                type: 'POST',
                data: {
                    getVisitorInfo: 'action',
                    personNo: selectedRow.data()[0]
                },
                success: function (data) {
                    data = JSON.parse(data);
                    document.forms["formUpdateVisitor"]["visitorPersonNo"].value = selectedRow.data()[0];
                    document.forms["formUpdateVisitor"]["congressPriceVisitor"].value = selectedRow.data()[1];
                    document.forms["formUpdateVisitor"]["visitorFirstName"].value = selectedRow.data()[2];
                    document.forms["formUpdateVisitor"]["visitorLastName"].value = selectedRow.data()[3];
                    document.forms["formUpdateVisitor"]["visitorMailAddress"].value = selectedRow.data()[4];
                    document.forms["formUpdateVisitor"]["visitorHasPaid"].value = selectedRow.data()[5];
                    document.forms["formUpdateVisitor"]["totalPrice"].value = selectedRow.data()[6];
                    tableEvents.rows().remove().draw(false);
                    for(var i = 0;i<data.length;i++) {
                        if (data[i][1] == null){
                            var price = "0,00"
                        }else{
                            var price = data[i][1];
                        }

                        tableEvents.row.add([data[i][0], price]);
                    }
                    tableEvents.row().draw();

                    $('[name = "congressPriceVisitor"]').closest(".form-group").after("<hr/>")
                    $('hr').addClass("priceLine");
                }

            });


        }else{
            alert("Er is geen selectie gemaakt.");
        }
    }
});