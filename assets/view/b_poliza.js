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

    if ($("#tableBusq").length > 0) {
        $('#tableBusq').DataTable({
            "order": [
                [0, "desc"]
            ],
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Todos"]
            ],
            columnDefs: [{
                targets: [6, 7],
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

    if ($("#tableUser").length > 0) {
        $('#tableUser').DataTable({
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

    if ($("#tableRepGC").length > 0) {
        $('#tableRepGC').DataTable({
            "order": [
                [0, "desc"]
            ],
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Todos"]
            ],
            columnDefs: [{
                targets: [1, 2, 3],
                render: $.fn.dataTable.render.moment('YYYY/MM/DD', 'DD-MM-YYYY'),
            }]
        });
        $('.dataTables_length').addClass('bs-select');
    }

    if ($("#tableRenov").length > 0) {
        $('#tableRenov').DataTable({
            "order": [
                [0, "desc"]
            ],
            "pageLength": 50,
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

    if ($("#tableRenovG").length > 0) {
        $('#tableRenovG').DataTable({
            "order": [
                [0, "asc"]
            ],
            "pageLength": 50,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Todos"]
            ],
            columnDefs: [{
                targets: [7],
                render: $.fn.dataTable.render.moment('YYYY/MM/DD', 'DD-MM-YYYY'),
            }]
        });
        $('.dataTables_length').addClass('bs-select');
    }

    if ($("#tableRenovAct").length > 0) {
        $('#tableRenovAct').DataTable({
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

    if ($("#tableRenovAct1").length > 0) {
        $('#tableRenovAct1').DataTable({
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

    if ($("#tableRenovAct2").length > 0) {
        $('#tableRenovAct2').DataTable({
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

    if ($("#tableRenovAct3").length > 0) {
        $('#tableRenovAct3').DataTable({
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

$("#table tbody tr").dblclick(function () {
    var customerId = $(this).find("td").eq(1).html();

    window.open("v_poliza.php?id_poliza=" + customerId, '_blank');
});

$("#tableBusq tbody tr").dblclick(function () {
    var customerId = $(this).find("td").eq(1).html();

    window.open("v_poliza.php?id_poliza=" + customerId, '_blank');
});


$("#tableP tbody tr").dblclick(function () {
    var customerId = $(this).find("td").eq(1).html();

    window.open("v_poliza.php?id_poliza=" + customerId, '_blank');
});

$("#tableRenovCia tbody tr").dblclick(function () {

    if ($(this).attr('class') != 'no-tocar') {
        var customerId = $(this).find("td").eq(7).html();

        if (customerId == null) {
            var customerId = $(this).find("td").eq(6).html();
        }

        window.open("../v_poliza.php?id_poliza=" + customerId, '_blank');
    }
});

$("#tableRenovA tbody tr").dblclick(function () {

    if ($(this).attr('class') != 'no-tocar') {
        var customerId = $(this).find("td").eq(9).html();

        if (customerId == null) {
            var customerId = $(this).find("td").eq(8).html();
        }

        window.open("../v_poliza.php?id_poliza=" + customerId, '_blank');
    }
});

$("#tablePD tbody tr").dblclick(function () {

    if ($(this).attr('class') != 'no-tocar') {
        var customerId = $(this).find("td").eq(21).html();

        if (customerId == null) {
            var customerId = $(this).find("td").eq(20).html();
        }

        window.open("v_poliza.php?pagos=1&id_poliza=" + customerId, '_blank');
    }
});

$("#tableRep tbody tr").dblclick(function () {
    var customerId = $(this).find("td").eq(1).html();

    window.open("v_reporte_com.php?id_rep_com=" + customerId, '_blank');
});

$("#tableRepC tbody tr").dblclick(function () {
    var customerId = $(this).find("td").eq(0).html();

    window.open("b_reportes1.php?anio=&mes=&cia=" + customerId, '_blank');
});

$("#tableRepGC tbody tr").dblclick(function () {
    var customerId = $(this).find("td").eq(0).html();

    window.location.href = "v_reporte_gc.php?id_rep_gc=" + customerId;
});

$("#tableRepGCView tbody tr").dblclick(function () {
    if ($(this).attr('id') != 'no-tocar') {
        var customerId = $(this).find("td").eq(10).html();

        if (customerId == null) {
            var customerId = $(this).find("td").eq(9).html();
        }

        window.open("v_poliza.php?id_poliza=" + customerId, '_blank');
    }
});

$("#mytable tbody tr").dblclick(function () {
    if ($(this).attr('id') != 'no-tocar') {
        var customerId = $(this).find("td").eq(10).html();

        if (customerId == null) {
            var customerId = $(this).find("td").eq(9).html();
        }

        window.open("../v_poliza.php?id_poliza=" + customerId, '_blank');
    }
});

$("#mytableR tbody tr").dblclick(function () {
    if ($(this).attr('id') != 'no-tocar') {
        var customerId = $(this).find("td").eq(6).html();

        if (customerId == null) {
            var customerId = $(this).find("td").eq(5).html();
        }

        window.open("../v_poliza.php?id_poliza=" + customerId, '_blank');
    }
});

$("#tableUser tbody tr").dblclick(function () {
    var customerId = $(this).find("td").eq(0).html();

    window.open("v_usuario.php?id_usuario=" + customerId, '_blank');
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

$('#btnSeguimientoR').click(function () {
    if ($('#comentarioS').val() == '') {
        alertify.error("Debe escribir un comentario primero");
    } else {
        datos = $('#frmnuevoS').serialize();
        $.ajax({
            type: "POST",
            data: datos,
            url: "../../procesos/agregarSeguimiento.php",
            success: function (r) {
                if (r == 1) {
                    $('#frmnuevoS')[0].reset();
                    $('#seguimientoRenov').modal('hide');
                    alertify.success("Seguimiento agregado con exito");
                    //location.reload();
                } else {
                    alertify.error("Fallo al agregar");
                }
            }
        });
    }
});

$('#btnCargaPago').click(function () {
    if ($("#n_transf").val().length < 4) {
        alertify.error("El Nº de Transferencia es Obligatorio");
        return false;
    }
    if ($("#n_banco").val().length < 1) {
        alertify.error("El Nombre del Banco es Obligatorio");
        return false;
    }
    if ($("#f_pago_gc_r").val().length < 1) {
        alertify.error("Debe Seleccionar una Fecha de Pago");
        return false;
    }
    if ($("#monto_p").val().length < 1) {
        alertify.error("El Monto es Obligatorio");
        return false;
    }

    datos = $('#frmnuevoS').serialize();
    $.ajax({
        type: "POST",
        data: datos,
        url: "../../procesos/agregarCargaPago.php",
        success: function (r) {
            if (r == 1) {
                $('#frmnuevoS')[0].reset();
                $('#cargaPago').modal('hide');
                alertify.success("Pago agregado con exito");
                location.reload();
            } else {
                alertify.error("Fallo al agregar");
            }
        }
    });

});

$('#btnNoRenov').click(function () {
    if ($('#no_renov').val() == '') {
        alertify.error("Debe seleccionar un motivo primero");
    } else {
        datos = $('#frmnuevoNR').serialize();
        $.ajax({
            type: "POST",
            data: datos,
            url: "../../procesos/noRenovar.php",
            success: function (r) {
                if (r == 1) {
                    $('#frmnuevoNR')[0].reset();
                    $('#noRenov').modal('hide');
                    alertify.success("Agregada no Renovación con éxito");
                    location.reload();
                } else {
                    alertify.error("Fallo al agregar");
                }
            }
        });
    }
});

$('#btnNoRenovP').click(function () {
    if ($('#no_renov').val() == '') {
        alertify.error("Debe seleccionar un motivo primero");
    } else {
        datos = $('#frmnuevoNR').serialize();
        $.ajax({
            type: "POST",
            data: datos,
            url: "../procesos/noRenovar.php",
            success: function (r) {
                if (r == 1) {
                    $('#frmnuevoNR')[0].reset();
                    $('#noRenov').modal('hide');
                    alertify.success("Agregada no Renovación con éxito");
                    location.reload();
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

function eliminarUsuario(idusuario) {
    alertify.confirm('Eliminar Usuario', '¿Seguro de eliminar este Usuario?', function () {
        $.ajax({
            type: "POST",
            data: "idusuario=" + idusuario,
            url: "../procesos/eliminarUsuario.php",
            success: function (r) {
                if (r == 1) {
                    alertify.alert('Eliminado con exito !', 'El Usuario fue eliminado con exito', function () {
                        alertify.success('OK');
                        window.location.replace("b_usuario.php");
                    });
                } else {
                    alertify.error("No se pudo eliminar");
                }
            }
        });
    }, function () {
    }).set({ labels: { ok: 'Ok', cancel: 'Cancelar' } });
}

function eliminarAsesor(idasesor, a) {
    alertify.confirm('Eliminar Asesor', '¿Seguro de eliminar este Asesor?', function () {
        $.ajax({
            type: "POST",
            data: "idasesor=" + idasesor,
            url: "../procesos/eliminarAsesor.php?a=" + a,
            success: function (r) {
                if (r == 1) {
                    alertify.alert('Eliminado con exito !', 'El Asesor fue eliminado con exito', function () {
                        alertify.success('OK');
                        window.close();
                    });
                } else {
                    alertify.error("No se pudo eliminar");
                }
            }
        });
    }, function () {
    }).set({ labels: { ok: 'Ok', cancel: 'Cancelar' } });
}

function eliminarReporte(id_rep_com) {
    alertify.confirm('Eliminar Reporte de Comisiones', '¿Seguro de eliminar este Reporte de Comisiones?', function () {
        $('.alertify .ajs-header').css('background-color', 'green');
        $.ajax({
            type: "POST",
            data: "id_rep_com=" + id_rep_com,
            url: "../procesos/eliminarRepCom.php",
            success: function (r) {
                if (r == 1) {
                    alertify.alert('Eliminado con exito !', 'El Reporte de Comisiones fue eliminado con exito', function () {
                        alertify.success('OK');
                        window.close();
                    });
                } else {
                    alertify.error("No se pudo eliminar");
                }
            }
        });
    }, function () {
    }).set({ labels: { ok: 'Ok', cancel: 'Cancelar' } });
}

function eliminarComision(id_comision) {
    alertify.confirm('Eliminar Comisione Seleccionada', '¿Seguro de eliminar esta Comisión?', function () {
        $('.alertify .ajs-header').css('background-color', 'green');
        $.ajax({
            type: "POST",
            data: "id_comision=" + id_comision,
            url: "../procesos/eliminarComision.php",
            success: function (r) {
                if (r == 1) {
                    alertify.alert('Eliminada con exito !', 'La Comisión fue eliminada con exito', function () {
                        alertify.success('OK');
                        location.reload();
                    });
                } else {
                    alertify.error("No se pudo eliminar");
                }
            }
        });
    }, function () {
    }).set({ labels: { ok: 'Ok', cancel: 'Cancelar' } });
}

function eliminarReporteGC(id_rep_gc) {
    alertify.confirm('Eliminar un Reporte GC', '¿Seguro de eliminar este Reporte de GC?', function () {
        $.ajax({
            type: "POST",
            data: "id_rep_gc=" + id_rep_gc,
            url: "../procesos/eliminarReporteGC.php",
            success: function (r) {
                if (r == 1) {
                    alertify.alert('Eliminado con exito !', 'El Reporte de GC fue eliminado con exito', function () {
                        alertify.success('OK');
                        window.location.replace("b_reportes_gc.php");
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

function crearSeguimiento(idpoliza) {
    $('#id_polizaS').val(idpoliza)
    $('#seguimientoRenov').modal('show');
}

function crearPago(id_gc_h_r) {
    $('#id_gc_h_r').val(id_gc_h_r)
    $('#cargaPago').modal('show');
}

function noRenovar(idpoliza, f_hasta) {
    $('#id_polizaNR').val(idpoliza)
    $('#f_hastaNR').val(f_hasta)
    $('#noRenov').modal('show');
}