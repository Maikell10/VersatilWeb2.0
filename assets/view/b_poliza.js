$(document).ready(function () {

    var today = new Date();
    $('#anio').val(today.getFullYear());
    $('#anio').change();

    if ($("#table").length > 0) {
        $('#table').DataTable({
            "order": [
                [1, "desc"]
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

    if ($("#tableRenovF").length > 0) {
        $('#tableRenovF').DataTable({
            "order": [
                [1, "desc"]
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

    if ($("#tableAnulada").length > 0) {
        $('#tableAnulada').DataTable({
            "order": [
                [1, "desc"]
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

    if ($("#UtilGrafPol").length > 0) {
        $('#UtilGrafPol').DataTable({
            "order": [
                [1, "desc"]
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

    if ($("#table_ramo").length > 0) {
        $('#table_ramo').DataTable({
            "order": [
                [1, "asc"]
            ],
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Todos"]
            ],
        });
        $('.dataTables_length').addClass('bs-select');
    }

    if ($("#tableBusq").length > 0) {
        $('#tableBusq').DataTable({
            "order": [
                [2, "asc"]
            ],
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Todos"]
            ],
            columnDefs: [{
                targets: [7, 8],
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
                [1, "desc"]
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
                [0, "asc"]
            ],
            "pageLength": 50,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Todos"]
            ],
            columnDefs: [{
                targets: [7, 8],
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
            "pageLength": 50,
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
            "pageLength": 50,
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
            "pageLength": 50,
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
            "pageLength": 50,
            columnDefs: [{
                targets: [5],
                render: $.fn.dataTable.render.moment('YYYY/MM/DD', 'DD-MM-YYYY'),
            }]
        });
        $('.dataTables_length').addClass('bs-select');
    }

    if ($("#tableVRepCom").length > 0) {
        $('#tableVRepCom').DataTable({
            "order": [
                [10, "asc"]
            ],
            "pageLength": 50,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Todos"]
            ],
            columnDefs: [{
                targets: [3,4],
                render: $.fn.dataTable.render.moment('YYYY/MM/DD', 'DD-MM-YYYY'),
            }]
        });
        $('.dataTables_length').addClass('bs-select');
    }

    if ($("#tablePD").length > 0) {
        $('#tablePD').DataTable({
            "order": [
               
            ],
            "pageLength": -1,
            dom: 'Blfrtip', 
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Todos"]
            ],
            //para usar los botones   
            dom: 'Bfrtip',  
            buttons:[ 
                {
                    extend:    'excelHtml5',
                    text:      '<i class="fas fa-file-excel"></i> ',
                    titleAttr: 'Exportar a Excel',
                    className: 'btn btn-success',
                },
                'pageLength'
            ]
        });
        table.buttons().container()
              .appendTo('#datatable_wrapper .col-md-6:eq(0)');
    }

    if ($("#tablrPagoGCR").length > 0) {
        $('#tablrPagoGCR').DataTable({
            "order": [
                [4, "asc"]
            ],
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Todos"]
            ],
            "pageLength": 50,
            columnDefs: [{
                targets: [4],
                render: $.fn.dataTable.render.moment('YYYY/MM/DD', 'DD/MM/YYYY'),
            }]
        });
        $('.dataTables_length').addClass('bs-select');
    }

    if ($("#tablerPagoGCR").length > 0) {
        $('#tablerPagoGCR').DataTable({
            "order": [
                [1, "asc"]
            ],
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Todos"]
            ],
            "pageLength": 50,
            columnDefs: [{
                targets: [3],
                render: $.fn.dataTable.render.moment('YYYY/MM/DD', 'DD/MM/YYYY'),
            }]
        });
        $('.dataTables_length').addClass('bs-select');
    }

    if ($("#tablrBPagoGCR").length > 0) {
        $('#tablrBPagoGCR').DataTable({
            "order": [
                [1, "desc"]
            ],
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Todos"]
            ],
            "pageLength": 10,
            columnDefs: [{
                targets: [1],
                render: $.fn.dataTable.render.moment('YYYY/MM/DD', 'DD/MM/YYYY'),
            }]
        });
        $('.dataTables_length').addClass('bs-select');
    }

    if ($("#tablrBPagoGCP").length > 0) {
        $('#tablrBPagoGCP').DataTable({
            "order": [
                [1, "desc"]
            ],
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Todos"]
            ],
            "pageLength": 10,
            columnDefs: [{
                targets: [1],
                render: $.fn.dataTable.render.moment('YYYY/MM/DD', 'DD/MM/YYYY'),
            }]
        });
        $('.dataTables_length').addClass('bs-select');
    }
    
    
    $(".datepicker").prop('readonly', false);


});

$("#tablrPagoGCR tbody tr").dblclick(function () {
    var customerId = $(this).find("td").eq(0).html();

    window.open("../v_poliza.php?id_poliza=" + customerId, '_blank');
});

$("#tablerPagoGCR tbody tr").dblclick(function () {
    var customerId = $(this).find("td").eq(0).html();

    window.open("../v_poliza.php?id_poliza=" + customerId, '_blank');
});

$("#tablrBPagoGCR tbody tr").dblclick(function () {
    var customerId = $(this).find("td").eq(3).html();

    window.location.href = "v_pagos_ref.php?created_at=" + customerId;
});

$("#tablrBPagoGCP tbody tr").dblclick(function () {
    var customerId = $(this).find("td").eq(3).html();

    window.location.href = "v_pagos_proyect.php?created_at=" + customerId;
});

$("#table tbody tr").dblclick(function () {
    var customerId = $(this).find("td").eq(1).html();

    window.open("v_poliza.php?id_poliza=" + customerId, '_blank');
});

$("#tableRenovAct1 tbody tr").dblclick(function () {
    var customerId = $(this).find("td").eq(1).html();

    window.open("../v_poliza.php?id_poliza=" + customerId, '_blank');
});

$("#tableRenovF tbody tr").dblclick(function () {
    var customerId = $(this).find("td").eq(1).html();

    window.open("v_poliza.php?id_poliza=" + customerId, '_blank');
});

$("#tableAnulada tbody tr").dblclick(function () {
    var customerId = $(this).find("td").eq(1).html();

    window.open("v_poliza.php?id_poliza=" + customerId, '_blank');
});

$("#UtilGrafPol tbody tr").dblclick(function () {
    var customerId = $(this).find("td").eq(1).html();

    window.open("../../v_poliza.php?id_poliza=" + customerId, '_blank');
});

$("#tableBusq tbody tr").dblclick(function () {
    var customerId = $(this).find("td").eq(1).html();

    window.open("v_poliza.php?id_poliza=" + customerId, '_blank');
});


$("#tableP tbody tr").dblclick(function () {
    var customerId = $(this).find("td").eq(1).html();

    window.open("v_poliza.php?id_poliza=" + customerId, '_blank');
});

$("#tableVRepCom tbody tr").dblclick(function () {
    var customerId = $(this).find("td").eq(0).html();

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
        var customerId = $(this).find("td").eq(20).html();

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
    var customerId = $(this).find("td").eq(1).html();

    window.location.href = "b_reportes1.php?anio=&mes=&cia=" + customerId;
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
        var customerId = $(this).find("td").eq(7).html();

        if (customerId == null) {
            var customerId = $(this).find("td").eq(6).html();
        }

        window.open("../v_poliza.php?id_poliza=" + customerId, '_blank');
    }
});

$("#tableUser tbody tr").dblclick(function () {
    var customerId = $(this).find("td").eq(0).html();

    window.open("v_usuario.php?id_usuario=" + customerId, '_blank');
});

$("#tableModalPago tbody tr").dblclick(function () {
    var customerId = $(this).find("td").eq(0).html();

    window.open("v_reporte_com.php?id_rep_com=" + customerId, '_blank');
});

num_caracteres_permitidos = 300;

function valida_longitud() {
    num_caracteres = $('#comentarioS').val();
    $('#caracteres').val('Caracteres restantes: ' + (num_caracteres_permitidos - num_caracteres.length));
}

$('#btnSeguimiento').click(function () {
    if ($('#comentarioS').val() == '' && $('#comentarioSs').val() == 0) {
        alertify.error("Debe escribir un comentario o seleccionar de la lista primero");
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
    if ($('#comentarioS').val() == '' && $('#comentarioSs').val() == 0) {
        alertify.error("Debe escribir un comentario o seleccionar de la lista primero");
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
    if ($("#n_transf").val().length < 2) {
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

$('#btnCargaPagoP').click(function () {
    if ($("#n_transf").val().length < 2) {
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
        url: "../../procesos/agregarCargaPagoP.php",
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
                console.log(r)
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

$('#btnAgregarcon').click(function() {
    if ($("#fc_new").val().length < 1) {
        alertify.error("La Fecha de la Conciliación es Obligatoria");
        return false;
    }
    if ($("#mc_new").val().length < 1) {
        alertify.error("El Monto de la Conciliación es Obligatorio");
        return false;
    }

    datos = $('#frmnuevoC').serialize();

    $.ajax({
        type: "POST",
        data: datos,
        url: "../procesos/agregarConciliacion.php",
        success: function(r) {
            if (r == 1) {
                $('#frmnuevoC')[0].reset();
                alertify.success("Agregada con Exito!!");

                $('#agregarconciliacion').modal('hide');

                setTimeout(() => {
                    location.reload();
                }, 1000);

            } else {
                alertify.error("Fallo al agregar!");
            }
        }
    });
});

function eliminarPoliza(idpoliza,idusuario,num_poliza,cliente) {
    alertify.confirm('Eliminar una Póliza', '¿Seguro de eliminar esta Póliza?', function () {
        $.ajax({
            type: "POST",
            data: "idpoliza=" + idpoliza,
            url: "../procesos/eliminarPoliza.php?idusuario="+idusuario+"&num_poliza="+num_poliza+"&cliente="+cliente,
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

function eliminarComision(id_comision,idusuario,num_poliza,f_hasta_rep,cia) {
    alertify.confirm('Eliminar Comisión Seleccionada', '¿Seguro de eliminar esta Comisión?', function () {
        $('.alertify .ajs-header').css('background-color', 'green');
        $.ajax({
            type: "POST",
            data: "id_comision=" + id_comision,
            url: "../procesos/eliminarComision.php?idusuario="+idusuario+"&num_poliza="+num_poliza+"&f_hasta_rep="+f_hasta_rep+"&cia="+cia,
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

function eliminarConciliacion(id_conciliacion) {
    alertify.confirm('Eliminar Conciliación Seleccionada', '¿Seguro de eliminar esta Conciliación?', function () {
        $('.alertify .ajs-header').css('background-color', 'green');
        $.ajax({
            type: "POST",
            data: "id_conciliacion=" + id_conciliacion,
            url: "../procesos/eliminarConciliacion.php",
            success: function (r) {
                if (r == 1) {
                    alertify.alert('Eliminada con exito !', 'La Conciliación fue eliminada con exito', function () {
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

function eliminarRamo(id_cod_ramo) {
    alertify.confirm('Eliminar un Ramo', '¿Seguro de eliminar este Ramo?', function () {
        $.ajax({
            type: "POST",
            data: "id_cod_ramo=" + id_cod_ramo,
            url: "../procesos/eliminarRamo.php",
            success: function (r) {
                if (r == 1) {
                    alertify.alert('Eliminado con exito !', 'El Ramo fue eliminado con exito', function () {
                        alertify.success('OK');
                        window.location.replace("b_ramo.php");
                    });
                } else {
                    alertify.error("No se pudo eliminar, puede tener pólizas asociadas");
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

function crearConciliacion(id_rep_com) {
    $('#id_reporte').val(id_rep_com)
    $('#agregarconciliacion').modal('show');
}

function crearPago(id_gc_h_r, monto_h) {
    $('#id_gc_h_r').val(id_gc_h_r)
    $('#monto_p').val(monto_h)
    $('#cargaPago').modal('show');
}

function crearPagoP(id_gc_h_p, monto_h) {
    $('#id_gc_h_p').val(id_gc_h_p)
    $('#monto_p').val(monto_h)
    $('#cargaPagoP').modal('show');
}

function noRenovar(idpoliza, f_hasta) {
    $('#id_polizaNR').val(idpoliza)
    $('#f_hastaNR').val(f_hasta)
    $('#noRenov').modal('show');
}

function mayus(e) {
    e.value = e.value.toUpperCase();
}