<?php
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'b_poliza2';

require_once '../../Controller/Poliza.php';

header("Pragma: public");
header("Expires: 0");
$filename = "Póliza Totales del Ejecutivo ". $polizas[0]['nombre'] .".xls";
header('Content-type: application/x-msdownload; charset=UTF-8');
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");


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
                <th style="background-color: #4285F4; color: white">-</th>
                <th style="background-color: #4285F4; color: white">Nº Póliza</th>
                <th style="background-color: #4285F4; color: white">Cía</th>
                <th style="background-color: #4285F4; color: white">Ramo</th>
                <th style="background-color: #4285F4; color: white">F Desde Seguro</th>
                <th style="background-color: #4285F4; color: white">F Hasta Seguro</th>
                <th style="background-color: #4285F4; color:white;">Prima Suscrita</th>
                <th style="background-color: #4285F4; color: white">Prima Cobrada</th>
                <th style="background-color: #E54848; color:white;">Prima Pendiente</th>
                <th style="background-color: #4285F4; color: white">Nombre Titular</th>
                <th style="background-color: #4285F4; color:white;">GC Pagada</th>
                <th style="background-color: #4285F4; color:white;">F Pago GC</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $totalCantPV = 0;
            foreach ($polizas as $poliza) {
                $currency = ($poliza['currency'] == 1) ? "$ " : "Bs ";

                $totalsuma = $totalsuma + $poliza['sumaasegurada'];
                $totalprima = $totalprima + $poliza['prima'];

                $newDesde = date("Y/m/d", strtotime($poliza['f_desdepoliza']));
                $newHasta = date("Y/m/d", strtotime($poliza['f_hastapoliza']));

                $nombre = $poliza['nombre_t'] . ' ' . $poliza['apellido_t'];

                $primac = $obj->obetnComisiones($poliza['id_poliza']);
                $primacT = $primacT + $primac[0]['SUM(prima_com)'];

                $ppendiente = $poliza['prima'] - $primac[0]['SUM(prima_com)'];
                $ppendiente = number_format($ppendiente, 2);
                if ($ppendiente >= -0.10 && $ppendiente <= 0.10) {
                    $ppendiente = 0;
                }

                $no_renov = $obj->verRenov1($poliza['id_poliza']);

                $tooltip = 'Ramo: ' . $poliza['nramo'];


                // Obtener comisiones para la gc pagada
                $polizap = $obj->get_comision_rep_com_by_id($poliza['id_poliza']);
                $newFPagoGC = '';
                $pGCpago = 0;
                if ($polizap[0]['comision'] != null) {
                    if(substr($polizap[0]['cod_vend'], 0, 1) == 'P' || substr($polizap[0]['cod_vend'], 0, 1) == 'R') {
                        $polizapp = $obj->get_comision_proyecto_by_id($poliza['id_poliza']);
                        $pGCpago = $polizapp[0]['monto_p'];
                        $totalGC = $polizapp[0]['monto_p'];
                        $newFPagoGC = date("d/m/Y", strtotime($polizapp[0]['f_pago_gc_r']));
                        if ($newFPagoGC == '01/01/1970') {
                            $newFPagoGC = '';
                        }
                    } else {
                        for ($i=0; $i < sizeof($polizap); $i++) { 
                            $pGCpago = $pGCpago + ( ($polizap[$i]['comision'] * $polizap[$i]['per_gc']) / 100 );
                            $newFPagoGC = date("d/m/Y", strtotime($polizap[$i]['f_pago_gc']));
                        }
                    }
                }
            ?>
                <tr style="cursor: pointer;">

                    <?php if ($poliza['id_tpoliza'] == 1) { ?>
                        <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Nueva">N<span hidden>ueva</span></td>
                    <?php }
                    if ($poliza['id_tpoliza'] == 2) { ?>
                        <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Renovación">R<span hidden>enovacion</span></td>
                    <?php }
                    if ($poliza['id_tpoliza'] == 3) { ?>
                        <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Traspaso de Cartera">T<span hidden>raspaso de Cartera</span></td>
                    <?php } ?>

                    <?php if ($no_renov[0]['no_renov'] != 1) {
                        if ($poliza['f_hastapoliza'] >= date("Y-m-d")) {
                            $primaSV = $primaSV + $poliza['prima'];
                            $primaCV = $primaCV + $primac[0]['SUM(prima_com)'];
                            $totalCantPV = $totalCantPV + 1;
                    ?>
                            <td style="color: #2B9E34;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>">
                                <?= $poliza['cod_poliza']; ?>
                            </td>
                        <?php } else { ?>
                            <td style="color: #E54848;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>">
                                <?= $poliza['cod_poliza']; ?>
                            </td>
                        <?php }
                    } else { ?>
                        <td style="color: #4a148c;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>">
                            <?= $poliza['cod_poliza']; ?>
                        </td>
                    <?php } ?>

                    <td data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>">
                        <?= $poliza['nomcia']; ?>
                    </td>
                    <td data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>">
                        <?= $poliza['nramo']; ?>
                    </td>
                    <td data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>">
                        <?= $newDesde; ?>
                    </td>
                    <td data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>">
                        <?= $newHasta; ?>
                    </td>
                    <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>">
                        <?= $currency . number_format($poliza['prima'], 2); ?>
                    </td>
                    <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>">
                        <?= $currency . number_format($primac[0]['SUM(prima_com)'], 2); ?>
                    </td>

                    <?php if ($ppendiente > 0) { ?>
                        <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#F53333;font-size: 16px"><?= $currency . $ppendiente; ?></td>
                    <?php }
                    if ($ppendiente == 0) { ?>
                        <td style="background-color: #D9D9D9 ;color:black;text-align: right;font-weight: bold;"><?= $currency . $ppendiente; ?></td>
                    <?php }
                    if ($ppendiente < 0) { ?>
                        <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#2B9E34;font-size: 16px"><?= $currency . $ppendiente; ?></td>
                    <?php } ?>
                    

                    <td><?= ($nombre); ?></td>

                    <td style="text-align: right"><?= $currency . number_format($pGCpago,2); ?></td>
                    <td><?= $newFPagoGC; ?></td>

                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>

</html>