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
});

$("#tableA tbody tr").dblclick(function () {
    var customerId = $(this).find("td").eq(1).html();
    var customerId1 = $(this).find("td").eq(0).html();

    window.open("v_cliente.php?id_cliente=" + customerId + "&id_titu=" + customerId1, '_blank');
});

function mayus(e) {
    e.value = e.value.toUpperCase();
}