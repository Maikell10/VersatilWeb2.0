<?php
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'gc/gc_cargada';

require_once '../../Controller/Poliza.php';

header("Pragma: public");
header("Expires: 0");
$filename = "Resultado GC Total Cargada.xls";
header('Content-type: application/x-msdownload; charset=UTF-8');
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Versatil Seguros</title>

    <style>
        thead th {
            font-size: 23px;
        }

        td {
            border: 1px solid #dee2e6;
        }
    </style>
</head>

<body>

    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th colspan="8">GC Asesores</th>
            </tr>
            <tr>
                <th style="background-color: #4285F4; color: white">Asesor</th>
                <th style="background-color: #4285F4; color: white">Mes Pago GC</th>
                <th style="background-color: #4285F4; color: white">F Pago GC</th>
                <th style="background-color: #4285F4; color: white">Prima Cobrada</th>
                <th style="background-color: #4285F4; color: white">Comisión Cobrada</th>
                <th style="background-color: #4285F4; color: white">% Com</th>
                <th style="background-color: #E54848;color:white;">GC Pagada</th>
                <th style="background-color: #4285F4; color: white">%GC Asesor</th>
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
                        <td rowspan="<?= sizeof($distinct_fpgc); ?>" style="background-color: #D9D9D9;font-weight: bold;color: red" class="align-middle"><?= $distinct_a[$a]['nombre'] . ' (' . $distinct_a[$a]['cod_vend'] . ')'; ?></td>
                    <?php
                    }
                    if ($distinct_a[$a]['act'] == 1) {
                    ?>
                        <td rowspan="<?= sizeof($distinct_fpgc); ?>" style="background-color: #D9D9D9;font-weight: bold;color: green" class="align-middle"><?= $distinct_a[$a]['nombre'] . ' (' . $distinct_a[$a]['cod_vend'] . ')'; ?></td>
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
                </tr>
                <?php
                    }
                    if($_GET['mes'] == null) { ?>

                    <tr>
                        <td colspan="3" style="text-align: right;font-weight: bold;font-size: 15px;background-color: #F53333;color: white"></td>

                        <td style="text-align: right;font-weight: bold;font-size: 15px;background-color: #F53333;color: white">$ <?= number_format($totalprimacom_TA, 2); ?></td>
                        <td style="text-align: right;font-weight: bold;font-size: 15px;background-color: #F53333;color: white">$ <?= number_format($totalcomision_TA, 2); ?></td>
                        <td style="text-align: center;font-weight: bold;font-size: 15px;background-color: #F53333;color: white"><?= number_format($total_per_com_TA / sizeof($distinct_fpgc), 2); ?> %</td>
                        <td style="text-align: right;font-weight: bold;font-size: 15px;background-color: #F53333;color: white">$ <?= number_format($totalgc_TA, 2); ?></td>
                        <td style="text-align: center;font-weight: bold;font-size: 15px;background-color: #F53333;color: white"><?= number_format($total_gc_a_t_TA / sizeof($distinct_fpgc), 2); ?> %</td>
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
            <tr id="no-tocar" class="blue-gradient text-white">
                <td colspan="3" style="font-weight: bold;background-color: #2FA4E7;color: white">Total General</td>

                <td align="right" style="font-weight: bold;background-color: #2FA4E7;color: white">
                    <font size=4><?= "$ " . number_format($totalprimacomT, 2); ?></font>
                </td>
                <td align="right" style="font-weight: bold;background-color: #2FA4E7;color: white">
                    <font size=4><?= "$ " . number_format($totalcomisionT, 2); ?></font>
                </td>

                <td nowrap align="center" style="font-weight: bold;background-color: #2FA4E7;color: white">
                    <font size=4><?= $var1 . " %"; ?></font>
                </td>

                <td align="right" style="font-weight: bold;background-color: #2FA4E7;color: white">
                    <font size=4><?= "$ " . number_format($totalgcT, 2); ?></font>
                </td>

                <td nowrap align="center" style="font-weight: bold;background-color: #2FA4E7;color: white">
                    <font size=4><?= $var2 . " %"; ?></font>
                </td>
            </tr>


            <tr>
                <th colspan="8">GC Referidores</th>
            </tr>
            <tr>
                <th colspan="3" style="background-color: #4285F4; color: white">Referidor</th>
                <th style="background-color: #4285F4; color: white">Mes Desde Póliza</th>
                <th style="background-color: #4285F4; color: white">Prima Suscrita</th>
                <th style="background-color: #4285F4; color: white">Prima Cobrada</th>
                <th style="background-color: #4285F4; color: white">Comisión Cobrada</th>
                <th style="background-color: #E54848; color: white">Monto GC</th>
            </tr>

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
                    <td colspan="3" rowspan="<?= sizeof($distinct_fdp); ?>" style="background-color: #D9D9D9;font-weight: bold" class="text-danger align-middle"><?= $distinct_r[$a]['nombre'] . ' (' . $distinct_r[$a]['codvend'] . ')'; ?></td>
                <?php
                }
                if ($distinct_r[$a]['act'] == 1) {
                ?>
                    <td colspan="3" rowspan="<?= sizeof($distinct_fdp); ?>" style="background-color: #D9D9D9;font-weight: bold" class="text-success align-middle"><?= $distinct_r[$a]['nombre'] . ' (' . $distinct_r[$a]['codvend'] . ')'; ?></td>
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
                </tr>
            <?php
                }
                if($_GET['mes'] == null) { ?>

                <tr>
                    <td colspan="4" style="background-color: #F53333;color: white"></td>

                    <td style="text-align: right;font-weight: bold;font-size: 15px;background-color: #F53333;color: white">$ <?= number_format($totalprima_TA, 2); ?></td>

                    <td style="text-align: right;font-weight: bold;font-size: 15px;background-color: #F53333;color: white">$ <?= number_format($totalprimacom_TA, 2); ?></td>
                    <td style="text-align: right;font-weight: bold;font-size: 15px;background-color: #F53333;color: white">$ <?= number_format($totalcomision_TA, 2); ?></td>
                    <td style="text-align: right;font-weight: bold;font-size: 15px;background-color: #F53333;color: white">$ <?= number_format($totalgc_TA, 2); ?></td>
                </tr>

                <?php   }
                $totalpoliza = $totalpoliza + sizeof($poliza);
            }
            ?>
            <tr id="no-tocar" class="blue-gradient text-white">
                <td colspan="4" style="font-weight: bold;background-color: #2FA4E7;color: white">Total General</td>

                <td align="right" style="font-weight: bold;background-color: #2FA4E7;color: white">
                    <font size=4><?= "$ " . number_format($totalprimaT, 2); ?></font>
                </td>

                <td align="right" style="font-weight: bold;background-color: #2FA4E7;color: white">
                    <font size=4><?= "$ " . number_format($totalprimacomT, 2); ?></font>
                </td>
                <td align="right" style="font-weight: bold;background-color: #2FA4E7;color: white">
                    <font size=4><?= "$ " . number_format($totalcomisionT, 2); ?></font>
                </td>

                <td align="right" style="font-weight: bold;background-color: #2FA4E7;color: white">
                    <font size=4><?= "$ " . number_format($totalgcT, 2); ?></font>
                </td>
            </tr>

            <tr>
                <th colspan="8">GC Proyectos</th>
            </tr>
            <tr>
                <th colspan="3" style="background-color: #4285F4; color: white">Proyecto</th>
                <th style="background-color: #4285F4; color: white">Mes Desde Póliza</th>
                <th style="background-color: #4285F4; color: white">Prima Suscrita</th>
                <th style="background-color: #4285F4; color: white">Prima Cobrada</th>
                <th style="background-color: #4285F4; color: white">Comisión Cobrada</th>
                <th style="background-color: #E54848; color: white">Monto GC</th>
            </tr>

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
                    <td colspan="3" rowspan="<?= sizeof($distinct_fdp); ?>" style="background-color: #D9D9D9;font-weight: bold" class="text-danger align-middle"><?= $distinct_p[$a]['nombre'] . ' (' . $distinct_p[$a]['codvend'] . ')'; ?></td>
                <?php
                }
                if ($distinct_p[$a]['act'] == 1) {
                ?>
                    <td colspan="3" rowspan="<?= sizeof($distinct_fdp); ?>" style="background-color: #D9D9D9;font-weight: bold" class="text-success align-middle"><?= $distinct_p[$a]['nombre'] . ' (' . $distinct_p[$a]['codvend'] . ')'; ?></td>
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
                </tr>
            <?php
                }
                if($_GET['mes'] == null) { ?>

                <tr>
                    <td colspan="4" style="background-color: #F53333;color: white"></td>

                    <td style="text-align: right;font-weight: bold;font-size: 15px;background-color: #F53333;color: white">$ <?= number_format($totalprima_TA, 2); ?></td>

                    <td style="text-align: right;font-weight: bold;font-size: 15px;background-color: #F53333;color: white">$ <?= number_format($totalprimacom_TA, 2); ?></td>
                    <td style="text-align: right;font-weight: bold;font-size: 15px;background-color: #F53333;color: white">$ <?= number_format($totalcomision_TA, 2); ?></td>
                    <td style="text-align: right;font-weight: bold;font-size: 15px;background-color: #F53333;color: white">$ <?= number_format($totalgc_TA, 2); ?></td>
                </tr>

                <?php   }
                $totalpoliza = $totalpoliza + sizeof($poliza);
            }
            ?>
            <tr id="no-tocar" class="blue-gradient text-white">
                <td colspan="4" style="font-weight: bold;background-color: #2FA4E7;color: white">Total General</td>

                <td align="right" style="font-weight: bold;background-color: #2FA4E7;color: white">
                    <font size=4><?= "$ " . number_format($totalprimaT, 2); ?></font>
                </td>

                <td align="right" style="font-weight: bold;background-color: #2FA4E7;color: white">
                    <font size=4><?= "$ " . number_format($totalprimacomT, 2); ?></font>
                </td>
                <td align="right" style="font-weight: bold;background-color: #2FA4E7;color: white">
                    <font size=4><?= "$ " . number_format($totalcomisionT, 2); ?></font>
                </td>

                <td align="right" style="font-weight: bold;background-color: #2FA4E7;color: white">
                    <font size=4><?= "$ " . number_format($totalgcT, 2); ?></font>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>