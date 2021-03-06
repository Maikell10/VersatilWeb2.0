<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'v_poliza';

require_once '../Controller/Poliza.php';

$newDesdeP = date("d/m/Y", strtotime($poliza[0]['f_desdepoliza']));
$newHastaP = date("d/m/Y", strtotime($poliza[0]['f_hastapoliza']));
$newfechaV = date("d/m/Y", strtotime($poliza[0]['fechaV']));
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

            <div class="card-header p-5 animated bounceInDown">
                <?php if (isset($_GET['m']) == 2) { ?>
                    <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                        <strong>Póliza Subida correctamente en .pdf!</strong>
                        <button style="cursor: pointer" type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php } ?>
                <div class="ml-5 mr-5">

                    <?php if($poliza[0]['id_tpoliza'] == 1) { ?>
                        <h1 class="font-weight-bold">N</h1>
                    <?php } if($polizap != 0 && $poliza[0]['id_tpoliza'] == 2) { ?>
                        <h1 class="font-weight-bold">R</h1>
                    <?php } ?>

                    <h1><span class="font-weight-bold">Cliente:</span>
                        <?php $nombre = utf8_decode($poliza[0]['nombre_t'] . " " . $poliza[0]['apellido_t']);

                        echo utf8_encode($nombre); ?>
                    </h1>

                    <?php if ($no_renov[0]['no_renov'] != 1) {
                        if ($poliza[0]['f_hastapoliza'] >= date("Y-m-d")) { ?>
                            <h2 style="color: #2B9E34" class="font-weight-bold">Póliza N°: <?= $poliza[0]['cod_poliza']; ?></h2>
                        <?php } else { ?>
                            <h2 style="color: #E54848" class="font-weight-bold">Póliza N°: <?= $poliza[0]['cod_poliza']; ?></h2>
                        <?php }
                    } else { ?>
                        <h2 style="color: #4a148c" class="font-weight-bold">Póliza N°: <?= $poliza[0]['cod_poliza']; ?></h2>
                    <?php } ?>

                    <?php
                    if (isset($poliza[0]['idnom']) == null) {
                        $asesorr = $poliza[0]['cod'] . " -> " . $poliza[0]['nombre'];
                    } else {
                        $asesorr = $poliza[0]['cod'] . " -> " . $poliza[0]['idnom'];
                    }
                    ?>

                    <h3><span class="font-weight-bold">Asesor: </span><?= utf8_encode($asesorr); ?></h3>

                    <h3><span class="font-weight-bold">Fecha Desde Seg: </span><?= $newDesdeP; ?> | <span class="font-weight-bold">Fecha Hasta Seg: </span><?= $newHastaP; ?></h3>

                    <div class="row">
                        <div class="col-md-8">
                            <h3><span class="font-weight-bold">Cía: </span><?= ($poliza[0]['nomcia']); ?> | <span class="font-weight-bold">Ramo: </span><?= ($poliza[0]['nramo']); ?> | <span class="font-weight-bold">Nº de Cuotas: </span><?= $poliza[0]['ncuotas']; ?></h3>
                        </div>

                        <div class="col-md-4">
                            <?php if ($no_renov[0]['no_renov'] != 1) {
                                if ($poliza[0]['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                    <h2 style="color: #2B9E34" class="font-weight-bold float-right"><?= "ACTIVA"; ?></h2>
                                <?php } else { ?>
                                    <h2 style="color: #E54848" class="font-weight-bold float-right"><?= "INACTIVA"; ?></h2>
                                <?php }
                            } else { ?>
                                <h2 style="color: #4a148c" class="font-weight-bold float-right"><?= "ANULADA"; ?></h2>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comienzo tabla -->
            <div class="card-body p-5">
                <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('tableModalPagoE', 'Listado de Pólizas')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../assets/img/excel.png" width="60" alt=""></a></center>

                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered" id="tableModalPago">
                        <thead class="blue-gradient text-white">
                            <tr class="text-center">
                                <th hidden>id</th>
                                <th class="align-middle">Ejecutivo</th>
                                <th class="align-middle">Prima Cobrada</th>
                                <th class="align-middle">F Pago Prima</th>
                                <th class="align-middle">Comisión Cobrada</th>
                                <th class="align-middle">% Com</th>
                                <th class="align-middle">F Hasta Reporte</th>
                                <th class="align-middle">GC Pagada</th>
                                <?php if($as != 1 && $poliza[0]['currencyM'] == '%' ) { ?>
                                <th class="align-middle">% GC</th>
                                <?php } else {  ?>
                                <th class="align-middle">Monto GC</th>
                                <?php } ?>
                                <th class="align-middle">F Pago GC</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($polizap[0]['comision'] != null) {

                                for ($i = 0; $i < sizeof($polizap); $i++) {
                                    $totalprimaC = $totalprimaC + $polizap[$i]['prima_com'];
                                    $totalcomisionC = $totalcomisionC + $polizap[$i]['comision'];
                                    $totalGC = $totalGC + (($polizap[$i]['comision'] * $polizap[$i]['per_gc']) / 100);

                                    $newFPago = date("d/m/Y", strtotime($polizap[$i]['f_pago_prima']));
                                    $newFHastaR = date("d/m/Y", strtotime($polizap[$i]['f_hasta_rep']));
                                    $newFPagoGC = date("d/m/Y", strtotime($polizap[$i]['f_pago_gc']));

                                    $ejecutivo = $obj->get_ejecutivo_by_cod($polizap[$i]['cod_vend']);

                                    $pGCpago = ($polizap[$i]['comision'] * $polizap[$i]['per_gc']) / 100;

                                    if(substr($poliza[0]['cod'], 0, 1) == 'P' || substr($poliza[0]['cod'], 0, 1) == 'R') {
                                        $polizapp = $obj->get_comision_proyecto_by_id($id_poliza);
                                        $pGCpago = $polizapp[0]['monto_p'];
                                        $totalGC = $polizapp[0]['monto_p'];
                                        $newFPagoGC = date("d/m/Y", strtotime($polizapp[0]['f_pago_gc_r']));
                                    }
                            ?>
                                    <tr style="cursor: pointer">
                                        <td hidden><?= $polizap[$i]['id_rep_com']; ?></td>
                                        <td class="align-middle"><?= $ejecutivo[0]['nombre']; ?></td>
                                        <td class="align-middle" align="right"><?= number_format($polizap[$i]['prima_com'], 2); ?></td>
                                        <td class="align-middle"><?= $newFPago; ?></td>
                                        <td class="align-middle" align="right"><?= number_format($polizap[$i]['comision'], 2); ?></td>
                                        <td class="align-middle" align="right"><?= number_format(($polizap[$i]['comision'] * 100) / $polizap[$i]['prima_com'], 2); ?></td>

                                        <td class="align-middle" nowrap><?= $newFHastaR; ?></td>
                                        <td class="align-middle" align="right"><?= number_format($pGCpago, 2); ?></td>

                                        <td class="align-middle" align="right"><?= number_format($polizap[$i]['per_gc'], 2); ?></td>

                                        <td class="align-middle" nowrap><?= $newFPagoGC; ?></td>
                                    </tr>
                            <?php
                                }
                            }
                            $primaPendiente = number_format($poliza[0]['prima'] - $totalprimaC, 2);
                            ?>
                        </tbody>
                        <tr style="background-color: #F53333;color: white">
                            <td></td>
                            <td class="font-weight-bold">Prima Cobrada: <?= $currency . number_format($totalprimaC, 2); ?></td>
                            <td class="font-weight-bold">Prima Suscrita: <?= $currency . number_format($poliza[0]['prima'], 2); ?></td>
                            <td class="font-weight-bold">Comisión Cobrada: <?= $currency . number_format($totalcomisionC, 2); ?></td>
                            <td></td>
                            <td></td>
                            <td class="font-weight-bold">GC Pagada: <?= $currency . number_format($totalGC, 2); ?></td>
                            <td></td>
                            <td class="font-weight-bold"></td>
                        </tr>
                    </table>
                </div>

                <?php if ($primaPendiente > 0) { ?>
                    <h2>Prima Pendiente: <span class="text-danger"><?= $currency . $primaPendiente; ?></span></h2>
                <?php }
                if ($primaPendiente == 0) { ?>
                    <h2>Prima Pendiente: <?= $currency . $primaPendiente; ?></h2>
                <?php }
                if ($primaPendiente < 0) { ?>
                    <h2>Prima Pendiente: <span class="text-success"><?= $currency . $primaPendiente; ?></span></h2>
                <?php } ?>


                <div class="table-responsive" hidden>
                    <table class="table table-hover table-striped table-bordered" id="tableModalPagoE">
                        <thead>
                            <tr>
                                <th style="background-color: #4285F4; color: white">Ejecutivo</th>
                                <th style="background-color: #4285F4; color: white">Prima Cobrada</th>
                                <th style="background-color: #4285F4; color: white">F Pago Prima</th>
                                <th style="background-color: #4285F4; color: white">Comisión Cobrada</th>
                                <th style="background-color: #4285F4; color: white">% Com</th>
                                <th style="background-color: #4285F4; color: white">F Hasta Reporte</th>
                                <th style="background-color: #4285F4; color: white">GC Pagada</th>
                                <?php if($as != 1 && $poliza[0]['currencyM'] == '%' ) { ?>
                                    <th style="background-color: #4285F4; color: white">% GC</th>
                                <?php } else {  ?>
                                    <th style="background-color: #4285F4; color: white">Monto GC</th>
                                <?php } ?>
                                <th style="background-color: #4285F4; color: white">F Pago GC</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($polizap[0]['comision'] != null) {
                                for ($i = 0; $i < sizeof($polizap); $i++) {
                                    $totalprimaC = $totalprimaC + $polizap[$i]['prima_com'];
                                    $totalcomisionC = $totalcomisionC + $polizap[$i]['comision'];
                                    $totalGC = $totalGC + (($polizap[$i]['comision'] * $polizap[$i]['per_gc']) / 100);

                                    $newFPago = date("d/m/Y", strtotime($polizap[$i]['f_pago_prima']));
                                    $newFHastaR = date("d/m/Y", strtotime($polizap[$i]['f_hasta_rep']));
                                    $newFPagoGC = date("d/m/Y", strtotime($polizap[$i]['f_pago_gc']));

                                    $ejecutivo = $obj->get_ejecutivo_by_cod($polizap[$i]['cod_vend']);

                                    $pGCpago = ($polizap[$i]['comision'] * $polizap[$i]['per_gc']) / 100;

                                    if(substr($poliza[0]['cod'], 0, 1) == 'P' || substr($poliza[0]['cod'], 0, 1) == 'R') {
                                        $polizapp = $obj->get_comision_proyecto_by_id($id_poliza);
                                        $pGCpago = $polizapp[0]['monto_p'];
                                        $totalGC = $polizapp[0]['monto_p'];
                                        $newFPagoGC = date("d/m/Y", strtotime($polizapp[0]['f_pago_gc_r']));
                                    }
                            ?>
                                    <tr>
                                        <td><?= $ejecutivo[0]['nombre']; ?></td>
                                        <td align="right"><?= number_format($polizap[$i]['prima_com'], 2); ?></td>
                                        <td><?= $newFPago; ?></td>
                                        <td align="right"><?= number_format($polizap[$i]['comision'], 2); ?></td>
                                        <td align="right"><?= number_format(($polizap[$i]['comision'] * 100) / $polizap[$i]['prima_com'], 2); ?></td>

                                        <td nowrap><?= $newFHastaR; ?></td>
                                        <td align="right"><?= number_format($pGCpago, 2); ?></td>

                                        <td align="right"><?= number_format($polizap[$i]['per_gc'], 2); ?></td>

                                        <td nowrap><?= $newFPagoGC; ?></td>
                                    </tr>
                            <?php
                                }
                            }
                            ?>
                        </tbody>
                        <tr>
                            <td style="background-color: #F53333"></td>
                            <td style="background-color: #F53333;color: white;font-weight: bold">Prima Cobrada: <?= $currency . number_format($totalprimaC, 2); ?></td>
                            <td style="background-color: #F53333;color: white;font-weight: bold">Prima Suscrita: <?= $currency . number_format($poliza[0]['prima'], 2); ?></td>
                            <td style="background-color: #F53333;color: white;font-weight: bold">Comisión Cobrada: <?= $currency . number_format($totalcomisionC, 2); ?></td>
                            <td style="background-color: #F53333"></td>
                            <td style="background-color: #F53333"></td>
                            <td style="background-color: #F53333;color: white;font-weight: bold">GC Pagada: <?= $currency . number_format($totalGC, 2); ?></td>
                            <td style="background-color: #F53333"></td>
                            <td style="background-color: #F53333;color: white;font-weight: bold"></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>





    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'footer_b.php'; ?>

    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'footer.php'; ?>

    <script src="../assets/view/b_poliza.js"></script>

</body>

</html>