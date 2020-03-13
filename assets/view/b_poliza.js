$(document).ready(function () {


    alertify.defaults.theme.ok = "btn blue-gradient";
    alertify.defaults.theme.cancel = "btn young-passion-gradient text-white";
    alertify.defaults.theme.input = "form-control";

    var today = new Date();
    $('#anio').val(today.getFullYear());
    $('#anio').change();

    if ($("#table").length > 0) {
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
    }

    if ($("#tableP").length > 0) {
        $('#tableP').DataTable({
            "order": [
                [0, "desc"]
            ],
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Todos"]
            ],
            columnDefs: [{
                targets: [4, 5],
                render: $.fn.dataTable.render.moment('YYYY/MM/DD', 'DD-MM-YYYY'),
            }]
        });
        $('.dataTables_length').addClass('bs-select');
    }

    if ($("#tableRep").length > 0) {
        $('#tableRep').DataTable({
            "order": [
                [0, "desc"]
            ],
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Todos"]
            ],
            columnDefs: [{
                targets: [2, 6],
                render: $.fn.dataTable.render.moment('YYYY/MM/DD', 'DD-MM-YYYY'),
            }]
        });
        $('.dataTables_length').addClass('bs-select');
    }

    if ($("#tableRepC").length > 0) {
        $('#tableRepC').DataTable({
            "order": [
                [0, "asc"]
            ],
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Todos"]
            ],
            pageLength: 50 
        });
        $('.dataTables_length').addClass('bs-select');
    }

});

$("#table tbody tr").dblclick(function () {
    var customerId = $(this).find("td").eq(1).html();

    window.open("v_poliza.php?id_poliza=" + customerId, '_blank');
});

$("#tableP tbody tr").dblclick(function () {
    var customerId = $(this).find("td").eq(1).html();

    window.open("v_poliza.php?id_poliza=" + customerId, '_blank');
});

$("#tableRenovCia tbody tr").dblclick(function () {

    if ($(this).attr('class') != 'no-tocar') {
        var customerId = $(this).find("td").eq(5).html();

        if (customerId == null) {
            var customerId = $(this).find("td").eq(4).html();
        }

        window.open("../v_poliza.php?id_poliza=" + customerId, '_blank');
    }
});

$("#tableRenovA tbody tr").dblclick(function () {

    if ($(this).attr('class') != 'no-tocar') {
        var customerId = $(this).find("td").eq(7).html();

        if (customerId == null) {
            var customerId = $(this).find("td").eq(6).html();
        }

        window.open("../v_poliza.php?id_poliza=" + customerId, '_blank');
    }
});

$("#tableRep tbody tr").dblclick(function() {
    var customerId = $(this).find("td").eq(1).html();

    window.open("v_reporte_com.php?id_rep_com=" + customerId, '_blank');
});

$( "#tableRepC tbody tr" ).click(function() {
    var customerId = $(this).find("td").eq(0).html();   

    window.open ("b_reportes1.php?anio=&mes=&cia="+customerId ,'_blank');
});

num_caracteres_permitidos = 300;

function valida_longitud() {
    num_caracteres = $('#comentarioS').val();
    $('#caracteres').val('Caracteres restantes: ' + (num_caracteres_permitidos - num_caracteres.length));
}

$('#btnSeguimiento').click(function () {
    if ($('#comentarioS').val() == '') {
        alertify.error("Debe escribir un comentario primero");
    } else {
        datos = $('#frmnuevoS').serialize();
        $.ajax({
            type: "POST",
            data: datos,
            url: "../procesos/agregarSeguimiento.php",
            success: function (r) {
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

function eliminarPolizaP(idpoliza) {
    alertify.confirm('Eliminar una Póliza Pendiente', '¿Seguro de eliminar esta Póliza?', function () {
        console.log(idpoliza);
        $.ajax({
            type: "POST",
            data: "idpoliza=" + idpoliza,
            url: "../procesos/eliminarPoliza.php",
            success: function (r) {
                if (r == 1) {
                    alertify.alert('Eliminada con exito !', 'La Póliza fue eliminada con exito', function () {
                        alertify.success('OK');
                        window.location.replace("b_pendientes.php");
                    });
                } else {
                    alertify.error("No se pudo eliminar");
                }
            }
        });

    }, function () {
    }).set({ labels: { ok: 'Ok', cancel: 'Cancelar' } });
}

// DATEPICKER
// Get the elements
if ($("#startingDate").length > 0) {
    var from_input = $('#startingDate').pickadate(),
        from_picker = from_input.pickadate('picker')
    var to_input = $('#endingDate').pickadate(),
        to_picker = to_input.pickadate('picker')
    // Check if there’s a “from” or “to” date to start with and if so, set their appropriate properties.
    if (from_picker.get('value')) {
        to_picker.set('min', from_picker.get('select'))
    }
    if (to_picker.get('value')) {
        from_picker.set('max', to_picker.get('select'))
    }
    // Apply event listeners in case of setting new “from” / “to” limits to have them update on the other end. If ‘clear’ button is pressed, reset the value.
    from_picker.on('set', function (event) {
        if (event.select) {
            to_picker.set('min', from_picker.get('select'))
        } else if ('clear' in event) {
            to_picker.set('min', false)
        }
    })
    to_picker.on('set', function (event) {
        if (event.select) {
            from_picker.set('max', to_picker.get('select'))
        } else if ('clear' in event) {
            from_picker.set('max', false)
        }
    })
}