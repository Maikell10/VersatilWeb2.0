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

$("#tableCliente tbody tr").dblclick(function () {
    var customerId = $(this).find("td").eq(10).html();

    window.open("v_poliza.php?id_poliza=" + customerId, '_blank');
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