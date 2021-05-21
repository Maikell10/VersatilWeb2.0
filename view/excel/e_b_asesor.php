<?php
DEFINE('DS', DIRECTORY_SEPARATOR);

//$pag = 'b_poliza';

require_once '../../Controller/Poliza.php';

header("Pragma: public");
header("Expires: 0");
$filename = "Pólizas Generales.xls";
header('Content-type: application/x-msdownload; charset=UTF-8');
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");


$pag = 'gc/b_asesor';

require_once '../../Controller/Asesor.php';

$d = new DateTime();
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

        tfoot th {
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
                <th>Fecha de Exportada: <?= $d->format('d-m-Y'); ?></th>
            </tr>
            <tr>
                <th style="background-color: #4285F4; color: white">Nombre</th>
                <th style="background-color: #4285F4; color: white">Código</th>
                <th style="background-color: #4285F4; color: white">Cant Pólizas</th>
                <th style="background-color: #4285F4; color: white">Activas</th>
                <th style="background-color: #4285F4; color: white">Inactivas</th>
                <th style="background-color: #4285F4; color: white">Anuladas</th>
                <th style="background-color: #4285F4; color: white">Total Prima Suscrita</th>
                <th style="background-color: #4285F4; color: white">Total Prima Cobrada</th>
                <th style="background-color: #E54848;color:white;">Total Prima Pendiente</th>
                <th style="background-color: #4285F4; color: white">% Prima Cobrada de toda la Cartera</th>
                <th style="background-color: #4285F4; color: white">% Prima Cobrada del Asesor</th>
                <th style="background-color: #4285F4; color: white">GC Pagada</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $obj = new Asesor();
            foreach ($asesores as $asesor) {
                $primaS = $obj->get_prima_s_asesor_total($asesor['cod']);
                for ($i = 0; $i < sizeof($primaS); $i++) {
                    $totalPrimaT = $totalPrimaT + $primaS[$i]['prima'];
                }
            }
            $perCobTotal = 0;
            foreach ($asesores as $asesor) {
                $primaSusc = 0;
                $totalA = 0;
                $totalI = 0;
                $totalAn = 0;
                $primaS = $obj->get_prima_s_asesor_total($asesor['cod']);
                for ($i = 0; $i < sizeof($primaS); $i++) {
                    $primaSusc = $primaSusc + $primaS[$i]['prima'];
                    $totalPrima = $totalPrima + $primaS[$i]['prima'];

                    $no_renov = $obj->verRenov1($primaS[$i]['id_poliza']);
                    if ($no_renov[0]['no_renov'] != 1) {
                        if ($primaS[$i]['f_hastapoliza'] >= date("Y-m-d")) {
                            $totalA = $totalA + 1;
                            $tA = $tA + 1;
                        } else {
                            $totalI = $totalI + 1;
                            $tI = $tI + 1;
                        }
                    } else {
                        $totalAn = $totalAn + 1;
                        $tAn = $tAn + 1;
                    }
                }

                $primaC = $obj->get_prima_c_asesor_total($asesor['cod']);

                $totalPrimaC = $totalPrimaC + $primaC[0];
                $totalCant = $totalCant + sizeof($primaS);

                if ($primaSusc == 0) {
                    $perCob = 0;
                } else {
                    $perCob = ($primaC[0] * 100) / $primaSusc;
                    $perCobTotal = $perCobTotal + $perCob;
                }

                $perCobT = 0;
                if ($totalPrimaT != 0) {
                    $perCobT = ($primaC[0] * 100) / $totalPrimaT;
                }

                $ppendiente = number_format($primaSusc - $primaC[0], 2);
                if ($ppendiente >= -0.10 && $ppendiente <= 0.10) {
                    $ppendiente = 0;
                }

                $tooltip = 'Total Prima Suscrita: ' . number_format($primaSusc, 2) . ' | Total Prima Cobrada: ' . number_format($primaC[0], 2) . ' | % Prima Cobrada del Asesor: ' . number_format($perCob, 2) . '%';

                $gcPagada = 0;
                $gcPago = $obj->get_gc_pago_por_asesor($asesor['cod']);
                $cantGcPago = ($gcPago == 0) ? 0 : sizeof($gcPago);
                for ($i = 0; $i < $cantGcPago; $i++) {
                    $gcPagada = $gcPagada + (($gcPago[$i]['per_gc'] * $gcPago[$i]['comision']) / 100);
                }

                if ($gcPago == 0) {
                    $gcPago = $obj->get_gc_pago_por_proyecto($asesor['cod']);

                    for ($i = 0; $i < sizeof($gcPago); $i++) {
                        $gcPagada = $gcPagada + $gcPago[$i]['monto_p'];
                    }
                }
                $totalgcpagada = $totalgcpagada + $gcPagada;
            ?>
                <tr style="cursor: pointer">

                    <?php if ($asesor['act'] == 1) { ?>
                        <td style="color: #2B9E34;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= utf8_encode($asesor['nombre']); ?></td>
                    <?php } else { ?>
                        <td style="color: #E54848;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= utf8_encode($asesor['nombre']); ?></td>
                    <?php } ?>

                    <td data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $asesor['cod']; ?></td>
                    <td class="text-center" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= sizeof($primaS); ?></td>
                    <td class="text-center" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $totalA; ?></td>
                    <td class="text-center" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $totalI; ?></td>
                    <td class="text-center" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $totalAn; ?></td>

                    <td style="text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= '$ ' . number_format($primaSusc, 2); ?></td>
                    <td style="text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= '$ ' . number_format($primaC[0], 2); ?></td>

                    <?php if ($ppendiente > 0) { ?>
                        <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#F53333;font-size: 16px" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= '$ ' . $ppendiente; ?></td>
                    <?php }
                    if ($ppendiente == 0) { ?>
                        <td style="background-color: #D9D9D9 ;color:black;text-align: right;font-weight: bold;" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= '$ ' . $ppendiente; ?></td>
                    <?php }
                    if ($ppendiente < 0) { ?>
                        <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#2B9E34;font-size: 16px" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= '$ ' . $ppendiente; ?></td>
                    <?php } ?>

                    <td style="text-align: center" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= number_format($perCobT, 2); ?>%</td>

                    <td style="text-align: center" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= number_format($perCob, 2); ?>%</td>

                    <td nowrap style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>">$ <?= number_format($gcPagada, 2); ?></td>
                </tr>
            <?php }

            if ($totalPrima == 0) {
                $totaltotal = 0;
            } else {
                $totaltotal = ($totalPrimaC * 100) / $totalPrima;
            }
            ?>
        </tbody>

        <tfoot class="text-center">
            <tr>
                <th style="background-color: #6da9fc; color: white">Nombre</th>
                <th style="background-color: #6da9fc; color: white">Código</th>
                <th style="background-color: #6da9fc; color: white"><?= $totalCant; ?></th>
                <th style="background-color: #6da9fc; color: white"><?= $tA; ?></th>
                <th style="background-color: #6da9fc; color: white"><?= $tI; ?></th>
                <th style="background-color: #6da9fc; color: white"><?= $tAn; ?></th>

                <th style="background-color: #6da9fc; color: white; text-align: right;font-weight: bold;">$<?= number_format(($totalPrima), 2); ?></th>
                <th style="background-color: #6da9fc; color: white; text-align: right;font-weight: bold;">$<?= number_format(($totalPrimaC), 2); ?></th>

                <?php if (($totalPrima - $totalPrimaC) > 0) { ?>
                    <th style="background-color: #6da9fc;text-align: right;font-weight: bold;color:#F53333;">$<?= number_format(($totalPrima - $totalPrimaC), 2); ?></th>
                <?php }
                if (($totalPrima - $totalPrimaC) == 0) { ?>
                    <th style="background-color: #6da9fc;text-align: right;font-weight: bold;color:black;">$<?= number_format(($totalPrima - $totalPrimaC), 2); ?></th>
                <?php }
                if (($totalPrima - $totalPrimaC) < 0) { ?>
                    <th style="background-color: #6da9fc;text-align: right;font-weight: bold;color:#2B9E34;">$<?= number_format(($totalPrima - $totalPrimaC), 2); ?></th>
                <?php } ?>

                <th style="background-color: #6da9fc; color: white; text-align: center;font-weight: bold;"><?= number_format($totaltotal, 2); ?>%</th>

                <th style="background-color: #6da9fc; color: white; text-align: center;font-weight: bold;"><?= number_format($perCobTotal / sizeof($asesores), 2); ?>%</th>

                <th style="background-color: #6da9fc; color: white; text-align: center;font-weight: bold;">$ <?= number_format($totalgcpagada, 2); ?></th>
            </tr>
        </tfoot>
    </table>
</body>

</html>