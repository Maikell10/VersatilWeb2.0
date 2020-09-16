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

$("#tableA tbody tr").dblclick(function () {
    var customerId = $(this).find("td").eq(1).html();

    window.open("v_cia.php?id_cia=" + customerId, '_blank');
});