<?php
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'v_gc_asesor';

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

    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th style="background-color: #4285F4; color: white">F Pago GC</th>
                <th style="background-color: #4285F4; color: white">-</th>
                <th style="background-color: #4285F4; color: white">N° Póliza</th>
                <th style="background-color: #4285F4; color: white">Nombre Titular</th>
                <th style="background-color: #4285F4; color: white">Cía</th>
                <th style="background-color: #4285F4; color: white">F Pago Prima</th>
                <th style="background-color: #4285F4; color: white">Prima Cobrada</th>
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


            ?>
                <tr style="cursor: pointer">

                    <td data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $mes_arr[date('m', strtotime($poliza[$i]['f_pago_gc'])) - 1] . ' ' . date('Y', strtotime($poliza[$i]['f_pago_gc'])); ?></td>

                    <?php if ($poliza[$i]['id_tpoliza'] == 1) { ?>
                        <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Nueva">N<span hidden>ueva</span></td>
                    <?php } if ($poliza[$i]['id_tpoliza'] == 2) { ?>
                        <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Renovación">R<span hidden>enovacion</span></td>
                    <?php } if ($poliza[$i]['id_tpoliza'] == 3) { ?>
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
                    <td nowrap data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $newFPago; ?></td>

                    <?php if ($poliza[$i]['prima_com'] < 0) { ?>
                        <td style="color: #E54848;text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= "$ " . number_format($poliza[$i]['prima_com'], 2); ?></td>
                    <?php } else { ?>
                        <td style="text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= "$ " . number_format($poliza[$i]['prima_com'], 2); ?></td>
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
</body>

</html>