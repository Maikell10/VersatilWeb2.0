<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}
require_once '../../Controller/Poliza.php';

$idcia = $_GET['cia'];
$cant_poliza = $_GET['cant_poliza'];
$id_rep = $_GET['id_rep'];
$f_hasta = date("Y-m-d", strtotime($_GET['f_hasta']));
$f_pagoGc = date("Y-m-d", strtotime($_GET['f_pagoGc']));

$cia = $obj->get_element_by_id('dcia', 'idcia', $idcia);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . '\..\layout\header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . '\..\layout\navigation.php'; ?>
    <br><br><br><br><br><br>

    <div>
        <div class="card">


            <div class="card-header p-5 animated bounceInDown">
                <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                    <- Regresar</a> <br><br>
                        <div class="ml-5 mr-5 text-center">
                            <h1 class="font-weight-bold">
                                <i class="fas fa-info-circle" aria-hidden></i>&nbsp;Compañía: <?= ($cia[0]['nomcia']); ?>
                            </h1>
                        </div>
                        <br>

                        <div class="col-md-11 mx-auto">

                            <form action="comision.php" class="form-horizontal" method="POST" autocomplete="off" id="frmnuevo">
                                <div class="table-responsive-xl">
                                    <table class="table table-hover" width="100%" id="iddatatable">
                                        <thead class="blue-gradient text-white">
                                            <tr>
                                                <th colspan="2">Fecha Creación GC</th>
                                                <th colspan="2">Fecha Hasta Reporte</th>
                                                <th colspan="2">Total Prima Cobrada</th>
                                                <th>Total Comision Cobrada</th>
                                                <th hidden>id reporte</th>
                                                <th hidden>cia</th>
                                                <th hidden>cant_poliza</th>
                                                <th hidden>prima_comt</th>
                                                <th hidden>comt</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="2">
                                                    <div class="input-group md-form my-n1 grey lighten-2">
                                                        <input type="text" class="form-control" id="f_pagoGc" name="f_pagoGc" readonly value="<?= $_GET['f_pagoGc']; ?>">
                                                    </div>
                                                </td>
                                                <td colspan="2">
                                                    <div class="input-group md-form my-n1 grey lighten-2">
                                                        <input type="text" class="form-control" id="f_hasta" name="f_hasta" readonly value="<?= $_GET['f_hasta']; ?>">
                                                    </div>
                                                </td>
                                                <td colspan="2">
                                                    <div class="input-group md-form my-n1 grey lighten-2">
                                                        <input type="text" class="form-control" id="primat_com" name="primat_com" readonly value="<?= "$ " . number_format($_GET['primat_com'], 2); ?>">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group md-form my-n1 grey lighten-2">
                                                        <input type="text" class="form-control" id="comt" name="comt" readonly value="<?= "$ " . number_format($_GET['comt'], 2); ?>">
                                                    </div>
                                                </td>

                                                <td hidden><input type="text" class="form-control" id="id_rep" name="id_rep" value="<?= $id_rep; ?>"></td>
                                                <td hidden><input type="text" class="form-control" id="cia" name="cia" value="<?= $idcia; ?>"></td>
                                                <td hidden><input type="text" class="form-control" id="cant_poliza" name="cant_poliza" value="<?= $cant_poliza; ?>"></td>

                                                <td hidden><input type="text" class="form-control" id="primat_comt" name="primat_comt" value="<?= $_GET['primat_com']; ?>"></td>
                                                <td hidden><input type="text" class="form-control" id="comtt" name="comtt" value="<?= $_GET['comt']; ?>"></td>
                                            </tr>

                                            <tr class="blue-gradient text-white">
                                                <th>N° de Póliza *</th>
                                                <th>Asegurado</th>
                                                <th>Fecha de Pago de la Prima *</th>
                                                <th style="background-color: #E54848;">Prima Sujeta a Comisión *</th>
                                                <th>% Comisión *</th>
                                                <th>Comisión</th>
                                                <th>Asesor - Ejecutivo</th>
                                            </tr>

                                            <?php
                                            if ($_GET['exx'] == 1) {
                                                $repEx = $obj->get_comision($id_rep);
                                                $totalprimaant = 0;
                                                $totalcomant = 0;
                                                for ($i = 0; $i < sizeof($repEx); $i++) {
                                                    $totalprimaant = $totalprimaant + $repEx[$i]['prima_com'];
                                                    $totalcomant = $totalcomant + $repEx[$i]['comision'];

                                                    $nombre = $repEx[$i]['nombre_t'] . " " . $repEx[$i]['apellido_t'];
                                                    $newFPP = date("d-m-Y", strtotime($repEx[$i]['f_pago_prima']));
                                            ?>
                                                    <tr>
                                                        <td>
                                                            <div class="input-group md-form my-n1 grey lighten-2">
                                                                <input type="text" class="form-control" value="<?= $repEx[$i]['num_poliza']; ?>" readonly>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group md-form my-n1 grey lighten-2">
                                                                <input type="text" class="form-control" value="<?= $nombre; ?>" readonly>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group md-form my-n1 grey lighten-2">
                                                                <input type="text" class="form-control" value="<?= $newFPP; ?>" readonly>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group md-form my-n1 grey lighten-2">
                                                                <input type="text" class="form-control" value="<?= "$ " . number_format($repEx[$i]['prima_com'], 2); ?>" readonly style="text-align:right">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group md-form my-n1 grey lighten-2">
                                                                <input type="text" class="form-control" value="<?= number_format((($repEx[$i]['comision'] * 100) / $repEx[$i]['prima_com']), 2) . "%"; ?>" readonly style="text-align:center">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group md-form my-n1 grey lighten-2">
                                                                <input type="text" class="form-control" value="<?= "$ " . number_format($repEx[$i]['comision'], 2); ?>" readonly style="text-align:right">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group md-form my-n1 grey lighten-2">
                                                                <input type="text" class="form-control" value="<?= $repEx[$i]['cod_vend']; ?>" readonly>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php }
                                            }
                                            for ($i = 0; $i < $cant_poliza; $i++) { ?>

                                                <tr style="background-color: white">
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input onblur="<?= 'validarPoliza(this,' . $i . ')'; ?>" type="text" class="form-control <?= 'validarpoliza' . $i; ?>" id="<?= 'n_poliza' . $i; ?>" name="<?= 'n_poliza' . $i; ?>" required data-toggle="tooltip" data-placement="bottom" title="Sólo introducir números">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" readonly id="<?= 'nom_titu' . $i; ?>" name="<?= 'nom_titu' . $i; ?>">
                                                        </div>
                                                    </td>

                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control datepicker" id="<?= 'f_pago' . $i; ?>" name="<?= 'f_pago' . $i; ?>" required data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio" autocomplete="off">
                                                        </div>
                                                    </td>

                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input style="text-align: center" type="number" step="0.01" class="form-control" id="<?= 'prima' . $i; ?>" name="<?= 'prima' . $i; ?>" required data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio [Sólo introducir números y punto (.) como separador decimal]">
                                                        </div>
                                                    </td>

                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input style="text-align: center" onblur="<?= 'calcularP' . $i . '(this)'; ?> ;<?= 'calcularRest1(this)'; ?>" type="number" step="0.01" class="form-control" id="<?= 'comisionPor' . $i; ?>" name="<?= 'comisionPor' . $i; ?>" required data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio [Sólo introducir números y punto (.) como separador decimal]" autocomplete="off" value="<?= $cia[0]['per_com']; ?>">
                                                        </div>
                                                    </td>

                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control required" id="<?= 'comision' . $i; ?>" name="<?= 'comision' . $i; ?>" readonly>
                                                        </div>
                                                    </td>


                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" readonly id="<?= 'asesor' . $i; ?>" name="<?= 'asesor' . $i; ?>">
                                                        </div>
                                                    </td>

                                                    <td hidden><input type="text" class="form-control" id="<?= 'codasesor' . $i; ?>" name="<?= 'codasesor' . $i; ?>"></td>

                                                    <td hidden><input type="text" class="form-control" id="<?= 'id_poliza' . $i; ?>" name="<?= 'id_poliza' . $i; ?>"></td>

                                                    <td hidden><input type="text" class="form-control" id="<?= 'num'; ?>" name="<?= 'num'; ?>"></td>

                                                </tr>
                                                <tr>
                                                    <td colspan="7" style="padding:0px;background-color: white;text-align: center"><a style="width: 40%" href="" class="btn btn-rounded btn purple-gradient" data-toggle="modal" data-target="#precargapoliza" id="<?= 'btnPre' . $i; ?>" name="<?= 'btnPre' . $i; ?>" onclick="<?= 'botonPreCarga(' . $i . ')'; ?>" hidden>Precargar Póliza</a></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-md-12 text-center">
                                    <input type="button" onclick="deleterow()" class="btn young-passion-gradient text-white font-weight-bold btn-block" value="Eliminar Última Fila" id="borrar" />
                                </div>
                                <br />

                                <center>
                                    <button type="submit" id="btnForm" class="btn blue-gradient btn-lg btn-rounded">Previsualizar</button>
                                </center>

                            </form>
                        </div>
                        <br>
            </div>

        </div>





        <?php require_once dirname(__DIR__) . '\..\layout\footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . '\..\layout\footer.php'; ?>

        <!-- Modal -->
        <div class="modal fade" id="precargapoliza" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Agrega nueva Pre-Póliza</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="frmnuevoP">
                            <table class="table table-hover table-striped table-bordered" id="iddatatable1">
                                <thead class="blue-gradient text-white">
                                    <tr>
                                        <th>Nº de Póliza</th>
                                        <th>Nombre Asegurado</th>
                                        <th hidden>Cía</th>
                                    </tr>
                                </thead>
                                <tr style="background-color:white">
                                    <td>
                                        <div class="input-group md-form my-n1 grey lighten-2">
                                            <input type="text" class="form-control" id="num_poliza" name="num_poliza" readonly>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group md-form my-n1">
                                            <input type="text" class="form-control" id="asegurado" name="asegurado" required onkeyup="mayus(this);">
                                        </div>
                                    </td>
                                    <td hidden><input type="text" class="form-control" id="idcia" name="idcia" readonly value="<?= $idcia; ?>"></td>
                                </tr>
                            </table>
                            <button type="button" id="btnAgregarnuevo" class="btn blue-gradient float-right">Agregar nuevo</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>



        <!-- Modal Pre-CArga Poliza Existente-->
        <div class="modal fade" id="precargapolizaE" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Agrega nueva Pre-Póliza</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="frmnuevoPE">
                            <div class="table-responsive">
                                <table class="table" id="iddatatable1">
                                    <thead class="blue-gradient text-white">
                                        <tr>
                                            <th>Nº de Póliza</th>
                                            <th>Asegurado</th>
                                            <th>F Desde Seg</th>
                                            <th>F Hasta Seg</th>
                                            <th hidden>Cía</th>
                                            <th hidden>id poliza</th>
                                        </tr>
                                    </thead>
                                    <tr style="background-color:white">
                                        <td>
                                            <div class="input-group md-form my-n1">
                                                <input type="text" class="form-control" id="num_polizaE" name="num_polizaE">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group md-form my-n1 grey lighten-2">
                                                <input type="text" class="form-control" id="aseguradoE" name="aseguradoE" readonly>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group md-form my-n1">
                                                <input type="text" class="form-control datepicker" id="f_desde_se" name="f_desde_se" required>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group md-form my-n1">
                                                <input type="text" class="form-control datepicker" id="f_hasta_se" name="f_hasta_se" required>
                                            </div>
                                        </td>
                                        <td hidden><input type="text" class="form-control" id="idciaE" name="idciaE" readonly value="<?= $idcia; ?>"></td>
                                        <td hidden><input type="text" class="form-control" id="idpolizaE" name="idpolizaE"></td>
                                    </tr>
                                </table>
                            </div>
                            <button type="button" id="btnAgregarnuevoE" class="btn blue-gradient float-right">Pre-Cargar Póliza</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Polizas Existentes-->
        <div class="modal fade" id="polizaexistente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLabel">Seleccione la Póliza</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="table-responsive-xl">
                            <table class="table table-hover table-striped table-bordered" id="tablaPEC">
                                <thead class="blue-gradient text-white">
                                    <tr>
                                        <th>Nº de Póliza</th>
                                        <th>F Desde Seg</th>
                                        <th>F Hasta Seg</th>
                                        <th>Nombre Asegurado</th>
                                        <th>Cía</th>
                                        <th>Prima Suscrita</th>
                                        <th>Prima Cobrada</th>
                                        <th>Prima Pendiente</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="../../assets/view/b_poliza.js"></script>

        <script>
            $(document).ready(function() {

                //Abrir picker en un modal
                var $input = $('.datepicker').pickadate({
                    // Strings and translations
                    monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Augosto', 'Septiembre', 'Octubre',
                        'Noviembre', 'Diciembre'
                    ],
                    monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dec'],
                    weekdaysFull: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sabado'],
                    weekdaysShort: ['Dom', 'Lun', 'Mart', 'Mierc', 'Jue', 'Vie', 'Sab'],
                    showMonthsShort: undefined,
                    showWeekdaysFull: undefined,

                    // Buttons
                    today: 'Hoy',
                    clear: 'Borrar',
                    close: 'Cerrar',

                    // Accessibility labels
                    labelMonthNext: 'Próximo Mes',
                    labelMonthPrev: 'Mes Anterior',
                    labelMonthSelect: 'Seleccione un Mes',
                    labelYearSelect: 'Seleccione un Año',

                    // Formats
                    dateFormat: 'dd-mm-yyyy',
                    format: 'dd-mm-yyyy',
                    formatSubmit: 'yyyy-mm-dd',
                });
                var picker = $input.pickadate('picker');

                for (let index = 0; index < $('#cant_poliza').val(); index++) {
                    $('#f_pago' + index).val($('#f_hasta').val());
                    $('#f_pago' + index).pickadate('picker').set('select', $('#f_hasta').val());
                }


                $('#btnAgregarnuevo').click(function() {
                    if ($("#asegurado").val().length < 1) {
                        alertify.error("El Nombre del Cliente es Obligatorio");
                        return false;
                    }
                    datos = $('#frmnuevoP').serialize();
                    var num_poliza = $('#num_poliza').val();
                    var asegurado = $('#asegurado').val();
                    console.log($("#num").val());

                    $.ajax({
                        type: "POST",
                        data: datos,
                        url: "../../procesos/agregarPrePoliza.php",
                        success: function(r) {
                            if (r == 1) {
                                $('#frmnuevoP')[0].reset();
                                alertify.success("Agregada con Exito!!");

                                if (($("#num").val()) == 0) {
                                    $("#n_poliza0").val(datos['cod_poliza']);
                                    $("#n_poliza0").css('background-color', 'green');
                                    $("#n_poliza0").css('color', 'white');
                                    $('#n_poliza0').attr('data-original-title', 'Póliza Existente');
                                    $('#btnForm').removeAttr('disabled');
                                    $('#n_poliza0').val(num_poliza);
                                    $('#nom_titu0').val(asegurado);
                                    $('#asesor0').val('PENDIENTE');
                                    $('#codasesor0').val('AP-1');
                                    $('#btnPre0').attr('hidden', true);
                                    $('#id_poliza0').val('0');
                                }
                                if (($("#num").val()) == 1) {
                                    $("#n_poliza1").val(datos['cod_poliza']);
                                    $("#n_poliza1").css('background-color', 'green');
                                    $("#n_poliza1").css('color', 'white');
                                    $('#n_poliza1').attr('data-original-title', 'Póliza Existente');
                                    $('#btnForm').removeAttr('disabled');
                                    $('#n_poliza1').val(num_poliza);
                                    $('#nom_titu1').val(asegurado);
                                    $('#asesor1').val('PENDIENTE');
                                    $('#codasesor1').val('AP-1');
                                    $('#btnPre1').attr('hidden', true);
                                    $('#id_poliza1').val('0');
                                }
                                if (($("#num").val()) == 2) {
                                    $("#n_poliza2").val(datos['cod_poliza']);
                                    $("#n_poliza2").css('background-color', 'green');
                                    $("#n_poliza2").css('color', 'white');
                                    $('#n_poliza2').attr('data-original-title', 'Póliza Existente');
                                    $('#btnForm').removeAttr('disabled');
                                    $('#n_poliza2').val(num_poliza);
                                    $('#nom_titu2').val(asegurado);
                                    $('#asesor2').val('PENDIENTE');
                                    $('#codasesor2').val('AP-1');
                                    $('#btnPre2').attr('hidden', true);
                                    $('#id_poliza2').val('0');
                                }
                                if (($("#num").val()) == 3) {
                                    $("#n_poliza3").val(datos['cod_poliza']);
                                    $("#n_poliza3").css('background-color', 'green');
                                    $("#n_poliza3").css('color', 'white');
                                    $('#n_poliza3').attr('data-original-title', 'Póliza Existente');
                                    $('#btnForm').removeAttr('disabled');
                                    $('#n_poliza3').val(num_poliza);
                                    $('#nom_titu3').val(asegurado);
                                    $('#asesor3').val('PENDIENTE');
                                    $('#codasesor3').val('AP-1');
                                    $('#btnPre3').attr('hidden', true);
                                    $('#id_poliza3').val('0');
                                }
                                if (($("#num").val()) == 4) {
                                    $("#n_poliza4").val(datos['cod_poliza']);
                                    $("#n_poliza4").css('background-color', 'green');
                                    $("#n_poliza4").css('color', 'white');
                                    $('#n_poliza4').attr('data-original-title', 'Póliza Existente');
                                    $('#btnForm').removeAttr('disabled');
                                    $('#n_poliza4').val(num_poliza);
                                    $('#nom_titu4').val(asegurado);
                                    $('#asesor4').val('PENDIENTE');
                                    $('#codasesor4').val('AP-1');
                                    $('#btnPre4').attr('hidden', true);
                                    $('#id_poliza4').val('0');
                                }
                                if (($("#num").val()) == 5) {
                                    $("#n_poliza5").val(datos['cod_poliza']);
                                    $("#n_poliza5").css('background-color', 'green');
                                    $("#n_poliza5").css('color', 'white');
                                    $('#n_poliza5').attr('data-original-title', 'Póliza Existente');
                                    $('#btnForm').removeAttr('disabled');
                                    $('#n_poliza5').val(num_poliza);
                                    $('#nom_titu5').val(asegurado);
                                    $('#asesor5').val('PENDIENTE');
                                    $('#codasesor5').val('AP-1');
                                    $('#btnPre5').attr('hidden', true);
                                    $('#id_poliza5').val('0');
                                }
                                if (($("#num").val()) == 6) {
                                    $("#n_poliza6").val(datos['cod_poliza']);
                                    $("#n_poliza6").css('background-color', 'green');
                                    $("#n_poliza6").css('color', 'white');
                                    $('#n_poliza6').attr('data-original-title', 'Póliza Existente');
                                    $('#btnForm').removeAttr('disabled');
                                    $('#n_poliza6').val(num_poliza);
                                    $('#nom_titu6').val(asegurado);
                                    $('#asesor6').val('PENDIENTE');
                                    $('#codasesor6').val('AP-1');
                                    $('#btnPre6').attr('hidden', true);
                                    $('#id_poliza6').val('0');
                                }
                                if (($("#num").val()) == 7) {
                                    $("#n_poliza7").val(datos['cod_poliza']);
                                    $("#n_poliza7").css('background-color', 'green');
                                    $("#n_poliza7").css('color', 'white');
                                    $('#n_poliza7').attr('data-original-title', 'Póliza Existente');
                                    $('#btnForm').removeAttr('disabled');
                                    $('#n_poliza7').val(num_poliza);
                                    $('#nom_titu7').val(asegurado);
                                    $('#asesor7').val('PENDIENTE');
                                    $('#codasesor7').val('AP-1');
                                    $('#btnPre7').attr('hidden', true);
                                    $('#id_poliza7').val('0');
                                }
                                if (($("#num").val()) == 8) {
                                    $("#n_poliza8").val(datos['cod_poliza']);
                                    $("#n_poliza8").css('background-color', 'green');
                                    $("#n_poliza8").css('color', 'white');
                                    $('#n_poliza8').attr('data-original-title', 'Póliza Existente');
                                    $('#btnForm').removeAttr('disabled');
                                    $('#n_poliza8').val(num_poliza);
                                    $('#nom_titu8').val(asegurado);
                                    $('#asesor8').val('PENDIENTE');
                                    $('#codasesor8').val('AP-1');
                                    $('#btnPre8').attr('hidden', true);
                                    $('#id_poliza8').val('0');
                                }
                                if (($("#num").val()) == 9) {
                                    $("#n_poliza9").val(datos['cod_poliza']);
                                    $("#n_poliza9").css('background-color', 'green');
                                    $("#n_poliza9").css('color', 'white');
                                    $('#n_poliza9').attr('data-original-title', 'Póliza Existente');
                                    $('#btnForm').removeAttr('disabled');
                                    $('#n_poliza9').val(num_poliza);
                                    $('#nom_titu9').val(asegurado);
                                    $('#asesor9').val('PENDIENTE');
                                    $('#codasesor9').val('AP-1');
                                    $('#btnPre9').attr('hidden', true);
                                    $('#id_poliza9').val('0');
                                }

                                $('#precargapoliza').modal('hide');

                            } else {
                                alertify.error("Fallo al agregar!");

                            }
                        }
                    });
                });

                $('#btnAgregarnuevoE').click(function() {
                    datos = $('#frmnuevoPE').serialize();
                    var num_poliza = $('#num_polizaE').val();

                    $.ajax({
                        type: "POST",
                        data: datos,
                        url: "../../procesos/agregarPrePolizaE.php",
                        success: function(r) {
                            if (r == 1) {
                                $('#frmnuevoP')[0].reset();

                                alertify.set('notifier', 'position', 'top-center');
                                var msg = alertify.success('Agregada con Exito!! Vuelva a hacer click en Nº de Póliza y luego seleccione la Póliza', 'custom', 2, function() {
                                    console.log('dismissed');
                                });
                                msg.delay(8);

                                $('#precargapolizaE').modal('hide');

                            } else {
                                alertify.error("Fallo al agregar!");
                            }
                        }
                    });
                });

            });

            onload = function() {
                for (let index = 0; index < $('#cant_poliza').val(); index++) {
                    document.querySelectorAll('.validarpoliza' + index)[0].onkeypress = function(index) {
                        if (isNaN(this.value + String.fromCharCode(index.charCode)))
                            return false;
                    };
                }
            }

            async function validarPoliza(num_poliza, id) {
                if ($("#n_poliza" + id).val().length < 3) {
                    alertify.error("Debe escribir en la casilla más de 3 números para realizar la búsqueda");
                    return false;
                }
                await $.ajax({
                    type: "POST",
                    data: "num_poliza=" + num_poliza.value,
                    url: "../../procesos/validarpoliza_e.php",
                    success: async function(r) {
                        datos = jQuery.parseJSON(r);
                        if (datos == null) {
                            $("#n_poliza" + id).css('background-color', 'red');
                            $("#n_poliza" + id).css('color', 'white');

                            $('#n_poliza' + id).attr('data-original-title', 'No Existe la Póliza para la Compañía Seleccionada, Debe Crearla y luego volver a introducir su Nº');
                            $('#btnForm').attr('disabled', true);

                            $('#btnPre' + id).removeAttr('hidden');

                            $('#nom_titu' + id).val('');
                            $('#asesor' + id).val('');
                        } else {
                            $("#tablaPEC > tbody").empty();

                            for (let index = 0; index < datos.length; index++) {
                                var id_poliza = datos[index]['id_poliza'];
                                await $.ajax({
                                    type: "POST",
                                    data: "id_poliza=" + id_poliza,
                                    url: "../../procesos/validar_comisiones_poliza.php",
                                    success: function(r) {
                                        datos1 = jQuery.parseJSON(r);

                                        var d = new Date();
                                        d.setMonth((d.getMonth() + 1));
                                        var strDate = d.getFullYear() + "-" + d.getMonth() + "-" + d.getDate();

                                        var f = new Date(datos[index]['f_hastapoliza']);
                                        f.setDate((f.getDate() + 1));
                                        f.setMonth((f.getMonth() + 1));
                                        var f_hasta = f.getDate() + "-" + f.getMonth() + "-" + f.getFullYear();

                                        var f = new Date(datos[index]['f_desdepoliza']);
                                        f.setDate((f.getDate() + 1));
                                        f.setMonth((f.getMonth() + 1));
                                        var f_desde = f.getDate() + "-" + f.getMonth() + "-" + f.getFullYear();

                                        var ppendiente = datos[index]['prima'] - datos1[0]['SUM(prima_com)'];
                                        if (Math.sign(ppendiente) == -1) {
                                            var style = 'color:red'
                                            //console.log(Math.sign(ppendiente));
                                        } else {
                                            var style = 'color:black'
                                        }

                                        if ((new Date(strDate).getTime() <= new Date(datos[index]['f_hastapoliza']).getTime())) {
                                            var nombre_t = datos[index]['nombre_t'];
                                            var htmlTags = '<tr ondblclick="btnPoliza(' + datos[index]['id_poliza'] + ',' + id + ')" style="cursor:pointer">' +
                                                '<td style="color:green">' + datos[index]['cod_poliza'] + '</td>' +
                                                '<td nowrap>' + f_desde + '</td>' +
                                                '<td nowrap>' + f_hasta + '</td>' +
                                                '<td>' + decodeURIComponent(escape(datos[index]['nombre_t'])) + " " + decodeURIComponent(escape(datos[index]['apellido_t'])) + '</td>' +
                                                '<td nowrap>' + datos[index]['nomcia'] + '</td>' +
                                                '<td nowrap>' + datos[index]['prima'] + '</td>' +
                                                '<td nowrap>' + Number(datos1[0]['SUM(prima_com)']).toFixed(2) + '</td>' +
                                                '<td nowrap style=' + style + '>' + (ppendiente).toFixed(2) + '</td>' +
                                                '<td nowrap><a onclick="btnPoliza(' + datos[index]['id_poliza'] + ',' + id + ')" style="color:black" data-toggle="tooltip" data-placement="top" title="Añadir Póliza" class="btn dusty-grass-gradient btn-sm"><i class="fas fa-check-square" aria-hidden="true"></i></a><a onclick="btnPrePolizaE(' + datos[index]['id_poliza'] + ',' + datos[index]['cod_poliza'] + ')" style="color:white" data-toggle="tooltip" data-placement="top" title="Pre-Cargar Póliza" class="btn aqua-gradient btn-sm"><i class="fas fa-plus-square" aria-hidden="true"></i></a><a href="../v_poliza.php?id_poliza=' + datos[index]['id_poliza'] + '&pagos=1" target="_blank" style="color:white" data-toggle="tooltip" data-placement="top" title="Ver Póliza" class="btn blue-gradient btn-sm" ><i class="fas fa-eye"></i></i></a></td>' +
                                                '</tr>';
                                        } else {
                                            var htmlTags = '<tr ondblclick="btnPoliza(' + datos[index]['id_poliza'] + ',' + id + ')" style="cursor:pointer">' +
                                                '<td style="color:red">' + datos[index]['cod_poliza'] + '</td>' +
                                                '<td nowrap>' + f_desde + '</td>' +
                                                '<td nowrap>' + f_hasta + '</td>' +
                                                '<td>' + decodeURIComponent(escape(datos[index]['nombre_t'])) + " " + decodeURIComponent(escape(datos[index]['apellido_t'])) + '</td>' +
                                                '<td nowrap>' + datos[index]['nomcia'] + '</td>' +
                                                '<td nowrap>' + datos[index]['prima'] + '</td>' +
                                                '<td nowrap>' + Number(datos1[0]['SUM(prima_com)']).toFixed(2) + '</td>' +
                                                '<td nowrap style=' + style + '>' + (ppendiente).toFixed(2) + '</td>' +
                                                '<td nowrap><a onclick="btnPoliza(' + datos[index]['id_poliza'] + ',' + id + ')" style="color:black" data-toggle="tooltip" data-placement="top" title="Añadir Póliza" class="btn dusty-grass-gradient btn-sm"><i class="fas fa-check-square" aria-hidden="true"></i></a><a onclick="btnPrePolizaE(' + datos[index]['id_poliza'] + ',' + datos[index]['cod_poliza'] + ')" style="color:wwhite" data-toggle="tooltip" data-placement="top" title="Pre-Cargar Póliza" class="btn aqua-gradient btn-sm"><i class="fas fa-plus-square" aria-hidden="true"></i></a><a href="../v_poliza.php?id_poliza=' + datos[index]['id_poliza'] + '&pagos=1" target="_blank" style="color:white" data-toggle="tooltip" data-placement="top" title="Ver Póliza" class="btn blue-gradient btn-sm" ><i class="fas fa-eye"></i></i></a></td>' +
                                                '</tr>';
                                        }
                                        $('#tablaPEC > tbody').append(htmlTags);
                                        id_poliza = 0;
                                    }
                                });
                            }
                            $('#polizaexistente').modal({
                                backdrop: 'static',
                                keyboard: false
                            });
                            $('#polizaexistente').modal('show');
                        }
                    }
                });
            }

            async function btnPoliza(id_poliza, id) {
                await $.ajax({
                    type: "POST",
                    data: "id_poliza=" + id_poliza,
                    url: "../../procesos/validarpoliza_id.php",
                    success: function(r) {
                        datos = jQuery.parseJSON(r);
                        if (datos[0]['id_poliza'] == null) {
                            alert('seleccione una póliza');
                        } else {
                            $("#n_poliza" + id).css('background-color', 'green');
                            $("#n_poliza" + id).css('color', 'white');
                            $('#n_poliza' + id).attr('data-original-title', 'Póliza Existente');
                            $('#btnForm').removeAttr('disabled');
                            $('#btnPre' + id).attr('hidden', true);
                            $('#nom_titu' + id).val(datos[0]['nombre_t'] + " " + datos[0]['apellido_t']);
                            $('#asesor' + id).val(datos[0]['nombre']);
                            $('#codasesor' + id).val(datos[0]['codvend']);
                            $('#id_poliza' + id).val(datos[0]['id_poliza']);
                            $('#n_poliza' + id).val(datos[0]['cod_poliza']);
                            $('#polizaexistente').modal('hide');
                        }
                    }
                });
            }

            function botonPreCarga(id) {
                if ($("#n_poliza" + id).val() != '') {
                    $("#num_poliza").val($("#n_poliza" + id).val());
                    $("#num").val(0);
                    $("#asegurado").val('');
                }
            }

            async function btnPrePolizaE(id_poliza, cod_poliza) {
                $('#idpolizaE').val(id_poliza);
                $('#num_polizaE').val(cod_poliza);
                $('#polizaexistente').modal('hide');
                $('#precargapolizaE').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $('#precargapolizaE').modal('show');

                await $.ajax({
                    type: "POST",
                    data: "id_poliza=" + id_poliza,
                    url: "../../procesos/validarpoliza_id.php",
                    success: function(r) {
                        datos = jQuery.parseJSON(r);
                        if (datos[0]['id_poliza'] == null) {
                            console.log('vacio');
                            alert('seleccione una póliza');
                        } else {
                            $('#aseguradoE').val(datos[0]['nombre_t'] + ' ' + datos[0]['apellido_t']);
                            var f = new Date(datos[0]['f_desdepoliza']);
                            f.setDate((f.getDate() + 1));
                            f.setMonth((f.getMonth() + 1));
                            f.setFullYear((f.getFullYear() + 1));
                            if (10 > f.getMonth() > 0) {
                                var mes = '0' + f.getMonth().toString()
                            } else {
                                var mes = f.getMonth()
                            }
                            if (10 > f.getDate() > 0) {
                                var dia = '0' + (f.getDate()).toString()
                            } else {
                                var dia = f.getDate()
                            }
                            var f_desde = dia + "-" + mes + "-" + f.getFullYear();

                            var f = new Date(datos[0]['f_hastapoliza']);
                            f.setDate((f.getDate() + 1));
                            f.setMonth((f.getMonth() + 1));
                            f.setFullYear((f.getFullYear() + 1));
                            if (10 > f.getMonth() > 0) {
                                var mes = '0' + f.getMonth().toString()
                            } else {
                                var mes = f.getMonth()
                            }
                            if (10 > f.getDate() > 0) {
                                var dia = '0' + (f.getDate()).toString()
                            } else {
                                var dia = f.getDate()
                            }
                            var f_hasta = dia + "-" + mes + "-" + f.getFullYear();
                            $('#f_desde_se').val(f_desde);
                            $('#f_desde_se').pickadate('picker').set('select', f_desde);
                            $('#f_hasta_se').val(f_hasta);
                            $('#f_hasta_se').pickadate('picker').set('select', f_hasta);
                        }
                    }
                });
            }

            function calcularRest1(comision) {

                var comision0 = $("#comision0").val();
                var comision1 = $("#comision1").val();
                var comision2 = $("#comision2").val();
                var comision3 = $("#comision3").val();
                var comision4 = $("#comision4").val();
                var comision5 = $("#comision5").val();
                var comision6 = $("#comision6").val();
                var comision7 = $("#comision7").val();
                var comision8 = $("#comision8").val();
                var comision9 = $("#comision9").val();

                if (($("#comision0").val() == '')) {
                    var comision0 = 0;
                }
                if (($("#comision1").val() == '') || ($("#comision1").val() == null)) {
                    var comision1 = 0;
                }
                if (($("#comision2").val() == '') || ($("#comision2").val() == null)) {
                    var comision2 = 0;
                }
                if (($("#comision3").val() == '') || ($("#comision3").val() == null)) {
                    var comision3 = 0;
                }
                if (($("#comision4").val() == '') || ($("#comision4").val() == null)) {
                    var comision4 = 0;
                }
                if (($("#comision5").val() == '') || ($("#comision5").val() == null)) {
                    var comision5 = 0;
                }
                if (($("#comision6").val() == '') || ($("#comision6").val() == null)) {
                    var comision6 = 0;
                }
                if (($("#comision7").val() == '') || ($("#comision7").val() == null)) {
                    var comision7 = 0;
                }
                if (($("#comision8").val() == '') || ($("#comision8").val() == null)) {
                    var comision8 = 0;
                }
                if (($("#comision9").val() == '') || ($("#comision9").val() == null)) {
                    var comision9 = 0;
                }

                var comRestante = '<?= $comRestante; ?>';

                var Rest = comRestante - comision0 - comision1 - comision2 - comision3 - comision4 - comision5 - comision6 - comision7 - comision8 - comision9;

                $("#Rest1").text('Falta cargar $' + Rest + ' de comisiones');
            }

            function calcularP0(comision) {
                var comision = $("#comision0").val();
                var prima = $("#prima0").val();
                var porcent = $("#comisionPor0").val();

                $("#comision0").val(((prima * porcent) / 100));
            }

            function calcularP1(comision) {
                var comision = $("#comision1").val();
                var prima = $("#prima1").val();
                var porcent = $("#comisionPor1").val();

                $("#comision1").val(((prima * porcent) / 100));
            }

            function calcularP2(comision) {
                var comision = $("#comision2").val();
                var prima = $("#prima2").val();
                var porcent = $("#comisionPor2").val();

                $("#comision2").val(((prima * porcent) / 100));
            }

            function calcularP3(comision) {
                var comision = $("#comision3").val();
                var prima = $("#prima3").val();
                var porcent = $("#comisionPor3").val();

                $("#comision3").val(((prima * porcent) / 100));
            }

            function calcularP4(comision) {
                var comision = $("#comision4").val();
                var prima = $("#prima4").val();
                var porcent = $("#comisionPor4").val();

                $("#comision4").val(((prima * porcent) / 100));
            }

            function calcularP5(comision) {
                var comision = $("#comision5").val();
                var prima = $("#prima5").val();
                var porcent = $("#comisionPor5").val();

                $("#comision5").val(((prima * porcent) / 100));
            }

            function calcularP6(comision) {
                var comision = $("#comision6").val();
                var prima = $("#prima6").val();
                var porcent = $("#comisionPor6").val();

                $("#comision6").val(((prima * porcent) / 100));
            }

            function calcularP7(comision) {
                var comision = $("#comision7").val();
                var prima = $("#prima7").val();
                var porcent = $("#comisionPor7").val();

                $("#comision7").val(((prima * porcent) / 100));
            }

            function calcularP8(comision) {
                var comision = $("#comision8").val();
                var prima = $("#prima8").val();
                var porcent = $("#comisionPor8").val();

                $("#comision8").val(((prima * porcent) / 100));
            }

            function calcularP9(comision) {
                var comision = $("#comision9").val();
                var prima = $("#prima9").val();
                var porcent = $("#comisionPor9").val();

                $("#comision9").val(((prima * porcent) / 100));
            }

            function mayus(e) {
                e.value = e.value.toUpperCase();
            }

            function deleterow() {
                if ($('#cant_poliza').val() == 1) {
                    alertify.error('No se ha Eliminado por que es la fila restante')
                } else {
                    alertify.confirm('Eliminar Fila!', '¿Desea Eliminar la Fila?',
                        function() {
                            var table = document.getElementById("iddatatable");
                            table.deleteRow(-1);
                            table.deleteRow(-1);
                            alertify.success('Fila Eliminada');

                            var cant_poliza = $('#cant_poliza').val();
                            var cant_poliza = cant_poliza - 1;
                            $('#cant_poliza').val(cant_poliza);
                            console.log(cant_poliza);
                        },
                        function() {
                            alertify.error('No se ha Eliminado')
                        }).set('labels', {
                        ok: 'Sí',
                        cancel: 'No'
                    }).set({
                        transition: 'zoom'
                    }).show();
                }
            }
        </script>
</body>

</html>