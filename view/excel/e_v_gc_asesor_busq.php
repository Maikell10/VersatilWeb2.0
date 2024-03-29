<?php
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'v_gc_asesor1';

require_once '../../Controller/Asesor.php';

header("Pragma: public");
header("Expires: 0");
$filename = "Listado GC.xls";
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

    <?php if (substr($asesor[0]['cod'], 0, 1) == 'P' || substr($asesor[0]['cod'], 0, 1) == 'R') { ?>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th style="background-color: #4285F4; color: white">F Pago GC</th>
                    <th style="background-color: #4285F4; color: white">-</th>
                    <th style="background-color: #4285F4; color: white">N° Póliza</th>
                    <th style="background-color: #4285F4; color: white">Nombre Titular</th>
                    <th style="background-color: #4285F4; color: white">Cía</th>
                    <th style="background-color: #4285F4; color: white">Ramo</th>
                    <th style="background-color: #4285F4; color: white">F Pago Prima</th>
                    <th style="background-color: #4285F4; color: white">Prima Suscrita</th>
                    <th style="background-color: #4285F4; color: white">Prima Cobrada</th>
                    <th style="background-color: #4285F4; color: white">Dif Prima</th>
                    <th style="background-color: #4285F4; color: white">Comisión Cobrada</th>
                    <th style="background-color: #4285F4; color: white">% Com</th>
                    <th style="background-color: #E54848; color: white">GC Pagada</th>
                    <th style="background-color: #4285F4; color: white">Monto Convenido</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $Arr[] = null;
                $totalprimacom = 0;
                $totalcomision = 0;
                $totalgc = 0;
                $idpoliza_comp = 0;
                $totalGC = 0;
                for ($a = 0; $a < sizeof($distinct_poliza); $a++) {
                    $obj = new Asesor();
                    $poliza = $obj->get_gc_pago_por_proyecto_by_poliza($distinct_poliza[$a]['id_poliza']);

                    $prima_com = 0;
                    $comision = 0;
                    for ($i = 0; $i < sizeof($poliza); $i++) {
                        $prima_com = $prima_com + $poliza[$i]['prima_com'];
                        $comision = $comision + $poliza[$i]['comision'];

                        $polizapp = $obj->get_comision_proyecto_by_id($poliza[$i]['id_poliza']);
                        $pGCpago = $polizapp[0]['monto_p'];
                        $newFPagoGC = date("d/m/Y", strtotime($polizapp[0]['f_pago_gc_r']));

                        /*if($idpoliza_comp != $polizapp[0]['id_poliza']) {
                            $idpoliza_comp = $polizapp[0]['id_poliza'];
                            $totalGC = $totalGC + $polizapp[0]['monto_p'];
                        }*/

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
                    $totalGC = $totalGC + $polizapp[0]['monto_p'];
                    $totalprimasusT = $totalprimasusT + $poliza[0]['prima'];

                    $pendiente = number_format($poliza[0]['prima'] - $prima_com, 2);
                    if ($pendiente >= -0.10 && $pendiente <= 0.10) {
                        $pendiente = 0;
                    }
                ?>
                    <tr style="cursor: pointer">

                        <td data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $mes_arr[date('m', strtotime($polizapp[0]['f_pago_gc_r'])) - 1] . ' ' . date('Y', strtotime($polizapp[0]['f_pago_gc_r'])); ?></td>

                        <?php if ($poliza[0]['id_tpoliza'] == 1) { ?>
                            <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Nueva">N<span hidden>ueva</span></td>
                        <?php }
                        if ($poliza[0]['id_tpoliza'] == 2) { ?>
                            <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Renovación">R<span hidden>enovacion</span></td>
                        <?php }
                        if ($poliza[0]['id_tpoliza'] == 3) { ?>
                            <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Traspaso de Cartera">T<span hidden>raspaso de Cartera</span></td>
                        <?php } ?>

                        <?php if ($no_renov[0]['no_renov'] != 1) {
                            if ($poliza[$i]['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                <td style="color: #2B9E34;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $poliza[0]['cod_poliza']; ?></td>
                            <?php } else { ?>
                                <td style="color: #E54848;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $poliza[0]['cod_poliza']; ?></td>
                            <?php }
                        } else { ?>
                            <td style="color: #4a148c;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $poliza[0]['cod_poliza']; ?></td>
                        <?php } ?>

                        <?php
                        $originalFPago = $poliza[0]['f_pago_prima'];
                        $newFPago = date("Y/m/d", strtotime($originalFPago));
                        ?>
                        <td data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= ($nombretitu); ?></td>
                        <td nowrap data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= ($poliza[0]['nomcia']); ?></td>
                        <td nowrap data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= ($poliza[0]['nramo']); ?></td>
                        <td nowrap data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $newFPago; ?></td>

                        <td style="text-align: right;">$ <?= number_format($poliza[0]['prima'], 2); ?></td>

                        <?php if ($prima_com < 0) { ?>
                            <td style="color: #E54848;text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= "$ " . number_format($prima_com, 2); ?></td>
                        <?php } else { ?>
                            <td style="text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= "$ " . number_format($prima_com, 2); ?></td>
                        <?php } ?>

                        <?php if ($no_renov[0]['no_renov'] != 1) {
                            if ($pendiente > 0) { ?>
                                <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#F53333;font-size: 16px" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>" nowrap><?= '$ ' . $pendiente; ?></td>
                            <?php }
                            if ($pendiente == 0) { ?>
                                <td style="background-color: #D9D9D9 ;color:black;text-align: right;font-weight: bold;" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>" nowrap><?= '$ ' . $pendiente; ?></td>
                            <?php }
                            if ($pendiente < 0) { ?>
                                <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#2B9E34;font-size: 16px" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>" nowrap><?= '$ ' . $pendiente; ?></td>
                            <?php }
                        } else { ?>
                            <td style="background-color: #D9D9D9 ;color:#4a148c;text-align: right;font-weight: bold;" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>" nowrap><?= '$ ' . $pendiente; ?></td>
                        <?php } ?>

                        <?php if ($comision < 0) { ?>
                            <td style="color: #E54848;text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= "$ " . number_format($comision, 2); ?></td>
                        <?php } else { ?>
                            <td style="text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= "$ " . number_format($comision, 2); ?></td>
                        <?php } ?>

                        <td align="center" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= number_format(($comision * 100) / $prima_com, 0) . " %"; ?></td>

                        <?php if ($no_renov[0]['no_renov'] != 1) {
                            if ($pGCpago < 0) { ?>
                                <td class="align-middle" style="color: #E54848;text-align: right;background-color: #D9D9D9;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= "$ " . number_format($pGCpago, 2); ?></td>
                            <?php } else { ?>
                                <td class="align-middle" style="text-align: right;background-color: #D9D9D9;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= "$ " . number_format($pGCpago, 2); ?></td>

                            <?php }
                        } else { ?>
                            <td class="align-middle" style="color: #4a148c;text-align: right;background-color: #D9D9D9;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= "$ " . number_format($pGCpago, 2); ?></td>
                        <?php } ?>

                        <?php if ($asesor[0]['currency'] == '$') { ?>
                            <td class="align-middle" nowrap align="center" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= '$ ' . number_format($poliza[0]['per_gc'], 0); ?></td>
                        <?php } else { ?>
                            <td class="align-middle" nowrap align="center" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= number_format($poliza[0]['per_gc'], 0) . " %"; ?></td>
                        <?php } ?>
                    </tr>

                <?php
                    //$totalGCP = $totalGCP + (($poliza[$i]['comision'] * $poliza[$i]['per_gc']) / 100);
                }
                if ($totalcomision == 0) {
                    $totalcomision = 1;
                }
                ?>

            </tbody>
        </table>
    <?php } else { ?>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th style="background-color: #4285F4; color: white">F Pago GC</th>
                    <th style="background-color: #4285F4; color: white">-</th>
                    <th style="background-color: #4285F4; color: white">N° Póliza</th>
                    <th style="background-color: #4285F4; color: white">Nombre Titular</th>
                    <th style="background-color: #4285F4; color: white">Cía</th>
                    <th style="background-color: #4285F4; color: white">Ramo</th>
                    <th style="background-color: #4285F4; color: white">F Pago Prima</th>
                    <th style="background-color: #4285F4; color: white">Prima Cobrada</th>
                    <th style="background-color: #4285F4; color: white">Dif Prima</th>
                    <th style="background-color: #4285F4; color: white">Comisión Cobrada</th>
                    <th style="background-color: #4285F4; color: white">% Com</th>
                    <th style="background-color: #E54848; color: white">GC Pagada</th>
                    <th style="background-color: #4285F4; color: white">%GC Asesor</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $Arr[] = null;
                $totalprimacom = 0;
                $totalcomision = 0;
                $totalgc = 0;
                for ($i = 0; $i < sizeof($poliza); $i++) {


                    //$poliza = $obj->get_gc_by_filtro_by_a($desde, $hasta, $cia, $distinct_a[$a]['cod_vend']);

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

                    $pendiente = number_format($poliza[0]['prima'] - $prima_com, 2);
                    if ($pendiente >= -0.10 && $pendiente <= 0.10) {
                        $pendiente = 0;
                    }
                ?>
                    <tr style="cursor: pointer">

                        <td data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $mes_arr[date('m', strtotime($poliza[$i]['f_pago_gc'])) - 1] . ' ' . date('Y', strtotime($poliza[$i]['f_pago_gc'])); ?></td>

                        <?php if ($poliza[$i]['id_tpoliza'] == 1) { ?>
                            <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Nueva">N<span hidden>ueva</span></td>
                        <?php }
                        if ($poliza[$i]['id_tpoliza'] == 2) { ?>
                            <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Renovación">R<span hidden>enovacion</span></td>
                        <?php }
                        if ($poliza[$i]['id_tpoliza'] == 3) { ?>
                            <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Traspaso de Cartera">T<span hidden>raspaso de Cartera</span></td>
                        <?php } ?>

                        <?php if ($no_renov[0]['no_renov'] != 1) {
                            if ($poliza[$i]['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                <td style="color: #2B9E34;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $poliza[$i]['cod_poliza']; ?></td>
                            <?php } else { ?>
                                <td style="color: #E54848;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $poliza[$i]['cod_poliza']; ?></td>
                            <?php }
                        } else { ?>
                            <td style="color: #4a148c;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $poliza[$i]['cod_poliza']; ?></td>
                        <?php } ?>

                        <?php
                        $originalFPago = $poliza[$i]['f_pago_prima'];
                        $newFPago = date("Y/m/d", strtotime($originalFPago));
                        ?>
                        <td data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= ($nombretitu); ?></td>
                        <td nowrap data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= ($poliza[$i]['nomcia']); ?></td>
                        <td nowrap data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= ($poliza[$i]['nramo']); ?></td>
                        <td nowrap data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $newFPago; ?></td>

                        <?php if ($poliza[$i]['prima_com'] < 0) { ?>
                            <td style="color: #E54848;text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= "$ " . number_format($poliza[$i]['prima_com'], 2); ?></td>
                        <?php } else { ?>
                            <td style="text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= "$ " . number_format($poliza[$i]['prima_com'], 2); ?></td>
                        <?php } ?>

                        <?php if ($no_renov[0]['no_renov'] != 1) {
                            if ($pendiente > 0) { ?>
                                <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#F53333;font-size: 16px" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>" nowrap><?= '$ ' . $pendiente; ?></td>
                            <?php }
                            if ($pendiente == 0) { ?>
                                <td style="background-color: #D9D9D9 ;color:black;text-align: right;font-weight: bold;" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>" nowrap><?= '$ ' . $pendiente; ?></td>
                            <?php }
                            if ($pendiente < 0) { ?>
                                <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#2B9E34;font-size: 16px" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>" nowrap><?= '$ ' . $pendiente; ?></td>
                            <?php }
                        } else { ?>
                            <td style="background-color: #D9D9D9 ;color:#4a148c;text-align: right;font-weight: bold;" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>" nowrap><?= '$ ' . $pendiente; ?></td>
                        <?php } ?>

                        <?php if ($poliza[$i]['comision'] < 0) { ?>
                            <td style="color: #E54848;text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= "$ " . number_format($poliza[$i]['comision'], 2); ?></td>
                        <?php } else { ?>
                            <td style="text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= "$ " . number_format($poliza[$i]['comision'], 2); ?></td>
                        <?php } ?>

                        <td align="center" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= number_format(($poliza[$i]['comision'] * 100) / $poliza[$i]['prima_com'], 0) . " %"; ?></td>

                        <?php if ($no_renov[0]['no_renov'] != 1) {
                            if ((($poliza[$i]['comision'] * $poliza[$i]['per_gc']) / 100) < 0) { ?>
                                <td style="color: #E54848;text-align: right;background-color: #D9D9D9;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= "$ " . number_format(($poliza[$i]['comision'] * $poliza[$i]['per_gc']) / 100, 2); ?></td>
                            <?php } else { ?>
                                <td style="text-align: right;background-color: #D9D9D9;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= "$ " . number_format(($poliza[$i]['comision'] * $poliza[$i]['per_gc']) / 100, 2); ?></td>

                            <?php }
                        } else { ?>
                            <td style="color: #4a148c;text-align: right;background-color: #D9D9D9;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= "$ " . number_format(($poliza[$i]['comision'] * $poliza[$i]['per_gc']) / 100, 2); ?></td>
                        <?php } ?>

                        <td nowrap align="center" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= number_format($poliza[$i]['per_gc'], 0) . " %"; ?></td>
                    </tr>

                <?php
                    $totalGCP = $totalGCP + (($poliza[$i]['comision'] * $poliza[$i]['per_gc']) / 100);
                    //$totalcomision = $totalcomision + $poliza[$i]['comision'];
                }
                if ($totalcomision == 0) {
                    $totalcomision = 1;
                }
                ?>

            </tbody>
        </table>
    <?php } ?>
</body>

</html>