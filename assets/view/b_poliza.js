$(document).ready(function () {


    alertify.defaults.theme.ok = "btn blue-gradient";
    alertify.defaults.theme.cancel = "btn young-passion-gradient text-white";
    alertify.defaults.theme.input = "form-control";

    var today = new Date();
    $('#anio').val(today.getFullYear());
    $('#anio').change();

    $('#table').DataTable({
        "order": [
            [0, "desc"]
        ],
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "Todos"]
        ],
        columnDefs: [{
            targets: [5, 6],
            render: $.fn.dataTable.render.moment('YYYY/MM/DD', 'DD-MM-YYYY'),
        }]
    });
    $('.dataTables_length').addClass('bs-select');


});

$("#table tbody tr").dblclick(function () {
    var customerId = $(this).find("td").eq(1).html();

    window.open("v_poliza.php?id_poliza=" + customerId, '_blank');
});

num_caracteres_permitidos = 300;

function valida_longitud() {
    num_caracteres = $('#comentarioS').val();
    $('#caracteres').val('Caracteres restantes: ' + (num_caracteres_permitidos - num_caracteres.length));
}

$('#btnSeguimiento').click(function() {
    if ($('#comentarioS').val() == '') {
        alertify.error("Debe escribir un comentario primero");
    } else {
        datos = $('#frmnuevoS').serialize();
        $.ajax({
            type: "POST",
            data: datos,
            url: "../procesos/agregarSeguimiento.php",
            success: function(r) {
                if (r == 1) {
                    $('#frmnuevoS')[0].reset();
                    $('#seguimientoRenov').modal('hide');
                    alertify.success("Seguimiento agregado con exito");
                } else {
                    alertify.error("Fallo al agregar");
                }
            }
        });
    }
});

function eliminarPoliza(idpoliza) {
    alertify.confirm('Eliminar una Póliza', '¿Seguro de eliminar esta Póliza?', function () {
        $.ajax({
            type: "POST",
            data: "idpoliza=" + idpoliza,
            url: "../procesos/eliminarPoliza.php",
            success: function (r) {
                if (r == 1) {
                    alertify.alert('Eliminada con exito !', 'La Póliza fue eliminada con exito', function () {
                        alertify.success('OK');
                        window.location.replace("b_poliza.php");
                    });
                } else {
                    alertify.error("No se pudo eliminar, puede tener pagos asociados");
                }
            }
        });
    }, function () {
    }).set({ labels: { ok: 'Ok', cancel: 'Cancelar' } });
}