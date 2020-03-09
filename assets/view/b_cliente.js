$(document).ready(function () {
    $('#tableA').DataTable({
        "order": [
            [5, "desc"]
        ],
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "Todos"]
        ],
    });
    $('.dataTables_length').addClass('bs-select');

    $('#tableB').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "getData.php"
    });
});

$( "#tableA tbody tr" ).dblclick(function() {
    var customerId = $(this).find("td").eq(0).html();   

    window.open ("v_cliente.php?id_titular="+customerId ,'_blank');
});