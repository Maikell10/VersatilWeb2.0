<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'renov/b_renov_t';

require_once '../../Controller/Poliza.php';

$polizas = $obj->renovarM($_GET['anio'], $_GET['mes']);
$cant_p = sizeof($polizas);
/*foreach ($polizas as $poliza) {
    $poliza_renov = $obj->comprobar_poliza($poliza['cod_poliza'], $poliza['id_cia']);
    if (sizeof($poliza_renov) != 0) {
        $cant_p = $cant_p - 1;
    }
}*/

$polizasA = $obj->renovarME($_GET['anio'], $_GET['mes']);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'header.php'; ?>
    <style>
        .alertify .ajs-header {
            background-color: red;
        }
    </style>
</head>

<body>

    <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'navigation.php'; ?>
    <br><br><br><br><br><br>

    <div>
        <div class="card">

            <div id="carga" class="d-flex justify-content-center align-items-center">
                <div class="spinner-grow text-info" style="width: 7rem; height: 7rem;"></div>
            </div>

            <div class="card-header p-5 animated bounceInDown" id="headerload" hidden="true">
                <div class="ml-5 mr-5">
                    <h1 class="font-weight-bold text-center">Lista Pólizas por Efectividad de Renovación</h1>
                    <h1 class="font-weight-bold text-center">Mes: <?= $mes_arr[$_GET['mes'] - 1]; ?></h1>
                    <h1 class="font-weight-bold text-center">Año: <?= $_GET['anio']; ?></h1>
                </div>
            </div>

            <div id="enProceso"></div>

            <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">
                <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('tableRenovPolizas', 'Listado de Pólizas a Renovar')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../../assets/img/excel.png" width="60" alt=""></a></center>

                <h1 class="font-weight-bold text-black-50">En Proceso</h1>
                <div class="table-responsive-xl">
                    <table class="table table-hover table-striped table-bordered" id="tableRenovAct" width="100%">
                        <thead class="blue-gradient text-white text-center">
                            <tr>
                                <th hidden>f_hastapoliza</th>
                                <th hidden>id</th>
                                <th>N° Póliza</th>
                                <th>Nombre Titular</th>
                                <th>Cía</th>
                                <th>F Hasta Seguro</th>
                                <th>Ramo</th>
                                <th style="background-color: #E54848;">Prima Suscrita</th>
                                <th>PDF</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $prima_t = 0;
                            foreach ($polizas as $poliza) {
                                if ($_GET['mes'] < date('m') || $_GET['anio'] < date('Y')) {
                                    $poliza_renov = $obj->comprobar_poliza($poliza['cod_poliza'], $poliza['id_cia']);
                                    if (sizeof($poliza_renov) == 0) {
                                        $newDesde = date("Y/m/d", strtotime($poliza['f_desdepoliza']));
                                        $newHasta = date("Y/m/d", strtotime($poliza['f_hastapoliza']));

                                        $seguimiento = $obj->seguimiento($poliza['id_poliza']);
                                        $cant_seg = ($seguimiento == 0) ? 0 : sizeof($seguimiento);
                                        $ultimo_seg = (sizeof($seguimiento) == 0) ? '' : $seguimiento[0]['comentario'];

                                        if ($seguimiento == 0) {
                                            $prima_t = $prima_t + $poliza['prima']; ?>

                                            <tr style="cursor: pointer;">
                                                <td hidden><?= $poliza['f_hastapoliza']; ?></td>
                                                <td hidden><?= $poliza['id_poliza']; ?></td>
                                                <?php if ($poliza['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                                    <td style="color: #2B9E34;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                                <?php } else { ?>
                                                    <td style="color: #E54848;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                                <?php } ?>
                                                <td><?= ($poliza['nombre_t'] . ' ' . $poliza['apellido_t']); ?></td>
                                                <td><?= $poliza['nomcia']; ?></td>
                                                <td><?= $newHasta; ?></td>
                                                <td><?= $poliza['nramo']; ?></td>
                                                <td align="right"><?= '$ ' . number_format($poliza['prima'], 2); ?></td>

                                                <?php if ($poliza['pdf'] == 1) { ?>
                                                    <td class="text-center"><a href="../download.php?id_poliza=<?= $poliza['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../../assets/img/pdf-logo.png" width="20" id="pdf"></a></td>
                                                    <?php } else {
                                                    if ($poliza['nramo'] == 'Vida') {
                                                        $vRenov = $obj->verRenov3($poliza['id_poliza']);
                                                        if ($vRenov != 0) {
                                                            if ($vRenov[0]['pdf'] != 0) {
                                                                $poliza_pdf_vida = $obj->get_pdf_vida_id($vRenov[0]['id_poliza']); ?>
                                                                <td class="text-center"><a href="../download.php?id_poliza=<?= $poliza_pdf_vida[0]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../../assets/img/pdf-logo.png" width="20" id="pdf"></a></td>
                                                                <?php } else {
                                                                $poliza_pdf_vida = $obj->get_pdf_vida($vRenov[0]['cod_poliza'], $poliza['id_cia'], $poliza['f_hastapoliza']);
                                                                if ($poliza_pdf_vida[0]['pdf'] == 1) {  ?>
                                                                    <td class="text-center"><a href="../download.php?id_poliza=<?= $poliza_pdf_vida[0]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../../assets/img/pdf-logo.png" width="20" id="pdf"></a></td>
                                                                <?php } else { ?>
                                                                    <td></td>
                                                                <?php }
                                                            }
                                                        } else {
                                                            $poliza_pdf_vida = $obj->get_pdf_vida($poliza['cod_poliza'], $poliza['id_cia'], $poliza['f_hastapoliza']);
                                                            if ($poliza_pdf_vida[0]['pdf'] == 1) { ?>
                                                                <td class="text-center"><a href="../download.php?id_poliza=<?= $poliza_pdf_vida[0]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../../assets/img/pdf-logo.png" width="20" id="pdf"></a></td>
                                                            <?php } else { ?>
                                                                <td></td>
                                                        <?php }
                                                        }
                                                    } else { ?>
                                                        <td></td>
                                                    <?php } ?>
                                                <?php } ?>

                                            <?php } else {
                                            $cant_p = $cant_p - 1;
                                        }
                                    } else {
                                        $cant_p = $cant_p - 1;
                                    }
                                } else {
                                    $newDesde = date("Y/m/d", strtotime($poliza['f_desdepoliza']));
                                    $newHasta = date("Y/m/d", strtotime($poliza['f_hastapoliza']));

                                    $seguimiento = $obj->seguimiento($poliza['id_poliza']);
                                    $sizeSeg = ($seguimiento == 0) ? 0 : sizeof($seguimiento);
                                    $cant_seg = ($seguimiento == 0) ? 0 : $sizeSeg;
                                    $ultimo_seg = ($sizeSeg == 0) ? '' : $seguimiento[0]['comentario'];

                                    if ($seguimiento == 0) {
                                        $prima_t = $prima_t + $poliza['prima']; ?>

                                            <tr style="cursor: pointer;">
                                                <td hidden><?= $poliza['f_hastapoliza']; ?></td>
                                                <td hidden><?= $poliza['id_poliza']; ?></td>
                                                <?php if ($poliza['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                                    <td style="color: #2B9E34;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                                <?php } else { ?>
                                                    <td style="color: #E54848;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                                <?php } ?>
                                                <td><?= ($poliza['nombre_t'] . ' ' . $poliza['apellido_t']); ?></td>
                                                <td><?= $poliza['nomcia']; ?></td>
                                                <td><?= $newHasta; ?></td>
                                                <td><?= $poliza['nramo']; ?></td>
                                                <td align="right"><?= '$ ' . number_format($poliza['prima'], 2); ?></td>
                                                <?php if ($poliza['pdf'] == 1) { ?>
                                                    <td class="text-center"><a href="../download.php?id_poliza=<?= $poliza['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../../assets/img/pdf-logo.png" width="25" id="pdf"></a></td>
                                                <?php } else { ?>
                                                    <td></td>
                                                <?php } ?>

                                    <?php } else {
                                        $cant_p = $cant_p - 1;
                                    }
                                }
                            }
                                    ?>
                        </tbody>

                        <tfoot class="text-center">
                            <tr>
                                <th hidden>f_hastapoliza</th>
                                <th hidden>id</th>
                                <th>N° Póliza</th>
                                <th>Nombre Titular</th>
                                <th>Cía</th>
                                <th>F Hasta Seguro</th>
                                <th>Ramo</th>
                                <th>Prima Suscrita $<?= number_format($prima_t, 2); ?></th>
                                <th>PDF</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div id="enSeguimiento"></div>

                <h1 class="title text-center">Total de Pólizas en Proceso de <?= $mes_arr[$_GET['mes'] - 1]; ?></h1>
                <h1 class="title text-danger text-center"><?= $cant_p; ?></h1>

                <br>

                <h1 class="font-weight-bold text-black-50">En Seguimiento</h1>
                <div class="table-responsive-xl">
                    <table class="table table-hover table-striped table-bordered" id="tableRenovAct1" width="100%">
                        <thead class="blue-gradient text-white text-center">
                            <tr>
                                <th hidden>f_hastapoliza</th>
                                <th hidden>id</th>
                                <th>N° Póliza</th>
                                <th>Nombre Titular</th>
                                <th>Cía</th>
                                <th>F Hasta Seguro</th>
                                <th style="background-color: #E54848;">Prima Suscrita</th>
                                <th>Obs Seguimiento</th>
                                <th>Cant Seg</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $cantPoliza = 0;
                            $prima_tt = 0;
                            foreach ($polizas as $poliza) {
                                if ($_GET['mes'] < date('m') || $_GET['anio'] < date('Y')) {
                                    $poliza_renov = $obj->comprobar_poliza($poliza['cod_poliza'], $poliza['id_cia']);
                                    if (sizeof($poliza_renov) == 0) {
                                        $newDesde = date("Y/m/d", strtotime($poliza['f_desdepoliza']));
                                        $newHasta = date("Y/m/d", strtotime($poliza['f_hastapoliza']));

                                        $seguimiento = $obj->seguimiento($poliza['id_poliza']);
                                        $cant_seg = ($seguimiento == 0) ? 0 : sizeof($seguimiento);
                                        $ultimo_seg = (sizeof($seguimiento) == 0) ? '' : $seguimiento[0]['comentario'];

                                        if ($seguimiento != 0) {
                                            $cantPoliza++;
                                            $prima_t = $prima_t + $poliza['prima'];
                                            $prima_tt = $prima_tt + $poliza['prima']; ?>

                                            <tr style="cursor: pointer;">
                                                <td hidden><?= $poliza['f_hastapoliza']; ?></td>
                                                <td hidden><?= $poliza['id_poliza']; ?></td>
                                                <?php if ($poliza['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                                    <td style="color: #2B9E34;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                                <?php } else { ?>
                                                    <td style="color: #E54848;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                                <?php } ?>
                                                <td><?= ($poliza['nombre_t'] . ' ' . $poliza['apellido_t']); ?></td>
                                                <td><?= $poliza['nomcia']; ?></td>
                                                <td><?= $newHasta; ?></td>
                                                <td align="right"><?= '$ ' . number_format($poliza['prima'], 2); ?></td>
                                                <td><?= $ultimo_seg; ?></td>
                                                <td><?= $cant_seg; ?></td>


                                            <?php } else {
                                            $cant_p = $cant_p - 1;
                                        }
                                    } else {
                                        $cant_p = $cant_p - 1;
                                    }
                                } else {
                                    $newDesde = date("Y/m/d", strtotime($poliza['f_desdepoliza']));
                                    $newHasta = date("Y/m/d", strtotime($poliza['f_hastapoliza']));

                                    $seguimiento = $obj->seguimiento($poliza['id_poliza']);
                                    $sizeSeg = ($seguimiento == 0) ? 0 : sizeof($seguimiento);
                                    $cant_seg = ($seguimiento == 0) ? 0 : $sizeSeg;
                                    $ultimo_seg = ($sizeSeg == 0) ? '' : $seguimiento[0]['comentario'];

                                    if ($seguimiento != 0) {
                                        $cantPoliza++;
                                        $prima_t = $prima_t + $poliza['prima'];
                                        $prima_tt = $prima_tt + $poliza['prima']; ?>

                                            <tr style="cursor: pointer;">
                                                <td hidden><?= $poliza['f_hastapoliza']; ?></td>
                                                <td hidden><?= $poliza['id_poliza']; ?></td>
                                                <?php if ($poliza['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                                    <td style="color: #2B9E34;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                                <?php } else { ?>
                                                    <td style="color: #E54848;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                                <?php } ?>
                                                <td><?= ($poliza['nombre_t'] . ' ' . $poliza['apellido_t']); ?></td>
                                                <td><?= $poliza['nomcia']; ?></td>
                                                <td><?= $newHasta; ?></td>
                                                <td align="right"><?= '$ ' . number_format($poliza['prima'], 2); ?></td>
                                                <td><?= $ultimo_seg; ?></td>
                                                <td class="text-center"><?= $cant_seg; ?></td>

                                    <?php }
                                }
                            }
                                    ?>
                        </tbody>

                        <tfoot class="text-center">
                            <tr>
                                <th hidden>f_hastapoliza</th>
                                <th hidden>id</th>
                                <th>N° Póliza</th>
                                <th>Nombre Titular</th>
                                <th>Cía</th>
                                <th>F Hasta Seguro</th>
                                <th>Prima Suscrita $<?= $prima_tt; ?></th>
                                <th>Obs Seguimiento</th>
                                <th>Cant Seg</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div id="noRenovadas"></div>

                <h1 class="title text-center">Total de Pólizas en Seguimiento de <?= $mes_arr[$_GET['mes'] - 1]; ?></h1>
                <h1 class="title text-danger text-center"><?= $cantPoliza; ?></h1>

                <br>

                <h1 class="font-weight-bold text-black-50">No Renovadas</h1>
                <div class="table-responsive-xl">
                    <table class="table table-hover table-striped table-bordered" id="tableRenovAct2" width="100%">
                        <thead class="blue-gradient text-white text-center">
                            <tr>
                                <th hidden>f_hastapoliza</th>
                                <th hidden>id</th>
                                <th>N° Póliza</th>
                                <th>Nombre Titular</th>
                                <th>Cía</th>
                                <th>F Hasta Seguro</th>
                                <th style="background-color: #E54848;">Prima Suscrita</th>
                                <th>Asesor</th>
                                <th>Obs Seguimiento</th>
                                <th>Cant Seg</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $cantPoliza = 0;
                            $prima_tt = 0;
                            foreach ($polizasA as $polizaA) {
                                $vRenov = $obj->verRenov1($polizaA['id_poliza']);
                                if ($vRenov[0]['no_renov'] == 1) {
                                    $cantPoliza++;

                                    //$prima_t = $prima_t + $polizaA['prima'];
                                    $prima_tt = $prima_tt + $polizaA['prima'];

                                    $newDesde = date("Y/m/d", strtotime($polizaA['f_desdepoliza']));
                                    $newHasta = date("Y/m/d", strtotime($polizaA['f_hastapoliza']));

                                    $seguimiento = $obj->seguimiento($polizaA['id_poliza']);
                                    $cant_seg = ($seguimiento == 0) ? 0 : sizeof($seguimiento);
                                    $ultimo_seg = $vRenov[0]['no_renov_n'];
                            ?>
                                    <tr style="cursor: pointer;">
                                        <td hidden><?= $polizaA['f_hastapoliza']; ?></td>
                                        <td hidden><?= $polizaA['id_poliza']; ?></td>
                                        <td style="color: #E54848;font-weight: bold"><?= $polizaA['cod_poliza']; ?></td>
                                        <td><?= ($polizaA['nombre_t'] . ' ' . $polizaA['apellido_t']); ?></td>
                                        <td><?= $polizaA['nomcia']; ?></td>
                                        <td><?= $newHasta; ?></td>
                                        <td align="right"><?= '$ ' . number_format($polizaA['prima'], 2); ?></td>
                                        <td><?= $polizaA['nombre']; ?></td>
                                        <td><?= $ultimo_seg; ?></td>
                                        <td class="text-center"><?= $cant_seg + 1; ?></td>
                                    </tr>
                            <?php }
                            }  ?>
                        </tbody>

                        <tfoot class="text-center">
                            <tr>
                                <th hidden>f_hastapoliza</th>
                                <th hidden>id</th>
                                <th>N° Póliza</th>
                                <th>Nombre Titular</th>
                                <th>Cía</th>
                                <th>F Hasta Seguro</th>
                                <th>Prima Suscrita $<?= number_format($prima_tt, 2); ?></th>
                                <th>Asesor</th>
                                <th>Obs Seguimiento</th>
                                <th>Cant Seg</th>
                            </tr>
                        </tfoot>
                    </table>

                    <div id="siRenovadas"></div>

                    <h1 class="title text-center">Total de Pólizas No Renovadas de <?= $mes_arr[$_GET['mes'] - 1]; ?></h1>
                    <h1 class="title text-danger text-center"><?= $cantPoliza; ?></h1>

                    <br>

                    <h1 class="font-weight-bold text-black-50">Renovadas</h1>
                    <div class="table-responsive-xl">
                        <table class="table table-hover table-striped table-bordered" id="tableRenovAct3" width="100%">
                            <thead class="blue-gradient text-white text-center">
                                <tr>
                                    <th hidden>f_hastapoliza</th>
                                    <th hidden>id</th>
                                    <th>N° Póliza</th>
                                    <th>Nombre Titular</th>
                                    <th>Cía</th>
                                    <th>F Hasta Seguro</th>
                                    <th>Prima Suscrita</th>
                                    <th>Prima Cobrada</th>
                                    <th style="background-color: #E54848;">Prima Pendiente</th>
                                    <th>Asesor</th>
                                    <th>PDF</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $cantPoliza = 0;
                                $prima_tt = 0;
                                $prima_tc = 0;
                                $prima_tp = 0;
                                foreach ($polizasA as $polizaA) {
                                    $vRenov = $obj->verRenov2($polizaA['id_poliza']);
                                    if ($vRenov[0]['no_renov'] == 0) {
                                        $cantPoliza++;
                                        $prima_t = $prima_t + $vRenov[0]['prima'];
                                        $prima_tt = $prima_tt + $vRenov[0]['prima'];

                                        $newDesde = date("Y/m/d", strtotime($vRenov[0]['f_desdepoliza']));
                                        $newHasta = date("Y/m/d", strtotime($vRenov[0]['f_hastapoliza']));

                                        $primac = $obj->obetnComisiones($vRenov[0]['id_poliza']);
                                        $prima_tc = $prima_tc + $primac[0]['SUM(prima_com)'];

                                        $ppendiente = $vRenov[0]['prima'] - $primac[0]['SUM(prima_com)'];
                                        $prima_tp = $prima_tp + $ppendiente;
                                        $ppendiente = number_format($ppendiente, 2);
                                        if ($ppendiente >= -0.10 && $ppendiente <= 0.10) {
                                            $ppendiente = 0;
                                        }

                                        if ($primac[0]['SUM(prima_com)'] > 1) {
                                            $cant_pRVCom = $cant_pRVCom + 1;
                                        }

                                        $cant_pT = sizeof($polizas) + sizeof($polizasA);
                                        $perRenov = ($cant_pT == 0) ? 0 : (($cant_pRVCom * 100) / $cant_pT);
                                ?>
                                        <tr style="cursor: pointer;">
                                            <td hidden><?= $vRenov[0]['f_hastapoliza']; ?></td>
                                            <td hidden><?= $vRenov[0]['id_poliza']; ?></td>
                                            <?php if ($vRenov[0]['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                                <td style="color: #2B9E34;font-weight: bold"><?= $vRenov[0]['cod_poliza']; ?></td>
                                            <?php } else { ?>
                                                <td style="color: #E54848;font-weight: bold"><?= $vRenov[0]['cod_poliza']; ?></td>
                                            <?php } ?>
                                            <td><?= ($vRenov[0]['nombre_t'] . ' ' . $vRenov[0]['apellido_t']); ?></td>
                                            <td><?= $vRenov[0]['nomcia']; ?></td>
                                            <td><?= $newHasta; ?></td>
                                            <td align="right"><?= '$ ' . number_format($vRenov[0]['prima'], 2); ?></td>

                                            <td style="text-align: right"><?= '$ ' . number_format($primac[0]['SUM(prima_com)'], 2); ?></td>

                                            <?php if ($ppendiente > 0) { ?>
                                                <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#F53333;font-size: 16px"><?= '$ ' . $ppendiente; ?></td>
                                            <?php }
                                            if ($ppendiente == 0) { ?>
                                                <td style="background-color: #D9D9D9 ;color:black;text-align: right;font-weight: bold;"><?= '$ ' . $ppendiente; ?></td>
                                            <?php }
                                            if ($ppendiente < 0) { ?>
                                                <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#2B9E34;font-size: 16px"><?= '$ ' . $ppendiente; ?></td>
                                            <?php } ?>

                                            <td><?= $vRenov[0]['nombre']; ?></td>

                                            <?php if ($vRenov[0]['pdf'] == 1) { ?>
                                                <td class="text-center"><a href="../download.php?id_poliza=<?= $vRenov[0]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../../assets/img/pdf-logo.png" width="20" id="pdf"></a></td>
                                                <?php } else {
                                                if ($vRenov[0]['nramo'] == 'Vida') {
                                                    $vRenov1 = $obj->verRenov3($vRenov[0]['id_poliza']);
                                                    if ($vRenov1 != 0) {
                                                        if ($vRenov1[0]['pdf'] != 0) {
                                                            $poliza_pdf_vida = $obj->get_pdf_vida_id($vRenov1[0]['id_poliza']); ?>
                                                            <td class="text-center"><a href="../download.php?id_poliza=<?= $poliza_pdf_vida[0]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../../assets/img/pdf-logo.png" width="20" id="pdf"></a></td>
                                                            <?php } else {
                                                            $poliza_pdf_vida = $obj->get_pdf_vida($vRenov1[0]['cod_poliza'], $poliza['id_cia'], $poliza['f_hastapoliza']);
                                                            if ($poliza_pdf_vida[0]['pdf'] == 1) {  ?>
                                                                <td class="text-center"><a href="../download.php?id_poliza=<?= $poliza_pdf_vida[0]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../../assets/img/pdf-logo.png" width="20" id="pdf"></a></td>
                                                            <?php } else { ?>
                                                                <td></td>
                                                            <?php }
                                                        }
                                                    } else {
                                                        $poliza_pdf_vida = $obj->get_pdf_vida($vRenov[0]['cod_poliza'], $poliza['id_cia'], $poliza['f_hastapoliza']);
                                                        if ($poliza_pdf_vida[0]['pdf'] == 1) { ?>
                                                            <td class="text-center"><a href="../download.php?id_poliza=<?= $poliza_pdf_vida[0]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../../assets/img/pdf-logo.png" width="20" id="pdf"></a></td>
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
                                }  ?>
                            </tbody>

                            <tfoot class="text-center">
                                <tr>
                                    <th hidden>f_hastapoliza</th>
                                    <th hidden>id</th>
                                    <th>N° Póliza</th>
                                    <th>Nombre Titular</th>
                                    <th>Cía</th>
                                    <th>F Hasta Seguro</th>
                                    <th>Prima Suscrita $<?= number_format($prima_tt, 2); ?></th>
                                    <th>Prima Cobrada $<?= number_format($prima_tc, 2); ?></th>
                                    <th>Prima Pendiente $<?= number_format($prima_tp, 2); ?></th>
                                    <th>Asesor</th>
                                    <th>PDF</th>
                                </tr>
                            </tfoot>
                        </table>

                        <h1 class="title text-center">Total de Pólizas Renovadas de <?= $mes_arr[$_GET['mes'] - 1]; ?></h1>
                        <h1 class="title text-danger text-center"><?= $cantPoliza; ?></h1>

                        <h1 class="title text-center">% Efectividad de Renovación</h1>
                        <h1 class="title text-danger text-center"><?= number_format($perRenov, 2); ?>%</h1>

                        <br>
                        <hr>

                        <h1 class="title text-center">Total de Pólizas de <?= $mes_arr[$_GET['mes'] - 1]; ?></h1>
                        <h1 class="title text-danger text-center"><?= $cant_pT; ?></h1>

                        <h1 class="title text-center">Total de Prima Suscrita</h1>
                        <h1 class="title text-danger text-center">$ <?= number_format($prima_t, 2); ?></h1>

                    </div>
                </div>

            </div>





            <!--   TABLA EXCEL  -->
            <div class="table-responsive-xl" id="tableRenovPolizas" hidden>
                <table class="table table-hover table-striped table-bordered" width="100%">
                    <thead>
                        <tr>
                            <th style="text-align: center;font-size: 20px;" colspan="8">En Proceso</th>
                        </tr>
                        <tr>
                            <th style="background-color: #4285F4; color: white">N° Póliza</th>
                            <th style="background-color: #4285F4; color: white">Nombre Titular</th>
                            <th style="background-color: #4285F4; color: white">Cía</th>
                            <th style="background-color: #4285F4; color: white">F Hasta Seguro</th>
                            <th style="background-color: #4285F4; color: white" colspan="2">Ramo</th>
                            <th style="background-color: #E54848; color: white" colspan="2">Prima Suscrita</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $prima_t = 0;
                        foreach ($polizas as $poliza) {
                            if ($_GET['mes'] < date('m') || $_GET['anio'] < date('Y')) {
                                $poliza_renov = $obj->comprobar_poliza($poliza['cod_poliza'], $poliza['id_cia']);
                                if (sizeof($poliza_renov) == 0) {
                                    $newDesde = date("Y/m/d", strtotime($poliza['f_desdepoliza']));
                                    $newHasta = date("Y/m/d", strtotime($poliza['f_hastapoliza']));

                                    $seguimiento = $obj->seguimiento($poliza['id_poliza']);
                                    $cant_seg = ($seguimiento == 0) ? 0 : sizeof($seguimiento);
                                    $ultimo_seg = (sizeof($seguimiento) == 0) ? '' : $seguimiento[0]['comentario'];

                                    if ($seguimiento == 0) {
                                        $prima_t = $prima_t + $poliza['prima']; ?>

                                        <tr style="cursor: pointer;">
                                            <?php if ($poliza['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                                <td style="color: #2B9E34;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                            <?php } else { ?>
                                                <td style="color: #E54848;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                            <?php } ?>
                                            <td><?= ($poliza['nombre_t'] . ' ' . $poliza['apellido_t']); ?></td>
                                            <td><?= $poliza['nomcia']; ?></td>
                                            <td><?= $newHasta; ?></td>
                                            <td colspan="2"><?= $poliza['nramo']; ?></td>
                                            <td align="right" colspan="2"><?= '$ ' . number_format($poliza['prima'], 2); ?></td>

                                        <?php } else {
                                        $cant_p = $cant_p - 1;
                                    }
                                } else {
                                    $cant_p = $cant_p - 1;
                                }
                            } else {
                                $newDesde = date("Y/m/d", strtotime($poliza['f_desdepoliza']));
                                $newHasta = date("Y/m/d", strtotime($poliza['f_hastapoliza']));

                                $seguimiento = $obj->seguimiento($poliza['id_poliza']);
                                $sizeSeg = ($seguimiento == 0) ? 0 : sizeof($seguimiento);
                                $cant_seg = ($seguimiento == 0) ? 0 : $sizeSeg;
                                $ultimo_seg = ($sizeSeg == 0) ? '' : $seguimiento[0]['comentario'];

                                if ($seguimiento == 0) {
                                    $prima_t = $prima_t + $poliza['prima']; ?>

                                        <tr style="cursor: pointer;">
                                            <?php if ($poliza['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                                <td style="color: #2B9E34;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                            <?php } else { ?>
                                                <td style="color: #E54848;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                            <?php } ?>
                                            <td><?= ($poliza['nombre_t'] . ' ' . $poliza['apellido_t']); ?></td>
                                            <td><?= $poliza['nomcia']; ?></td>
                                            <td><?= $newHasta; ?></td>
                                            <td colspan="2"><?= $poliza['nramo']; ?></td>
                                            <td align="right" colspan="2"><?= '$ ' . number_format($poliza['prima'], 2); ?></td>

                                <?php } else {
                                    $cant_p = $cant_p - 1;
                                }
                            }
                        }
                                ?>
                    </tbody>
                </table>

                <table class="table table-hover table-striped table-bordered" width="100%">
                    <thead>
                        <tr>
                            <th style="text-align: center;font-size: 20px;" colspan="8">En Seguimiento</th>
                        </tr>
                        <tr>
                            <th style="background-color: #4285F4; color: white">N° Póliza</th>
                            <th style="background-color: #4285F4; color: white">Nombre Titular</th>
                            <th style="background-color: #4285F4; color: white">Cía</th>
                            <th style="background-color: #4285F4; color: white">F Hasta Seguro</th>
                            <th style="background-color: #E54848; color: white">Prima Suscrita</th>
                            <th style="background-color: #4285F4; color: white" colspan="2">Obs Seguimiento</th>
                            <th style="background-color: #4285F4; color: white">Cant Seg</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $cantPoliza = 0;
                        $prima_tt = 0;
                        foreach ($polizas as $poliza) {
                            if ($_GET['mes'] < date('m') || $_GET['anio'] < date('Y')) {
                                $poliza_renov = $obj->comprobar_poliza($poliza['cod_poliza'], $poliza['id_cia']);
                                if (sizeof($poliza_renov) == 0) {
                                    $newDesde = date("Y/m/d", strtotime($poliza['f_desdepoliza']));
                                    $newHasta = date("Y/m/d", strtotime($poliza['f_hastapoliza']));

                                    $seguimiento = $obj->seguimiento($poliza['id_poliza']);
                                    $cant_seg = ($seguimiento == 0) ? 0 : sizeof($seguimiento);
                                    $ultimo_seg = (sizeof($seguimiento) == 0) ? '' : $seguimiento[0]['comentario'];

                                    if ($seguimiento != 0) {
                                        $cantPoliza++;
                                        $prima_t = $prima_t + $poliza['prima'];
                                        $prima_tt = $prima_tt + $poliza['prima']; ?>

                                        <tr style="cursor: pointer;">
                                            <?php if ($poliza['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                                <td style="color: #2B9E34;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                            <?php } else { ?>
                                                <td style="color: #E54848;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                            <?php } ?>
                                            <td><?= ($poliza['nombre_t'] . ' ' . $poliza['apellido_t']); ?></td>
                                            <td><?= $poliza['nomcia']; ?></td>
                                            <td><?= $newHasta; ?></td>
                                            <td align="right"><?= '$ ' . number_format($poliza['prima'], 2); ?></td>
                                            <td colspan="2"><?= $ultimo_seg; ?></td>
                                            <td><?= $cant_seg; ?></td>


                                        <?php } else {
                                        $cant_p = $cant_p - 1;
                                    }
                                } else {
                                    $cant_p = $cant_p - 1;
                                }
                            } else {
                                $newDesde = date("Y/m/d", strtotime($poliza['f_desdepoliza']));
                                $newHasta = date("Y/m/d", strtotime($poliza['f_hastapoliza']));

                                $seguimiento = $obj->seguimiento($poliza['id_poliza']);
                                $sizeSeg = ($seguimiento == 0) ? 0 : sizeof($seguimiento);
                                $cant_seg = ($seguimiento == 0) ? 0 : $sizeSeg;
                                $ultimo_seg = ($sizeSeg == 0) ? '' : $seguimiento[0]['comentario'];

                                if ($seguimiento != 0) {
                                    $cantPoliza++;
                                    $prima_t = $prima_t + $poliza['prima'];
                                    $prima_tt = $prima_tt + $poliza['prima']; ?>

                                        <tr style="cursor: pointer;">
                                            <?php if ($poliza['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                                <td style="color: #2B9E34;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                            <?php } else { ?>
                                                <td style="color: #E54848;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                            <?php } ?>
                                            <td><?= ($poliza['nombre_t'] . ' ' . $poliza['apellido_t']); ?></td>
                                            <td><?= $poliza['nomcia']; ?></td>
                                            <td><?= $newHasta; ?></td>
                                            <td align="right"><?= '$ ' . number_format($poliza['prima'], 2); ?></td>
                                            <td colspan="2"><?= $ultimo_seg; ?></td>
                                            <td class="text-center"><?= $cant_seg; ?></td>

                                <?php }
                            }
                        }
                                ?>
                    </tbody>
                </table>

                <table class="table table-hover table-striped table-bordered" width="100%">
                    <thead>
                        <tr>
                            <th style="text-align: center;font-size: 20px;" colspan="8">No Renovadas</th>
                        </tr>
                        <tr>
                            <th style="background-color: #4285F4; color: white">N° Póliza</th>
                            <th style="background-color: #4285F4; color: white">Nombre Titular</th>
                            <th style="background-color: #4285F4; color: white">Cía</th>
                            <th style="background-color: #4285F4; color: white">F Hasta Seguro</th>
                            <th style="background-color: #E54848; color: white">Prima Suscrita</th>
                            <th style="background-color: #4285F4; color: white">Asesor</th>
                            <th style="background-color: #4285F4; color: white">Obs Seguimiento</th>
                            <th style="background-color: #4285F4; color: white">Cant Seg</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $cantPoliza = 0;
                        $prima_tt = 0;
                        foreach ($polizasA as $polizaA) {
                            $vRenov = $obj->verRenov1($polizaA['id_poliza']);
                            if ($vRenov[0]['no_renov'] == 1) {
                                $cantPoliza++;

                                //$prima_t = $prima_t + $polizaA['prima'];
                                $prima_tt = $prima_tt + $polizaA['prima'];

                                $newDesde = date("Y/m/d", strtotime($polizaA['f_desdepoliza']));
                                $newHasta = date("Y/m/d", strtotime($polizaA['f_hastapoliza']));

                                $seguimiento = $obj->seguimiento($polizaA['id_poliza']);
                                $cant_seg = ($seguimiento == 0) ? 0 : sizeof($seguimiento);
                                $ultimo_seg = $vRenov[0]['no_renov_n'];
                        ?>
                                <tr style="cursor: pointer;">
                                    <td style="color: #E54848;font-weight: bold"><?= $polizaA['cod_poliza']; ?></td>
                                    <td><?= ($polizaA['nombre_t'] . ' ' . $polizaA['apellido_t']); ?></td>
                                    <td><?= $polizaA['nomcia']; ?></td>
                                    <td><?= $newHasta; ?></td>
                                    <td align="right"><?= '$ ' . number_format($polizaA['prima'], 2); ?></td>
                                    <td><?= $polizaA['nombre']; ?></td>
                                    <td><?= $ultimo_seg; ?></td>
                                    <td class="text-center"><?= $cant_seg + 1; ?></td>
                                </tr>
                        <?php }
                        }  ?>
                    </tbody>
                </table>

                <table class="table table-hover table-striped table-bordered" width="100%">
                    <thead>
                        <tr>
                            <th style="text-align: center;font-size: 20px;" colspan="8">Renovadas</th>
                        </tr>
                        <tr>
                            <th style="background-color: #4285F4; color: white">N° Póliza</th>
                            <th style="background-color: #4285F4; color: white">Nombre Titular</th>
                            <th style="background-color: #4285F4; color: white">Cía</th>
                            <th style="background-color: #4285F4; color: white">F Hasta Seguro</th>
                            <th style="background-color: #4285F4; color: white">Prima Suscrita</th>
                            <th style="background-color: #4285F4; color: white">Prima Cobrada</th>
                            <th style="background-color: #E54848; color: white">Prima Pendiente</th>
                            <th style="background-color: #4285F4; color: white">Asesor</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $cantPoliza = 0;
                        $prima_tt = 0;
                        $prima_tc = 0;
                        $prima_tp = 0;
                        foreach ($polizasA as $polizaA) {
                            $vRenov = $obj->verRenov2($polizaA['id_poliza']);
                            if ($vRenov[0]['no_renov'] == 0) {
                                $cantPoliza++;
                                $prima_t = $prima_t + $vRenov[0]['prima'];
                                $prima_tt = $prima_tt + $vRenov[0]['prima'];

                                $newDesde = date("Y/m/d", strtotime($vRenov[0]['f_desdepoliza']));
                                $newHasta = date("Y/m/d", strtotime($vRenov[0]['f_hastapoliza']));

                                $primac = $obj->obetnComisiones($vRenov[0]['id_poliza']);
                                $prima_tc = $prima_tc + $primac[0]['SUM(prima_com)'];

                                $ppendiente = $vRenov[0]['prima'] - $primac[0]['SUM(prima_com)'];
                                $prima_tp = $prima_tp + $ppendiente;
                                $ppendiente = number_format($ppendiente, 2);
                                if ($ppendiente >= -0.10 && $ppendiente <= 0.10) {
                                    $ppendiente = 0;
                                }

                                if ($primac[0]['SUM(prima_com)'] > 1) {
                                    $cant_pRVCom = $cant_pRVCom + 1;
                                }

                                $cant_pT = sizeof($polizas) + sizeof($polizasA);
                                $perRenov = ($cant_pT == 0) ? 0 : (($cant_pRVCom * 100) / $cant_pT);
                        ?>
                                <tr style="cursor: pointer;">
                                    <?php if ($vRenov[0]['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                        <td style="color: #2B9E34;font-weight: bold"><?= $vRenov[0]['cod_poliza']; ?></td>
                                    <?php } else { ?>
                                        <td style="color: #E54848;font-weight: bold"><?= $vRenov[0]['cod_poliza']; ?></td>
                                    <?php } ?>
                                    <td><?= ($vRenov[0]['nombre_t'] . ' ' . $vRenov[0]['apellido_t']); ?></td>
                                    <td><?= $vRenov[0]['nomcia']; ?></td>
                                    <td><?= $newHasta; ?></td>
                                    <td align="right"><?= '$ ' . number_format($vRenov[0]['prima'], 2); ?></td>

                                    <td style="text-align: right"><?= '$ ' . number_format($primac[0]['SUM(prima_com)'], 2); ?></td>

                                    <?php if ($ppendiente > 0) { ?>
                                        <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#F53333;font-size: 16px"><?= '$ ' . $ppendiente; ?></td>
                                    <?php }
                                    if ($ppendiente == 0) { ?>
                                        <td style="background-color: #D9D9D9 ;color:black;text-align: right;font-weight: bold;"><?= '$ ' . $ppendiente; ?></td>
                                    <?php }
                                    if ($ppendiente < 0) { ?>
                                        <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#2B9E34;font-size: 16px"><?= '$ ' . $ppendiente; ?></td>
                                    <?php } ?>

                                    <td><?= $vRenov[0]['nombre']; ?></td>
                                </tr>
                        <?php }
                        }  ?>
                    </tbody>
                </table>
            </div>





            <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer_b.php'; ?>

            <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer.php'; ?>

            <!-- Modal SEGUIMIENTO RENOV-->
            <div class="modal fade" id="seguimientoRenov" tabindex="-1" role="dialog" aria-labelledby="seguimientoRenov" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="seguimientoRenov">Crear Comentario para Seguimiento de la Póliza</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="frmnuevoS" class="md-form">
                                <input type="text" class="form-control" id="id_polizaS" name="id_polizaS" hidden>
                                <input type="text" class="form-control" id="id_usuarioS" name="id_usuarioS" value="<?= $_SESSION['id_usuario']; ?>" hidden>
                                <label for="comentarioS">Ingrese Comentario</label>
                                <textarea class="form-control md-textarea" id="comentarioS" name="comentarioS" required onKeyDown="valida_longitud()" onKeyUp="valida_longitud()" maxlength="300"></textarea>

                                <input type="text" id="caracteres" class="form-control" disabled value="Caracteres restantes: 300">

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn young-passion-gradient text-white" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn dusty-grass-gradient" id="btnSeguimientoR">Crear</button>
                        </div>
                    </div>
                </div>
            </div>

            <script src="../../assets/view/b_poliza.js"></script>

            <script>
                $("#tableRenovAct tbody tr").dblclick(function() {
                    var customerId = $(this).find("td").eq(1).html();

                    window.open("../v_poliza.php?modal=true&id_poliza=" + customerId, '_blank');
                });
                $("#tableRenovAct1 tbody tr").dblclick(function() {
                    var customerId = $(this).find("td").eq(1).html();

                    window.open("../v_poliza.php?modal=true&id_poliza=" + customerId, '_blank');
                });
                $("#tableRenovAct2 tbody tr").dblclick(function() {
                    var customerId = $(this).find("td").eq(1).html();

                    window.open("../v_poliza.php?modal=true&id_poliza=" + customerId, '_blank');
                });
                $("#tableRenovAct3 tbody tr").dblclick(function() {
                    var customerId = $(this).find("td").eq(1).html();

                    //window.open("../v_poliza.php?modal=true&id_poliza=" + customerId, '_blank');
                    window.open("../v_poliza.php?id_poliza=" + customerId, '_blank');
                });
            </script>
</body>

</html>