<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}

$pag = 'prima_detail';

require_once '../Controller/Poliza.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . '\layout\header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . '\layout\navigation.php'; ?>
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
                                <h1 class="font-weight-bold ">Pólizas por Detalle de Prima Cobrada</h1>
                            </div>
                </div>
                <hr />


                <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">



                    <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('tablePDE', 'Listado de Pólizas')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../assets/img/excel.png" width="60" alt=""></a></center>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered" id="tablePD">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th>Mes Desde Seg</th>
                                    <th>N° Póliza</th>
                                    <th>Nombre Titular</th>
                                    <th>Prima Suscrita</th>
                                    <th>Prima Total</th>
                                    <th style="background-color: #E54848;">Dif Prima</th>
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
                            </thead>

                            <tbody style="cursor:pointer">
                                <?php
                                if ($estado == 0) {
                                    $c = 0;
                                    for ($i = 0; $i < $fechaMax; $i++) {
                                        $totalpsMes = 0;
                                        $totalpcMes = 0;
                                        $totaldifMes = 0;
                                        $polizas = $obj->get_poliza_total_by_filtro_detalle_p($fpago, $anio, $cia, $asesor, $i + 1);
                                ?>
                                        <tr>
                                            <td rowspan="<?= sizeof($polizas); ?>" style="background-color: #D9D9D9"><?= $mes_arr[$i]; ?></td>

                                            <?php for ($a = 0; $a < sizeof($polizas); $a++) {
                                                $ppendiente = number_format($p_dif1[$c], 2);
                                                if ($ppendiente >= -0.10 && $ppendiente <= 0.10) {
                                                    $ppendiente = 0;
                                                }

                                                $no_renov = $obj->verRenov1($idpoliza1[$c]); ?>

                                                <?php if ($no_renov[0]['no_renov'] != 1) {
                                                    if ($f_hasta_poliza1[$c] >= date("Y-m-d")) { ?>
                                                        <td style="color: #2B9E34;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$c]; ?>"><?= $cod_poliza1[$c]; ?></td>
                                                    <?php } else { ?>
                                                        <td style="color: #E54848;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$c]; ?>"><?= $cod_poliza1[$c]; ?></td>
                                                    <?php }
                                                } else { ?>
                                                    <td style="color: #4a148c;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$c]; ?>"><?= $cod_poliza1[$c]; ?></td>
                                                <?php } ?>

                                                <td data-toggle="tooltip" data-placement="top" title="<?= $tool1[$c]; ?>"><?= $ciente1[$c]; ?></td>

                                                <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="Prima Suscrita" nowrap><?= '$ ' . number_format($prima_s1[$c], 2); ?></td>
                                                <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="Prima Total" nowrap><?= '$ ' . number_format($p_tt1[$c], 2); ?></td>

                                                <?php if ($ppendiente > 0) { ?>
                                                    <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#F53333;font-size: 16px" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$c]; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                                                <?php }
                                                if ($ppendiente == 0) { ?>
                                                    <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$c]; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                                                <?php }
                                                if ($ppendiente < 0) { ?>
                                                    <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#2B9E34;font-size: 16px" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$c]; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                                                <?php } ?>

                                                <?php if ($p_enero1[$c] > 0) { ?>
                                                    <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Enero Año: ' . $a_enero1[$c]; ?>" nowrap><?= '$ ' . number_format($p_enero1[$c], 2); ?></td>
                                                <?php } else { ?>
                                                    <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Enero Año: ' . $a_enero1[$c]; ?>" nowrap><?= '$ ' . number_format($p_enero1[$c], 2); ?></td>
                                                <?php } ?>

                                                <?php if ($p_febrero1[$c] > 0) { ?>
                                                    <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Febrero Año: ' . $a_febrero1[$c]; ?>" nowrap><?= '$ ' . number_format($p_febrero1[$c], 2); ?></td>
                                                <?php } else { ?>
                                                    <td style="text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Febrero Año: ' . $a_febrero1[$c]; ?>" nowrap><?= '$ ' . number_format($p_febrero1[$c], 2); ?></td>
                                                <?php } ?>

                                                <?php if ($p_marzo1[$c] > 0) { ?>
                                                    <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Marzo Año: ' . $a_marzo1[$c]; ?>" nowrap><?= '$ ' . number_format($p_marzo1[$c], 2); ?></td>
                                                <?php } else { ?>
                                                    <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Marzo Año: ' . $a_marzo1[$c]; ?>" nowrap><?= '$ ' . number_format($p_marzo1[$c], 2); ?></td>
                                                <?php } ?>

                                                <?php if ($p_abril1[$c] > 0) { ?>
                                                    <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Abril Año: ' . $a_abril1[$c]; ?>" nowrap><?= '$ ' . number_format($p_abril1[$c], 2); ?></td>
                                                <?php } else { ?>
                                                    <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Abril Año: ' . $a_abril1[$c]; ?>" nowrap><?= '$ ' . number_format($p_abril1[$c], 2); ?></td>
                                                <?php } ?>

                                                <?php if ($p_mayo1[$c] > 0) { ?>
                                                    <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Mayo Año: ' . $a_mayo1[$c]; ?>" nowrap><?= '$ ' . number_format($p_mayo1[$c], 2); ?></td>
                                                <?php } else { ?>
                                                    <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Mayo Año: ' . $a_mayo1[$c]; ?>" nowrap><?= '$ ' . number_format($p_mayo1[$c], 2); ?></td>
                                                <?php } ?>

                                                <?php if ($p_junio1[$c] > 0) { ?>
                                                    <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Junio Año: ' . $a_junio1[$c]; ?>" nowrap><?= '$ ' . number_format($p_junio1[$c], 2); ?></td>
                                                <?php } else { ?>
                                                    <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Junio Año: ' . $a_junio1[$c]; ?>" nowrap><?= '$ ' . number_format($p_junio1[$c], 2); ?></td>
                                                <?php } ?>

                                                <?php if ($p_julio1[$c] > 0) { ?>
                                                    <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Julio Año: ' . $a_julio1[$c]; ?>" nowrap><?= '$ ' . number_format($p_julio1[$c], 2); ?></td>
                                                <?php } else { ?>
                                                    <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Julio Año: ' . $a_julio1[$c]; ?>" nowrap><?= '$ ' . number_format($p_julio1[$c], 2); ?></td>
                                                <?php } ?>

                                                <?php if ($p_agosto1[$c] > 0) { ?>
                                                    <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Agosto Año: ' . $a_agosto1[$c]; ?>" nowrap><?= '$ ' . number_format($p_agosto1[$c], 2); ?></td>
                                                <?php } else { ?>
                                                    <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Agosto Año: ' . $a_agosto1[$c]; ?>" nowrap><?= '$ ' . number_format($p_agosto1[$c], 2); ?></td>
                                                <?php } ?>

                                                <?php if ($p_septiempre1[$c] > 0) { ?>
                                                    <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Septiembre Año: ' . $a_septiempre1[$c]; ?>" nowrap><?= '$ ' . number_format($p_septiempre1[$c], 2); ?></td>
                                                <?php } else { ?>
                                                    <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Septiembre Año: ' . $a_septiempre1[$c]; ?>" nowrap><?= '$ ' . number_format($p_septiempre1[$c], 2); ?></td>
                                                <?php } ?>

                                                <?php if ($p_octubre1[$c] > 0) { ?>
                                                    <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Octubre Año: ' . $a_octubre1[$c]; ?>" nowrap><?= '$ ' . number_format($p_octubre1[$c], 2); ?></td>
                                                <?php } else { ?>
                                                    <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Octubre Año: ' . $a_octubre1[$c]; ?>" nowrap><?= '$ ' . number_format($p_octubre1[$c], 2); ?></td>
                                                <?php } ?>

                                                <?php if ($p_noviembre1[$c] > 0) { ?>
                                                    <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Noviembre Año: ' . $a_noviembre1[$c]; ?>" nowrap><?= '$ ' . number_format($p_noviembre1[$c], 2); ?></td>
                                                <?php } else { ?>
                                                    <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Noviembre Año: ' . $a_noviembre1[$c]; ?>" nowrap><?= '$ ' . number_format($p_noviembre1[$c], 2); ?></td>
                                                <?php } ?>

                                                <?php if ($p_diciembre1[$c] > 0) { ?>
                                                    <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Diciembre Año: ' . $a_diciembre1[$c]; ?>" nowrap><?= '$ ' . number_format($p_diciembre1[$c], 2); ?></td>
                                                <?php } else { ?>
                                                    <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Diciembre Año: ' . $a_diciembre1[$c]; ?>" nowrap><?= '$ ' . number_format($p_diciembre1[$c], 2); ?></td>
                                                <?php } ?>

                                                <td hidden><?= $idpoliza1[$c]; ?></td>
                                        </tr>

                                    <?php
                                                $totalpsMes = $totalpsMes + $prima_s1[$c];
                                                $totalpcMes = $totalpcMes + $p_tt1[$c];
                                                $totaldifMes = $totaldifMes + $p_dif1[$c];
                                                $c++;
                                            } ?>
                                    <tr class="no-tocar">
                                        <td colspan="3" style="background-color: #F53333;color: white;font-weight: bold">Total <?= $mes_arr[$i]; ?>: <font size=4><?= sizeof($polizas); ?></font>
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
                                <?php
                                        $totalpoliza = $totalpoliza + sizeof($polizas);
                                    }
                                } else {
                                    //SI ES UN SOLO MES SELECCIONADO
                                    $polizas = $obj->get_poliza_total_by_filtro_detalle_p($fpago, $anio, $cia, $asesor, $fechaMax);
                                    $c = 0;
                                ?>
                                <tr>
                                    <td rowspan="<?= sizeof($polizas); ?>" style="background-color: #D9D9D9"><?= $mes_arr[$fechaMax - 1]; ?></td>

                                    <?php for ($a = 0; $a < sizeof($polizas); $a++) {
                                        $ppendiente = number_format($p_dif1[$c], 2);
                                        if ($ppendiente >= -0.10 && $ppendiente <= 0.10) {
                                            $ppendiente = 0;
                                        }

                                        $no_renov = $obj->verRenov1($idpoliza1[$c]); ?>

                                        <?php if ($no_renov[0]['no_renov'] != 1) {
                                            if ($f_hasta_poliza1[$c] >= date("Y-m-d")) { ?>
                                                <td style="color: #2B9E34;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$c]; ?>"><?= $cod_poliza1[$c]; ?></td>
                                            <?php } else { ?>
                                                <td style="color: #E54848;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$c]; ?>"><?= $cod_poliza1[$c]; ?></td>
                                            <?php }
                                        } else { ?>
                                            <td style="color: #4a148c;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$c]; ?>"><?= $cod_poliza1[$c]; ?></td>
                                        <?php } ?>

                                        <td data-toggle="tooltip" data-placement="top" title="<?= $tool1[$c]; ?>"><?= $ciente1[$c]; ?></td>

                                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="Prima Suscrita" nowrap><?= '$ ' . number_format($prima_s1[$c], 2); ?></td>
                                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="Prima Total" nowrap><?= '$ ' . number_format($p_tt1[$c], 2); ?></td>

                                        <?php if ($ppendiente > 0) { ?>
                                            <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#F53333;font-size: 16px" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$c]; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                                        <?php }
                                        if ($ppendiente == 0) { ?>
                                            <td style="background-color: #D9D9D9 ;color:black;text-align: right;font-weight: bold;" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$c]; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                                        <?php }
                                        if ($ppendiente < 0) { ?>
                                            <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#2B9E34;font-size: 16px" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$c]; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                                        <?php } ?>

                                        <?php if ($p_enero1[$c] > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Enero Año: ' . $a_enero1[$c]; ?>" nowrap><?= '$ ' . number_format($p_enero1[$c], 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Enero Año: ' . $a_enero1[$c]; ?>" nowrap><?= '$ ' . number_format($p_enero1[$c], 2); ?></td>
                                        <?php } ?>

                                        <?php if ($p_febrero1[$c] > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Febrero Año: ' . $a_febrero1[$c]; ?>" nowrap><?= '$ ' . number_format($p_febrero1[$c], 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Febrero Año: ' . $a_febrero1[$c]; ?>" nowrap><?= '$ ' . number_format($p_febrero1[$c], 2); ?></td>
                                        <?php } ?>

                                        <?php if ($p_marzo1[$c] > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Marzo Año: ' . $a_marzo1[$c]; ?>" nowrap><?= '$ ' . number_format($p_marzo1[$c], 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Marzo Año: ' . $a_marzo1[$c]; ?>" nowrap><?= '$ ' . number_format($p_marzo1[$c], 2); ?></td>
                                        <?php } ?>

                                        <?php if ($p_abril1[$c] > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Abril Año: ' . $a_abril1[$c]; ?>" nowrap><?= '$ ' . number_format($p_abril1[$c], 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Abril Año: ' . $a_abril1[$c]; ?>" nowrap><?= '$ ' . number_format($p_abril1[$c], 2); ?></td>
                                        <?php } ?>

                                        <?php if ($p_mayo1[$c] > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Mayo Año: ' . $a_mayo1[$c]; ?>" nowrap><?= '$ ' . number_format($p_mayo1[$c], 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Mayo Año: ' . $a_mayo1[$c]; ?>" nowrap><?= '$ ' . number_format($p_mayo1[$c], 2); ?></td>
                                        <?php } ?>

                                        <?php if ($p_junio1[$c] > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Junio Año: ' . $a_junio1[$c]; ?>" nowrap><?= '$ ' . number_format($p_junio1[$c], 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Junio Año: ' . $a_junio1[$c]; ?>" nowrap><?= '$ ' . number_format($p_junio1[$c], 2); ?></td>
                                        <?php } ?>

                                        <?php if ($p_julio1[$c] > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Julio Año: ' . $a_julio1[$c]; ?>" nowrap><?= '$ ' . number_format($p_julio1[$c], 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Julio Año: ' . $a_julio1[$c]; ?>" nowrap><?= '$ ' . number_format($p_julio1[$c], 2); ?></td>
                                        <?php } ?>

                                        <?php if ($p_agosto1[$c] > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Agosto Año: ' . $a_agosto1[$c]; ?>" nowrap><?= '$ ' . number_format($p_agosto1[$c], 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Agosto Año: ' . $a_agosto1[$c]; ?>" nowrap><?= '$ ' . number_format($p_agosto1[$c], 2); ?></td>
                                        <?php } ?>

                                        <?php if ($p_septiempre1[$c] > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Septiembre Año: ' . $a_septiempre1[$c]; ?>" nowrap><?= '$ ' . number_format($p_septiempre1[$c], 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Septiembre Año: ' . $a_septiempre1[$c]; ?>" nowrap><?= '$ ' . number_format($p_septiempre1[$c], 2); ?></td>
                                        <?php } ?>

                                        <?php if ($p_octubre1[$c] > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Octubre Año: ' . $a_octubre1[$c]; ?>" nowrap><?= '$ ' . number_format($p_octubre1[$c], 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Octubre Año: ' . $a_octubre1[$c]; ?>" nowrap><?= '$ ' . number_format($p_octubre1[$c], 2); ?></td>
                                        <?php } ?>

                                        <?php if ($p_noviembre1[$c] > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Noviembre Año: ' . $a_noviembre1[$c]; ?>" nowrap><?= '$ ' . number_format($p_noviembre1[$c], 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Noviembre Año: ' . $a_noviembre1[$c]; ?>" nowrap><?= '$ ' . number_format($p_noviembre1[$c], 2); ?></td>
                                        <?php } ?>

                                        <?php if ($p_diciembre1[$c] > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Diciembre Año: ' . $a_diciembre1[$c]; ?>" nowrap><?= '$ ' . number_format($p_diciembre1[$c], 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Diciembre Año: ' . $a_diciembre1[$c]; ?>" nowrap><?= '$ ' . number_format($p_diciembre1[$c], 2); ?></td>
                                        <?php } ?>
                                        <td hidden><?= $idpoliza1[$c]; ?></td>
                                </tr>

                            <?php
                                        $totalpsMes = $totalpsMes + $prima_s1[$c];
                                        $totalpcMes = $totalpcMes + $p_tt1[$c];
                                        $totaldifMes = $totaldifMes + $p_dif1[$c];
                                        $c++;
                                    } ?>
                            <tr class="no-tocar">
                                <td colspan="3" style="background-color: #F53333;color: white;font-weight: bold">Total <?= $mes_arr[$i]; ?>: <font size=4><?= sizeof($polizas); ?></font>
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
                        <?php
                                    $totalpoliza = $totalpoliza + sizeof($polizas);
                                } ?>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th>Mes Desde Seg</th>
                                    <th>N° Póliza</th>
                                    <th>Nombre Titular</th>
                                    <th>Prima Suscrita</th>
                                    <th>Prima Total</th>
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
                    </div>

                    <p class="h1 text-center">Total de Prima Suscrita</p>
                    <p class="h1 text-center text-danger">$ <?php echo number_format($totalprima, 2); ?></p>

                    <p class="h1 text-center">Total de Pólizas</p>
                    <p class="h1 text-center text-danger"><?php echo $totalpoliza; ?></p>



                    <!--   -----------------TABLA PARA EXCEL-----------------   -->
                    <div class="table-responsive" hidden>
                        <table class="table table-hover table-striped table-bordered" id="tablePDE">
                            <thead>
                                <tr>
                                    <th style="background-color: #4285F4; color: white">Mes Desde Seg</th>
                                    <th style="background-color: #4285F4; color: white">N° Póliza</th>
                                    <th style="background-color: #4285F4; color: white">Nombre Titular</th>
                                    <th style="background-color: #4285F4; color: white">F Desde Seguro</th>
                                    <th style="background-color: #4285F4; color: white">Cía</th>
                                    <th style="background-color: #4285F4; color: white">Ramo</th>
                                    <th style="background-color: #4285F4; color: white">Prima Suscrita</th>
                                    <th style="background-color: #4285F4; color: white">Prima Total</th>
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

                            <tbody style="cursor:pointer">
                                <?php
                                if ($estado == 0) {
                                    $c = 0;
                                    for ($i = 0; $i < $fechaMax; $i++) {
                                        $totalpsMes = 0;
                                        $totalpcMes = 0;
                                        $totaldifMes = 0;
                                        $polizas = $obj->get_poliza_total_by_filtro_detalle_p($fpago, $anio, $cia, $asesor, $i + 1);
                                ?>
                                        <tr>
                                            <td rowspan="<?= sizeof($polizas); ?>" style="background-color: #D9D9D9"><?= $mes_arr[$i]; ?></td>

                                            <?php for ($a = 0; $a < sizeof($polizas); $a++) {
                                                $ppendiente = number_format($p_dif1[$c], 2);
                                                if ($ppendiente >= -0.10 && $ppendiente <= 0.10) {
                                                    $ppendiente = 0;
                                                }

                                                $no_renov = $obj->verRenov1($idpoliza1[$c]); ?>

                                                <?php if ($no_renov[0]['no_renov'] != 1) {
                                                    if ($f_hasta_poliza1[$c] >= date("Y-m-d")) { ?>
                                                        <td style="color: #2B9E34;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$c]; ?>"><?= $cod_poliza1[$c]; ?></td>
                                                    <?php } else { ?>
                                                        <td style="color: #E54848;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$c]; ?>"><?= $cod_poliza1[$c]; ?></td>
                                                    <?php }
                                                } else { ?>
                                                    <td style="color: #4a148c;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$c]; ?>"><?= $cod_poliza1[$c]; ?></td>
                                                <?php } ?>

                                                <td><?= $ciente1[$c]; ?></td>
                                                <td><?= $newDesde1[$c]; ?></td>
                                                <td><?= $nomcia1[$c]; ?></td>
                                                <td><?= $nramo1[$c]; ?></td>

                                                <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="Prima Suscrita" nowrap><?= '$ ' . number_format($prima_s1[$c], 2); ?></td>
                                                <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="Prima Total" nowrap><?= '$ ' . number_format($p_tt1[$c], 2); ?></td>

                                                <?php if ($ppendiente > 0) { ?>
                                                    <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#F53333;font-size: 16px" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$c]; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                                                <?php }
                                                if ($ppendiente == 0) { ?>
                                                    <td style="background-color: #D9D9D9 ;color:black;text-align: right;font-weight: bold;" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$c]; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                                                <?php }
                                                if ($ppendiente < 0) { ?>
                                                    <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#2B9E34;font-size: 16px" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$c]; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                                                <?php } ?>

                                                <?php if ($p_enero1[$c] > 0) { ?>
                                                    <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Enero Año: ' . $a_enero1[$c]; ?>" nowrap><?= '$ ' . number_format($p_enero1[$c], 2); ?></td>
                                                <?php } else { ?>
                                                    <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Enero Año: ' . $a_enero1[$c]; ?>" nowrap><?= '$ ' . number_format($p_enero1[$c], 2); ?></td>
                                                <?php } ?>

                                                <?php if ($p_febrero1[$c] > 0) { ?>
                                                    <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Febrero Año: ' . $a_febrero1[$c]; ?>" nowrap><?= '$ ' . number_format($p_febrero1[$c], 2); ?></td>
                                                <?php } else { ?>
                                                    <td style="text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Febrero Año: ' . $a_febrero1[$c]; ?>" nowrap><?= '$ ' . number_format($p_febrero1[$c], 2); ?></td>
                                                <?php } ?>

                                                <?php if ($p_marzo1[$c] > 0) { ?>
                                                    <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Marzo Año: ' . $a_marzo1[$c]; ?>" nowrap><?= '$ ' . number_format($p_marzo1[$c], 2); ?></td>
                                                <?php } else { ?>
                                                    <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Marzo Año: ' . $a_marzo1[$c]; ?>" nowrap><?= '$ ' . number_format($p_marzo1[$c], 2); ?></td>
                                                <?php } ?>

                                                <?php if ($p_abril1[$c] > 0) { ?>
                                                    <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Abril Año: ' . $a_abril1[$c]; ?>" nowrap><?= '$ ' . number_format($p_abril1[$c], 2); ?></td>
                                                <?php } else { ?>
                                                    <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Abril Año: ' . $a_abril1[$c]; ?>" nowrap><?= '$ ' . number_format($p_abril1[$c], 2); ?></td>
                                                <?php } ?>

                                                <?php if ($p_mayo1[$c] > 0) { ?>
                                                    <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Mayo Año: ' . $a_mayo1[$c]; ?>" nowrap><?= '$ ' . number_format($p_mayo1[$c], 2); ?></td>
                                                <?php } else { ?>
                                                    <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Mayo Año: ' . $a_mayo1[$c]; ?>" nowrap><?= '$ ' . number_format($p_mayo1[$c], 2); ?></td>
                                                <?php } ?>

                                                <?php if ($p_junio1[$c] > 0) { ?>
                                                    <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Junio Año: ' . $a_junio1[$c]; ?>" nowrap><?= '$ ' . number_format($p_junio1[$c], 2); ?></td>
                                                <?php } else { ?>
                                                    <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Junio Año: ' . $a_junio1[$c]; ?>" nowrap><?= '$ ' . number_format($p_junio1[$c], 2); ?></td>
                                                <?php } ?>

                                                <?php if ($p_julio1[$c] > 0) { ?>
                                                    <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Julio Año: ' . $a_julio1[$c]; ?>" nowrap><?= '$ ' . number_format($p_julio1[$c], 2); ?></td>
                                                <?php } else { ?>
                                                    <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Julio Año: ' . $a_julio1[$c]; ?>" nowrap><?= '$ ' . number_format($p_julio1[$c], 2); ?></td>
                                                <?php } ?>

                                                <?php if ($p_agosto1[$c] > 0) { ?>
                                                    <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Agosto Año: ' . $a_agosto1[$c]; ?>" nowrap><?= '$ ' . number_format($p_agosto1[$c], 2); ?></td>
                                                <?php } else { ?>
                                                    <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Agosto Año: ' . $a_agosto1[$c]; ?>" nowrap><?= '$ ' . number_format($p_agosto1[$c], 2); ?></td>
                                                <?php } ?>

                                                <?php if ($p_septiempre1[$c] > 0) { ?>
                                                    <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Septiembre Año: ' . $a_septiempre1[$c]; ?>" nowrap><?= '$ ' . number_format($p_septiempre1[$c], 2); ?></td>
                                                <?php } else { ?>
                                                    <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Septiembre Año: ' . $a_septiempre1[$c]; ?>" nowrap><?= '$ ' . number_format($p_septiempre1[$c], 2); ?></td>
                                                <?php } ?>

                                                <?php if ($p_octubre1[$c] > 0) { ?>
                                                    <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Octubre Año: ' . $a_octubre1[$c]; ?>" nowrap><?= '$ ' . number_format($p_octubre1[$c], 2); ?></td>
                                                <?php } else { ?>
                                                    <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Octubre Año: ' . $a_octubre1[$c]; ?>" nowrap><?= '$ ' . number_format($p_octubre1[$c], 2); ?></td>
                                                <?php } ?>

                                                <?php if ($p_noviembre1[$c] > 0) { ?>
                                                    <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Noviembre Año: ' . $a_noviembre1[$c]; ?>" nowrap><?= '$ ' . number_format($p_noviembre1[$c], 2); ?></td>
                                                <?php } else { ?>
                                                    <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Noviembre Año: ' . $a_noviembre1[$c]; ?>" nowrap><?= '$ ' . number_format($p_noviembre1[$c], 2); ?></td>
                                                <?php } ?>

                                                <?php if ($p_diciembre1[$c] > 0) { ?>
                                                    <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Diciembre Año: ' . $a_diciembre1[$c]; ?>" nowrap><?= '$ ' . number_format($p_diciembre1[$c], 2); ?></td>
                                                <?php } else { ?>
                                                    <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Diciembre Año: ' . $a_diciembre1[$c]; ?>" nowrap><?= '$ ' . number_format($p_diciembre1[$c], 2); ?></td>
                                                <?php } ?>
                                        </tr>

                                    <?php
                                                $totalpsMes = $totalpsMes + $prima_s1[$c];
                                                $totalpcMes = $totalpcMes + $p_tt1[$c];
                                                $totaldifMes = $totaldifMes + $p_dif1[$c];
                                                $c++;
                                            } ?>
                                    <tr class="no-tocar">
                                        <td colspan="6" style="background-color: #F53333;color: white;font-weight: bold">Total <?= $mes_arr[$i]; ?>: <font size=4><?= sizeof($polizas); ?></font>
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
                                <?php
                                        $totalpoliza = $totalpoliza + sizeof($polizas);
                                    }
                                } else {
                                    //SI ES UN SOLO MES SELECCIONADO
                                    $polizas = $obj->get_poliza_total_by_filtro_detalle_p($fpago, $anio, $cia, $asesor, $fechaMax);
                                    $totalpsMes = 0;
                                    $totalpcMes = 0;
                                    $totaldifMes = 0;
                                    $c = 0;
                                ?>
                                <tr>
                                    <td rowspan="<?= sizeof($polizas); ?>" style="background-color: #D9D9D9"><?= $mes_arr[$fechaMax - 1]; ?></td>

                                    <?php for ($a = 0; $a < sizeof($polizas); $a++) {
                                        $ppendiente = number_format($p_dif1[$c], 2);
                                        if ($ppendiente >= -0.10 && $ppendiente <= 0.10) {
                                            $ppendiente = 0;
                                        }

                                        $no_renov = $obj->verRenov1($idpoliza1[$c]); ?>

                                        <?php if ($no_renov[0]['no_renov'] != 1) {
                                            if ($f_hasta_poliza1[$c] >= date("Y-m-d")) { ?>
                                                <td style="color: #2B9E34;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$c]; ?>"><?= $cod_poliza1[$c]; ?></td>
                                            <?php } else { ?>
                                                <td style="color: #E54848;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$c]; ?>"><?= $cod_poliza1[$c]; ?></td>
                                            <?php }
                                        } else { ?>
                                            <td style="color: #4a148c;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$c]; ?>"><?= $cod_poliza1[$c]; ?></td>
                                        <?php } ?>

                                        <td><?= $ciente1[$c]; ?></td>
                                        <td><?= $newDesde1[$c]; ?></td>
                                        <td><?= $nomcia1[$c]; ?></td>
                                        <td><?= $nramo1[$c]; ?></td>

                                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="Prima Suscrita" nowrap><?= '$ ' . number_format($prima_s1[$c], 2); ?></td>
                                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="Prima Total" nowrap><?= '$ ' . number_format($p_tt1[$c], 2); ?></td>

                                        <?php if ($ppendiente > 0) { ?>
                                            <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#F53333;font-size: 16px" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$c]; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                                        <?php }
                                        if ($ppendiente == 0) { ?>
                                            <td style="background-color: #D9D9D9 ;color:black;text-align: right;font-weight: bold;" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$c]; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                                        <?php }
                                        if ($ppendiente < 0) { ?>
                                            <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#2B9E34;font-size: 16px" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$c]; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                                        <?php } ?>

                                        <?php if ($p_enero1[$c] > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Enero Año: ' . $a_enero1[$c]; ?>" nowrap><?= '$ ' . number_format($p_enero1[$c], 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Enero Año: ' . $a_enero1[$c]; ?>" nowrap><?= '$ ' . number_format($p_enero1[$c], 2); ?></td>
                                        <?php } ?>

                                        <?php if ($p_febrero1[$c] > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Febrero Año: ' . $a_febrero1[$c]; ?>" nowrap><?= '$ ' . number_format($p_febrero1[$c], 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Febrero Año: ' . $a_febrero1[$c]; ?>" nowrap><?= '$ ' . number_format($p_febrero1[$c], 2); ?></td>
                                        <?php } ?>

                                        <?php if ($p_marzo1[$c] > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Marzo Año: ' . $a_marzo1[$c]; ?>" nowrap><?= '$ ' . number_format($p_marzo1[$c], 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Marzo Año: ' . $a_marzo1[$c]; ?>" nowrap><?= '$ ' . number_format($p_marzo1[$c], 2); ?></td>
                                        <?php } ?>

                                        <?php if ($p_abril1[$c] > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Abril Año: ' . $a_abril1[$c]; ?>" nowrap><?= '$ ' . number_format($p_abril1[$c], 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Abril Año: ' . $a_abril1[$c]; ?>" nowrap><?= '$ ' . number_format($p_abril1[$c], 2); ?></td>
                                        <?php } ?>

                                        <?php if ($p_mayo1[$c] > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Mayo Año: ' . $a_mayo1[$c]; ?>" nowrap><?= '$ ' . number_format($p_mayo1[$c], 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Mayo Año: ' . $a_mayo1[$c]; ?>" nowrap><?= '$ ' . number_format($p_mayo1[$c], 2); ?></td>
                                        <?php } ?>

                                        <?php if ($p_junio1[$c] > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Junio Año: ' . $a_junio1[$c]; ?>" nowrap><?= '$ ' . number_format($p_junio1[$c], 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Junio Año: ' . $a_junio1[$c]; ?>" nowrap><?= '$ ' . number_format($p_junio1[$c], 2); ?></td>
                                        <?php } ?>

                                        <?php if ($p_julio1[$c] > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Julio Año: ' . $a_julio1[$c]; ?>" nowrap><?= '$ ' . number_format($p_julio1[$c], 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Julio Año: ' . $a_julio1[$c]; ?>" nowrap><?= '$ ' . number_format($p_julio1[$c], 2); ?></td>
                                        <?php } ?>

                                        <?php if ($p_agosto1[$c] > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Agosto Año: ' . $a_agosto1[$c]; ?>" nowrap><?= '$ ' . number_format($p_agosto1[$c], 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Agosto Año: ' . $a_agosto1[$c]; ?>" nowrap><?= '$ ' . number_format($p_agosto1[$c], 2); ?></td>
                                        <?php } ?>

                                        <?php if ($p_septiempre1[$c] > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Septiembre Año: ' . $a_septiempre1[$c]; ?>" nowrap><?= '$ ' . number_format($p_septiempre1[$c], 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Septiembre Año: ' . $a_septiempre1[$c]; ?>" nowrap><?= '$ ' . number_format($p_septiempre1[$c], 2); ?></td>
                                        <?php } ?>

                                        <?php if ($p_octubre1[$c] > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Octubre Año: ' . $a_octubre1[$c]; ?>" nowrap><?= '$ ' . number_format($p_octubre1[$c], 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Octubre Año: ' . $a_octubre1[$c]; ?>" nowrap><?= '$ ' . number_format($p_octubre1[$c], 2); ?></td>
                                        <?php } ?>

                                        <?php if ($p_noviembre1[$c] > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Noviembre Año: ' . $a_noviembre1[$c]; ?>" nowrap><?= '$ ' . number_format($p_noviembre1[$c], 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Noviembre Año: ' . $a_noviembre1[$c]; ?>" nowrap><?= '$ ' . number_format($p_noviembre1[$c], 2); ?></td>
                                        <?php } ?>

                                        <?php if ($p_diciembre1[$c] > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Diciembre Año: ' . $a_diciembre1[$c]; ?>" nowrap><?= '$ ' . number_format($p_diciembre1[$c], 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Diciembre Año: ' . $a_diciembre1[$c]; ?>" nowrap><?= '$ ' . number_format($p_diciembre1[$c], 2); ?></td>
                                        <?php } ?>
                                </tr>

                            <?php
                                        $totalpsMes = $totalpsMes + $prima_s1[$c];
                                        $totalpcMes = $totalpcMes + $p_tt1[$c];
                                        $totaldifMes = $totaldifMes + $p_dif1[$c];
                                        $c++;
                                    } ?>
                            <tr class="no-tocar">
                                <td colspan="6" style="background-color: #F53333;color: white;font-weight: bold">Total <?= $mes_arr[$i]; ?>: <font size=4><?= sizeof($polizas); ?></font>
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
                        <?php
                                    $totalpoliza = $totalpoliza + sizeof($polizas);
                                } ?>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th>Mes Desde Seg</th>
                                    <th>N° Póliza</th>
                                    <th>Nombre Titular</th>
                                    <th>F Desde Seguro</th>
                                    <th>Cía</th>
                                    <th>Ramo</th>
                                    <th>Prima Suscrita</th>
                                    <th>Prima Total</th>
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
                    </div>
                    <!--   -----------------TABLA PARA EXCEL-----------------   -->

                </div>

                <!--   TABLA PARA USUARIOS QUE SON ASESORES   -->
            <?php } ?>
        </div>





        <?php require_once dirname(__DIR__) . '\layout\footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . '\layout\footer.php'; ?>

        <script src="../assets/view/b_poliza.js"></script>
</body>

</html>