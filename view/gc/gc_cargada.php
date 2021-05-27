<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);
$pag = 'gc/gc_cargada';

require_once '../../Controller/Poliza.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'navigation.php'; ?>
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
                                <h1 class="font-weight-bold">Resultado de Búsqueda de GC Total Cargada</h1>
                                <h2>Año: <font style="font-weight:bold" class="text-danger"><?= $_GET['anio'];
                                                                                            if ($_GET['mes'] == null) {
                                                                                            } else {
                                                                                            ?></font>
                                    Mes: <font style="font-weight:bold" class="text-danger"><?= $mes_arr[$_GET['mes'] - 1];
                                                                                            } ?></font>
                                </h2>
                            </div>
                            <br><br><br>
                </div>

                <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden>

                    <center><a class="btn dusty-grass-gradient" href="../excel/e_gc_cargada.php?anio=<?= $anio; ?>&mes=<?= $mes; ?>" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../../assets/img/excel.png" width="60" alt=""></a></center>

                    <center><a href="gc_eistente_copia.php?anio=<?= $_GET['anio']; ?>&mes=<?= $_GET['mes']; ?>&cia=<?= $ciaEnv1; ?>&asesor=<?= $asesorEnv; ?>" class="btn blue-gradient btn-lg" data-toggle="tooltip" data-placement="right" title="Ver Detalles para la Búsqueda Actual" style="color:white" target="_blank">Detalle</a></center>



                    <div class="table-responsive-xl">
                        <h3 class="text-black-50 font-weight-bold">GC Asesores</h3>
                        <table class="table table-hover table-striped table-bordered" id="mytableGC" style="cursor: pointer;">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th>Asesor</th>
                                    <th>Mes Pago GC</th>
                                    <th>F Pago GC</th>
                                    <th>Prima Cobrada</th>
                                    <th>Comisión Cobrada</th>
                                    <th>% Com</th>
                                    <th style="background-color: #E54848; color: white">GC Pagada</th>
                                    <th>%GC Asesor</th>
                                    <th hidden>id</th>
                                    <th hidden>F Pago GC</th>
                                    <th hidden>cod_asesor</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $Arr[] = null;
                                $cantdistinct_a = ($distinct_a == null) ? 0 : sizeof($distinct_a);
                                for ($a = 0; $a < $cantdistinct_a; $a++) {
                                    $totalprimacom = 0;
                                    $totalcomision = 0;
                                    $totalgc = 0;

                                    $distinct_fpgc = $obj->get_distinct_fgc_exist_by_a($desde, $hasta, $distinct_a[$a]['cod_vend']);
                                ?>
                                    <tr>
                                        <?php
                                        if ($distinct_a[$a]['act'] == 0) {
                                        ?>
                                            <td rowspan="<?= sizeof($distinct_fpgc); ?>" style="background-color: #D9D9D9;font-weight: bold" class="text-danger align-middle"><?= $distinct_a[$a]['nombre'] . ' (' . $distinct_a[$a]['cod_vend'] . ')'; ?></td>
                                        <?php
                                        }
                                        if ($distinct_a[$a]['act'] == 1) {
                                        ?>
                                            <td rowspan="<?= sizeof($distinct_fpgc); ?>" style="background-color: #D9D9D9;font-weight: bold" class="text-success align-middle"><?= $distinct_a[$a]['nombre'] . ' (' . $distinct_a[$a]['cod_vend'] . ')'; ?></td>
                                        <?php
                                        }

                                        $totalprimacom_TA = 0;
                                        $totalcomision_TA = 0;
                                        $total_per_com_TA = 0;
                                        $totalgc_TA = 0;
                                        $total_gc_a_t_TA = 0;
                                        for ($b = 0; $b < sizeof($distinct_fpgc); $b++) {
                                            $totalprimacom = 0;
                                            $totalcomision = 0;
                                            $total_per_com = 0;
                                            $totalgc = 0;

                                            $poliza = $obj->get_gc_exist_by_a_by_fpgc($distinct_a[$a]['cod_vend'], $distinct_fpgc[$b]['f_pago_gc']);

                                            for ($i = 0; $i < sizeof($poliza); $i++) {
                                                $Arr[] = $poliza[$i]['id_comision'];

                                                $totalsuma = $totalsuma + $poliza[$i]['sumaasegurada'];
                                                $totalprima = $totalprima + $poliza[$i]['prima'];

                                                $totalprimacom = $totalprimacom + $poliza[$i]['prima_com'];
                                                $totalcomision = $totalcomision + $poliza[$i]['comision'];
                                                $totalgc = $totalgc + (($poliza[$i]['comision'] * $poliza[$i]['per_gc']) / 100);

                                                $totalprimacomT = $totalprimacomT + $poliza[$i]['prima_com'];
                                                $totalcomisionT = $totalcomisionT + $poliza[$i]['comision'];
                                                $totalgcT = $totalgcT + (($poliza[$i]['comision'] * $poliza[$i]['per_gc']) / 100);

                                                $newDesde = date("d/m/Y", strtotime($poliza[$i]['f_desdepoliza']));
                                                $newHasta = date("d/m/Y", strtotime($poliza[$i]['f_hastapoliza']));
                                                $tooltip = 'Fecha Desde: ' . $newDesde . ' | Fecha Hasta: ' . $newHasta;

                                                $no_renov = $obj->verRenov1($poliza[$i]['id_poliza']);

                                                if ($poliza[$i]['currency'] == 1) {
                                                    $currency = "$ ";
                                                } else {
                                                    $currency = "Bs ";
                                                }

                                                if ($poliza[$i]['id_titular'] == 0) {
                                                    $titular_pre = $obj->get_element_by_id('titular_pre_poliza', 'id_poliza', $poliza[$i]['id_poliza']);
                                                    $nombretitu = $titular_pre[0]['asegurado'];
                                                } else {
                                                    $nombretitu = $poliza[$i]['nombre_t'] . " " . $poliza[$i]['apellido_t'];
                                                }
                                            }

                                            if (number_format($totalprimacom, 2) == 0.00) {
                                                $totalprimacom = 0;
                                            }
                                            
                                            if ($totalcomision == 0) {
                                                $total_gc_a_t = 0;
                                            } else {
                                                $total_gc_a_t = ($totalgc * 100) / $totalcomision;
                                            }

                                            if ($totalprimacom == 0) {
                                                $total_per_com = 0;
                                            } else {
                                                $total_per_com = ($totalcomision * 100) / $totalprimacom;
                                            }

                                            $originalFPagoGC = $distinct_fpgc[$b]['f_pago_gc'];
                                            $newFPagoGC = date("d/m/Y", strtotime($originalFPagoGC));

                                            $originalFPagoGCMes = $distinct_fpgc[$b]['f_pago_gc'];
                                            $newFPagoGCMes = date("m", strtotime($originalFPagoGCMes));

                                            $cia_para_enviar_via_url = serialize($_GET['cia']);
                                            $ciaEnv = urlencode($cia_para_enviar_via_url);

                                            $totalprimacom_TA = $totalprimacom_TA + $totalprimacom;
                                            $totalcomision_TA = $totalcomision_TA + $totalcomision;
                                            $total_per_com_TA = $total_per_com_TA + $total_per_com;
                                            $totalgc_TA = $totalgc_TA + $totalgc;
                                            $total_gc_a_t_TA = $total_gc_a_t_TA + $total_gc_a_t;
                                        ?>

                                            <td nowrap><?= $mes_arr[$newFPagoGCMes - 1]; ?></td>
                                            <td nowrap><?= $newFPagoGC; ?></td>

                                            <?php if ($totalprimacom < 0) { ?>
                                                <td style="color: #E54848;text-align: right;"><?= "$ " . number_format($totalprimacom, 2); ?></td>
                                            <?php } else { ?>
                                                <td style="text-align: right;"><?= "$ " . number_format($totalprimacom, 2); ?></td>
                                            <?php } ?>

                                            <?php if ($totalcomision < 0) { ?>
                                                <td style="color: #E54848;text-align: right;"><?= "$ " . number_format($totalcomision, 2); ?></td>
                                            <?php } else { ?>
                                                <td style="text-align: right;"><?= "$ " . number_format($totalcomision, 2); ?></td>
                                            <?php } ?>

                                            <td align="center"><?= number_format($total_per_com, 0) . " %"; ?></td>

                                            <?php if ($totalgc < 0) { ?>
                                                <td style="color: #E54848;text-align: right;background-color: #D9D9D9;font-weight: bold"><?= "$ " . number_format($totalgc, 2); ?></td>
                                            <?php } else { ?>
                                                <td style="text-align: right;background-color: #D9D9D9;font-weight: bold"><?= "$ " . number_format($totalgc, 2); ?></td>
                                            <?php } ?>

                                            <td nowrap align="center"><?= number_format($total_gc_a_t, 0) . " %"; ?></td>
                                            <td hidden><?= $poliza[0]['id_poliza']; ?></td>

                                            <td hidden><?= $distinct_fpgc[$b]['f_pago_gc']; ?></td>
                                            <td hidden><?= $distinct_a[$a]['cod_vend']; ?></td>
                                    </tr>
                            <?php
                                        }
                                        if($_GET['mes'] == null) { ?>

                                        <tr style="background-color: #F53333;color: white">
                                            <td colspan="3"></td>

                                            <td style="text-align: right;font-weight: bold;font-size: 15px">$ <?= number_format($totalprimacom_TA, 2); ?></td>
                                            <td style="text-align: right;font-weight: bold;font-size: 15px">$ <?= number_format($totalcomision_TA, 2); ?></td>
                                            <td style="text-align: center;font-weight: bold;font-size: 15px"><?= number_format($total_per_com_TA / sizeof($distinct_fpgc), 2); ?> %</td>
                                            <td style="text-align: right;font-weight: bold;font-size: 15px">$ <?= number_format($totalgc_TA, 2); ?></td>
                                            <td style="text-align: center;font-weight: bold;font-size: 15px"><?= number_format($total_gc_a_t_TA / sizeof($distinct_fpgc), 2); ?> %</td>
                                        </tr>

                                        <?php   }
                                        $totalpoliza = $totalpoliza + sizeof($poliza);
                                    }

                                    $var1 = 0;
                                    if ($totalprimacomT != 0) {
                                        $var1 = number_format(($totalcomisionT * 100) / $totalprimacomT, 2);
                                    }
                                    $var2 = 0;
                                    if ($totalcomisionT != 0) {
                                        $var2 = number_format(($totalgcT * 100) / $totalcomisionT, 2);
                                    }
                            ?>
                            <tr style="background-color: #2FA4E7;color: white;font-weight: bold" id="no-tocar" class="blue-gradient text-white">
                                <td colspan="3" style="font-weight: bold">Total General</td>

                                <td align="right" style="font-weight: bold">
                                    <font size=4><?= "$ " . number_format($totalprimacomT, 2); ?></font>
                                </td>
                                <td align="right" style="font-weight: bold">
                                    <font size=4><?= "$ " . number_format($totalcomisionT, 2); ?></font>
                                </td>

                                <td nowrap align="center" style="font-weight: bold">
                                    <font size=4><?= $var1 . " %"; ?></font>
                                </td>

                                <td align="right" style="font-weight: bold">
                                    <font size=4><?= "$ " . number_format($totalgcT, 2); ?></font>
                                </td>

                                <td nowrap align="center" style="font-weight: bold">
                                    <font size=4><?= $var2 . " %"; ?></font>
                                </td>
                            </tr>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th>Asesor</th>
                                    <th>Mes Pago GC</th>
                                    <th>F Pago GC</th>
                                    <th>Prima Cobrada</th>
                                    <th>Comisión Cobrada</th>
                                    <th>% Com</th>
                                    <th>GC Pagada</th>
                                    <th>%GC Asesor</th>
                                    <th hidden>id</th>
                                    <th hidden>F Pago GC</th>
                                    <th hidden>cod_asesor</th>
                                </tr>
                            </tfoot>
                        </table>

                        <h1 class="font-weight-bold text-center">Total de Prima Cobrada</h1>
                        <h1 class="font-weight-bold text-center text-danger">$ <?php echo number_format($totalprimacomT, 2); ?></h1>
                    </div>


                    <div class="table-responsive-xl">
                    <h3 class="text-black-50 font-weight-bold">GC Referidores</h3>
                        <table class="table table-hover table-striped table-bordered" id="mytableGCRef" style="cursor: pointer;">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th>Referidor</th>
                                    <th>Mes Desde Póliza</th>
                                    <th>Prima Suscrita</th>
                                    <th>Prima Cobrada</th>
                                    <th>Comisión Cobrada</th>
                                    <th style="background-color: #E54848; color: white">Monto GC</th>
                                    <th hidden>id</th>
                                    <th hidden>F Pago GC</th>
                                    <th hidden>cod_asesor</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $totalprimaT = 0;
                                $totalprimacomT = 0;
                                $totalcomisionT = 0;
                                $totalgcT = 0;
                                $cantdistinct_r = ($distinct_r == null) ? 0 : sizeof($distinct_r);
                                for ($a = 0; $a < $cantdistinct_r; $a++) {
                                    $totalprimacom = 0;
                                    $totalcomision = 0;

                                    $distinct_fdp = $obj->get_distinct_fgc_exist_by_r($desde, $hasta, $distinct_r[$a]['codvend']);
                                ?>
                                    <tr>
                                        <?php
                                        if ($distinct_r[$a]['act'] == 0) {
                                        ?>
                                            <td rowspan="<?= sizeof($distinct_fdp); ?>" style="background-color: #D9D9D9;font-weight: bold" class="text-danger align-middle"><?= $distinct_r[$a]['nombre'] . ' (' . $distinct_r[$a]['codvend'] . ')'; ?></td>
                                        <?php
                                        }
                                        if ($distinct_r[$a]['act'] == 1) {
                                        ?>
                                            <td rowspan="<?= sizeof($distinct_fdp); ?>" style="background-color: #D9D9D9;font-weight: bold" class="text-success align-middle"><?= $distinct_r[$a]['nombre'] . ' (' . $distinct_r[$a]['codvend'] . ')'; ?></td>
                                        <?php
                                        }

                                        $totalprima_TA = 0;
                                        $totalprimacom_TA = 0;
                                        $totalcomision_TA = 0;
                                        $totalgc_TA = 0;
                                        for ($b = 0; $b < sizeof($distinct_fdp); $b++) {
                                            $totalprima = 0;
                                            $totalprimacom = 0;
                                            $totalcomision = 0;
                                            $totalgc = 0;

                                            $poliza = $obj->get_gc_exist_by_r_by_fpgc($distinct_r[$a]['codvend'], $distinct_fdp[$b]['mes'],$distinct_fdp[$b]['anio']);

                                            for ($i = 0; $i < sizeof($poliza); $i++) {
                                                $totalprima = $totalprima + $poliza[$i]['prima'];
                                                $totalprimaT = $totalprimaT + $poliza[$i]['prima'];

                                                $monto_pago_prima = $obj->get_comision_by_poliza_id_monto($poliza[$i]['id_poliza']);
                                                $totalprimacom = $totalprimacom + $monto_pago_prima[0]['prima_com'];

                                                $totalcomision = $totalcomision + $monto_pago_prima[0]['comision'];

                                                $totalgc = $totalgc + $poliza[$i]['per_gc'];

                                                $totalprimacomT = $totalprimacomT + $monto_pago_prima[0]['prima_com'];
                                                $totalcomisionT = $totalcomisionT + $monto_pago_prima[0]['comision'];
                                                $totalgcT = $totalgcT + $poliza[$i]['per_gc'];

                                                $no_renov = $obj->verRenov1($poliza[$i]['id_poliza']);

                                                if ($poliza[$i]['currency'] == 1) {
                                                    $currency = "$ ";
                                                } else {
                                                    $currency = "Bs ";
                                                }
                                            }

                                            if (number_format($totalprimacom, 2) == 0.00) {
                                                $totalprimacom = 0;
                                            }

                                            $newFPagoGCMes = $distinct_fdp[$b]['mes'];

                                            $totalprima_TA = $totalprima_TA + $totalprima;
                                            $totalprimacom_TA = $totalprimacom_TA + $totalprimacom;
                                            $totalcomision_TA = $totalcomision_TA + $totalcomision;
                                            $totalgc_TA = $totalgc_TA + $totalgc;
                                        ?>

                                            <td nowrap><?= $mes_arr[$newFPagoGCMes - 1]; ?></td>

                                            <td style="text-align: right;"><?= number_format($totalprima, 2); ?></td>
                                            

                                            <?php if ($totalprimacom < 0) { ?>
                                                <td style="color: #E54848;text-align: right;"><?= "$ " . number_format($totalprimacom, 2); ?></td>
                                            <?php } else { ?>
                                                <td style="text-align: right;"><?= "$ " . number_format($totalprimacom, 2); ?></td>
                                            <?php } ?>

                                            <?php if ($totalcomision < 0) { ?>
                                                <td style="color: #E54848;text-align: right;"><?= "$ " . number_format($totalcomision, 2); ?></td>
                                            <?php } else { ?>
                                                <td style="text-align: right;"><?= "$ " . number_format($totalcomision, 2); ?></td>
                                            <?php } ?>

                                            <?php if ($totalgc < 0) { ?>
                                                <td style="color: #E54848;text-align: right;background-color: #D9D9D9;font-weight: bold"><?= "$ " . number_format($totalgc, 2); ?></td>
                                            <?php } else { ?>
                                                <td style="text-align: right;background-color: #D9D9D9;font-weight: bold"><?= "$ " . number_format($totalgc, 2); ?></td>
                                            <?php } ?>

                                            <td hidden><?= $poliza[0]['id_poliza']; ?></td>

                                            <td hidden><?= $distinct_fdp[$b]['mes']; ?></td>
                                            <td hidden><?= $distinct_fdp[$b]['anio']; ?></td>
                                            <td hidden><?= $distinct_r[$a]['codvend']; ?></td>
                                    </tr>
                            <?php
                                        }
                                        if($_GET['mes'] == null) { ?>

                                        <tr style="background-color: #F53333;color: white">
                                            <td colspan="2"></td>

                                            <td style="text-align: right;font-weight: bold;font-size: 15px">$ <?= number_format($totalprima_TA, 2); ?></td>

                                            <td style="text-align: right;font-weight: bold;font-size: 15px">$ <?= number_format($totalprimacom_TA, 2); ?></td>
                                            <td style="text-align: right;font-weight: bold;font-size: 15px">$ <?= number_format($totalcomision_TA, 2); ?></td>
                                            <td style="text-align: right;font-weight: bold;font-size: 15px">$ <?= number_format($totalgc_TA, 2); ?></td>
                                        </tr>

                                        <?php   }
                                        $totalpoliza = $totalpoliza + sizeof($poliza);
                                    }
                            ?>
                            <tr style="background-color: #2FA4E7;color: white;font-weight: bold" id="no-tocar" class="blue-gradient text-white">
                                <td colspan="2" style="font-weight: bold">Total General</td>

                                <td align="right" style="font-weight: bold">
                                    <font size=4><?= "$ " . number_format($totalprimaT, 2); ?></font>
                                </td>

                                <td align="right" style="font-weight: bold">
                                    <font size=4><?= "$ " . number_format($totalprimacomT, 2); ?></font>
                                </td>
                                <td align="right" style="font-weight: bold">
                                    <font size=4><?= "$ " . number_format($totalcomisionT, 2); ?></font>
                                </td>

                                <td align="right" style="font-weight: bold">
                                    <font size=4><?= "$ " . number_format($totalgcT, 2); ?></font>
                                </td>

                            </tr>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th>Referidor</th>
                                    <th>Mes Desde Póliza</th>
                                    <th>Prima Suscrita</th>
                                    <th>Prima Cobrada</th>
                                    <th>Comisión Cobrada</th>
                                    <th>Monto GC</th>
                                    <th hidden>id</th>
                                    <th hidden>F Pago GC</th>
                                    <th hidden>cod_asesor</th>
                                </tr>
                            </tfoot>
                        </table>

                        <h1 class="font-weight-bold text-center">Total de Prima Cobrada</h1>
                        <h1 class="font-weight-bold text-center text-danger">$ <?php echo number_format($totalprimacomT, 2); ?></h1>
                    </div>

                    <div class="table-responsive-xl">
                    <h3 class="text-black-50 font-weight-bold">GC Proyectos</h3>
                        <table class="table table-hover table-striped table-bordered" id="mytableGCProy" style="cursor: pointer;">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th>Proyecto</th>
                                    <th>Mes Desde Póliza</th>
                                    <th>Prima Suscrita</th>
                                    <th>Prima Cobrada</th>
                                    <th>Comisión Cobrada</th>
                                    <th style="background-color: #E54848; color: white">Monto GC</th>
                                    <th hidden>id</th>
                                    <th hidden>F Pago GC</th>
                                    <th hidden>cod_asesor</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $totalprimaT = 0;
                                $totalprimacomT = 0;
                                $totalcomisionT = 0;
                                $totalgcT = 0;
                                $cantdistinct_p = ($distinct_p == null) ? 0 : sizeof($distinct_p);
                                for ($a = 0; $a < $cantdistinct_p; $a++) {
                                    $totalprimacom = 0;
                                    $totalcomision = 0;

                                    $distinct_fdp = $obj->get_distinct_fgc_exist_by_p($desde, $hasta, $distinct_p[$a]['codvend']);
                                ?>
                                    <tr>
                                        <?php
                                        if ($distinct_p[$a]['act'] == 0) {
                                        ?>
                                            <td rowspan="<?= sizeof($distinct_fdp); ?>" style="background-color: #D9D9D9;font-weight: bold" class="text-danger align-middle"><?= $distinct_p[$a]['nombre'] . ' (' . $distinct_p[$a]['codvend'] . ')'; ?></td>
                                        <?php
                                        }
                                        if ($distinct_p[$a]['act'] == 1) {
                                        ?>
                                            <td rowspan="<?= sizeof($distinct_fdp); ?>" style="background-color: #D9D9D9;font-weight: bold" class="text-success align-middle"><?= $distinct_p[$a]['nombre'] . ' (' . $distinct_p[$a]['codvend'] . ')'; ?></td>
                                        <?php
                                        }

                                        $totalprima_TA = 0;
                                        $totalprimacom_TA = 0;
                                        $totalcomision_TA = 0;
                                        $totalgc_TA = 0;
                                        for ($b = 0; $b < sizeof($distinct_fdp); $b++) {
                                            $totalprima = 0;
                                            $totalprimacom = 0;
                                            $totalcomision = 0;
                                            $totalgc = 0;

                                            $poliza = $obj->get_gc_exist_by_p_by_fpgc($distinct_p[$a]['codvend'], $distinct_fdp[$b]['mes'],$distinct_fdp[$b]['anio']);

                                            for ($i = 0; $i < sizeof($poliza); $i++) {
                                                $totalprima = $totalprima + $poliza[$i]['prima'];
                                                $totalprimaT = $totalprimaT + $poliza[$i]['prima'];

                                                $monto_pago_prima = $obj->get_comision_by_poliza_id_monto($poliza[$i]['id_poliza']);
                                                $totalprimacom = $totalprimacom + $monto_pago_prima[0]['prima_com'];

                                                $totalcomision = $totalcomision + $monto_pago_prima[0]['comision'];

                                                $totalgc = $totalgc + $poliza[$i]['per_gc'];

                                                $totalprimacomT = $totalprimacomT + $monto_pago_prima[0]['prima_com'];
                                                $totalcomisionT = $totalcomisionT + $monto_pago_prima[0]['comision'];
                                                $totalgcT = $totalgcT + $poliza[$i]['per_gc'];

                                                $no_renov = $obj->verRenov1($poliza[$i]['id_poliza']);

                                                if ($poliza[$i]['currency'] == 1) {
                                                    $currency = "$ ";
                                                } else {
                                                    $currency = "Bs ";
                                                }
                                            }

                                            if (number_format($totalprimacom, 2) == 0.00) {
                                                $totalprimacom = 0;
                                            }

                                            $newFPagoGCMes = $distinct_fdp[$b]['mes'];

                                            $totalprima_TA = $totalprima_TA + $totalprima;
                                            $totalprimacom_TA = $totalprimacom_TA + $totalprimacom;
                                            $totalcomision_TA = $totalcomision_TA + $totalcomision;
                                            $totalgc_TA = $totalgc_TA + $totalgc;
                                        ?>

                                            <td nowrap><?= $mes_arr[$newFPagoGCMes - 1]; ?></td>

                                            <td style="text-align: right;"><?= number_format($totalprima, 2); ?></td>
                                            

                                            <?php if ($totalprimacom < 0) { ?>
                                                <td style="color: #E54848;text-align: right;"><?= "$ " . number_format($totalprimacom, 2); ?></td>
                                            <?php } else { ?>
                                                <td style="text-align: right;"><?= "$ " . number_format($totalprimacom, 2); ?></td>
                                            <?php } ?>

                                            <?php if ($totalcomision < 0) { ?>
                                                <td style="color: #E54848;text-align: right;"><?= "$ " . number_format($totalcomision, 2); ?></td>
                                            <?php } else { ?>
                                                <td style="text-align: right;"><?= "$ " . number_format($totalcomision, 2); ?></td>
                                            <?php } ?>

                                            <?php if ($totalgc < 0) { ?>
                                                <td style="color: #E54848;text-align: right;background-color: #D9D9D9;font-weight: bold"><?= "$ " . number_format($totalgc, 2); ?></td>
                                            <?php } else { ?>
                                                <td style="text-align: right;background-color: #D9D9D9;font-weight: bold"><?= "$ " . number_format($totalgc, 2); ?></td>
                                            <?php } ?>

                                            <td hidden><?= $poliza[0]['id_poliza']; ?></td>

                                            <td hidden><?= $distinct_fdp[$b]['mes']; ?></td>
                                            <td hidden><?= $distinct_fdp[$b]['anio']; ?></td>
                                            <td hidden><?= $distinct_p[$a]['codvend']; ?></td>
                                    </tr>
                            <?php
                                        }
                                        if($_GET['mes'] == null) { ?>

                                        <tr style="background-color: #F53333;color: white">
                                            <td colspan="2"></td>

                                            <td style="text-align: right;font-weight: bold;font-size: 15px">$ <?= number_format($totalprima_TA, 2); ?></td>

                                            <td style="text-align: right;font-weight: bold;font-size: 15px">$ <?= number_format($totalprimacom_TA, 2); ?></td>
                                            <td style="text-align: right;font-weight: bold;font-size: 15px">$ <?= number_format($totalcomision_TA, 2); ?></td>
                                            <td style="text-align: right;font-weight: bold;font-size: 15px">$ <?= number_format($totalgc_TA, 2); ?></td>
                                        </tr>

                                        <?php   }
                                        $totalpoliza = $totalpoliza + sizeof($poliza);
                                    }
                            ?>
                            <tr style="background-color: #2FA4E7;color: white;font-weight: bold" id="no-tocar" class="blue-gradient text-white">
                                <td colspan="2" style="font-weight: bold">Total General</td>

                                <td align="right" style="font-weight: bold">
                                    <font size=4><?= "$ " . number_format($totalprimaT, 2); ?></font>
                                </td>

                                <td align="right" style="font-weight: bold">
                                    <font size=4><?= "$ " . number_format($totalprimacomT, 2); ?></font>
                                </td>
                                <td align="right" style="font-weight: bold">
                                    <font size=4><?= "$ " . number_format($totalcomisionT, 2); ?></font>
                                </td>

                                <td align="right" style="font-weight: bold">
                                    <font size=4><?= "$ " . number_format($totalgcT, 2); ?></font>
                                </td>

                            </tr>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th>Proyecto</th>
                                    <th>Mes Desde Póliza</th>
                                    <th>Prima Suscrita</th>
                                    <th>Prima Cobrada</th>
                                    <th>Comisión Cobrada</th>
                                    <th>Monto GC</th>
                                    <th hidden>id</th>
                                    <th hidden>F Pago GC</th>
                                    <th hidden>cod_asesor</th>
                                </tr>
                            </tfoot>
                        </table>

                        <h1 class="font-weight-bold text-center">Total de Prima Cobrada</h1>
                        <h1 class="font-weight-bold text-center text-danger">$ <?php echo number_format($totalprimacomT, 2); ?></h1>
                    </div>

                </div>

                <?php //print_r($Arr);
                //echo sizeof($Arr)-1;
                ?>

                <!--   TABLA PARA USUARIOS QUE SON ASESORES   -->
            <?php }
            if ($_SESSION['id_permiso'] == 3) {
            } ?>


        </div>





        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer.php'; ?>

        <script src="../../assets/view/b_poliza.js"></script>
        <script src="../../assets/view/modalN.js"></script>

        <script>
            function generarR() {

                alertify.confirm('!!', '¿Desea Generar la GC para la búsqueda actual?',
                    function() {
                        window.location.replace("pago_gc_a.php?anio=<?= $_GET['anio']; ?>&mes=<?= $_GET['mes']; ?>&cia=<?= $ciaEnv1; ?>&asesor=<?= $asesorEnv; ?>");
                        /*window.location.replace("../../procesos/agregarGC.php?desde=<?= $desde; ?>&hasta=<?= $hasta; ?>&cia=<?= $ciaEnv; ?>&asesor=<?= $asesorEnv; ?>&tPoliza=<?= $totalpoliza; ?>");*/
                    },
                    function() {
                        alertify.error('Cancelada')
                    }).set('labels', {
                    ok: 'Sí',
                    cancel: 'No'
                }).set({
                    transition: 'zoom'
                }).show();
            }
        </script>
</body>

</html>