<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
require_once '../Model/Poliza.php';

$obj = new Poliza();

$fecha_min = $obj->get_fecha_min_max('MIN', 'f_desdepoliza', 'poliza');
$fecha_max = $obj->get_fecha_min_max('MAX', 'f_desdepoliza', 'poliza');

$fhoy = date("Y");

//FECHA MAYORES A 2024
$dateString = $fecha_max[0]["MAX(f_desdepoliza)"];
// Parse a textual date/datetime into a Unix timestamp
$date = new DateTime($dateString);
$format = 'Y';
// Parse a textual date/datetime into a Unix timestamp
$date = new DateTime($dateString);
// Print it
$fecha_max = $date->format($format);

$fecha_min = date('Y', strtotime($fecha_min[0]["MIN(f_desdepoliza)"]));

$asesor = $obj->get_ejecutivo();
$cia = $obj->get_distinct_element('nomcia', 'dcia');
$ramo = $obj->get_distinct_element('nramo', 'dramo');


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . '\layout\header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . '\layout\navigation.php'; ?>
    <!--
    <div class="landing darken-3" style="background-image: url(<?= constant('URL') . '/assets/img/logo2.png'; ?>);">
        <div class="container">
            <br><br><br>
            <h1 class="text-center font-weight-bold">Bienvenido <i class="material-icons">person</i></h1>
        </div>
    </div>-->
    <br><br><br><br><br><br>

    <div>
        <div class="card">

            <div id="carga" class="d-flex justify-content-center align-items-center">
                <div class="spinner-grow text-info" style="width: 7rem; height: 7rem;"></div>
            </div>

            <div class="card-header p-5 animated bounceInDown">
                <a href="javascript:history.back(-1);" data-tooltip="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                    <- Regresar</a> <br><br>
                        <div class="row ml-5 mr-5">
                            <h1 class="font-weight-bold ">Lista de Pólizas</h1>
                            <?php if ($_SESSION['id_permiso'] != 3) { ?>
                                <a href="add/crear_poliza.php" class="btn blue-gradient ml-auto"><i class="fa fa-plus-circle" aria-hidden="true"></i> Nueva Póliza</a>
                            <?php } ?>
                        </div>

                        <div class="col-md-8 mx-auto">
                            <form action="" class="form-horizontal">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label align="left">Año Vigencia Desde Seguro:</label>
                                        <select class="form-control selectpicker" name="anio[]" id="anio" multiple data-style="btn-white" data-size="13" data-header="Seleccione Año" data-actions-box="true" data-live-search="true">
                                            <?php for ($i = $fecha_min; $i <= $fecha_max; $i++) { ?>
                                                <option value="<?= $fecha_min; ?>"><?= $fecha_min; ?></option>
                                            <?php $fecha_min = $fecha_min + 1;
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Mes Vigencia Desde Seguro:</label>
                                        <select class="form-control selectpicker" name="mes[]" id="mes" multiple data-style="btn-white" data-header="Seleccione Mes" data-actions-box="true" data-live-search="true">
                                            <option value="1">Enero</option>
                                            <option value="2">Febrero</option>
                                            <option value="3">Marzo</option>
                                            <option value="4">Abril</option>
                                            <option value="5">Mayo</option>
                                            <option value="6">Junio</option>
                                            <option value="7">Julio</option>
                                            <option value="8">Agosto</option>
                                            <option value="9">Septiembre</option>
                                            <option value="10">Octubre</option>
                                            <option value="11">Noviembre</option>
                                            <option value="12">Diciembre</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Cía:</label>
                                        <select class="form-control selectpicker" name="cia[]" multiple data-style="btn-white" data-header="Seleccione Cía" data-actions-box="true" data-live-search="true">
                                            <?php
                                            for ($i = 0; $i < sizeof($cia); $i++) {
                                            ?>
                                                <option value="<?= $cia[$i]["nomcia"]; ?>"><?= ($cia[$i]["nomcia"]); ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Asesor:</label>
                                        <select class="form-control selectpicker" name="asesor[]" multiple data-style="btn-white" data-header="Seleccione el Asesor" data-size="12" data-actions-box="true" data-live-search="true">
                                            <?php
                                            for ($i = 0; $i < sizeof($asesor); $i++) {
                                            ?>
                                                <option value="<?= $asesor[$i]["cod"]; ?>"><?= utf8_encode($asesor[$i]["nombre"]); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label>Ramo:</label>
                                        <select class="form-control selectpicker custom-select" name="ramo[]" multiple data-style="btn-white" data-header="Seleccione Ramo" data-actions-box="true" data-live-search="true">
                                            <?php
                                            for ($i = 0; $i < sizeof($ramo); $i++) {
                                            ?>
                                                <option value="<?= $ramo[$i]["nramo"]; ?>"><?= ($ramo[$i]["nramo"]); ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <center><button type="submit" class="btn aqua-gradient btn-rounded btn-lg">Buscar</button></center>
                            </form>
                        </div>
            </div>
            <hr />


            <div class="card-body p-5" id="tablaLoad" hidden="true">



                <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('table', 'Listado de Pólizas')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../assets/img/excel.png" width="60" alt=""></a></center>

                <div class="table-responsive-xl">
                    <table class="table table-hover table-striped table-bordered" id="table" width="100%">
                        <thead class="blue-gradient text-white">
                            <tr>
                                <th hidden>f_poliza</th>
                                <th hidden>id</th>
                                <th>N° Póliza</th>
                                <th>Nombre Asesor</th>
                                <th>Cía</th>
                                <th>F Desde Seguro</th>
                                <th>F Hasta Seguro</th>
                                <th style="background-color: #E54848;">Prima Suscrita</th>
                                <th>Nombre Titular</th>
                                <th>PDF</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php

                            $polizas = $obj->getPolizas();

                            foreach ($polizas as $poliza) {
                                $currency = ($poliza['currency'] == 1) ? "$ " : "Bs ";

                                $originalDesde = $poliza['f_desdepoliza'];
                                $newDesde = date("Y/m/d", strtotime($originalDesde));
                                $originalHasta = $poliza['f_hastapoliza'];
                                $newHasta = date("Y/m/d", strtotime($originalHasta));

                                $nombre = $poliza['nombre_t'] . ' ' . $poliza['apellido_t'];
                            ?>
                                <tr style="cursor: pointer;">
                                    <td hidden><?= $poliza['f_poliza']; ?></td>
                                    <td hidden><?= $poliza['id_poliza']; ?></td>

                                    <?php if ($poliza['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                        <td style="color: #2B9E34;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                    <?php } else { ?>
                                        <td style="color: #E54848;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                    <?php } ?>
                                    <td><?= $poliza['nombre']; ?></td>
                                    <td><?= $poliza['nomcia']; ?></td>
                                    <td><?= $newDesde; ?></td>
                                    <td><?= $newHasta; ?></td>
                                    <td class="text-right"><?= $currency . number_format($poliza['prima'], 2); ?></td>
                                    <td><?= utf8_encode($nombre); ?></td>
                                    <?php if ($poliza['pdf'] == 1) { ?>
                                        <td><a href="../Controller/download.php?id_poliza=<?= $poliza['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank" style="float: right"><img src="../assets/img/pdf-logo.png" width="30" id="pdf"></a></td>
                                    <?php } else { ?>
                                        <td></td>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                        </tbody>

                        <tfoot>
                            <tr>
                                <th hidden>f_poliza</th>
                                <th hidden>id</th>
                                <th>N° Póliza</th>
                                <th>Nombre Asesor</th>
                                <th>Cía</th>
                                <th>F Desde Seguro</th>
                                <th>F Hasta Seguro</th>
                                <th>Prima Suscrita $<?= number_format($totalprima, 2); ?></th>
                                <th>Nombre Titular</th>
                                <th>PDF</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>





    <?php require_once dirname(__DIR__) . '\layout\footer_b.php'; ?>

    <?php require_once dirname(__DIR__) . '\layout\footer.php'; ?>
</body>

</html>