$(document).ready(function () {
    $('#tableA').DataTable({
        "order": [
            [0, "asc"]
        ],
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "Todos"]
        ],
    });
    $('.dataTables_length').addClass('bs-select');
});

$( "#tableA tbody tr" ).click(function() {
    var customerId = $(this).find("td").eq(2).html();   

    window.open ("v_asesor.php?cod_asesor="+customerId ,'_blank');
});