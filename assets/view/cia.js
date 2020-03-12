$(document).ready(function () {
    alertify.defaults.theme.ok = "btn blue-gradient";
    alertify.defaults.theme.cancel = "btn young-passion-gradient text-white";
    alertify.defaults.theme.input = "form-control";

    $('#tableCP').DataTable({
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

function eliminarCiaPref(idcia, f_desde, f_hasta) {
    alertify.confirm('Eliminar Datos de Preferencial', '¿Seguro de eliminar estos datos de la Cía en la fecha preferencial seleccionada?', function () {
        $.ajax({
            type: "POST",
            data: "idcia=" + idcia + "&f_desde=" + f_desde + "&f_hasta=" + f_hasta,
            url: "../procesos/eliminarCiaPref.php",
            success: function (r) {
                if (r == 1) {
                    alertify.alert('Eliminada con exito !', 'Fue eliminado con exito', function () {
                        alertify.success('OK');
                        window.location.replace("v_cia.php?id_cia=" + idcia);
                    });
                } else {
                    alertify.error("No se pudo eliminar");
                }
            }
        });
    }
        , function () {
        }).set({ labels: { ok: 'Ok', cancel: 'Cancelar' } });
}