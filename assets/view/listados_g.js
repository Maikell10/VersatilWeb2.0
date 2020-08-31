$(document).ready(function () {
    if ($("#PorcentajeRamo").length > 0) {
        $('#PorcentajeRamo').DataTable({
            "order": [
                [1, "desc"]
            ],
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Todos"]
            ],
            columnDefs: [{
                targets: [6, 7],
                render: $.fn.dataTable.render.moment('YYYY/MM/DD', 'DD-MM-YYYY'),
            }]
        });
        $('.dataTables_length').addClass('bs-select');
    }





    $("#PorcentajeRamo tbody tr").dblclick(function () {
        var customerId = $(this).find("td").eq(1).html();
    
        window.open("../../../v_poliza.php?id_poliza=" + customerId, '_blank');
    });
});