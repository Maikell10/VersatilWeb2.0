<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}

$pag = 'add/crear_poliza';

require_once '../../Controller/Poliza.php';
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
                            <?php
                            if (isset($_GET['cond'])) {
                            ?>
                                <h1 class="title"><i class="fas fa-check-square text-success" aria-hidden="true"></i>&nbsp;Agregada con Éxito</h1>
                            <?php
                            }
                            ?>
                            <h1 class="font-weight-bold"><i class="fas fa-book" aria-hidden="true"></i> Añadir Nueva Póliza</h1>
                        </div>
                        <br><br><br>

                        <div class="col-md-10 mx-auto">
                            <h2 id="verifP" class="bg-warning text-center" hidden>Espere mientras se verifica la póliza!</h2>

                            <h2 id="existeP" class="bg-success text-white text-center"><strong></strong></h2>
                            <h2 id="no_existeP" class="bg-danger text-white text-center"><strong></strong></h2>
                            <form class="form-horizontal" id="frmnuevo" action="poliza.php" method="post">
                                <div class="table-responsive-xl">
                                    <table class="table" width="100%">
                                        <thead class="blue-gradient text-white">
                                            <tr>
                                                <th>N° de Póliza *</th>
                                                <th>Fecha Desde Seguro *</th>
                                                <th>Fecha Hasta Seguro *</th>
                                                <th>Tipo de Póliza *</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <div class="form-group col-md-12">
                                                <tr style="background-color: white">
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input onblur="cargarRecibo(this);validarPoliza(this)" type="text" class="form-control validanumericos" id="n_poliza" name="n_poliza" required data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio [Sólo introducir números]">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input onchange="cargarFechaDesde(this)" type="text" id="desdeP" name="desdeP" class="form-control datepicker" required data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio" autocomplete="off">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" id="hastaP" name="hastaP" class="form-control datepicker" required data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio" autocomplete="off">
                                                        </div>
                                                    </td>
                                                    <td><select class="mdb-select md-form colorful-select dropdown-primary my-n2" id="tipo_poliza" name="tipo_poliza" required data-toggle="tooltip" data-placement="bottom" title="Seleccione un elemento de la lista">
                                                            <option value="">Seleccione Tipo Póliza</option>
                                                            <option value="1">Primer Año</option>
                                                            <option value="2">Renovación</option>
                                                            <option value="3">Traspaso de Cartera</option>
                                                            <option value="4">Anexos</option>
                                                            <option value="5">Revalorización</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            </div>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="table-responsive-xl">
                                    <table class="table" width="100%">
                                        <thead class="blue-gradient text-white">
                                            <tr>
                                                <th>Ramo *</th>
                                                <th>Compañía *</th>
                                                <th>Tipo de Cuenta</th>
                                            </tr>
                                            <tr style="background-color: white">
                                                <td><select class="mdb-select md-form colorful-select dropdown-primary my-n2" id="ramo" name="ramo" required data-toggle="tooltip" data-placement="bottom" title="Seleccione un elemento de la lista" searchable="Búsqueda rápida">
                                                        <option value="">Seleccione el Ramo</option>
                                                        <?php
                                                        for ($i = 0; $i < sizeof($ramo); $i++) {
                                                        ?>
                                                            <option value="<?= $ramo[$i]["cod_ramo"]; ?>"><?= utf8_encode($ramo[$i]["nramo"]); ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td><select class="mdb-select md-form colorful-select dropdown-primary my-n2" id="cia" name="cia" required data-toggle="tooltip" data-placement="bottom" title="Seleccione un elemento de la lista" searchable="Búsqueda rápida">
                                                        <option value="">Seleccione Compañía</option>
                                                        <?php
                                                        for ($i = 0; $i < sizeof($cia); $i++) {
                                                        ?>
                                                            <option value="<?= $cia[$i]["idcia"]; ?>"><?= ($cia[$i]["nomcia"]); ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td><select class="mdb-select md-form colorful-select dropdown-primary my-n2" id="t_cuenta" name="t_cuenta" required data-toggle="tooltip" data-placement="bottom" title="Seleccione un elemento de la lista">
                                                        <option value="1">Individual</option>
                                                        <option value="2">Colectivo</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>

                                <div class="table-responsive-xl">
                                    <table class="table" width="100%">
                                        <thead class="blue-gradient text-white">
                                            <tr>
                                                <th hidden>Tipo de Cobertura</th>
                                                <th>Moneda</th>
                                                <th>Suma Asegurada</th>
                                                <th style="background-color: #E54848;">Prima Total sin Impuesto *</th>
                                                <th>Periocidad de Pago *</th>
                                                <th>Forma de Pago *</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <div class="form-group col-md-12">
                                                <tr style="background-color: white">
                                                    <td hidden><input type="text" class="form-control" id="t_cobertura" name="t_cobertura" onkeyup="mayus(this);"></td>
                                                    <?php if ($usuario[0]['z_produccion'] == 'PANAMA') { ?>
                                                        <td><select class="mdb-select md-form colorful-select dropdown-primary my-n2" id="currency" name="currency" required>
                                                                <option value="1">$</option>
                                                            </select>
                                                        </td>
                                                    <?php } else { ?>
                                                        <td><select class="mdb-select md-form colorful-select dropdown-primary my-n2" id="currency" name="currency" required>
                                                                <option value="1">$</option>
                                                                <option value="2">BsS</option>
                                                            </select>
                                                        </td>
                                                    <?php } ?>

                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control validanumericos1" id="sumaA" name="sumaA" data-toggle="tooltip" data-placement="bottom" title="Sólo introducir números y punto (.) como separador decimal">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control validanumericos2" id="prima" name="prima" required data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio [Sólo introducir números y punto (.) como separador decimal]">
                                                        </div>
                                                    </td>
                                                    <td><select onchange="cargarCuotas(this)" class="mdb-select md-form colorful-select dropdown-primary my-n2" name="f_pago" id="f_pago" required>
                                                            <option value="">Seleccione Forma de Pago</option>
                                                            <option value="1" selected>CONTADO</option>
                                                            <option value="2">FRACCIONADO</option>
                                                            <option value="3">FINANCIADO</option>
                                                        </select>
                                                    </td>

                                                    <td><select onchange="cargarTarjeta(this)" class="mdb-select md-form colorful-select dropdown-primary my-n2" name="forma_pago" id="forma_pago" required>
                                                            <option value="1">ACH (CARGO EN CUENTA)</option>
                                                            <option value="2">TARJETA DE CREDITO / DEBITO</option>
                                                            <option value="3">PAGO VOLUNTARIO</option>
                                                        </select>
                                                    </td>
                                                </tr>

                                                <tr class="blue-gradient text-white" hidden id="trTarjeta1">
                                                    <th>Nº Tarjeta</th>
                                                    <th>CVV</th>
                                                    <th>Fecha de Vencimiento</th>
                                                    <th>Nombre Tarjetahabiente</th>
                                                    <th>Banco</th>
                                                    <th hidden>alert</th>
                                                    <th hidden>id_tarjeta</th>
                                                </tr>
                                                <tr style="background-color: white" hidden id="trTarjeta2">
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="number" step="0.01" onblur="validarTarjeta(this)" class="form-control" id="n_tarjeta" name="n_tarjeta" data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio [Sólo introducir números]">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control validanumericos4" id="cvv" name="cvv" data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio [Sólo introducir números]">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" id="fechaV" name="fechaV" class="form-control datepicker" data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio" autocomplete="off">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" id="titular_tarjeta" name="titular_tarjeta" onkeyup="mayus(this);" data-toggle="tooltip" data-placement="bottom" title="Nombre del Tarjetahabiente">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" id="bancoT" name="bancoT" onkeyup="mayus(this);" data-toggle="tooltip" data-placement="bottom" title="Nombre del Banco">
                                                        </div>
                                                    </td>

                                                    <td hidden><input type="text" class="form-control" id="alert" name="alert" value="0"></td>
                                                    <td hidden><input type="text" class="form-control" id="id_tarjeta" name="id_tarjeta" value="0"></td>

                                                    <td hidden><input type="text" class="form-control" id="cvv_h" name="cvv_h" value="0"></td>
                                                    <td hidden>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control datepicker" id="fechaV_h" name="fechaV_h" value="0">
                                                        </div>
                                                    </td>
                                                    <td hidden><input type="text" class="form-control" id="titular_tarjeta_h" name="titular_tarjeta_h" value="0"></td>
                                                    <td hidden><input type="text" class="form-control" id="bancoT_h" name="bancoT_h" value="0"></td>
                                                </tr>

                                            </div>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="table-responsive-xl">
                                    <table class="table" width="100%">
                                        <thead class="blue-gradient text-white">
                                            <tr>
                                                <th>N° Recibo *</th>
                                                <th>Fecha Desde Recibo *</th>
                                                <th>Fecha Hasta Recibo *</th>
                                                <th>Zona de Produc</th>
                                                <th>N° de Cuotas *</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <div class="form-group col-md-12">
                                                <tr style="background-color: white">
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" id="n_recibo" name="n_recibo" required data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio [Sólo introducir números]">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control datepicker" id="desde_recibo" name="desde_recibo" required data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio" />
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control datepicker" id="hasta_recibo" name="hasta_recibo" required data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio" />
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" id="z_produc" name="z_produc" readonly value="<?= utf8_encode($usuario[0]['z_produccion']); ?>">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="number" class="form-control validanumericos3" id="n_cuotas" name="n_cuotas" min="1" max="12" required data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio">
                                                        </div>
                                                    </td>
                                                </tr>
                                            </div>
                                        </tbody>
                                    </table>
                                </div>

                                <h2 id="existeT" class="text-success text-center"><strong></strong></h2>
                                <h2 id="no_existeT" class="text-danger text-center"><strong></strong></h2>
                                <div class="table-responsive-xl">
                                    <table class="table">
                                        <thead class="blue-gradient text-white">
                                            <tr>
                                                <th>N° ID Titular *</th>
                                                <th>Nombre(s) Titular</th>
                                                <th>Apellido(s) Titular</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <div class="form-group col-md-12">
                                                <tr style="background-color: white">
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input onblur="validartitular(this)" type="text" class="form-control validanumericos5" id="titular" name="titular" required data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" id="n_titular" name="n_titular" readonly="readonly" required="true">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" id="a_titular" name="a_titular" readonly="readonly" required="true">
                                                        </div>
                                                    </td>
                                                </tr>
                                            </div>
                                        </tbody>
                                    </table>
                                </div>

                                <h2 id="existeTom" class="text-success text-center"><strong></strong></h2>
                                <h2 id="no_existeTom" class="text-danger text-center"><strong></strong></h2>
                                <div class="table-responsive-xl" id="tablatomador" hidden="true">
                                    <table class="table" id="iddatatable">
                                        <thead class="blue-gradient text-white">
                                            <tr>
                                                <th>N° ID Tomador *</th>
                                                <th>Nombre(s) Tomador</th>
                                                <th>Apellido(s) Tomador</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <div class="form-group col-md-12">
                                                <tr style="background-color: white">
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input onblur="validartomador(this)" type="text" class="form-control validanumericos6" id="tomador" name="tomador" required data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" id="n_tomador" name="n_tomador" readonly>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" id="a_tomador" name="a_tomador" readonly>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </div>
                                        </tbody>
                                    </table>
                                </div>


                                <div class="table-responsive-xl" id="tablaveh" hidden="true">
                                    <h2 class="text-info"><strong>Datos Vehículo</strong></h2>
                                    <table class="table" id="idtablaveh">
                                        <thead class="blue-gradient text-white">
                                            <tr>
                                                <th>Placa</th>
                                                <th>Marca</th>
                                                <th>Modelo</th>
                                                <th>Tipo</th>
                                                <th>Año</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <div class="form-group col-md-12">
                                                <tr style="background-color: white">
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" id="placa" name="placa">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" id="marca" name="marca">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" id="modelo" name="modelo">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" id="tipo" name="tipo">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" id="anio" name="anio" placeholder="2019">
                                                        </div>
                                                    </td>
                                                </tr>
                                            </div>
                                        </tbody>
                                    </table>
                                </div>



                                <div class="table-responsive-xl">
                                    <table class="table" width="100%">
                                        <thead class="blue-gradient text-white">
                                            <tr>
                                                <th>Asesor *</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr style="background-color: white">
                                                <td><select class="form-control selectpicker" id="asesor" name="asesor" required data-toggle="tooltip" data-placement="bottom" title="Seleccione un elemento de la lista" data-style="btn-white" data-header="Seleccione Cía" data-actions-box="true" data-live-search="true">
                                                        <option value="">Seleccione el Asesor</option>
                                                        <?php
                                                        for ($i = 0; $i < sizeof($asesor); $i++) {
                                                        ?>
                                                            <option value='<?= utf8_encode($asesor[$i]["cod"] . "=" . $asesor[$i]["nombre"]); ?>'><?= utf8_encode($asesor[$i]["nombre"]) . ' (' . $asesor[$i]["cod"] . ')'; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>


                                <div class="table-responsive-xl">
                                    <table class="table" id="tablaAsesor">
                                        <thead class="blue-gradient text-white">
                                            <tr>
                                                <th>Observaciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr style="background-color: white">
                                                <td>
                                                    <div class="input-group md-form my-n1">
                                                        <input onkeyup="mayus(this);" type="text" class="form-control" id="obs" name="obs" maxlength="200" autocomplete="off">
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>


                                <center>
                                    <button type="submit" id="btnForm" class="btn blue-gradient btn-lg btn-rounded">Previsualizar</button>
                                </center>

                            </form>

                            <div id="load" class="d-flex justify-content-center align-items-center" hidden>
                                <div class="spinner-grow text-info" style="width: 9rem; height: 9rem;" id="load1" hidden></div>
                            </div>


                        </div>
                        <br><br><br>
            </div>

        </div>





        <?php require_once dirname(__DIR__) . '\..\layout\footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . '\..\layout\footer.php'; ?>

        <!-- Modal TITULAR -->
        <div class="modal fade" id="agregarnuevotitular" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Debe Agregar Nuevo Cliente</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="frmnuevoT" autocomplete="off">

                            <div class="form-row">
                                <table class="table table-hover table-striped table-bordered" id="iddatatable">
                                    <thead class="blue-gradient text-white">
                                        <tr>
                                            <th>Razón Social *</th>
                                            <th colspan="3">N° ID Titular</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <div class="form-group col-md-12">
                                            <tr style="background-color: white">
                                                <td><select class="mdb-select md-form colorful-select dropdown-primary my-n2" name="r_sNew" id="r_sNew" required>
                                                        <option value="">Seleccione</option>
                                                        <option value="PN-">PN-</option>
                                                        <option value="J-">J-</option>
                                                    </select>
                                                </td>
                                                <td colspan="3">
                                                    <div class="input-group md-form my-n1 grey lighten-2">
                                                        <input type="text" class="form-control" id="id_new_titular" name="id_new_titular" readonly="readonly">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="blue-gradient text-white">
                                                <th colspan="2">Nombre(s) Titular *</th>
                                                <th colspan="2">Apellido(s) Titular</th>
                                            </tr>
                                            <tr style="background-color: white">
                                                <td colspan="2">
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control" id="nT_new" name="nT_new" required onkeyup="mayus(this);">
                                                    </div>
                                                </td>
                                                <td colspan="2">
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control" id="aT_new" name="aT_new" required onkeyup="mayus(this);">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="blue-gradient text-white">
                                                <th colspan="2">Celular *</th>
                                                <th colspan="2">Teléfono</th>
                                            </tr>
                                            <tr style="background-color: white">
                                                <td colspan="2">
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control validanumericos7" id="cT_new" name="cT_new" required>
                                                    </div>
                                                </td>
                                                <td colspan="2">
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control validanumericos8" id="tT_new" name="tT_new" required>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="blue-gradient text-white">
                                                <th>Fecha de Nacimiento</th>
                                                <th colspan="2">Email *</th>
                                            </tr>
                                            <tr style="background-color: white">
                                                <td>
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control datepicker" id="fnT_new" name="fnT_new" required />
                                                    </div>
                                                </td>
                                                <td colspan="2">
                                                    <div class="input-group md-form my-n1">
                                                        <input type="email" class="form-control" id="eT_new" name="eT_new" required>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="blue-gradient text-white">
                                                <th colspan=4>Dirección *</th>
                                            </tr>
                                            <tr style="background-color: white">
                                                <td colspan=4>
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control" id="dT_new" name="dT_new" required onkeyup="mayus(this);">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="blue-gradient text-white">
                                                <th colspan=2>Ocupación</th>
                                                <th colspan=2>Ingreso</th>
                                            </tr>
                                            <tr style="background-color: white">
                                                <td colspan=2>
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control" id="oT_new" name="oT_new" required onkeyup="mayus(this);">
                                                    </div>
                                                </td>
                                                <td colspan=2>
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control validanumericos9" id="iT_new" name="iT_new" value="0" required>
                                                    </div>
                                                </td>
                                            </tr>
                                        </div>
                                    </tbody>
                                </table>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="btnAgregarnuevo" class="btn aqua-gradient">Agregar nuevo</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal TOMADOR -->
        <div class="modal fade" id="agregarnuevotomador" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Debe Agregar Nuevo Cliente</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="frmnuevoTom" autocomplete="off">

                            <div class="form-row">
                                <table class="table table-hover table-striped table-bordered nowrap" id="iddatatable1">
                                    <thead class="blue-gradient text-white">
                                        <tr>
                                            <th>Razón Social *</th>
                                            <th colspan="3">N° ID Tomador</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <div class="form-group col-md-12">
                                            <tr style="background-color: white">
                                                <td><select class="mdb-select md-form colorful-select dropdown-primary my-n2" name="r_sNewT" id="r_sNewT" required>
                                                        <option value="">Seleccione</option>
                                                        <option value="PN-">PN-</option>
                                                        <option value="J-">J-</option>
                                                    </select>
                                                </td>
                                                <td colspan="3">
                                                    <div class="input-group md-form my-n1 grey lighten-2">
                                                        <input type="text" class="form-control" id="id_new_titularT" name="id_new_titularT" readonly="readonly">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="blue-gradient text-white">
                                                <th colspan="2">Nombre(s) Tomador *</th>
                                                <th colspan="2">Apellido(s) Tomador</th>
                                            </tr>
                                            <tr style="background-color: white">
                                                <td colspan="2">
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control" id="nT_newT" name="nT_newT" required onkeyup="mayus(this);">
                                                    </div>
                                                </td>
                                                <td colspan="2">
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control" id="aT_newT" name="aT_newT" required onkeyup="mayus(this);">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="blue-gradient text-white">
                                                <th colspan="2">Celular *</th>
                                                <th colspan="2">Teléfono</th>
                                            </tr>
                                            <tr style="background-color: white">
                                                <td colspan="2">
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control validanumericos10" id="cT_newT" name="cT_newT" required>
                                                    </div>
                                                </td>
                                                <td colspan="2">
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control validanumericos11" id="tT_newT" name="tT_newT" required>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="blue-gradient text-white">
                                                <th>Fecha de Nacimiento</th>
                                                <th colspan="2">Email *</th>
                                            </tr>
                                            <tr style="background-color: white">
                                                <td>
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control datepicker" id="fnT_newT" name="fnT_newT" required>
                                                    </div>
                                                </td>
                                                <td colspan="2">
                                                    <div class="input-group md-form my-n1">
                                                        <input type="email" class="form-control" id="eT_newT" name="eT_newT" required>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="blue-gradient text-white">
                                                <th colspan=4>Dirección *</th>
                                            </tr>
                                            <tr style="background-color: white">
                                                <td colspan=4>
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control" id="dT_newT" name="dT_newT" required onkeyup="mayus(this);">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="blue-gradient text-white">
                                                <th colspan=2>Ocupación</th>
                                                <th colspan=2>Ingreso</th>
                                            </tr>
                                            <tr style="background-color: white">
                                                <td colspan=2>
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control" id="oT_newT" name="oT_newT" required onkeyup="mayus(this);">
                                                    </div>
                                                </td>
                                                <td colspan=2>
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control validanumericos12" id="iT_newT" name="iT_newT" value="0" required>
                                                    </div>
                                                </td>
                                            </tr>
                                        </div>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="btnAgregarnuevoT" class="btn aqua-gradient">Agregar nuevo</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal Tarjetas Existentes-->
        <div class="modal fade" id="tarjetaexistente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLabel">Seleccione la Tarjeta</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body ">
                        <form id="frmnuevoP">
                            <div class="table-responsive-xl">
                                <a onclick="selecTarjetaNew()" style="color:black" data-toggle="tooltip" data-placement="top" title="Añadir Tarjeta Nueva" class="btn dusty-grass-gradient btn-sm float-right">Añadir Tarjeta Nueva <i class="fa fa-plus" aria-hidden="true"></i></a>
                                <table class="table table-hover table-striped table-bordered" id="tablaPE">
                                    <thead class="blue-gradient text-white">
                                        <tr>
                                            <th>Nº de Tarjeta</th>
                                            <th>CVV</th>
                                            <th>F Vencimiento</th>
                                            <th>Nombre Tarjetahabiente</th>
                                            <th>Banco</th>
                                            <th>Póliza(s) Asociada(s)</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script src="../../assets/view/b_poliza.js"></script>
        <script src="../../assets/view/modalN.js"></script>

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

                $(window).on('shown.bs.modal', function() {
                    picker.close();
                });

                $('#n_cuotas').val(1);
                $("#n_cuotas").attr("readonly", true);
                $("#n_cuotas").attr("class", "form-control validanumericos3 grey lighten-2");


                onload = function() {
                    var ele = document.querySelectorAll('.validanumericos')[0];
                    var ele1 = document.querySelectorAll('.validanumericos1')[0];
                    var ele2 = document.querySelectorAll('.validanumericos2')[0];
                    var ele3 = document.querySelectorAll('.validanumericos3')[0];
                    var ele4 = document.querySelectorAll('.validanumericos4')[0];
                    var ele5 = document.querySelectorAll('.validanumericos5')[0];
                    var ele6 = document.querySelectorAll('.validanumericos6')[0];
                    var ele7 = document.querySelectorAll('.validanumericos7')[0];
                    var ele8 = document.querySelectorAll('.validanumericos8')[0];
                    var ele9 = document.querySelectorAll('.validanumericos9')[0];
                    var ele10 = document.querySelectorAll('.validanumericos10')[0];
                    var ele11 = document.querySelectorAll('.validanumericos11')[0];
                    var ele12 = document.querySelectorAll('.validanumericos12')[0];

                    ele.onkeypress = function(e) {
                        if (isNaN(this.value + String.fromCharCode(e.charCode)))
                            return false;
                    }
                    ele1.onkeypress = function(e1) {
                        if (isNaN(this.value + String.fromCharCode(e1.charCode)))
                            return false;
                    }
                    ele1.onpaste = function(e1) {
                        e1.preventDefault();
                    }
                    ele2.onkeypress = function(e2) {
                        if (isNaN(this.value + String.fromCharCode(e2.charCode)))
                            return false;
                    }
                    ele2.onpaste = function(e2) {
                        e2.preventDefault();
                    }
                    ele3.onkeypress = function(e3) {
                        if (isNaN(this.value + String.fromCharCode(e3.charCode)))
                            return false;
                    }
                    ele3.onpaste = function(e3) {
                        e3.preventDefault();
                    }
                    ele4.onkeypress = function(e4) {
                        if (isNaN(this.value + String.fromCharCode(e4.charCode)))
                            return false;
                    }
                    ele4.onpaste = function(e4) {
                        e4.preventDefault();
                    }
                    ele5.onkeypress = function(e5) {
                        if (isNaN(this.value + String.fromCharCode(e5.charCode)))
                            return false;
                    }
                    ele6.onkeypress = function(e6) {
                        if (isNaN(this.value + String.fromCharCode(e6.charCode)))
                            return false;
                    }
                    ele7.onkeypress = function(e7) {
                        if (isNaN(this.value + String.fromCharCode(e7.charCode)))
                            return false;
                    }
                    ele7.onpaste = function(e7) {
                        e7.preventDefault();
                    }
                    ele8.onkeypress = function(e8) {
                        if (isNaN(this.value + String.fromCharCode(e8.charCode)))
                            return false;
                    }
                    ele9.onkeypress = function(e9) {
                        if (isNaN(this.value + String.fromCharCode(e9.charCode)))
                            return false;
                    }
                    ele10.onkeypress = function(e10) {
                        if (isNaN(this.value + String.fromCharCode(e10.charCode)))
                            return false;
                    }
                    ele11.onkeypress = function(e11) {
                        if (isNaN(this.value + String.fromCharCode(e11.charCode)))
                            return false;
                    }
                    ele12.onkeypress = function(e12) {
                        if (isNaN(this.value + String.fromCharCode(e12.charCode)))
                            return false;
                    }
                }


                $('#btnAgregarnuevo').click(function() {
                    if ($("#r_sNew").val().length < 1) {
                        alertify.error("La Razón Social del Cliente es Obligatoria");
                        return false;
                    }
                    if ($("#id_new_titular").val().length < 1) {
                        alertify.error("El Nº de ID del Cliente es Obligatorio");
                        return false;
                    }
                    if ($("#nT_new").val().length < 1) {
                        alertify.error("El Nombre del Cliente es Obligatorio");
                        return false;
                    }
                    if ($("#dT_new").val().length < 1) {
                        alertify.error("La Dirección del Cliente es Obligatorio");
                        return false;
                    }

                    datos = $('#frmnuevoT').serialize();
                    var titular = $('#id_new_titular').val();
                    var n_titular = $('#nT_new').val();
                    var a_titular = $('#aT_new').val();

                    $.ajax({
                        type: "POST",
                        data: datos,
                        url: "../../procesos/agregarCliente.php",
                        success: function(r) {
                            console.log(r);
                            if (r == 1) {
                                $('#frmnuevoT')[0].reset();
                                alertify.success("Agregado con Exito!!");

                                $('#titular').val(titular);
                                $('#titular').removeAttr('hidden');
                                $('#n_titular').val(n_titular);
                                $('#a_titular').val(a_titular);

                                $('#no_existeT').text("");
                                $("#titular").attr("readonly", true);
                                $('#titular').removeAttr('onblur');

                                $('#tablatomador').removeAttr('hidden');
                                $('#tomador').val(titular);
                                $('#n_tomador').val(n_titular);
                                $('#a_tomador').val(a_titular);

                                $('#agregarnuevotitular').modal('hide');
                            } else {
                                alertify.error("Fallo al agregar!");
                            }
                        }
                    });
                });


                $('#btnAgregarnuevoT').click(function() {
                    if ($("#r_sNewT").val().length < 1) {
                        alertify.error("La Razón Social del Cliente es Obligatoria");
                        return false;
                    }
                    if ($("#id_new_titularT").val().length < 1) {
                        alertify.error("El Nº de ID del Cliente es Obligatorio");
                        return false;
                    }
                    if ($("#nT_newT").val().length < 1) {
                        alertify.error("El Nombre del Cliente es Obligatorio");
                        return false;
                    }
                    if ($("#dT_newT").val().length < 1) {
                        alertify.error("La Dirección del Cliente es Obligatorio");
                        return false;
                    }

                    datos = $('#frmnuevoTom').serialize();
                    var titular = $('#id_new_titularT').val();
                    var n_titular = $('#nT_newT').val();
                    var a_titular = $('#aT_newT').val();

                    $.ajax({
                        type: "POST",
                        data: datos,
                        url: "../../procesos/agregarTomador.php",
                        success: function(r) {
                            if (r == 1) {
                                $('#frmnuevoTom')[0].reset();
                                alertify.success("Agregado con Exito!!");
                                $('#no_existeTom').text("");

                                $('#tablatomador').removeAttr('hidden');
                                $('#tomador').val(titular);
                                $('#n_tomador').val(n_titular);
                                $('#a_tomador').val(a_titular);

                                $('#agregarnuevotomador').modal('hide');
                            } else {
                                alertify.error("Fallo al agregar!");
                            }
                        }
                    });
                });

            });

            function mayus(e) {
                e.value = e.value.toUpperCase();
            }

            function cargarRecibo(n_poliza) {
                $('#n_recibo').val($(n_poliza).val());
            }

            async function validarPoliza(num_poliza) {
                $('#verifP').removeAttr('hidden');

                $('#trTarjeta1').attr('hidden', true);
                $('#trTarjeta2').attr('hidden', true);
                $('#forma_pago').val(1);
                $('#n_tarjeta').val('');
                $('#cvv').val('');
                $('#fechaV').val('');
                $('#titular_tarjeta').val('');
                $('#bancoT').val('');

                $("#bancoT").css('background-color', 'white');
                await $.ajax({
                    type: "POST",
                    data: "num_poliza=" + num_poliza.value,
                    url: "../../procesos/validarpoliza.php?num_poliza=" + num_poliza.value,
                    success: function(r) {
                        datos = jQuery.parseJSON(r);

                        if (datos == null) {
                            $('#id_new_titular').val("");
                            $('#existeP').text("");
                            $('#no_existeP').text("No Existe Póliza");
                            $('#titular').val("");
                            $('#n_titular').val("");
                            $('#a_titular').val("");
                            $('#titular').removeAttr("readonly", true);
                            $('#titular').attr('onblur', 'validartitular(this)');
                            $("#titular").attr("class", "form-control validanumericos5");
                            $("#tipo_poliza option:selected").removeAttr("selected");
                            $("#ramo option:selected").removeAttr("selected");
                            $("#cia option:selected").removeAttr("selected");
                            $("#t_cuenta option:selected").removeAttr("selected");
                            $('#asesor option:first').prop('selected', true);
                            $('#asesor').change();

                            $('#desdeP').val("");
                            $('#hastaP').val("");
                            $('#desde_recibo').val("");
                            $('#hasta_recibo').val("");

                            $('#t_cobertura').val("");
                            $('#t_cobertura').removeAttr('readonly');
                            $('#currency option:first').prop('selected', true);
                            $('#currency').css('pointer-events', 'auto');
                            $("#currency").css('background-color', 'white');
                            $('#tomador').val("");
                            $('#n_tomador').val("");
                            $('#a_tomador').val("");

                            $('#existeT').text("");
                            $('#no_existeT').text("");
                            $('#titular').val("");

                            $('#tablatomador').attr("hidden", true);

                            $('#existeTom').text("");
                            $('#no_existeTom').text("");
                            $("#tomador").css('color', 'black');

                            $('#tablaveh').attr('hidden', true);
                            $('#placa').val('');
                            $('#tipo').val('');
                            $('#marca').val('');
                            $('#modelo').val('');
                            $('#anio').val('');
                            $('#serial').val('');
                            $('#color').val('');
                            $('#categoria').val('');
                        } else {
                            if (datos[0]['id_cod_ramo'] == null) {
                                $('#id_new_titular').val("");
                                $('#existeP').text("");
                                $('#no_existeP').text("No Existe Póliza");
                                $('#titular').val("");
                                $('#n_titular').val("");
                                $('#a_titular').val("");
                                $('#titular').removeAttr("readonly", true);
                                $('#titular').attr('onblur', 'validartitular(this)');
                                $("#titular").attr("class", "form-control validanumericos5");
                                $("#tipo_poliza option:selected").removeAttr("selected");
                                $("#ramo option:selected").removeAttr("selected");
                                $("#cia option:selected").removeAttr("selected");
                                $("#t_cuenta option:selected").removeAttr("selected");
                                $('#asesor option:first').prop('selected', true);
                                $('#asesor').change();

                                $('#desdeP').val("");
                                $('#hastaP').val("");
                                $('#desde_recibo').val("");
                                $('#hasta_recibo').val("");

                                $('#t_cobertura').val("");
                                $('#t_cobertura').removeAttr('readonly');
                                $('#currency option:first').prop('selected', true);
                                $('#currency').css('pointer-events', 'auto');
                                $("#currency").css('background-color', 'white');
                                $('#tomador').val("");
                                $('#n_tomador').val("");
                                $('#a_tomador').val("");

                                $('#existeT').text("");
                                $('#no_existeT').text("");
                                $('#titular').val("");
                                $('#tablatomador').attr("hidden", true);

                                $('#existeTom').text("");
                                $('#no_existeTom').text("");
                                $("#tomador").css('color', 'black');

                                $('#tablaveh').attr('hidden', true);
                                $('#placa').val('');
                                $('#tipo').val('');
                                $('#marca').val('');
                                $('#modelo').val('');
                                $('#anio').val('');
                                $('#serial').val('');
                                $('#color').val('');
                                $('#categoria').val('');
                            } else if (datos[0]['id_cod_ramo'] == 2 || datos[0]['id_cod_ramo'] == 25) {
                                alertify.confirm('Existe!', 'La Póliza que introdujo ya Existe ¿Desea Renovarla?',
                                    function() {
                                        window.location.replace("../renov/crear_renov.php?id_poliza=" + datos[0]['id_poliza']);

                                        /*alertify.prompt('Desea modificar el Nº de Póliza?', 'Ingrese el Nº de Póliza Nuevo', num_poliza.value,
                                            function(evt, value) {
                                                alertify.notify('Nuevo Nº de Póliza es: ' + value);
                                                alertify.success('Proceda a Renovar la Póliza');
                                                $('#n_poliza').val(value);
                                                $('#titular').val(datos[0]['ci']);
                                                $('#titular').removeAttr('onblur');
                                                $('#titular').attr("readonly", true);
                                                $("#titular").attr("class", "form-control validanumericos5 grey lighten-2");
                                                $('#n_titular').val(datos[0]['nombre_t']);
                                                $('#a_titular').val(datos[0]['apellido_t']);

                                                $("#tipo_poliza option[value=2]").attr("selected", true);
                                                $("#ramo option[value=" + datos[0]['id_cod_ramo'] + "]").attr("selected", true);
                                                $("#cia option[value=" + datos[0]['id_cia'] + "]").attr("selected", true);
                                                $("#t_cuenta option[value=" + datos[0]['t_cuenta'] + "]").attr("selected", true);
                                                $("#asesor").val(datos[0]['codvend'] + "=" + datos[0]['nombre']);
                                                $('#asesor').change();

                                                //----------DATES-----------
                                                $('#desdeP').val(datos[0]['f_desdepoliza']);
                                                $('#hastaP').val(datos[0]['f_hastapoliza']);
                                                var mydate = new Date($('#desdeP').val());
                                                mydate.setDate(mydate.getDate() + 1)
                                                if (10 > mydate.getMonth() + 1 > 0) {
                                                    var mes = '0' + (mydate.getMonth() + 1).toString()
                                                } else {
                                                    var mes = (mydate.getMonth() + 1)
                                                }
                                                if (10 > mydate.getDate() > 0) {
                                                    var dia = '0' + (mydate.getDate()).toString()
                                                } else {
                                                    var dia = mydate.getDate()
                                                }

                                                $('#desdeP').val((mydate.getFullYear() + 1) + '-' + mes + '-' + dia);
                                                var mydate1 = new Date($('#hastaP').val());
                                                mydate1.setDate(mydate1.getDate() + 1)
                                                if (10 > mydate1.getMonth() + 1 > 0) {
                                                    var mes1 = '0' + (mydate1.getMonth() + 1).toString()
                                                } else {
                                                    var mes1 = (mydate1.getMonth() + 1)
                                                }
                                                if (10 > mydate1.getDate() > 0) {
                                                    var dia1 = '0' + (mydate1.getDate()).toString()
                                                } else {
                                                    var dia1 = mydate1.getDate()
                                                }

                                                $('#hastaP').val((mydate1.getFullYear() + 1) + '-' + mes1 + '-' + dia1);
                                                var desdeP = ($('#desdeP').val()).split('-').reverse().join('-');
                                                var hastaP = ($('#hastaP').val()).split('-').reverse().join('-');
                                                $('#desdeP').val(desdeP);
                                                $('#hastaP').val(hastaP);
                                                $('#hastaP').pickadate('picker').set('select', hastaP);
                                                $('#desde_recibo').pickadate('picker').set('select', desdeP);
                                                $('#hasta_recibo').pickadate('picker').set('select', hastaP);
                                                //-----

                                                $('#placa').val(datos[0]['placa']);
                                                $('#tipo').val(datos[0]['tveh']);
                                                $('#marca').val(datos[0]['marca']);
                                                $('#modelo').val(datos[0]['mveh']);
                                                $('#anio').val(datos[0]['f_veh']);
                                                $('#serial').val(datos[0]['serial']);
                                                $('#color').val(datos[0]['cveh']);
                                                $('#categoria').val(datos[0]['catveh']);

                                                $('#t_cobertura').val(datos[0]['tcobertura']);
                                                $('#t_cobertura').attr("readonly", true);
                                                $("#currency").val(datos[0]['currency']);
                                                $('#currency').css('pointer-events', 'none');
                                                $("#currency").css('background-color', '#e6e6e6');

                                                $('#existeP').text("Existe Póliza");
                                                $('#no_existeP').text("");

                                                $('#id_new_titular').val("");
                                                $('#tomador').val(titular.value);
                                                $('#n_tomador').val(datos[0]['nombre_t']);
                                                $('#a_tomador').val(datos[0]['apellido_t']);
                                                $('#existeT').text("");
                                                $('#no_existeT').text("");
                                                $('#existeTom').text("");
                                                $('#no_existeTom').text("");
                                                $('#tablatomador').removeAttr('hidden');
                                                $("#tomador").css('color', 'red');

                                                $('#tablaveh').removeAttr('hidden');
                                            },
                                            function() {
                                                alertify.notify('No se modificó el Nº de Póliza');
                                                alertify.success('Proceda a Renovar la Póliza');
                                                $('#titular').val(datos[0]['ci']);
                                                $('#titular').removeAttr('onblur');
                                                $('#titular').attr("readonly", true);
                                                $("#titular").attr("class", "form-control validanumericos5 grey lighten-2");
                                                $('#n_titular').val(datos[0]['nombre_t']);
                                                $('#a_titular').val(datos[0]['apellido_t']);
                                                $("#tipo_poliza option[value=2]").attr("selected", true);
                                                $("#ramo option[value=" + datos[0]['id_cod_ramo'] + "]").attr("selected", true);
                                                $("#cia option[value=" + datos[0]['id_cia'] + "]").attr("selected", true);
                                                $("#t_cuenta option[value=" + datos[0]['t_cuenta'] + "]").attr("selected", true);
                                                $("#asesor").val(datos[0]['codvend'] + "=" + datos[0]['nombre']);
                                                $('#asesor').change();

                                                //----------DATES-----------
                                                $('#desdeP').val(datos[0]['f_desdepoliza']);
                                                $('#hastaP').val(datos[0]['f_hastapoliza']);
                                                var mydate = new Date($('#desdeP').val());
                                                mydate.setDate(mydate.getDate() + 1)
                                                if (10 > mydate.getMonth() + 1 > 0) {
                                                    var mes = '0' + (mydate.getMonth() + 1).toString()
                                                } else {
                                                    var mes = (mydate.getMonth() + 1)
                                                }
                                                if (10 > mydate.getDate() > 0) {
                                                    var dia = '0' + (mydate.getDate()).toString()
                                                } else {
                                                    var dia = mydate.getDate()
                                                }

                                                $('#desdeP').val((mydate.getFullYear() + 1) + '-' + mes + '-' + dia);
                                                var mydate1 = new Date($('#hastaP').val());
                                                mydate1.setDate(mydate1.getDate() + 1)
                                                if (10 > mydate1.getMonth() + 1 > 0) {
                                                    var mes1 = '0' + (mydate1.getMonth() + 1).toString()
                                                } else {
                                                    var mes1 = (mydate1.getMonth() + 1)
                                                }
                                                if (10 > mydate1.getDate() > 0) {
                                                    var dia1 = '0' + (mydate1.getDate()).toString()
                                                } else {
                                                    var dia1 = mydate1.getDate()
                                                }

                                                $('#hastaP').val((mydate1.getFullYear() + 1) + '-' + mes1 + '-' + dia1);
                                                var desdeP = ($('#desdeP').val()).split('-').reverse().join('-');
                                                var hastaP = ($('#hastaP').val()).split('-').reverse().join('-');
                                                $('#desdeP').val(desdeP);
                                                $('#hastaP').val(hastaP);
                                                $('#hastaP').pickadate('picker').set('select', hastaP);
                                                $('#desde_recibo').pickadate('picker').set('select', desdeP);
                                                $('#hasta_recibo').pickadate('picker').set('select', hastaP);
                                                //-----

                                                $('#placa').val(datos[0]['placa']);
                                                $('#tipo').val(datos[0]['tveh']);
                                                $('#marca').val(datos[0]['marca']);
                                                $('#modelo').val(datos[0]['mveh']);
                                                $('#anio').val(datos[0]['f_veh']);
                                                $('#serial').val(datos[0]['serial']);
                                                $('#color').val(datos[0]['cveh']);
                                                $('#categoria').val(datos[0]['catveh']);

                                                $('#t_cobertura').val(datos[0]['tcobertura']);
                                                $('#t_cobertura').attr("readonly", true);
                                                $("#currency").val(datos[0]['currency']);
                                                $('#currency').css('pointer-events', 'none');
                                                $("#currency").css('background-color', '#e6e6e6');

                                                $('#existeP').text("Existe Póliza");
                                                $('#no_existeP').text("");

                                                $('#id_new_titular').val("");
                                                $('#tomador').val(titular.value);
                                                $('#n_tomador').val(datos[0]['nombre_t']);
                                                $('#a_tomador').val(datos[0]['apellido_t']);
                                                $('#existeT').text("");
                                                $('#no_existeT').text("");
                                                $('#existeTom').text("");
                                                $('#no_existeTom').text("");
                                                $('#tablatomador').removeAttr('hidden');
                                                $("#tomador").css('color', 'red');

                                                $('#tablaveh').removeAttr('hidden');
                                            }).set('labels', {
                                            ok: 'Sí',
                                            cancel: 'No'
                                        }).set({
                                            transition: 'zoom'
                                        }).show();*/
                                    },
                                    function() {
                                        window.location.replace("crear_poliza.php");
                                        alertify.error('Cancel')
                                    }).set('labels', {
                                    ok: 'Sí',
                                    cancel: 'No'
                                }).set({
                                    transition: 'zoom'
                                }).show();
                            } else {
                                alertify.confirm('Existe!', 'La Póliza que introdujo ya Existe ¿Desea Renovarla?',
                                    function() {
                                        window.location.replace("../renov/crear_renov.php?id_poliza=" + datos[0]['id_poliza']);

                                        /*alertify.prompt('Desea modificar el Nº de Póliza?', 'Ingrese el Nº de Póliza Nuevo', num_poliza.value,
                                            function(evt, value) {
                                                alertify.notify('Nuevo Nº de Póliza es: ' + value);
                                                alertify.success('Proceda a Renovar la Póliza');
                                                $('#n_poliza').val(value);
                                                $('#titular').val(datos[0]['ci']);
                                                $('#titular').removeAttr('onblur');
                                                $('#titular').attr("readonly", true);
                                                $("#titular").attr("class", "form-control validanumericos5 grey lighten-2");
                                                $('#n_titular').val(datos[0]['nombre_t']);
                                                $('#a_titular').val(datos[0]['apellido_t']);
                                                $("#tipo_poliza option[value=2]").attr("selected", true);
                                                $("#ramo option[value=" + datos[0]['id_cod_ramo'] + "]").attr("selected", true);
                                                $("#cia option[value=" + datos[0]['id_cia'] + "]").attr("selected", true);
                                                $("#t_cuenta option[value=" + datos[0]['t_cuenta'] + "]").attr("selected", true);
                                                $("#asesor").val(datos[0]['codvend'] + "=" + datos[0]['nombre']);
                                                $('#asesor').change();

                                                //----------DATES-----------
                                                $('#desdeP').val(datos[0]['f_desdepoliza']);
                                                $('#hastaP').val(datos[0]['f_hastapoliza']);
                                                var mydate = new Date($('#desdeP').val());
                                                mydate.setDate(mydate.getDate() + 1)
                                                if (10 > mydate.getMonth() + 1 > 0) {
                                                    var mes = '0' + (mydate.getMonth() + 1).toString()
                                                } else {
                                                    var mes = (mydate.getMonth() + 1)
                                                }
                                                if (10 > mydate.getDate() > 0) {
                                                    var dia = '0' + (mydate.getDate()).toString()
                                                } else {
                                                    var dia = mydate.getDate()
                                                }

                                                $('#desdeP').val((mydate.getFullYear() + 1) + '-' + mes + '-' + dia);
                                                var mydate1 = new Date($('#hastaP').val());
                                                mydate1.setDate(mydate1.getDate() + 1)
                                                if (10 > mydate1.getMonth() + 1 > 0) {
                                                    var mes1 = '0' + (mydate1.getMonth() + 1).toString()
                                                } else {
                                                    var mes1 = (mydate1.getMonth() + 1)
                                                }
                                                if (10 > mydate1.getDate() > 0) {
                                                    var dia1 = '0' + (mydate1.getDate()).toString()
                                                } else {
                                                    var dia1 = mydate1.getDate()
                                                }

                                                $('#hastaP').val((mydate1.getFullYear() + 1) + '-' + mes1 + '-' + dia1);
                                                var desdeP = ($('#desdeP').val()).split('-').reverse().join('-');
                                                var hastaP = ($('#hastaP').val()).split('-').reverse().join('-');
                                                $('#desdeP').val(desdeP);
                                                $('#hastaP').val(hastaP);
                                                $('#hastaP').pickadate('picker').set('select', hastaP);
                                                $('#desde_recibo').pickadate('picker').set('select', desdeP);
                                                $('#hasta_recibo').pickadate('picker').set('select', hastaP);
                                                //-----

                                                $('#t_cobertura').val(datos[0]['tcobertura']);
                                                $('#t_cobertura').attr("readonly", true);
                                                $("#currency").val(datos[0]['currency']);
                                                $('#currency').css('pointer-events', 'none');
                                                $("#currency").css('background-color', '#e6e6e6');

                                                $('#existeP').text("Existe Póliza");
                                                $('#no_existeP').text("");

                                                $('#id_new_titular').val("");
                                                $('#tomador').val(titular.value);
                                                $('#n_tomador').val(datos[0]['nombre_t']);
                                                $('#a_tomador').val(datos[0]['apellido_t']);
                                                $('#existeT').text("");
                                                $('#no_existeT').text("");
                                                $('#existeTom').text("");
                                                $('#no_existeTom').text("");
                                                $('#tablatomador').removeAttr('hidden');
                                                $("#tomador").css('color', 'red');
                                                $('#tablaveh').attr('hidden', true);
                                                $('#placa').val('');
                                                $('#tipo').val('');
                                                $('#marca').val('');
                                                $('#modelo').val('');
                                                $('#anio').val('');
                                                $('#serial').val('');
                                                $('#color').val('');
                                                $('#categoria').val('');
                                            },
                                            function() {
                                                alertify.notify('No se modificó el Nº de Póliza');
                                                alertify.success('Proceda a Renovar la Póliza');
                                                $('#titular').val(datos[0]['ci']);
                                                $('#titular').removeAttr('onblur');
                                                $('#titular').attr("readonly", true);
                                                $("#titular").attr("class", "form-control validanumericos5 grey lighten-2");
                                                $('#n_titular').val(datos[0]['nombre_t']);
                                                $('#a_titular').val(datos[0]['apellido_t']);
                                                $("#tipo_poliza option[value=2]").attr("selected", true);
                                                $("#ramo option[value=" + datos[0]['id_cod_ramo'] + "]").attr("selected", true);
                                                $("#cia option[value=" + datos[0]['id_cia'] + "]").attr("selected", true);
                                                $("#t_cuenta option[value=" + datos[0]['t_cuenta'] + "]").attr("selected", true);
                                                $("#asesor").val(datos[0]['codvend'] + "=" + datos[0]['nombre']);
                                                $('#asesor').change();

                                                //----------DATES-----------
                                                $('#desdeP').val(datos[0]['f_desdepoliza']);
                                                $('#hastaP').val(datos[0]['f_hastapoliza']);
                                                var mydate = new Date($('#desdeP').val());
                                                mydate.setDate(mydate.getDate() + 1)
                                                if (10 > mydate.getMonth() + 1 > 0) {
                                                    var mes = '0' + (mydate.getMonth() + 1).toString()
                                                } else {
                                                    var mes = (mydate.getMonth() + 1)
                                                }
                                                if (10 > mydate.getDate() > 0) {
                                                    var dia = '0' + (mydate.getDate()).toString()
                                                } else {
                                                    var dia = mydate.getDate()
                                                }

                                                $('#desdeP').val((mydate.getFullYear() + 1) + '-' + mes + '-' + dia);
                                                var mydate1 = new Date($('#hastaP').val());
                                                mydate1.setDate(mydate1.getDate() + 1)
                                                if (10 > mydate1.getMonth() + 1 > 0) {
                                                    var mes1 = '0' + (mydate1.getMonth() + 1).toString()
                                                } else {
                                                    var mes1 = (mydate1.getMonth() + 1)
                                                }
                                                if (10 > mydate1.getDate() > 0) {
                                                    var dia1 = '0' + (mydate1.getDate()).toString()
                                                } else {
                                                    var dia1 = mydate1.getDate()
                                                }

                                                $('#hastaP').val((mydate1.getFullYear() + 1) + '-' + mes1 + '-' + dia1);
                                                var desdeP = ($('#desdeP').val()).split('-').reverse().join('-');
                                                var hastaP = ($('#hastaP').val()).split('-').reverse().join('-');
                                                $('#desdeP').val(desdeP);
                                                $('#hastaP').val(hastaP);
                                                $('#hastaP').pickadate('picker').set('select', hastaP);
                                                $('#desde_recibo').pickadate('picker').set('select', desdeP);
                                                $('#hasta_recibo').pickadate('picker').set('select', hastaP);
                                                //-----

                                                $('#t_cobertura').val(datos[0]['tcobertura']);
                                                $('#t_cobertura').attr("readonly", true);
                                                $("#currency").val(datos[0]['currency']);
                                                $('#currency').css('pointer-events', 'none');
                                                $("#currency").css('background-color', '#e6e6e6');

                                                $('#existeP').text("Existe Póliza");
                                                $('#no_existeP').text("");

                                                $('#id_new_titular').val("");
                                                $('#tomador').val(titular.value);
                                                $('#n_tomador').val(datos[0]['nombre_t']);
                                                $('#a_tomador').val(datos[0]['apellido_t']);
                                                $('#existeT').text("");
                                                $('#no_existeT').text("");
                                                $('#existeTom').text("");
                                                $('#no_existeTom').text("");
                                                $('#tablatomador').removeAttr('hidden');
                                                $("#tomador").css('color', 'red');
                                                $('#tablaveh').attr('hidden', true);
                                                $('#placa').val('');
                                                $('#tipo').val('');
                                                $('#marca').val('');
                                                $('#modelo').val('');
                                                $('#anio').val('');
                                                $('#serial').val('');
                                                $('#color').val('');
                                                $('#categoria').val('');
                                            }).set('labels', {
                                            ok: 'Sí',
                                            cancel: 'No'
                                        }).set({
                                            transition: 'zoom'
                                        }).show();*/
                                    },
                                    function() {
                                        window.location.replace("crear_poliza.php");
                                        alertify.error('Cancel')
                                    }).set('labels', {
                                    ok: 'Sí',
                                    cancel: 'No'
                                }).set({
                                    transition: 'zoom'
                                }).show();
                            }
                        }

                    }
                });
                $('#verifP').attr('hidden', true);
            }

            function cargarTarjeta(forma_pago) {
                if (forma_pago.value == 2) {
                    $('#trTarjeta1').removeAttr('hidden');
                    $('#trTarjeta2').removeAttr('hidden');
                } else {
                    $('#trTarjeta1').attr('hidden', true);
                    $('#trTarjeta2').attr('hidden', true);
                    $('#n_tarjeta').val('');
                    $('#cvv').val('');
                    $('#fechaV').val('');
                    $('#titular_tarjeta').val('');
                    $('#bancoT').val('');
                    $('#alert').val('0');
                    $('#id_tarjeta').val('0');
                }
            }

            function cargarFechaDesde(desdeP) {
                var dia = $("#desdeP").pickadate('picker').get('select', 'dd');
                var mes = $("#desdeP").pickadate('picker').get('select', 'mm');
                var anio = parseInt($("#desdeP").pickadate('picker').get('select', 'yyyy'));

                $('#hastaP').val(dia + '-' + mes + '-' + (anio + 1));

                var desdeP = $('#desdeP').val();
                var hastaP = $('#hastaP').val();

                $('#hastaP').pickadate('picker').set('select', hastaP);
                $('#desde_recibo').pickadate('picker').set('select', desdeP);
                $('#hasta_recibo').pickadate('picker').set('select', hastaP);
            }

            function cargarCuotas(f_pago) {

                if (f_pago.value == 1) {
                    $('#n_cuotas').val(1);
                    $("#n_cuotas").attr("readonly", true);
                    $("#n_cuotas").attr("class", "form-control validanumericos3 grey lighten-2");
                } else {
                    $('#n_cuotas').removeAttr('readonly');
                    $("#n_cuotas").attr("class", "form-control validanumericos3");
                }
            }

            function validartitular(titular) {
                if (titular.value == 1) {
                    $('#titular').val("");
                    $('#n_titular').val("");
                    $('#a_titular').val("");
                    alertify.error("Debe escribir un valor diferente a 1");
                    return false;
                } else {
                    $.ajax({
                        type: "POST",
                        data: "titular=" + titular.value,
                        url: "../../procesos/validartitular.php",
                        success: function(r) {
                            datos = jQuery.parseJSON(r);
                            if (datos['nombre_t'] == null) {
                                $('#n_titular').val("");
                                $('#a_titular').val("");

                                $('#id_new_titular').val(titular.value);
                                $('#existeT').text("");
                                $('#no_existeT').text("No Existe Titular");
                                $('#titular').val("");
                                $('#agregarnuevotitular').modal('show');

                                $('#tablatomador').attr("hidden", true);
                            } else {
                                $('#tablatomador').removeAttr('readonly');
                                $('#n_titular').val(datos['nombre_t']);
                                $('#a_titular').val(datos['apellido_t']);
                                $('#existeT').text("Existe Titular");
                                $('#no_existeT').text("");
                                $('#id_new_titular').val("");

                                $('#tablatomador').removeAttr('hidden');
                                $('#tomador').val(titular.value);
                                $('#n_tomador').val(datos['nombre_t']);
                                $('#a_tomador').val(datos['apellido_t']);

                            }
                        }
                    });
                }
            }

            function validartomador(titular) {
                if (titular.value == 1) {
                    $('#tomador').val("");
                    $('#n_tomador').val("");
                    $('#a_tomador').val("");
                    alertify.error("Debe escribir un valor diferente a 1");
                    return false;
                } else {
                    $.ajax({
                        type: "POST",
                        data: "titular=" + titular.value,
                        url: "../../procesos/validartitular.php",
                        success: function(r) {
                            datos = jQuery.parseJSON(r);
                            if (datos['nombre_t'] == null) {
                                $('#n_tomador').val("");
                                $('#a_tomador').val("");

                                $('#id_new_titularT').val(titular.value);
                                $('#existeTom').text("");
                                $('#no_existeTom').text("No Existe Tomador");
                                $('#tomador').val("");
                                $("#tomador").css('color', 'black');

                                $('#agregarnuevotomador').modal('show');
                            } else {
                                $('#n_tomador').val(datos['nombre_t']);
                                $('#a_tomador').val(datos['apellido_t']);
                                $("#btnAggTom").attr("hidden", true);
                                $('#existeTom').text("Existe Tomador");
                                $('#no_existeTom').text("");

                                $('#id_new_titularT').val("");
                                $("#tomador").css('color', 'black');
                            }
                        }
                    });
                }
            }

            $("#ramo").change(function() {
                if ($('#ramo').val() == 2 || $('#ramo').val() == 25) {
                    $('#tablaveh').removeAttr('hidden');
                } else {
                    $('#tablaveh').attr('hidden', true);
                }
            });

            async function validarTarjeta(n_tarjeta) {
                $('#alert').val('0');
                $('#id_tarjeta').val('0');
                if ($("#n_tarjeta").val().length < 1) {
                    alertify.error("Debe escribir en la casilla para realizar la búsqueda");
                    return false;
                }
                $.ajax({
                    type: "POST",
                    data: "n_tarjeta=" + n_tarjeta.value,
                    url: "../../procesos/validar_tarjeta.php",
                    success: function(r) {
                        datos = jQuery.parseJSON(r);
                        if (datos == null) {
                            $('#cvv').val('');
                            $('#fechaV').val('');
                            $('#titular_tarjeta').val('');
                            $('#bancoT').val('');
                            $('#cvv_h').val('');
                            $('#fechaV_h').val('');
                            $('#titular_tarjeta_h').val('');
                            $('#bancoT_h').val('');
                            $("#bancoT").css('background-color', 'white');
                            $('#alert').val('1');
                            alertify.success('Número de Tarjeta no existente en la BD');
                        } else {
                            if (datos[0]['n_tarjeta'] == null) {
                                $('#cvv').val('');
                                $('#fechaV').val('');
                                $('#titular_tarjeta').val('');
                                $('#bancoT').val('');
                                $('#cvv_h').val('');
                                $('#fechaV_h').val('');
                                $('#titular_tarjeta_h').val('');
                                $('#bancoT_h').val('');
                                $("#bancoT").css('background-color', 'white');
                                $('#alert').val('1');
                                alertify.success('Número de Tarjeta no existente en la BD');
                            } else {
                                $("#tablaPE  tbody").empty();
                                for (let index = 0; index < datos.length; index++) {
                                    $.ajax({
                                        type: "POST",
                                        data: "id_tarjeta=" + datos[index].id_tarjeta,
                                        url: "../../procesos/ver_poliza_tarjeta.php",
                                        success: function(r) {
                                            datos1 = jQuery.parseJSON(r);

                                            var d = new Date();
                                            var strDate = d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate();

                                            var f = new Date(datos[index]['fechaV']);
                                            f.setDate(f.getDate() + 1)
                                            var f_venc = f.getDate() + "-" + (f.getMonth() + 1) + "-" + f.getFullYear();
                                            if (datos1 == null) {
                                                if ((new Date(strDate).getTime() <= new Date(datos[index]['fechaV']).getTime())) {
                                                    var htmlTags = '<tr ondblclick="selecTarjeta(' + datos[index]['id_tarjeta'] + ')" style="cursor:pointer">' +
                                                        '<td style="color:green">' + datos[index]['n_tarjeta'] + '</td>' +
                                                        '<td nowrap>' + datos[index]['cvv'] + '</td>' +
                                                        '<td nowrap>' + f_venc + '</td>' +
                                                        '<td>' + datos[index]['nombre_titular'] + '</td>' +
                                                        '<td nowrap>' + datos[index]['banco'] + '</td>' +
                                                        '<td nowrap>No</td>' +

                                                        '<td nowrap><a onclick="selecTarjeta(' + datos[index]['id_tarjeta'] + ')" style="color:black" data-toggle="tooltip" data-placement="top" title="Añadir Tarjeta" class="btn dusty-grass-gradient btn-sm"><i class="fa fa-check-square" ></i></a></td>' +
                                                        '</tr>';
                                                } else {
                                                    var htmlTags = '<tr ondblclick="selecTarjeta(' + datos[index]['id_tarjeta'] + ')" style="cursor:pointer">' +
                                                        '<td style="color:red">' + datos[index]['n_tarjeta'] + '</td>' +
                                                        '<td nowrap>' + datos[index]['cvv'] + '</td>' +
                                                        '<td nowrap>' + f_venc + '</td>' +
                                                        '<td>' + datos[index]['nombre_titular'] + '</td>' +
                                                        '<td nowrap>' + datos[index]['banco'] + '</td>' +
                                                        '<td nowrap>No</td>' +

                                                        '<td nowrap><a onclick="selecTarjeta(' + datos[index]['id_tarjeta'] + ')" style="color:black" data-toggle="tooltip" data-placement="top" title="Añadir Tarjeta" class="btn dusty-grass-gradient btn-sm"><i class="fa fa-check-square"></i></a></td>' +
                                                        '</tr>';
                                                }
                                            } else {
                                                if ((new Date(strDate).getTime() <= new Date(datos[index]['fechaV']).getTime())) {
                                                    var htmlTags = '<tr ondblclick="selecTarjeta(' + datos[index]['id_tarjeta'] + ')" style="cursor:pointer">' +
                                                        '<td style="color:green">' + datos[index]['n_tarjeta'] + '</td>' +
                                                        '<td nowrap>' + datos[index]['cvv'] + '</td>' +
                                                        '<td nowrap>' + f_venc + '</td>' +
                                                        '<td>' + datos[index]['nombre_titular'] + '</td>' +
                                                        '<td nowrap>' + datos[index]['banco'] + '</td>' +
                                                        '<td nowrap>Sí</td>' +

                                                        '<td nowrap><a onclick="selecTarjeta(' + datos[index]['id_tarjeta'] + ')" style="color:black" data-toggle="tooltip" data-placement="top" title="Añadir Tarjeta" class="btn dusty-grass-gradient btn-sm"><i class="fa fa-check-square" ></i></a><a href="../b_polizaT.php?id_tarjeta=' + datos[index]['id_tarjeta'] + '" target="_blank" style="color:white" data-toggle="tooltip" data-placement="top" title="Ver Pólizas" class="btn blue-gradient btn-sm" ><i class="fa fa-eye"></i></a></td>' +
                                                        '</tr>';

                                                } else {

                                                    var htmlTags = '<tr ondblclick="selecTarjeta(' + datos[index]['id_tarjeta'] + ')" style="cursor:pointer">' +
                                                        '<td style="color:red">' + datos[index]['n_tarjeta'] + '</td>' +
                                                        '<td nowrap>' + datos[index]['cvv'] + '</td>' +
                                                        '<td nowrap>' + f_venc + '</td>' +
                                                        '<td>' + datos[index]['nombre_titular'] + '</td>' +
                                                        '<td nowrap>' + datos[index]['banco'] + '</td>' +
                                                        '<td nowrap>Sí</td>' +

                                                        '<td nowrap><a onclick="selecTarjeta(' + datos[index]['id_tarjeta'] + ')" style="color:black" data-toggle="tooltip" data-placement="top" title="Añadir Tarjeta" class="btn dusty-grass-gradient btn-sm"><i class="fa fa-check-square"></i></a><a href="../b_polizaT.php?id_tarjeta=' + datos[index]['id_tarjeta'] + '" target="_blank" style="color:white" data-toggle="tooltip" data-placement="top" title="Ver Pólizas" class="btn blue-gradient btn-sm" ><i class="fa fa-eye"></i></a></td>' +
                                                        '</tr>';
                                                }
                                            }
                                            $('#tablaPE tbody').append(htmlTags);
                                        }
                                    });
                                }
                                $('#tarjetaexistente').modal({
                                    backdrop: 'static',
                                    keyboard: false
                                });
                                $('#tarjetaexistente').modal('show');
                            }
                        }
                    }
                });
            }

            async function selecTarjeta(id_tarjeta) {
                await $.ajax({
                    type: "POST",
                    data: "id_tarjeta=" + id_tarjeta,
                    url: "../../procesos/b_tarjeta.php",
                    success: function(r) {
                        datos = jQuery.parseJSON(r);

                        if (datos[0]['id_tarjeta'] == null) {
                            alert('seleccione una tarjeta');
                        } else {
                            $('#n_tarjeta').val(datos[0]['n_tarjeta']);
                            $('#cvv').val(datos[0]['cvv']);
                            $('#titular_tarjeta').val(datos[0]['nombre_titular']);
                            $('#bancoT').val(datos[0]['banco']);
                            $('#n_tarjeta_h').val(datos[0]['n_tarjeta_h']);
                            $('#cvv_h').val(datos[0]['cvv']);
                            $('#titular_tarjeta_h').val(datos[0]['nombre_titular']);
                            $('#bancoT_h').val(datos[0]['banco']);
                            $('#id_tarjeta').val(datos[0]['id_tarjeta']);
                            $('#alert').val('1');

                            //----------DATES-----------
                            $('#fechaV').val(datos[0]['fechaV']);
                            $('#fechaV_h').val(datos[0]['fechaV']);
                            var mydate = new Date($('#fechaV').val());
                            mydate.setDate(mydate.getDate() + 1)
                            if (10 > mydate.getMonth() + 1 > 0) {
                                var mes = '0' + (mydate.getMonth() + 1).toString()
                            } else {
                                var mes = (mydate.getMonth() + 1)
                            }
                            if (10 > mydate.getDate() > 0) {
                                var dia = '0' + (mydate.getDate()).toString()
                            } else {
                                var dia = mydate.getDate()
                            }

                            $('#fechaV').val((mydate.getFullYear() + 1) + '-' + mes + '-' + dia);
                            var mydate1 = new Date($('#fechaV_h').val());
                            mydate1.setDate(mydate1.getDate() + 1)
                            if (10 > mydate1.getMonth() + 1 > 0) {
                                var mes1 = '0' + (mydate1.getMonth() + 1).toString()
                            } else {
                                var mes1 = (mydate1.getMonth() + 1)
                            }
                            if (10 > mydate1.getDate() > 0) {
                                var dia1 = '0' + (mydate1.getDate()).toString()
                            } else {
                                var dia1 = mydate1.getDate()
                            }

                            $('#fechaV_h').val((mydate1.getFullYear() + 1) + '-' + mes1 + '-' + dia1);
                            var fechaV = ($('#fechaV').val()).split('-').reverse().join('-');
                            var fechaV_h = ($('#fechaV_h').val()).split('-').reverse().join('-');
                            $('#fechaV').val(fechaV);
                            $('#fechaV_h').val(fechaV_h);
                            $('#fechaV').pickadate('picker').set('select', fechaV);
                            $('#fechaV_h').pickadate('picker').set('select', fechaV_h);
                            //-----

                            alertify.success('Tarjeta Existente');

                            $('#tarjetaexistente').modal('hide');
                        }
                    }
                });
            }

            function selecTarjetaNew() {
                $('#cvv').val('');
                $('#fechaV').val('');
                $('#titular_tarjeta').val('');
                $('#bancoT').val('');
                $('#cvv_h').val('');
                $('#fechaV_h').val('');
                $('#titular_tarjeta_h').val('');
                $('#bancoT_h').val('');
                $('#tarjetaexistente').modal('hide');
                $('#alert').val('1');
                $('#id_tarjeta').val('0');

                alertify.success('Ingrese los datos faltantes de la Tarjeta');
            }
        </script>
</body>

</html>