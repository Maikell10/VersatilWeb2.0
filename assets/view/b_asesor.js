$(document).ready(function () {
    if ($("#tableA").length > 0) {
        $('#tableA').DataTable({
            "order": [
                [11, "desc"]
            ],
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Todos"]
            ],
        });
        $('.dataTables_length').addClass('bs-select');
    }

    if ($("#tableAs").length > 0) {
        $('#tableAs').DataTable({
            "order": [
                [0, "asc"]
            ],
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Todos"]
            ],
        });
        $('.dataTables_length').addClass('bs-select');
    }

    if ($("#tablaAc").length > 0) {
        $('#tablaAc').DataTable({
            "order": [
                [0, "desc"]
            ],
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Todos"]
            ],
            columnDefs: [{
                targets: [5],
                render: $.fn.dataTable.render.moment('YYYY/MM/DD', 'DD-MM-YYYY'),
            }]
        });
        $('.dataTables_length').addClass('bs-select');
    }
});

$( "#tableA tbody tr" ).dblclick(function() {
    var customerId = $(this).find("td").eq(2).html();   

    window.open ("v_asesor.php?cod_asesor="+customerId ,'_blank');
});

$( "#tableAs tbody tr" ).dblclick(function() {
    var customerId = $(this).find("td").eq(2).html();   

    window.open ("../v_asesor.php?cod_asesor="+customerId ,'_blank');
});

$("#tablaAc tbody tr").dblclick(function () {
    var customerId = $(this).find("td").eq(11).html();

    window.open("../v_poliza.php?id_poliza=" + customerId, '_blank');
});