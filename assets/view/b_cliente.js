$(document).ready(function () {
    
    if ($("#tableA").length > 0) {
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
    }

    if ($("#table_cliente_nb").length > 0) {
        $('#table_cliente_nb').DataTable({
            "order": [
                [4, "desc"]
            ],
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Todos"]
            ],
            columnDefs: [{
                targets: [4],
                render: $.fn.dataTable.render.moment('YYYY/MM/DD', 'DD-MM-YYYY'),
            }]
        });
        $('.dataTables_length').addClass('bs-select');
    }

    if ($("#table_cliente_b").length > 0) {
        $('#table_cliente_b').DataTable({
            "order": [
                [5, "asc"]
            ],
            pageLength: -1,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Todos"]
            ],
        });
        $('.dataTables_length').addClass('bs-select');
    }

    if ($("#table_cliente_pb").length > 0) {
        $('#table_cliente_pb').DataTable({
            "order": [
                [4, "asc"]
            ],
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Todos"]
            ],
        });
        $('.dataTables_length').addClass('bs-select');
    }

    if ($("#table_cliente_bm").length > 0) {
        $('#table_cliente_bm').DataTable({
            "order": [
                [5, "asc"],
                [4, "asc"]
            ],
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Todos"]
            ],
        });
        $('.dataTables_length').addClass('bs-select');
    }

    if ($("#table_cliente_bp").length > 0) {
        $('#table_cliente_bp').DataTable({
            "order": [
                [4, "desc"]
            ],
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Todos"]
            ],
        });
        $('.dataTables_length').addClass('bs-select');
    }
});

$("#tableA tbody tr").dblclick(function () {
    var customerId = $(this).find("td").eq(1).html();
    var customerId1 = $(this).find("td").eq(0).html();

    window.open("v_cliente.php?id_cliente=" + customerId + "&id_titu=" + customerId1, '_blank');
});

$("#table_cliente_bm tbody tr").dblclick(function () {
    var customerId = $(this).find("td").eq(1).html();
    var customerId1 = $(this).find("td").eq(0).html();

    window.open("../v_cliente.php?id_cliente=" + customerId + "&id_titu=" + customerId1, '_blank');
});

$("#table_cliente_bp tbody tr").dblclick(function () {
    var customerId = $(this).find("td").eq(1).html();
    var customerId1 = $(this).find("td").eq(0).html();

    window.open("../v_cliente.php?id_cliente=" + customerId + "&id_titu=" + customerId1, '_blank');
});

$("#table_cliente_nb tbody tr").dblclick(function () {
    var customerId = $(this).find("td").eq(1).html();
    var customerId1 = $(this).find("td").eq(0).html();

    window.open("../v_cliente.php?id_cliente=" + customerId + "&id_titu=" + customerId1, '_blank');
});

$("#table_cliente_b tbody tr").dblclick(function () {
    var customerId = $(this).find("td").eq(1).html();
    var customerId1 = $(this).find("td").eq(0).html();

    window.open("../v_cliente.php?id_cliente=" + customerId + "&id_titu=" + customerId1, '_blank');
});

$("#table_cliente_pb tbody tr").dblclick(function () {
    var customerId = $(this).find("td").eq(1).html();
    var customerId1 = $(this).find("td").eq(0).html();

    window.open("../v_cliente.php?id_cliente=" + customerId + "&id_titu=" + customerId1, '_blank');
});

$("#tableCliente tbody tr").dblclick(function () {
    var customerId = $(this).find("td").eq(10).html();

    window.open("v_poliza.php?id_poliza=" + customerId, '_blank');
});

$("#tableClienteEmail tbody tr").dblclick(function () {
    var customerId = $(this).find("td").eq(10).html();

    window.open("../v_poliza.php?id_poliza=" + customerId, '_blank');
});

function mayus(e) {
    e.value = e.value.toUpperCase();
}

function eliminarCliente(id_titular) {
    alertify.confirm('Eliminar una Cliente', '¿Seguro de eliminar este Cliente?', function () {
        $.ajax({
            type: "POST",
            data: "id_titular=" + id_titular,
            url: "../procesos/eliminarCliente.php",
            success: function (r) {
                if (r == 1) {
                    alertify.alert('Eliminado con exito !', 'El Cliente fue eliminado con exito', function () {
                        alertify.success('OK');
                        window.location.replace("b_cliente.php");
                    });
                } else {
                    alertify.error("No se pudo eliminar, puede tener pagos asociados");
                }
            }
        });
    }, function () {
    }).set({ labels: { ok: 'Ok', cancel: 'Cancelar' } });
}