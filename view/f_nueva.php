<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'f_nueva';

require_once '../Controller/Poliza.php';
$totalPrimaNR = 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'header.php'; ?>
    <style>
        .alertify .ajs-header {
            background-color: red;
        }
    </style>
</head>

<body>

    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'navigation.php'; ?>
    <br><br><br><br><br><br>

    <div>
        <div class="card">

            <div id="carga" class="d-flex justify-content-center align-items-center">
                <div class="spinner-grow text-info" style="width: 7rem; height: 7rem;"></div>
            </div>

            <?php if ($_SESSION['id_permiso'] != 3) { ?>
                <div class="card-header p-5 animated bounceInDown" id="headerload" hidden="true">
                    <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                        <- Regresar</a> <br><br>
                            <div class="ml-5 mr-5">
                                <h1 class="font-weight-bold">Resultado de Búsqueda de Pólizas Emitidas</h1>
                                <h2 class="font-weight-bold">Desde: <font style="color:red"><?= $desdeP; ?></font> Hasta: <font style="color:red"><?= $hastaP; ?></font>
                                </h2>
                            </div>
                </div>


                <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">
                    <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('tableE', 'Listado de Pólizas')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../assets/img/excel.png" width="60" alt=""></a></center>

                    <h1 class="font-weight-bold text-black-50">Pólizas Nuevas</h1>
                    <div class="table-responsive-xl">
                        <table class="table table-hover table-striped table-bordered" id="table" width="100%">
                            <thead class="blue-gradient text-white text-center">
                                <tr>
                                    <th hidden>f_poliza</th>
                                    <th hidden>id</th>
                                    <th>-</th>
                                    <th>N° Póliza</th>
                                    <th>Nombre Asesor</th>
                                    <th>Cía</th>
                                    <th>F Desde Seguro</th>
                                    <th>F Hasta Seguro</th>
                                    <th>Prima Suscrita</th>
                                    <th>Prima Cobrada</th>
                                    <th style="background-color: #E54848;">Prima Pendiente</th>
                                    <th>Nombre Titular</th>
                                    <th>PDF</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                foreach ($polizas as $poliza) {
                                    if ($poliza['id_titular'] == 0) {
                                    } else {
                                        $cont = $cont + 1;
                                        $currency = ($poliza['currency'] == 1) ? "$ " : "Bs ";

                                        $totalsuma = $totalsuma + $poliza['sumaasegurada'];
                                        $totalprima = $totalprima + $poliza['prima'];

                                        $newDesde = date("Y/m/d", strtotime($poliza['f_desdepoliza']));
                                        $newHasta = date("Y/m/d", strtotime($poliza['f_hastapoliza']));

                                        $nombre = $poliza['nombre_t'] . ' ' . $poliza['apellido_t'];

                                        $no_renov = $obj->verRenov1($poliza['id_poliza']);

                                        $primac = $obj->obetnComisiones($poliza['id_poliza']);

                                        $totalprimaC = $totalprimaC + $primac[0]['SUM(prima_com)'];
                                        $ppendiente = $poliza['prima'] - $primac[0]['SUM(prima_com)'];
                                        $ppendiente = number_format($ppendiente, 2);
                                        if ($ppendiente >= -0.10 && $ppendiente <= 0.10) {
                                            $ppendiente = 0;
                                        }
                                ?>
                                        <tr style="cursor: pointer;">
                                            <td hidden><?= $poliza['f_poliza']; ?></td>
                                            <td hidden><?= $poliza['id_poliza']; ?></td>

                                            <?php if ($poliza['id_tpoliza'] == 1) { ?>
                                                <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Nueva">N</td>
                                            <?php }
                                            if ($poliza['id_tpoliza'] == 2) { ?>
                                                <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Renovación">R</td>
                                            <?php }
                                            if ($poliza['id_tpoliza'] == 3) { ?>
                                                <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Traspaso de Cartera">T</td>
                                            <?php } ?>

                                            <?php if ($no_renov[0]['no_renov'] != 1) {
                                                if ($poliza['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                                    <td style="color: #2B9E34;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                                <?php } else { ?>
                                                    <td style="color: #E54848;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                                <?php }
                                            } else { ?>
                                                <td style="color: #4a148c;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                            <?php } ?>

                                            <td><?= $poliza['nombre']; ?></td>
                                            <td><?= $poliza['nomcia']; ?></td>
                                            <td><?= $newDesde; ?></td>
                                            <td><?= $newHasta; ?></td>
                                            <td class="text-right"><?= $currency . number_format($poliza['prima'], 2); ?></td>
                                            <td style="text-align: right"><?= $currency . number_format($primac[0]['SUM(prima_com)'], 2); ?></td>

                                            <?php if ($no_renov[0]['no_renov'] != 1) {
                                                if ($ppendiente > 0) { ?>
                                                    <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#F53333;font-size: 16px"><?= $currency . $ppendiente; ?></td>
                                                <?php }
                                                if ($ppendiente == 0) { ?>
                                                    <td style="background-color: #D9D9D9 ;color:black;text-align: right;font-weight: bold;"><?= $currency . $ppendiente; ?></td>
                                                <?php }
                                                if ($ppendiente < 0) { ?>
                                                    <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#2B9E34;font-size: 16px"><?= $currency . $ppendiente; ?></td>
                                                <?php }
                                            } else { ?>
                                                <td style="background-color: #D9D9D9 ;text-align: right;font-weight: bold;color:#4a148c"><?= $currency . $ppendiente; ?></td>
                                            <?php } ?>

                                            <td><?= ($nombre); ?></td>

                                            <?php if ($poliza['pdf'] == 1) { ?>
                                                <td class="text-center"><a href="download.php?id_poliza=<?= $poliza['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../assets/img/pdf-logo.png" width="25" id="pdf"></a></td>
                                                <?php } else {
                                                if ($poliza['nramo'] == 'Vida') {
                                                    $vRenov = $obj->verRenov3($poliza['id_poliza']);
                                                    if ($vRenov != 0) {
                                                        if ($vRenov[0]['pdf'] != 0) {
                                                            $poliza_pdf_vida = $obj->get_pdf_vida_id($vRenov[0]['id_poliza']); ?>
                                                            <td class="text-center"><a href="download.php?id_poliza=<?= $poliza_pdf_vida[0]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../assets/img/pdf-logo.png" width="25" id="pdf"></a></td>
                                                            <?php } else {
                                                            $poliza_pdf_vida = $obj->get_pdf_vida($vRenov[0]['cod_poliza'], $poliza['id_cia'], $poliza['f_hastapoliza']);
                                                            if ($poliza_pdf_vida[0]['pdf'] == 1) {  ?>
                                                                <td class="text-center"><a href="download.php?id_poliza=<?= $poliza_pdf_vida[0]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../assets/img/pdf-logo.png" width="25" id="pdf"></a></td>
                                                            <?php } else { ?>
                                                                <td></td>
                                                            <?php }
                                                        }
                                                    } else {
                                                        $poliza_pdf_vida = $obj->get_pdf_vida($poliza['cod_poliza'], $poliza['id_cia'], $poliza['f_hastapoliza']);
                                                        if ($poliza_pdf_vida[0]['pdf'] == 1) { ?>
                                                            <td class="text-center"><a href="download.php?id_poliza=<?= $poliza_pdf_vida[0]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../assets/img/pdf-logo.png" width="25" id="pdf"></a></td>
                                                        <?php } else { ?>
                                                            <td></td>
                                                    <?php }
                                                    }
                                                } else { ?>
                                                    <td></td>
                                                <?php } ?>
                                            <?php } ?>

                                        </tr>
                                <?php }
                                } ?>
                            </tbody>

                            <tfoot class="text-center">
                                <tr>
                                    <th hidden>f_poliza</th>
                                    <th hidden>id</th>
                                    <th></th>
                                    <th>N° Póliza</th>
                                    <th>Nombre Asesor</th>
                                    <th>Cía</th>
                                    <th>F Desde Seguro</th>
                                    <th>F Hasta Seguro</th>
                                    <th style="font-weight: bold" class="text-right">Prima Suscrita $<?= number_format($totalprima, 2); ?></th>
                                    <th style="font-weight: bold" class="text-right">Prima Cobrada $<?= number_format($totalprimaC, 2); ?></th>
                                    <th style="font-weight: bold" class="text-right">Prima Pendiente $<?= number_format($totalprima - $totalprimaC, 2); ?></th>
                                    <th>Nombre Titular</th>
                                    <th>PDF</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- TABLA EXCEL -->
                    <div class="table-responsive-xl" hidden>
                        <table class="table table-hover table-striped table-bordered" id="tableE" width="100%">
                            <thead class="text-center">
                                <tr>
                                    <th style="background-color: #4285F4; color: white">Fecha de Producción</th>
                                    <th style="background-color: #4285F4; color: white">N° Póliza</th>
                                    <th style="background-color: #4285F4; color: white">Nombre Asesor</th>
                                    <th style="background-color: #4285F4; color: white">Cía</th>
                                    <th style="background-color: #4285F4; color: white">F Desde Seguro</th>
                                    <th style="background-color: #4285F4; color: white">F Hasta Seguro</th>
                                    <th style="background-color: #4285F4; color: white">Prima Suscrita</th>
                                    <th style="background-color: #4285F4; color: white">Prima Cobrada</th>
                                    <th style="background-color: #E54848; color: white">Prima Pendiente</th>
                                    <th style="background-color: #4285F4; color: white">Nombre Titular</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $totalprima = 0;
                                $totalprimaC = 0;
                                $cont = 0;
                                foreach ($polizas as $poliza) {
                                    if ($poliza['id_titular'] == 0) {
                                    } else {
                                        $cont = $cont + 1;
                                        $currency = ($poliza['currency'] == 1) ? "$ " : "Bs ";

                                        $totalsuma = $totalsuma + $poliza['sumaasegurada'];
                                        $totalprima = $totalprima + $poliza['prima'];

                                        $newDesde = date("Y/m/d", strtotime($poliza['f_desdepoliza']));
                                        $newHasta = date("Y/m/d", strtotime($poliza['f_hastapoliza']));

                                        $nombre = $poliza['nombre_t'] . ' ' . $poliza['apellido_t'];

                                        $no_renov = $obj->verRenov1($poliza['id_poliza']);

                                        $primac = $obj->obetnComisiones($poliza['id_poliza']);

                                        $totalprimaC = $totalprimaC + $primac[0]['SUM(prima_com)'];
                                        $ppendiente = $poliza['prima'] - $primac[0]['SUM(prima_com)'];
                                        $ppendiente = number_format($ppendiente, 2);
                                        if ($ppendiente >= -0.10 && $ppendiente <= 0.10) {
                                            $ppendiente = 0;
                                        }
                                ?>
                                        <tr style="cursor: pointer;">
                                            <td><?= $poliza['f_poliza']; ?></td>

                                            <?php if ($no_renov[0]['no_renov'] != 1) {
                                                if ($poliza['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                                    <td style="color: #2B9E34;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                                <?php } else { ?>
                                                    <td style="color: #E54848;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                                <?php }
                                            } else { ?>
                                                <td style="color: #4a148c;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                            <?php } ?>

                                            <td><?= $poliza['nombre']; ?></td>
                                            <td><?= $poliza['nomcia']; ?></td>
                                            <td><?= $newDesde; ?></td>
                                            <td><?= $newHasta; ?></td>
                                            <td style="text-align: right"><?= $currency . number_format($poliza['prima'], 2); ?></td>
                                            <td style="text-align: right"><?= $currency . number_format($primac[0]['SUM(prima_com)'], 2); ?></td>

                                            <?php if ($no_renov[0]['no_renov'] != 1) {
                                                if ($ppendiente > 0) { ?>
                                                    <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#F53333;font-size: 16px"><?= $currency . $ppendiente; ?></td>
                                                <?php }
                                                if ($ppendiente == 0) { ?>
                                                    <td style="background-color: #D9D9D9 ;color:black;text-align: right;font-weight: bold;"><?= $currency . $ppendiente; ?></td>
                                                <?php }
                                                if ($ppendiente < 0) { ?>
                                                    <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#2B9E34;font-size: 16px"><?= $currency . $ppendiente; ?></td>
                                                <?php }
                                            } else { ?>
                                                <td style="background-color: #D9D9D9 ;text-align: right;font-weight: bold;color:#4a148c"><?= $currency . $ppendiente; ?></td>
                                            <?php } ?>

                                            <td><?= ($nombre); ?></td>

                                        </tr>
                                <?php }
                                } ?>
                            </tbody>

                            <tfoot class="text-center">
                                <tr>
                                    <th>Fecha de Producción</th>
                                    <th>N° Póliza</th>
                                    <th>Nombre Asesor</th>
                                    <th>Cía</th>
                                    <th>F Desde Seguro</th>
                                    <th>F Hasta Seguro</th>
                                    <th style="font-weight: bold" class="text-right">Prima Suscrita $<?= number_format($totalprima, 2); ?></th>
                                    <th style="font-weight: bold" class="text-right">Prima Cobrada $<?= number_format($totalprimaC, 2); ?></th>
                                    <th style="font-weight: bold" class="text-right">Prima Pendiente $<?= number_format($totalprima - $totalprimaC, 2); ?></th>
                                    <th>Nombre Titular</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>


                    

                    <p class="h1 text-center">Total de Pólizas Nuevas</p>
                    <p class="h1 text-center text-danger"><?php echo $cont; ?></p>

                    <hr>

                    <h1 class="font-weight-bold text-black-50">Pólizas Renovadas</h1>
                    <div class="table-responsive-xl">
                        <table class="table table-hover table-striped table-bordered" id="tableRenovF" width="100%">
                            <thead class="blue-gradient text-white text-center">
                                <tr>
                                    <th hidden>f_poliza</th>
                                    <th hidden>id</th>
                                    <th></th>
                                    <th>N° Póliza</th>
                                    <th>Nombre Asesor</th>
                                    <th>Cía</th>
                                    <th>F Desde Seguro</th>
                                    <th>F Hasta Seguro</th>
                                    <th>Prima Suscrita</th>
                                    <th>Prima Cobrada</th>
                                    <th style="background-color: #E54848;">Prima Pendiente</th>
                                    <th>Nombre Titular</th>
                                    <th>PDF</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $totalPrimaNR = $totalprima;
                                $totalprima = 0;
                                $totalprimaC = 0;
                                $cont = 0;
                                foreach ($polizasR as $poliza) {
                                    if ($poliza['id_titular'] == 0) {
                                    } else {
                                        $cont = $cont + 1;
                                        $currency = ($poliza['currency'] == 1) ? "$ " : "Bs ";
                                        
                                        $newDesde = date("Y/m/d", strtotime($poliza['f_desdepoliza']));
                                        $newHasta = date("Y/m/d", strtotime($poliza['f_hastapoliza']));

                                        $nombre = $poliza['nombre_t'] . ' ' . $poliza['apellido_t'];
                                        $no_renov = $obj->verRenov1($poliza['id_poliza']);
                                        
                                        if ($no_renov[0]['no_renov'] != 1) {
                                            $primac = $obj->obetnComisiones($poliza['id_poliza']);

                                            $totalsuma = $totalsuma + $poliza['sumaasegurada'];
                                            $totalprima = $totalprima + $poliza['prima'];

                                            $totalprimaC = $totalprimaC + $primac[0]['SUM(prima_com)'];
                                            $ppendiente = $poliza['prima'] - $primac[0]['SUM(prima_com)'];
                                            $ppendiente = number_format($ppendiente, 2);
                                            if ($ppendiente >= -0.10 && $ppendiente <= 0.10) {
                                                $ppendiente = 0;
                                            }
                                ?>
                                        <tr style="cursor: pointer;">
                                            <td hidden><?= $poliza['f_poliza']; ?></td>
                                            <td hidden><?= $poliza['id_poliza']; ?></td>

                                            <?php if ($poliza['id_tpoliza'] == 1) { ?>
                                                <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Nueva">N</td>
                                            <?php }
                                            if ($poliza['id_tpoliza'] == 2) { ?>
                                                <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Renovación">R</td>
                                            <?php }
                                            if ($poliza['id_tpoliza'] == 3) { ?>
                                                <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Traspaso de Cartera">T</td>
                                            <?php } ?>

                                            <?php if ($no_renov[0]['no_renov'] != 1) {
                                                if ($poliza['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                                    <td style="color: #2B9E34;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                                <?php } else { ?>
                                                    <td style="color: #E54848;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                                <?php }
                                            } else { ?>
                                                <td style="color: #4a148c;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                            <?php } ?>

                                            <td><?= $poliza['nombre']; ?></td>
                                            <td><?= $poliza['nomcia']; ?></td>
                                            <td><?= $newDesde; ?></td>
                                            <td><?= $newHasta; ?></td>
                                            <td class="text-right"><?= $currency . number_format($poliza['prima'], 2); ?></td>
                                            <td style="text-align: right"><?= $currency . number_format($primac[0]['SUM(prima_com)'], 2); ?></td>

                                            <?php if ($no_renov[0]['no_renov'] != 1) {
                                                if ($ppendiente > 0) { ?>
                                                    <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#F53333;font-size: 16px"><?= $currency . $ppendiente; ?></td>
                                                <?php }
                                                if ($ppendiente == 0) { ?>
                                                    <td style="background-color: #D9D9D9 ;color:black;text-align: right;font-weight: bold;"><?= $currency . $ppendiente; ?></td>
                                                <?php }
                                                if ($ppendiente < 0) { ?>
                                                    <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#2B9E34;font-size: 16px"><?= $currency . $ppendiente; ?></td>
                                                <?php }
                                            } else { ?>
                                                <td style="background-color: #D9D9D9 ;text-align: right;font-weight: bold;color:#4a148c"><?= $currency . $ppendiente; ?></td>
                                            <?php } ?>

                                            <td><?= ($nombre); ?></td>

                                            <?php if ($poliza['pdf'] == 1) { ?>
                                                <td class="text-center"><a href="download.php?id_poliza=<?= $poliza['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../assets/img/pdf-logo.png" width="25" id="pdf"></a></td>
                                                <?php } else {
                                                if ($poliza['nramo'] == 'Vida') {
                                                    $vRenov = $obj->verRenov3($poliza['id_poliza']);
                                                    if ($vRenov != 0) {
                                                        if ($vRenov[0]['pdf'] != 0) {
                                                            $poliza_pdf_vida = $obj->get_pdf_vida_id($vRenov[0]['id_poliza']); ?>
                                                            <td class="text-center"><a href="download.php?id_poliza=<?= $poliza_pdf_vida[0]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../assets/img/pdf-logo.png" width="25" id="pdf"></a></td>
                                                            <?php } else {
                                                            $poliza_pdf_vida = $obj->get_pdf_vida($vRenov[0]['cod_poliza'], $poliza['id_cia'], $poliza['f_hastapoliza']);
                                                            if ($poliza_pdf_vida[0]['pdf'] == 1) {  ?>
                                                                <td class="text-center"><a href="download.php?id_poliza=<?= $poliza_pdf_vida[0]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../assets/img/pdf-logo.png" width="25" id="pdf"></a></td>
                                                            <?php } else { ?>
                                                                <td></td>
                                                            <?php }
                                                        }
                                                    } else {
                                                        $poliza_pdf_vida = $obj->get_pdf_vida($poliza['cod_poliza'], $poliza['id_cia'], $poliza['f_hastapoliza']);
                                                        if ($poliza_pdf_vida[0]['pdf'] == 1) { ?>
                                                            <td class="text-center"><a href="download.php?id_poliza=<?= $poliza_pdf_vida[0]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../assets/img/pdf-logo.png" width="25" id="pdf"></a></td>
                                                        <?php } else { ?>
                                                            <td></td>
                                                    <?php }
                                                    }
                                                } else { ?>
                                                    <td></td>
                                                <?php } ?>
                                            <?php } ?>

                                        </tr>
                                <?php } else {$cont=$cont-1;} }
                                } ?>
                            </tbody>

                            <tfoot class="text-center">
                                <tr>
                                    <th hidden>f_poliza</th>
                                    <th hidden>id</th>
                                    <th></th>
                                    <th>N° Póliza</th>
                                    <th>Nombre Asesor</th>
                                    <th>Cía</th>
                                    <th>F Desde Seguro</th>
                                    <th>F Hasta Seguro</th>
                                    <th style="font-weight: bold" class="text-right">Prima Suscrita $<?= number_format($totalprima, 2); ?></th>
                                    <th style="font-weight: bold" class="text-right">Prima Cobrada $<?= number_format($totalprimaC, 2); ?></th>
                                    <th style="font-weight: bold" class="text-right">Prima Pendiente $<?= number_format($totalprima - $totalprimaC, 2); ?></th>
                                    <th>Nombre Titular</th>
                                    <th>PDF</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <p class="h1 text-center">Total de Pólizas Renovadas</p>
                    <p class="h1 text-center text-danger"><?php echo $cont; ?></p>



                    <hr>

                    <h1 class="font-weight-bold text-black-50">Pólizas Anuladas</h1>
                    <div class="table-responsive-xl">
                        <table class="table table-hover table-striped table-bordered" id="tableAnulada" width="100%">
                            <thead class="blue-gradient text-white text-center">
                                <tr>
                                    <th hidden>f_poliza</th>
                                    <th hidden>id</th>
                                    <th></th>
                                    <th>N° Póliza</th>
                                    <th>Nombre Asesor</th>
                                    <th>Cía</th>
                                    <th>F Desde Seguro</th>
                                    <th>F Hasta Seguro</th>
                                    <th>Prima Suscrita</th>
                                    <th>Prima Cobrada</th>
                                    <th style="background-color: #E54848;">Prima Pendiente</th>
                                    <th>Nombre Titular</th>
                                    <th>PDF</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $totalPrimaNR = $totalPrimaNR + $totalprima;
                                $totalprima = 0;
                                $totalprimaC = 0;
                                $cont = 0;
                                foreach ($polizasA as $poliza) {
                                    if ($poliza['id_titular'] == 0) {
                                    } else {
                                        $cont = $cont + 1;
                                        $currency = ($poliza['currency'] == 1) ? "$ " : "Bs ";
                                        
                                        $newDesde = date("Y/m/d", strtotime($poliza['f_desdepoliza']));
                                        $newHasta = date("Y/m/d", strtotime($poliza['f_hastapoliza']));

                                        $nombre = $poliza['nombre_t'] . ' ' . $poliza['apellido_t'];

                                        $no_renov = $obj->verRenov1($poliza['id_poliza']);
                                        
                                        if ($no_renov[0]['no_renov'] == 1) {
                                            $primac = $obj->obetnComisiones($poliza['id_poliza']);

                                            $totalsuma = $totalsuma + $poliza['sumaasegurada'];
                                            $totalprima = $totalprima + $poliza['prima'];

                                            $totalprimaC = $totalprimaC + $primac[0]['SUM(prima_com)'];
                                            $ppendiente = $poliza['prima'] - $primac[0]['SUM(prima_com)'];
                                            $ppendiente = number_format($ppendiente, 2);
                                            if ($ppendiente >= -0.10 && $ppendiente <= 0.10) {
                                                $ppendiente = 0;
                                            }
                                ?>
                                        <tr style="cursor: pointer;">
                                            <td hidden><?= $poliza['f_poliza']; ?></td>
                                            <td hidden><?= $poliza['id_poliza']; ?></td>

                                            <?php if ($poliza['id_tpoliza'] == 1) { ?>
                                                <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Nueva">N</td>
                                            <?php }
                                            if ($poliza['id_tpoliza'] == 2) { ?>
                                                <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Renovación">R</td>
                                            <?php }
                                            if ($poliza['id_tpoliza'] == 3) { ?>
                                                <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Traspaso de Cartera">T</td>
                                            <?php } ?>

                                            <?php if ($no_renov[0]['no_renov'] != 1) {
                                                if ($poliza['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                                    <td style="color: #2B9E34;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                                <?php } else { ?>
                                                    <td style="color: #E54848;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                                <?php }
                                            } else { ?>
                                                <td style="color: #4a148c;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                            <?php } ?>

                                            <td><?= $poliza['nombre']; ?></td>
                                            <td><?= $poliza['nomcia']; ?></td>
                                            <td><?= $newDesde; ?></td>
                                            <td><?= $newHasta; ?></td>
                                            <td class="text-right"><?= $currency . number_format($poliza['prima'], 2); ?></td>
                                            <td style="text-align: right"><?= $currency . number_format($primac[0]['SUM(prima_com)'], 2); ?></td>

                                            <?php if ($no_renov[0]['no_renov'] != 1) {
                                                if ($ppendiente > 0) { ?>
                                                    <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#F53333;font-size: 16px"><?= $currency . $ppendiente; ?></td>
                                                <?php }
                                                if ($ppendiente == 0) { ?>
                                                    <td style="background-color: #D9D9D9 ;color:black;text-align: right;font-weight: bold;"><?= $currency . $ppendiente; ?></td>
                                                <?php }
                                                if ($ppendiente < 0) { ?>
                                                    <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#2B9E34;font-size: 16px"><?= $currency . $ppendiente; ?></td>
                                                <?php }
                                            } else { ?>
                                                <td style="background-color: #D9D9D9 ;text-align: right;font-weight: bold;color:#4a148c"><?= $currency . $ppendiente; ?></td>
                                            <?php } ?>

                                            <td><?= ($nombre); ?></td>

                                            <?php if ($poliza['pdf'] == 1) { ?>
                                                <td class="text-center"><a href="download.php?id_poliza=<?= $poliza['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../assets/img/pdf-logo.png" width="25" id="pdf"></a></td>
                                                <?php } else {
                                                if ($poliza['nramo'] == 'Vida') {
                                                    $vRenov = $obj->verRenov3($poliza['id_poliza']);
                                                    if ($vRenov != 0) {
                                                        if ($vRenov[0]['pdf'] != 0) {
                                                            $poliza_pdf_vida = $obj->get_pdf_vida_id($vRenov[0]['id_poliza']); ?>
                                                            <td class="text-center"><a href="download.php?id_poliza=<?= $poliza_pdf_vida[0]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../assets/img/pdf-logo.png" width="25" id="pdf"></a></td>
                                                            <?php } else {
                                                            $poliza_pdf_vida = $obj->get_pdf_vida($vRenov[0]['cod_poliza'], $poliza['id_cia'], $poliza['f_hastapoliza']);
                                                            if ($poliza_pdf_vida[0]['pdf'] == 1) {  ?>
                                                                <td class="text-center"><a href="download.php?id_poliza=<?= $poliza_pdf_vida[0]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../assets/img/pdf-logo.png" width="25" id="pdf"></a></td>
                                                            <?php } else { ?>
                                                                <td></td>
                                                            <?php }
                                                        }
                                                    } else {
                                                        $poliza_pdf_vida = $obj->get_pdf_vida($poliza['cod_poliza'], $poliza['id_cia'], $poliza['f_hastapoliza']);
                                                        if ($poliza_pdf_vida[0]['pdf'] == 1) { ?>
                                                            <td class="text-center"><a href="download.php?id_poliza=<?= $poliza_pdf_vida[0]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../assets/img/pdf-logo.png" width="25" id="pdf"></a></td>
                                                        <?php } else { ?>
                                                            <td></td>
                                                    <?php }
                                                    }
                                                } else { ?>
                                                    <td></td>
                                                <?php } ?>
                                            <?php } ?>

                                        </tr>
                                <?php } else {$cont=$cont-1;} }
                                } ?>
                            </tbody>

                            <tfoot class="text-center">
                                <tr>
                                    <th hidden>f_poliza</th>
                                    <th hidden>id</th>
                                    <th></th>
                                    <th>N° Póliza</th>
                                    <th>Nombre Asesor</th>
                                    <th>Cía</th>
                                    <th>F Desde Seguro</th>
                                    <th>F Hasta Seguro</th>
                                    <th style="font-weight: bold" class="text-right">Prima Suscrita $<?= number_format($totalprima, 2); ?></th>
                                    <th style="font-weight: bold" class="text-right">Prima Cobrada $<?= number_format($totalprimaC, 2); ?></th>
                                    <th style="font-weight: bold" class="text-right">Prima Pendiente $<?= number_format($totalprima - $totalprimaC, 2); ?></th>
                                    <th>Nombre Titular</th>
                                    <th>PDF</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <p class="h1 text-center">Total de Pólizas Anuladas</p>
                    <p class="h1 text-center text-danger"><?php echo $cont; ?></p>



                    
                    <hr>

                    <p class="h1 text-center">Total de Prima Suscrita Pólizas Nuevas y Renovadas</p>
                    <p class="h1 text-center text-danger">$ <?php echo number_format($totalPrimaNR, 2); ?></p> 
                </div>


            <?php } ?>
        </div>





        <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'footer.php'; ?>

        <script src="../assets/view/b_poliza.js"></script>
</body>

</html>