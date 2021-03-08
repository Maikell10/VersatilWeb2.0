<?php
DEFINE('DS', DIRECTORY_SEPARATOR);

//$pag = 'b_poliza';

require_once '../../Controller/Poliza.php';

header("Pragma: public");
header("Expires: 0");
$filename = "Pólizas de la Búsqueda.xls";
header('Content-type: application/x-msdownload; charset=UTF-8');
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");


$busq = $_GET['busq'];

//----------------------------------------------------------------------------
$user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);
$asesor_u = $user[0]['cod_vend'];
$permiso = $_SESSION['id_permiso'];
//---------------------------------------------------------------------------


$polizas = $obj->get_poliza_by_busq($busq, $asesor_u);

$cantPolizas = ($polizas != 0) ? sizeof($polizas) : 0;

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
                <th style="background-color: #4285F4; color: white">Nombre Asesor</th>
                <th style="background-color: #4285F4; color: white">Cía</th>
                <th style="background-color: #4285F4; color: white">Ramo</th>
                <th style="background-color: #4285F4; color: white">F Desde Seguro</th>
                <th style="background-color: #4285F4; color: white">F Hasta Seguro</th>
                <th style="background-color: #4285F4; color: white">Prima Suscrita</th>
                <th style="background-color: #4285F4; color: white">Prima Cobrada</th>
                <th style="background-color: #E54848;color:white;">Prima Pendiente</th>
                <th style="background-color: #4285F4; color: white">Nombre Titular</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($cantPolizas != 0) {

                foreach ($polizas as $poliza) {
                    $currency = ($poliza['currency'] == 1) ? "$ " : "Bs ";

                    $totalsuma = $totalsuma + $poliza['sumaasegurada'];
                    $totalprima = $totalprima + $poliza['prima'];

                    $newDesde = date("Y/m/d", strtotime($poliza['f_desdepoliza']));
                    $newHasta = date("Y/m/d", strtotime($poliza['f_hastapoliza']));

                    $nombre = $poliza['nombre_t'] . ' ' . $poliza['apellido_t'];

                    $ejecutivo = $obj->get_ejecutivo_by_cod($poliza['codvend']);

                    $primac = $obj->obetnComisiones($poliza['id_poliza']);

                    $totalprimaC = $totalprimaC + $primac[0]['SUM(prima_com)'];
                    $ppendiente = $poliza['prima'] - $primac[0]['SUM(prima_com)'];
                    $ppendiente = number_format($ppendiente, 2);
                    if ($ppendiente >= -0.10 && $ppendiente <= 0.10) {
                        $ppendiente = 0;
                    }

                    $no_renov = $obj->verRenov1($poliza['id_poliza']);
            ?>
                    <tr style="cursor: pointer;">

                        <?php if ($poliza['id_tpoliza'] == 1) { ?>
                            <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Nueva">Nueva</td>
                        <?php }
                        if ($poliza['id_tpoliza'] == 2) { ?>
                            <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Renovación">Renovacion</td>
                        <?php }
                        if ($poliza['id_tpoliza'] == 3) { ?>
                            <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Traspaso de Cartera">Traspaso de Cartera</td>
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

                        <td nowrap><?= $ejecutivo[0]['nombre'] . ' (' . $poliza['codvend'] . ')'; ?></td>
                        <td><?= $poliza['nomcia']; ?></td>
                        <td><?= $poliza['nramo']; ?></td>
                        <td><?= $newDesde; ?></td>
                        <td><?= $newHasta; ?></td>
                        <td style="text-align: right"><?= $currency . number_format($poliza['prima'], 2); ?></td>
                        <td style="text-align: right"><?= $currency . number_format($primac[0]['SUM(prima_com)'], 2); ?></td>

                        <?php if ($no_renov[0]['no_renov'] != 1) { ?>
                            <?php if ($ppendiente > 0) { ?>
                                <td style="background-color: #D9D9D9 ;text-align: right;font-weight: bold;color:#F53333;font-size: 16px"><?= $currency . $ppendiente; ?></td>
                            <?php }
                            if ($ppendiente == 0) { ?>
                                <td style="background-color: #D9D9D9 ;color:black;text-align: right;font-weight: bold;"><?= $currency . $ppendiente; ?></td>
                            <?php }
                            if ($ppendiente < 0) { ?>
                                <td style="background-color: #D9D9D9 ;text-align: right;font-weight: bold;color:#2B9E34;font-size: 16px"><?= $currency . $ppendiente; ?></td>
                            <?php } ?>
                        <?php } else { ?>

                            <td style="background-color: #D9D9D9 ;text-align: right;font-weight: bold;color:#4a148c;font-size: 16px"><?= $currency . $ppendiente; ?></td>
                        <?php } ?>

                        <td><?= ($nombre); ?></td>


                    </tr>
            <?php }
            } ?>
        </tbody>

        <tfoot>
            <tr>
                <th>-</th>
                <th>N° Póliza</th>
                <th>Nombre Asesor</th>
                <th>Cía</th>
                <th>Ramo</th>
                <th>F Desde Seguro</th>
                <th>F Hasta Seguro</th>
                <th style="font-weight: bold" class="text-right">Prima Suscrita $<?= number_format($totalprima, 2); ?></th>
                <th style="font-weight: bold" class="text-right">Prima Cobrada $<?= number_format($totalprimaC, 2); ?></th>
                <th style="font-weight: bold" class="text-right">Prima Pendiente $<?= number_format($totalprima - $totalprimaC, 2); ?></th>
                <th>Nombre Titular</th>
            </tr>
        </tfoot>
    </table>
</body>

</html>