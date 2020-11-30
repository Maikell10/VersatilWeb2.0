<?php
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'prima_moroso_excel';

require_once '../../Controller/Poliza.php';


header("Pragma: public");
header("Expires: 0");
$filename = "Listado de Seguimiento de la Cobranza de Primas.xls";
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
                <th style="background-color: #4285F4; color: white">N° Póliza</th>
                <th style="background-color: #4285F4; color: white">Nombre Titular</th>
                <th style="background-color: #4285F4; color: white">F Desde Seguro</th>
                <th style="background-color: #4285F4; color: white">Cía</th>
                <th style="background-color: #4285F4; color: white">Ramo</th>
                <th style="background-color: #4285F4; color: white">Ejecutivo</th>
                <th style="background-color: #4285F4; color: white">Prima Suscrita</th>
                <th style="background-color: #4285F4; color: white">Prima Cobrada</th>
                <th style="background-color: #E54848; color: white">Dif Prima</th>
                <th style="background-color: #4285F4; color: white">Ene</th>
                <th style="background-color: #4285F4; color: white">Feb</th>
                <th style="background-color: #4285F4; color: white">Mar</th>
                <th style="background-color: #4285F4; color: white">Abr</th>
                <th style="background-color: #4285F4; color: white">May</th>
                <th style="background-color: #4285F4; color: white">Jun</th>
                <th style="background-color: #4285F4; color: white">Jul</th>
                <th style="background-color: #4285F4; color: white">Ago</th>
                <th style="background-color: #4285F4; color: white">Sep</th>
                <th style="background-color: #4285F4; color: white">Oct</th>
                <th style="background-color: #4285F4; color: white">Nov</th>
                <th style="background-color: #4285F4; color: white">Dic</th>
            </tr>
        </thead>

        <tbody>
            <?php
            $c = 0;
            $totalpsMes = 0;
            $totalpcMes = 0;
            $totaldifMes = 0;
            $totalpoliza = $totalpoliza + $cantPolizas;

            $cont1 = (isset($p_dif1)) ? sizeof($p_dif1) : 0;
            for ($i = 0; $i < $cont1; $i++) {
                $no_renov = $obj->verRenov1($idpoliza1[$i]);
                $ppendiente = number_format($p_dif1[$i], 2);
                if ($ppendiente >= -0.10 && $ppendiente <= 0.10) {
                    $ppendiente = 0;
                }
                $totalpsMes = $totalpsMes + $prima_s1[$i];
                $totalpcMes = $totalpcMes + $p_tt1[$i];
                $totaldifMes = $totaldifMes + ($prima_s1[$i] - $p_tt1[$i]);
            ?>
                <tr>
                    <?php if ($no_renov[0]['no_renov'] != 1) {
                        if ($f_hasta_poliza1[$i] >= date("Y-m-d")) { ?>
                            <td style="color: #2B9E34;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$i]; ?>"><?= $cod_poliza1[$i]; ?></td>
                        <?php } else { ?>
                            <td style="color: #E54848;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$i]; ?>"><?= $cod_poliza1[$i]; ?></td>
                        <?php }
                    } else { ?>
                        <td style="color: #4a148c;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$i]; ?>"><?= $cod_poliza1[$i]; ?></td>
                    <?php } ?>

                    <td data-toggle="tooltip" data-placement="top" title="<?= $tool1[$i]; ?>"><?= $ciente1[$i]; ?></td>

                    <td ><?= $newDesde1[$i]; ?></td>
                    <td ><?= $nomcia1[$i]; ?></td>
                    <td ><?= $nramo1[$i]; ?></td>
                    <td ><?= $ejecutivo1[$i]; ?></td>

                    <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="Prima Suscrita" nowrap><?= '$ ' . number_format($prima_s1[$i], 2); ?></td>
                    <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="Prima Total" nowrap><?= '$ ' . number_format($p_tt1[$i], 2); ?></td>

                    <?php if ($no_renov[0]['no_renov'] != 1) {
                        if ($ppendiente > 0) { ?>
                            <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#F53333;font-size: 16px" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$i]; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                        <?php }
                        if ($ppendiente == 0) { ?>
                            <td style="background-color: #D9D9D9 ;color:black;text-align: right;font-weight: bold;" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$i]; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                        <?php }
                        if ($ppendiente < 0) { ?>
                            <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#2B9E34;font-size: 16px" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$i]; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                        <?php }
                    } else { ?>
                        <td style="background-color: #D9D9D9 ;color:#4a148c;text-align: right;font-weight: bold;" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$i]; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                    <?php } ?>

                    <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Enero Año: ' . $a_enero1[$i]; ?>" nowrap><?= '$ ' . number_format($p_enero1[$i], 2); ?></td>

                    <td style="text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Febrero Año: ' . $a_febrero1[$i]; ?>" nowrap><?= '$ ' . number_format($p_febrero1[$i], 2); ?></td>

                    <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Marzo Año: ' . $a_marzo1[$i]; ?>" nowrap><?= '$ ' . number_format($p_marzo1[$i], 2); ?></td>

                    <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Abril Año: ' . $a_abril1[$i]; ?>" nowrap><?= '$ ' . number_format($p_abril1[$i], 2); ?></td>

                    <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Mayo Año: ' . $a_mayo1[$i]; ?>" nowrap><?= '$ ' . number_format($p_mayo1[$i], 2); ?></td>

                    <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Junio Año: ' . $a_junio1[$i]; ?>" nowrap><?= '$ ' . number_format($p_junio1[$i], 2); ?></td>

                    <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Julio Año: ' . $a_julio1[$i]; ?>" nowrap><?= '$ ' . number_format($p_julio1[$i], 2); ?></td>

                    <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Agosto Año: ' . $a_agosto1[$i]; ?>" nowrap><?= '$ ' . number_format($p_agosto1[$i], 2); ?></td>

                    <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Septiembre Año: ' . $a_septiempre1[$i]; ?>" nowrap><?= '$ ' . number_format($p_septiempre1[$i], 2); ?></td>

                    <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Octubre Año: ' . $a_octubre1[$i]; ?>" nowrap><?= '$ ' . number_format($p_octubre1[$i], 2); ?></td>

                    <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Noviembre Año: ' . $a_noviembre1[$i]; ?>" nowrap><?= '$ ' . number_format($p_noviembre1[$i], 2); ?></td>

                    <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Diciembre Año: ' . $a_diciembre1[$i]; ?>" nowrap><?= '$ ' . number_format($p_diciembre1[$i], 2); ?></td>
                </tr>
            <?php }
            //parte 2 variable terminando en a
            $cont2 = (isset($p_dif1a)) ? sizeof($p_dif1a) : 0;
            for ($i = 0; $i < $cont2; $i++) {
                $no_renov = $obj->verRenov1($idpoliza1a[$i]);
                $ppendiente = number_format($p_dif1a[$i], 2);
                if ($ppendiente >= -0.10 && $ppendiente <= 0.10) {
                    $ppendiente = 0;
                }
                $totalpsMes = $totalpsMes + $prima_s1a[$i];
                $totalpcMes = $totalpcMes + $p_tt1a[$i];
                $totaldifMes = $totaldifMes + ($prima_s1a[$i] - $p_tt1a[$i]);
            ?>
                <tr>
                    <?php if ($no_renov[0]['no_renov'] != 1) {
                        if ($f_hasta_poliza1a[$i] >= date("Y-m-d")) { ?>
                            <td style="color: #2B9E34;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool1a[$i]; ?>"><?= $cod_poliza1a[$i]; ?></td>
                        <?php } else { ?>
                            <td style="color: #E54848;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool1a[$i]; ?>"><?= $cod_poliza1a[$i]; ?></td>
                        <?php }
                    } else { ?>
                        <td style="color: #4a148c;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool1a[$i]; ?>"><?= $cod_poliza1a[$i]; ?></td>
                    <?php } ?>

                    <td data-toggle="tooltip" data-placement="top" title="<?= $tool1a[$i]; ?>"><?= $ciente1a[$i]; ?></td>

                    <td ><?= $newDesde1a[$i]; ?></td>
                    <td ><?= $nomcia1a[$i]; ?></td>
                    <td ><?= $nramo1a[$i]; ?></td>
                    <td ><?= $ejecutivo1a[$i]; ?></td>

                    <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="Prima Suscrita" nowrap><?= '$ ' . number_format($prima_s1a[$i], 2); ?></td>
                    <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="Prima Total" nowrap><?= '$ ' . number_format($p_tt1a[$i], 2); ?></td>

                    <?php if ($no_renov[0]['no_renov'] != 1) {
                        if ($ppendiente > 0) { ?>
                            <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#F53333;font-size: 16px" data-toggle="tooltip" data-placement="top" title="<?= $tool1a[$i]; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                        <?php }
                        if ($ppendiente == 0) { ?>
                            <td style="background-color: #D9D9D9 ;color:black;text-align: right;font-weight: bold;" data-toggle="tooltip" data-placement="top" title="<?= $tool1a[$i]; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                        <?php }
                        if ($ppendiente < 0) { ?>
                            <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#2B9E34;font-size: 16px" data-toggle="tooltip" data-placement="top" title="<?= $tool1a[$i]; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                        <?php }
                    } else { ?>
                        <td style="background-color: #D9D9D9 ;color:#4a148c;text-align: right;font-weight: bold;" data-toggle="tooltip" data-placement="top" title="<?= $tool1a[$i]; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                    <?php } ?>

                    <?php if ($p_enero1a[$i] > 0) { ?>
                        <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Enero Año: ' . $a_enero1a[$i]; ?>" nowrap><?= '$ ' . number_format($p_enero1a[$i], 2); ?></td>
                    <?php } else { ?>
                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Enero Año: ' . $a_enero1a[$i]; ?>" nowrap><?= '$ ' . number_format($p_enero1a[$i], 2); ?></td>
                    <?php } ?>

                    <?php if ($p_febrero1a[$i] > 0) { ?>
                        <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Febrero Año: ' . $a_febrero1a[$i]; ?>" nowrap><?= '$ ' . number_format($p_febrero1a[$i], 2); ?></td>
                    <?php } else { ?>
                        <td style="text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Febrero Año: ' . $a_febrero1a[$i]; ?>" nowrap><?= '$ ' . number_format($p_febrero1a[$i], 2); ?></td>
                    <?php } ?>

                    <?php if ($p_marzo1a[$i] > 0) { ?>
                        <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Marzo Año: ' . $a_marzo1a[$i]; ?>" nowrap><?= '$ ' . number_format($p_marzo1a[$i], 2); ?></td>
                    <?php } else { ?>
                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Marzo Año: ' . $a_marzo1a[$i]; ?>" nowrap><?= '$ ' . number_format($p_marzo1a[$i], 2); ?></td>
                    <?php } ?>

                    <?php if ($p_abril1a[$i] > 0) { ?>
                        <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Abril Año: ' . $a_abril1a[$i]; ?>" nowrap><?= '$ ' . number_format($p_abril1a[$i], 2); ?></td>
                    <?php } else { ?>
                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Abril Año: ' . $a_abril1a[$i]; ?>" nowrap><?= '$ ' . number_format($p_abril1a[$i], 2); ?></td>
                    <?php } ?>

                    <?php if ($p_mayo1a[$i] > 0) { ?>
                        <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Mayo Año: ' . $a_mayo1a[$i]; ?>" nowrap><?= '$ ' . number_format($p_mayo1a[$i], 2); ?></td>
                    <?php } else { ?>
                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Mayo Año: ' . $a_mayo1a[$i]; ?>" nowrap><?= '$ ' . number_format($p_mayo1a[$i], 2); ?></td>
                    <?php } ?>

                    <?php if ($p_junio1a[$i] > 0) { ?>
                        <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Junio Año: ' . $a_junio1a[$i]; ?>" nowrap><?= '$ ' . number_format($p_junio1a[$i], 2); ?></td>
                    <?php } else { ?>
                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Junio Año: ' . $a_junio1a[$i]; ?>" nowrap><?= '$ ' . number_format($p_junio1a[$i], 2); ?></td>
                    <?php } ?>

                    <?php if ($p_julio1a[$i] > 0) { ?>
                        <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Julio Año: ' . $a_julio1a[$i]; ?>" nowrap><?= '$ ' . number_format($p_julio1a[$i], 2); ?></td>
                    <?php } else { ?>
                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Julio Año: ' . $a_julio1a[$i]; ?>" nowrap><?= '$ ' . number_format($p_julio1a[$i], 2); ?></td>
                    <?php } ?>

                    <?php if ($p_agosto1a[$i] > 0) { ?>
                        <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Agosto Año: ' . $a_agosto1a[$i]; ?>" nowrap><?= '$ ' . number_format($p_agosto1a[$i], 2); ?></td>
                    <?php } else { ?>
                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Agosto Año: ' . $a_agosto1a[$i]; ?>" nowrap><?= '$ ' . number_format($p_agosto1a[$i], 2); ?></td>
                    <?php } ?>

                    <?php if ($p_septiempre1a[$i] > 0) { ?>
                        <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Septiembre Año: ' . $a_septiempre1a[$i]; ?>" nowrap><?= '$ ' . number_format($p_septiempre1a[$i], 2); ?></td>
                    <?php } else { ?>
                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Septiembre Año: ' . $a_septiempre1a[$i]; ?>" nowrap><?= '$ ' . number_format($p_septiempre1a[$i], 2); ?></td>
                    <?php } ?>

                    <?php if ($p_octubre1a[$i] > 0) { ?>
                        <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Octubre Año: ' . $a_octubre1a[$i]; ?>" nowrap><?= '$ ' . number_format($p_octubre1a[$i], 2); ?></td>
                    <?php } else { ?>
                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Octubre Año: ' . $a_octubre1a[$i]; ?>" nowrap><?= '$ ' . number_format($p_octubre1a[$i], 2); ?></td>
                    <?php } ?>

                    <?php if ($p_noviembre1a[$i] > 0) { ?>
                        <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Noviembre Año: ' . $a_noviembre1a[$i]; ?>" nowrap><?= '$ ' . number_format($p_noviembre1a[$i], 2); ?></td>
                    <?php } else { ?>
                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Noviembre Año: ' . $a_noviembre1a[$i]; ?>" nowrap><?= '$ ' . number_format($p_noviembre1a[$i], 2); ?></td>
                    <?php } ?>

                    <?php if ($p_diciembre1a[$i] > 0) { ?>
                        <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Diciembre Año: ' . $a_diciembre1a[$i]; ?>" nowrap><?= '$ ' . number_format($p_diciembre1a[$i], 2); ?></td>
                    <?php } else { ?>
                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Diciembre Año: ' . $a_diciembre1a[$i]; ?>" nowrap><?= '$ ' . number_format($p_diciembre1a[$i], 2); ?></td>
                    <?php } ?>
                </tr>
            <?php }


            //parte 3 variable terminando en b
            $cont3 = (isset($p_dif1b)) ? sizeof($p_dif1b) : 0;
            for ($i = 0; $i < $cont3; $i++) {
                $no_renov = $obj->verRenov1($idpoliza1b[$i]);
                $ppendiente = number_format($p_dif1b[$i], 2);
                if ($ppendiente >= -0.10 && $ppendiente <= 0.10) {
                    $ppendiente = 0;
                }
                $totalpsMes = $totalpsMes + $prima_s1b[$i];
                $totalpcMes = $totalpcMes + $p_tt1b[$i];
                $totaldifMes = $totaldifMes + ($prima_s1b[$i] - $p_tt1b[$i]);
            ?>
                <tr>
                    <?php if ($no_renov[0]['no_renov'] != 1) {
                        if ($f_hasta_poliza1b[$i] >= date("Y-m-d")) { ?>
                            <td style="color: #2B9E34;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool1b[$i]; ?>"><?= $cod_poliza1b[$i]; ?></td>
                        <?php } else { ?>
                            <td style="color: #E54848;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool1b[$i]; ?>"><?= $cod_poliza1b[$i]; ?></td>
                        <?php }
                    } else { ?>
                        <td style="color: #4a148c;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool1b[$i]; ?>"><?= $cod_poliza1b[$i]; ?></td>
                    <?php } ?>

                    <td data-toggle="tooltip" data-placement="top" title="<?= $tool1b[$i]; ?>"><?= $ciente1b[$i]; ?></td>

                    <td ><?= $newDesde1b[$i]; ?></td>
                    <td ><?= $nomcia1b[$i]; ?></td>
                    <td ><?= $nramo1b[$i]; ?></td>
                    <td ><?= $ejecutivo1b[$i]; ?></td>

                    <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="Prima Suscrita" nowrap><?= '$ ' . number_format($prima_s1b[$i], 2); ?></td>
                    <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="Prima Total" nowrap><?= '$ ' . number_format($p_tt1b[$i], 2); ?></td>

                    <?php if ($no_renov[0]['no_renov'] != 1) {
                        if ($ppendiente > 0) { ?>
                            <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#F53333;font-size: 16px" data-toggle="tooltip" data-placement="top" title="<?= $tool1b[$i]; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                        <?php }
                        if ($ppendiente == 0) { ?>
                            <td style="background-color: #D9D9D9 ;color:black;text-align: right;font-weight: bold;" data-toggle="tooltip" data-placement="top" title="<?= $tool1b[$i]; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                        <?php }
                        if ($ppendiente < 0) { ?>
                            <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#2B9E34;font-size: 16px" data-toggle="tooltip" data-placement="top" title="<?= $tool1b[$i]; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                        <?php }
                    } else { ?>
                        <td style="background-color: #D9D9D9 ;color:#4a148c;text-align: right;font-weight: bold;" data-toggle="tooltip" data-placement="top" title="<?= $tool1b[$i]; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                    <?php } ?>

                    <?php if ($p_enero1b[$i] > 0) { ?>
                        <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Enero Año: ' . $a_enero1b[$i]; ?>" nowrap><?= '$ ' . number_format($p_enero1b[$i], 2); ?></td>
                    <?php } else { ?>
                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Enero Año: ' . $a_enero1b[$i]; ?>" nowrap><?= '$ ' . number_format($p_enero1b[$i], 2); ?></td>
                    <?php } ?>

                    <?php if ($p_febrero1b[$i] > 0) { ?>
                        <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Febrero Año: ' . $a_febrero1b[$i]; ?>" nowrap><?= '$ ' . number_format($p_febrero1b[$i], 2); ?></td>
                    <?php } else { ?>
                        <td style="text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Febrero Año: ' . $a_febrero1b[$i]; ?>" nowrap><?= '$ ' . number_format($p_febrero1b[$i], 2); ?></td>
                    <?php } ?>

                    <?php if ($p_marzo1b[$i] > 0) { ?>
                        <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Marzo Año: ' . $a_marzo1b[$i]; ?>" nowrap><?= '$ ' . number_format($p_marzo1b[$i], 2); ?></td>
                    <?php } else { ?>
                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Marzo Año: ' . $a_marzo1b[$i]; ?>" nowrap><?= '$ ' . number_format($p_marzo1b[$i], 2); ?></td>
                    <?php } ?>

                    <?php if ($p_abril1b[$i] > 0) { ?>
                        <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Abril Año: ' . $a_abril1b[$i]; ?>" nowrap><?= '$ ' . number_format($p_abril1b[$i], 2); ?></td>
                    <?php } else { ?>
                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Abril Año: ' . $a_abril1b[$i]; ?>" nowrap><?= '$ ' . number_format($p_abril1b[$i], 2); ?></td>
                    <?php } ?>

                    <?php if ($p_mayo1b[$i] > 0) { ?>
                        <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Mayo Año: ' . $a_mayo1b[$i]; ?>" nowrap><?= '$ ' . number_format($p_mayo1b[$i], 2); ?></td>
                    <?php } else { ?>
                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Mayo Año: ' . $a_mayo1b[$i]; ?>" nowrap><?= '$ ' . number_format($p_mayo1b[$i], 2); ?></td>
                    <?php } ?>

                    <?php if ($p_junio1b[$i] > 0) { ?>
                        <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Junio Año: ' . $a_junio1b[$i]; ?>" nowrap><?= '$ ' . number_format($p_junio1b[$i], 2); ?></td>
                    <?php } else { ?>
                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Junio Año: ' . $a_junio1b[$i]; ?>" nowrap><?= '$ ' . number_format($p_junio1b[$i], 2); ?></td>
                    <?php } ?>

                    <?php if ($p_julio1b[$i] > 0) { ?>
                        <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Julio Año: ' . $a_julio1b[$i]; ?>" nowrap><?= '$ ' . number_format($p_julio1b[$i], 2); ?></td>
                    <?php } else { ?>
                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Julio Año: ' . $a_julio1b[$i]; ?>" nowrap><?= '$ ' . number_format($p_julio1b[$i], 2); ?></td>
                    <?php } ?>

                    <?php if ($p_agosto1b[$i] > 0) { ?>
                        <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Agosto Año: ' . $a_agosto1b[$i]; ?>" nowrap><?= '$ ' . number_format($p_agosto1b[$i], 2); ?></td>
                    <?php } else { ?>
                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Agosto Año: ' . $a_agosto1b[$i]; ?>" nowrap><?= '$ ' . number_format($p_agosto1b[$i], 2); ?></td>
                    <?php } ?>

                    <?php if ($p_septiempre1b[$i] > 0) { ?>
                        <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Septiembre Año: ' . $a_septiempre1b[$i]; ?>" nowrap><?= '$ ' . number_format($p_septiempre1b[$i], 2); ?></td>
                    <?php } else { ?>
                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Septiembre Año: ' . $a_septiempre1b[$i]; ?>" nowrap><?= '$ ' . number_format($p_septiempre1b[$i], 2); ?></td>
                    <?php } ?>

                    <?php if ($p_octubre1b[$i] > 0) { ?>
                        <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Octubre Año: ' . $a_octubre1b[$i]; ?>" nowrap><?= '$ ' . number_format($p_octubre1b[$i], 2); ?></td>
                    <?php } else { ?>
                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Octubre Año: ' . $a_octubre1b[$i]; ?>" nowrap><?= '$ ' . number_format($p_octubre1b[$i], 2); ?></td>
                    <?php } ?>

                    <?php if ($p_noviembre1b[$i] > 0) { ?>
                        <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Noviembre Año: ' . $a_noviembre1b[$i]; ?>" nowrap><?= '$ ' . number_format($p_noviembre1b[$i], 2); ?></td>
                    <?php } else { ?>
                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Noviembre Año: ' . $a_noviembre1b[$i]; ?>" nowrap><?= '$ ' . number_format($p_noviembre1b[$i], 2); ?></td>
                    <?php } ?>

                    <?php if ($p_diciembre1b[$i] > 0) { ?>
                        <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Diciembre Año: ' . $a_diciembre1b[$i]; ?>" nowrap><?= '$ ' . number_format($p_diciembre1b[$i], 2); ?></td>
                    <?php } else { ?>
                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Diciembre Año: ' . $a_diciembre1b[$i]; ?>" nowrap><?= '$ ' . number_format($p_diciembre1b[$i], 2); ?></td>
                    <?php } ?>
                </tr>
            <?php }


            //parte 4 variable terminando en c
            $cont4 = (isset($p_dif1c)) ? sizeof($p_dif1c) : 0;
            for ($i = 0; $i < $cont4; $i++) {
                $no_renov = $obj->verRenov1($idpoliza1c[$i]);
                $ppendiente = number_format($p_dif1c[$i], 2);
                if ($ppendiente >= -0.10 && $ppendiente <= 0.10) {
                    $ppendiente = 0;
                }
                $totalpsMes = $totalpsMes + $prima_s1c[$i];
                $totalpcMes = $totalpcMes + $p_tt1c[$i];
                $totaldifMes = $totaldifMes + ($prima_s1c[$i] - $p_tt1c[$i]);
            ?>
                <tr>
                    <?php if ($no_renov[0]['no_renov'] != 1) {
                        if ($f_hasta_poliza1c[$i] >= date("Y-m-d")) { ?>
                            <td style="color: #2B9E34;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool1c[$i]; ?>"><?= $cod_poliza1c[$i]; ?></td>
                        <?php } else { ?>
                            <td style="color: #E54848;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool1c[$i]; ?>"><?= $cod_poliza1c[$i]; ?></td>
                        <?php }
                    } else { ?>
                        <td style="color: #4a148c;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool1c[$i]; ?>"><?= $cod_poliza1c[$i]; ?></td>
                    <?php } ?>

                    <td data-toggle="tooltip" data-placement="top" title="<?= $tool1c[$i]; ?>"><?= $ciente1c[$i]; ?></td>

                    <td ><?= $newDesde1c[$i]; ?></td>
                    <td ><?= $nomcia1c[$i]; ?></td>
                    <td ><?= $nramo1c[$i]; ?></td>
                    <td ><?= $ejecutivo1c[$i]; ?></td>

                    <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="Prima Suscrita" nowrap><?= '$ ' . number_format($prima_s1c[$i], 2); ?></td>
                    <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="Prima Total" nowrap><?= '$ ' . number_format($p_tt1c[$i], 2); ?></td>

                    <?php if ($no_renov[0]['no_renov'] != 1) {
                        if ($ppendiente > 0) { ?>
                            <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#F53333;font-size: 16px" data-toggle="tooltip" data-placement="top" title="<?= $tool1c[$i]; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                        <?php }
                        if ($ppendiente == 0) { ?>
                            <td style="background-color: #D9D9D9 ;color:black;text-align: right;font-weight: bold;" data-toggle="tooltip" data-placement="top" title="<?= $tool1c[$i]; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                        <?php }
                        if ($ppendiente < 0) { ?>
                            <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#2B9E34;font-size: 16px" data-toggle="tooltip" data-placement="top" title="<?= $tool1c[$i]; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                        <?php }
                    } else { ?>
                        <td style="background-color: #D9D9D9 ;color:#4a148c;text-align: right;font-weight: bold;" data-toggle="tooltip" data-placement="top" title="<?= $tool1c[$i]; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                    <?php } ?>

                    <?php if ($p_enero1c[$i] > 0) { ?>
                        <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Enero Año: ' . $a_enero1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_enero1c[$i], 2); ?></td>
                    <?php } else { ?>
                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Enero Año: ' . $a_enero1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_enero1c[$i], 2); ?></td>
                    <?php } ?>

                    <?php if ($p_febrero1c[$i] > 0) { ?>
                        <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Febrero Año: ' . $a_febrero1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_febrero1c[$i], 2); ?></td>
                    <?php } else { ?>
                        <td style="text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Febrero Año: ' . $a_febrero1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_febrero1c[$i], 2); ?></td>
                    <?php } ?>

                    <?php if ($p_marzo1c[$i] > 0) { ?>
                        <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Marzo Año: ' . $a_marzo1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_marzo1c[$i], 2); ?></td>
                    <?php } else { ?>
                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Marzo Año: ' . $a_marzo1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_marzo1c[$i], 2); ?></td>
                    <?php } ?>

                    <?php if ($p_abril1c[$i] > 0) { ?>
                        <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Abril Año: ' . $a_abril1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_abril1c[$i], 2); ?></td>
                    <?php } else { ?>
                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Abril Año: ' . $a_abril1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_abril1c[$i], 2); ?></td>
                    <?php } ?>

                    <?php if ($p_mayo1c[$i] > 0) { ?>
                        <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Mayo Año: ' . $a_mayo1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_mayo1c[$i], 2); ?></td>
                    <?php } else { ?>
                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Mayo Año: ' . $a_mayo1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_mayo1c[$i], 2); ?></td>
                    <?php } ?>

                    <?php if ($p_junio1c[$i] > 0) { ?>
                        <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Junio Año: ' . $a_junio1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_junio1c[$i], 2); ?></td>
                    <?php } else { ?>
                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Junio Año: ' . $a_junio1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_junio1c[$i], 2); ?></td>
                    <?php } ?>

                    <?php if ($p_julio1c[$i] > 0) { ?>
                        <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Julio Año: ' . $a_julio1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_julio1c[$i], 2); ?></td>
                    <?php } else { ?>
                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Julio Año: ' . $a_julio1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_julio1c[$i], 2); ?></td>
                    <?php } ?>

                    <?php if ($p_agosto1c[$i] > 0) { ?>
                        <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Agosto Año: ' . $a_agosto1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_agosto1c[$i], 2); ?></td>
                    <?php } else { ?>
                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Agosto Año: ' . $a_agosto1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_agosto1c[$i], 2); ?></td>
                    <?php } ?>

                    <?php if ($p_septiempre1c[$i] > 0) { ?>
                        <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Septiembre Año: ' . $a_septiempre1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_septiempre1c[$i], 2); ?></td>
                    <?php } else { ?>
                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Septiembre Año: ' . $a_septiempre1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_septiempre1c[$i], 2); ?></td>
                    <?php } ?>

                    <?php if ($p_octubre1c[$i] > 0) { ?>
                        <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Octubre Año: ' . $a_octubre1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_octubre1c[$i], 2); ?></td>
                    <?php } else { ?>
                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Octubre Año: ' . $a_octubre1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_octubre1c[$i], 2); ?></td>
                    <?php } ?>

                    <?php if ($p_noviembre1c[$i] > 0) { ?>
                        <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Noviembre Año: ' . $a_noviembre1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_noviembre1c[$i], 2); ?></td>
                    <?php } else { ?>
                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Noviembre Año: ' . $a_noviembre1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_noviembre1c[$i], 2); ?></td>
                    <?php } ?>

                    <?php if ($p_diciembre1c[$i] > 0) { ?>
                        <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Diciembre Año: ' . $a_diciembre1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_diciembre1c[$i], 2); ?></td>
                    <?php } else { ?>
                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Diciembre Año: ' . $a_diciembre1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_diciembre1c[$i], 2); ?></td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </tbody>

        <tr>
            <td colspan="5" style="background-color: #F53333;color: white;font-weight: bold">Total: <font size=4><?= $cantPolizas; ?></font>
            </td>

            <td nowrap style="background-color: #F53333;color: white;font-weight: bold;text-align: right">
                <font size=4><?= '$ ' . number_format($totalpsMes, 2); ?></font>
            </td>
            <td nowrap style="background-color: #F53333;color: white;font-weight: bold;text-align: right">
                <font size=4><?= '$ ' . number_format($totalpcMes, 2); ?></font>
            </td>
            <td nowrap style="background-color: #F53333;color: white;font-weight: bold;text-align: right">
                <font size=4><?= '$ ' . number_format($totaldifMes, 2); ?></font>
            </td>
            <td colspan="12" style="background-color: #F53333;color: white;font-weight: bold;text-align: right">
            </td>
        </tr>

        <tfoot class="text-center">
            <tr>
                <th>N° Póliza</th>
                <th>Nombre Titular</th>
                <th>F Desde Seguro</th>
                <th>Cía</th>
                <th>Ramo</th>
                <th>Ejecutivo</th>
                <th>Prima Suscrita</th>
                <th>Prima Cobrada</th>
                <th>Dif Prima</th>
                <th>Ene</th>
                <th>Feb</th>
                <th>Mar</th>
                <th>Abr</th>
                <th>May</th>
                <th>Jun</th>
                <th>Jul</th>
                <th>Ago</th>
                <th>Sep</th>
                <th>Oct</th>
                <th>Nov</th>
                <th>Dic</th>
            </tr>
        </tfoot>
    </table>
</body>

</html>