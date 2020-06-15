<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'prima_detail1';

require_once '../Controller/Poliza.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'navigation.php'; ?>
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
                                <h1 class="font-weight-bold ">Listado de Seguimiento de la Cobranza de Primas</h1>
                                <h2 class="font-weight-bold">Desde: <font style="color:red"><?= $desdeP; ?></font> Hasta: <font style="color:red"><?= $hastaP; ?></font>
                                </h2>
                            </div>
                </div>
                <hr />


                <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">



                    <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('tablePDE', 'Listado de Pólizas')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel (280 Pólizas)"><img src="../assets/img/excel.png" width="60" alt=""></a></center>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered mx-auto" id="tablePD">
                            <thead class="blue-gradient text-white text-center">
                                <tr>
                                    <!-- <th>Mes Desde Seg</th> -->
                                    <th>N° Póliza</th>
                                    <th>Nombre Titular</th>
                                    <th>Prima Suscrita</th>
                                    <th>Prima Cobrada</th>
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
                                    <th hidden>id</th>
                                </tr>
                            </thead>

                            <tbody style="cursor:pointer">
                                <?php
                                $c = 0;
                                $totalpsMes = 0;
                                $totalpcMes = 0;
                                $totaldifMes = 0;

                                $totalpoliza = $totalpoliza + sizeof($polizas);

                                for ($i = 0; $i < sizeof($polizas); $i++) {

                                    $datemes = date("m", strtotime($polizas[$i]['f_desdepoliza'])) - 1;

                                    $no_renov = $obj->verRenov1($polizas[$i]['id_poliza']);

                                    $tool = 'Fecha Desde Seguro: ' . date("d/m/Y", strtotime($polizas[$i]['f_desdepoliza'])) . ' | Cía: ' . $polizas[$i]['nomcia'] . ' | Ramo: ' . $polizas[$i]['nramo'] . ' | Nº de Cuotas: ' . $polizas[$i]['ncuotas'];


                                    $p_ene1 = $obj->get_prima_cob_d($polizas[$i]['id_poliza'], '01');
                                    $p_ene = ($p_ene1[0]['SUM(prima_com)'] == 0) ? 0 : $p_ene1[0]['SUM(prima_com)'];
                                    $a_ene = ($p_ene1[0]['SUM(prima_com)'] == 0) ? '-' : $p_ene1[0]['YEAR(f_pago_prima)'];
                                    $p_feb1 = $obj->get_prima_cob_d($polizas[$i]['id_poliza'], '02');
                                    $p_feb = ($p_feb1[0]['SUM(prima_com)'] == 0) ? 0 : $p_feb1[0]['SUM(prima_com)'];
                                    $a_feb = ($p_feb1[0]['SUM(prima_com)'] == 0) ? '-' : $p_feb1[0]['YEAR(f_pago_prima)'];
                                    $p_mar1 = $obj->get_prima_cob_d($polizas[$i]['id_poliza'], '03');
                                    $p_mar = ($p_mar1[0]['SUM(prima_com)'] == 0) ? 0 : $p_mar1[0]['SUM(prima_com)'];
                                    $a_mar = ($p_mar1[0]['SUM(prima_com)'] == 0) ? '-' : $p_mar1[0]['YEAR(f_pago_prima)'];
                                    $p_abr1 = $obj->get_prima_cob_d($polizas[$i]['id_poliza'], '04');
                                    $p_abr = ($p_abr1[0]['SUM(prima_com)'] == 0) ? 0 : $p_abr1[0]['SUM(prima_com)'];
                                    $a_abr = ($p_abr1[0]['SUM(prima_com)'] == 0) ? '-' : $p_abr1[0]['YEAR(f_pago_prima)'];
                                    $p_may1 = $obj->get_prima_cob_d($polizas[$i]['id_poliza'], '05');
                                    $p_may = ($p_may1[0]['SUM(prima_com)'] == 0) ? 0 : $p_may1[0]['SUM(prima_com)'];
                                    $a_may = ($p_may1[0]['SUM(prima_com)'] == 0) ? '-' : $p_may1[0]['YEAR(f_pago_prima)'];
                                    $p_jun1 = $obj->get_prima_cob_d($polizas[$i]['id_poliza'], '06');
                                    $p_jun = ($p_jun1[0]['SUM(prima_com)'] == 0) ? 0 : $p_jun1[0]['SUM(prima_com)'];
                                    $a_jun = ($p_jun1[0]['SUM(prima_com)'] == 0) ? '-' : $p_jun1[0]['YEAR(f_pago_prima)'];
                                    $p_jul1 = $obj->get_prima_cob_d($polizas[$i]['id_poliza'], '07');
                                    $p_jul = ($p_jul1[0]['SUM(prima_com)'] == 0) ? 0 : $p_jul1[0]['SUM(prima_com)'];
                                    $a_jul = ($p_jul1[0]['SUM(prima_com)'] == 0) ? '-' : $p_jul1[0]['YEAR(f_pago_prima)'];
                                    $p_ago1 = $obj->get_prima_cob_d($polizas[$i]['id_poliza'], '08');
                                    $p_ago = ($p_ago1[0]['SUM(prima_com)'] == 0) ? 0 : $p_ago1[0]['SUM(prima_com)'];
                                    $a_ago = ($p_ago1[0]['SUM(prima_com)'] == 0) ? '-' : $p_ago1[0]['YEAR(f_pago_prima)'];
                                    $p_sep1 = $obj->get_prima_cob_d($polizas[$i]['id_poliza'], '09');
                                    $p_sep = ($p_sep1[0]['SUM(prima_com)'] == 0) ? 0 : $p_sep1[0]['SUM(prima_com)'];
                                    $a_sep = ($p_sep1[0]['SUM(prima_com)'] == 0) ? '-' : $p_sep1[0]['YEAR(f_pago_prima)'];
                                    $p_oct1 = $obj->get_prima_cob_d($polizas[$i]['id_poliza'], '10');
                                    $p_oct = ($p_oct1[0]['SUM(prima_com)'] == 0) ? 0 : $p_oct1[0]['SUM(prima_com)'];
                                    $a_oct = ($p_oct1[0]['SUM(prima_com)'] == 0) ? '-' : $p_oct1[0]['YEAR(f_pago_prima)'];
                                    $p_nov1 = $obj->get_prima_cob_d($polizas[$i]['id_poliza'], '11');
                                    $p_nov = ($p_nov1[0]['SUM(prima_com)'] == 0) ? 0 : $p_nov1[0]['SUM(prima_com)'];
                                    $a_nov = ($p_nov1[0]['SUM(prima_com)'] == 0) ? '-' : $p_nov1[0]['YEAR(f_pago_prima)'];
                                    $p_dic1 = $obj->get_prima_cob_d($polizas[$i]['id_poliza'], '12');
                                    $p_dic = ($p_dic1[0]['SUM(prima_com)'] == 0) ? 0 : $p_dic1[0]['SUM(prima_com)'];
                                    $a_dic = ($p_dic1[0]['SUM(prima_com)'] == 0) ? '-' : $p_dic1[0]['YEAR(f_pago_prima)'];

                                    $p_t = $p_ene + $p_feb + $p_mar + $p_abr + $p_may + $p_jun + $p_jul + $p_ago + $p_sep + $p_oct + $p_nov + $p_dic;

                                    $p_dif = ($polizas[$i]['prima'] - $p_t);
                                    $ppendiente = number_format($p_dif, 2);
                                    if ($ppendiente >= -0.10 && $ppendiente <= 0.10) {
                                        $ppendiente = 0;
                                    }

                                    $totalpsMes = $totalpsMes + $polizas[$i]['prima'];
                                    $totalpcMes = $totalpcMes + $p_t;
                                    $totaldifMes = $totaldifMes + ($polizas[$i]['prima'] - $p_t);
                                ?>

                                    <tr>
                                        <!-- <td><?= $mes_arr[$datemes]; ?></td> -->

                                        <?php if ($no_renov[0]['no_renov'] != 1) {
                                            if ($polizas[$i]['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                                <td style="color: #2B9E34;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool; ?>"><?= $polizas[$i]['cod_poliza']; ?></td>
                                            <?php } else { ?>
                                                <td style="color: #E54848;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool; ?>"><?= $polizas[$i]['cod_poliza']; ?></td>
                                            <?php }
                                        } else { ?>
                                            <td style="color: #4a148c;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool; ?>"><?= $polizas[$i]['cod_poliza']; ?></td>
                                        <?php } ?>

                                        <td data-toggle="tooltip" data-placement="top" title="<?= $tool; ?>"><?= $polizas[$i]['nombre_t'] . " " . $polizas[$i]['apellido_t']; ?></td>

                                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="Prima Suscrita" nowrap><?= '$ ' . number_format($polizas[$i]['prima'], 2); ?></td>
                                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="Prima Total" nowrap><?= '$ ' . number_format($p_t, 2); ?></td>

                                        <?php if ($ppendiente > 0) { ?>
                                            <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#F53333;font-size: 16px" data-toggle="tooltip" data-placement="top" title="<?= $tool; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                                        <?php }
                                        if ($ppendiente == 0) { ?>
                                            <td style="background-color: #D9D9D9 ;color:black;text-align: right;font-weight: bold;" data-toggle="tooltip" data-placement="top" title="<?= $tool; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                                        <?php }
                                        if ($ppendiente < 0) { ?>
                                            <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#2B9E34;font-size: 16px" data-toggle="tooltip" data-placement="top" title="<?= $tool; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                                        <?php } ?>

                                        <?php if ($p_ene > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Enero Año: ' . $a_ene; ?>" nowrap><?= '$ ' . number_format($p_ene, 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Enero Año: ' . $a_ene; ?>" nowrap><?= '$ ' . number_format($p_ene, 2); ?></td>
                                        <?php } ?>

                                        <?php if ($p_feb > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Febrero Año: ' . $a_feb; ?>" nowrap><?= '$ ' . number_format($p_feb, 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Febrero Año: ' . $a_feb; ?>" nowrap><?= '$ ' . number_format($p_feb, 2); ?></td>
                                        <?php } ?>

                                        <?php if ($p_mar > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Marzo Año: ' . $a_mar; ?>" nowrap><?= '$ ' . number_format($p_mar, 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Marzo Año: ' . $a_mar; ?>" nowrap><?= '$ ' . number_format($p_mar, 2); ?></td>
                                        <?php } ?>

                                        <?php if ($p_abr > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Abril Año: ' . $a_abr; ?>" nowrap><?= '$ ' . number_format($p_abr, 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Abril Año: ' . $a_abr; ?>" nowrap><?= '$ ' . number_format($p_abr, 2); ?></td>
                                        <?php } ?>

                                        <?php if ($p_may > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Mayo Año: ' . $a_may; ?>" nowrap><?= '$ ' . number_format($p_may, 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Mayo Año: ' . $a_may; ?>" nowrap><?= '$ ' . number_format($p_may, 2); ?></td>
                                        <?php } ?>

                                        <?php if ($p_jun > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Junio Año: ' . $a_jun; ?>" nowrap><?= '$ ' . number_format($p_jun, 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Junio Año: ' . $a_jun; ?>" nowrap><?= '$ ' . number_format($p_jun, 2); ?></td>
                                        <?php } ?>

                                        <?php if ($p_jul > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Julio Año: ' . $a_jul; ?>" nowrap><?= '$ ' . number_format($p_jul, 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Julio Año: ' . $a_jul; ?>" nowrap><?= '$ ' . number_format($p_jul, 2); ?></td>
                                        <?php } ?>

                                        <?php if ($p_ago > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Agosto Año: ' . $a_ago; ?>" nowrap><?= '$ ' . number_format($p_ago, 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Agosto Año: ' . $a_ago; ?>" nowrap><?= '$ ' . number_format($p_ago, 2); ?></td>
                                        <?php } ?>

                                        <?php if ($p_sep > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Septiembre Año: ' . $a_sep; ?>" nowrap><?= '$ ' . number_format($p_sep, 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Septiembre Año: ' . $a_sep; ?>" nowrap><?= '$ ' . number_format($p_sep, 2); ?></td>
                                        <?php } ?>

                                        <?php if ($p_oct > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Octubre Año: ' . $a_oct; ?>" nowrap><?= '$ ' . number_format($p_oct, 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Octubre Año: ' . $a_oct; ?>" nowrap><?= '$ ' . number_format($p_oct, 2); ?></td>
                                        <?php } ?>

                                        <?php if ($p_nov > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Noviembre Año: ' . $a_nov; ?>" nowrap><?= '$ ' . number_format($p_nov, 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Noviembre Año: ' . $a_nov; ?>" nowrap><?= '$ ' . number_format($p_nov, 2); ?></td>
                                        <?php } ?>

                                        <?php if ($p_dic > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Diciembre Año: ' . $a_dic; ?>" nowrap><?= '$ ' . number_format($p_dic, 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Diciembre Año: ' . $a_dic; ?>" nowrap><?= '$ ' . number_format($p_dic, 2); ?></td>
                                        <?php } ?>
                                        <td hidden><?= $polizas[$i]['id_poliza']; ?></td>

                                    </tr>
                                <?php } ?>

                            </tbody>


                            <tr class="no-tocar">
                                <td colspan="2" style="background-color: #F53333;color: white;font-weight: bold">Total: <font size=4><?= sizeof($polizas); ?></font>
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
                                    <!-- <th>Mes Desde Seg</th> -->
                                    <th>N° Póliza</th>
                                    <th>Nombre Titular</th>
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
                                    <th hidden>id</th>
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

                            <tbody style="cursor:pointer">

                                <?php
                                $c = 0;
                                $totalpsMes = 0;
                                $totalpcMes = 0;
                                $totaldifMes = 0;

                                $totalpoliza = $totalpoliza + sizeof($polizas);
//EXCEL HASTA 280
                                for ($i = 0; $i < sizeof($polizas); $i++) {

                                    $datemes = date("m", strtotime($polizas[$i]['f_desdepoliza'])) - 1;

                                    $no_renov = $obj->verRenov1($polizas[$i]['id_poliza']);

                                    $tool = 'Fecha Desde Seguro: ' . date("d/m/Y", strtotime($polizas[$i]['f_desdepoliza'])) . ' | Cía: ' . $polizas[$i]['nomcia'] . ' | Ramo: ' . $polizas[$i]['nramo'] . ' | Nº de Cuotas: ' . $polizas[$i]['ncuotas'];


                                    $p_ene1 = $obj->get_prima_cob_d($polizas[$i]['id_poliza'], '01');
                                    $p_ene = ($p_ene1[0]['SUM(prima_com)'] == 0) ? 0 : $p_ene1[0]['SUM(prima_com)'];
                                    $a_ene = ($p_ene1[0]['SUM(prima_com)'] == 0) ? '-' : $p_ene1[0]['YEAR(f_pago_prima)'];
                                    $p_feb1 = $obj->get_prima_cob_d($polizas[$i]['id_poliza'], '02');
                                    $p_feb = ($p_feb1[0]['SUM(prima_com)'] == 0) ? 0 : $p_feb1[0]['SUM(prima_com)'];
                                    $a_feb = ($p_feb1[0]['SUM(prima_com)'] == 0) ? '-' : $p_feb1[0]['YEAR(f_pago_prima)'];
                                    $p_mar1 = $obj->get_prima_cob_d($polizas[$i]['id_poliza'], '03');
                                    $p_mar = ($p_mar1[0]['SUM(prima_com)'] == 0) ? 0 : $p_mar1[0]['SUM(prima_com)'];
                                    $a_mar = ($p_mar1[0]['SUM(prima_com)'] == 0) ? '-' : $p_mar1[0]['YEAR(f_pago_prima)'];
                                    $p_abr1 = $obj->get_prima_cob_d($polizas[$i]['id_poliza'], '04');
                                    $p_abr = ($p_abr1[0]['SUM(prima_com)'] == 0) ? 0 : $p_abr1[0]['SUM(prima_com)'];
                                    $a_abr = ($p_abr1[0]['SUM(prima_com)'] == 0) ? '-' : $p_abr1[0]['YEAR(f_pago_prima)'];
                                    $p_may1 = $obj->get_prima_cob_d($polizas[$i]['id_poliza'], '05');
                                    $p_may = ($p_may1[0]['SUM(prima_com)'] == 0) ? 0 : $p_may1[0]['SUM(prima_com)'];
                                    $a_may = ($p_may1[0]['SUM(prima_com)'] == 0) ? '-' : $p_may1[0]['YEAR(f_pago_prima)'];
                                    $p_jun1 = $obj->get_prima_cob_d($polizas[$i]['id_poliza'], '06');
                                    $p_jun = ($p_jun1[0]['SUM(prima_com)'] == 0) ? 0 : $p_jun1[0]['SUM(prima_com)'];
                                    $a_jun = ($p_jun1[0]['SUM(prima_com)'] == 0) ? '-' : $p_jun1[0]['YEAR(f_pago_prima)'];
                                    $p_jul1 = $obj->get_prima_cob_d($polizas[$i]['id_poliza'], '07');
                                    $p_jul = ($p_jul1[0]['SUM(prima_com)'] == 0) ? 0 : $p_jul1[0]['SUM(prima_com)'];
                                    $a_jul = ($p_jul1[0]['SUM(prima_com)'] == 0) ? '-' : $p_jul1[0]['YEAR(f_pago_prima)'];
                                    $p_ago1 = $obj->get_prima_cob_d($polizas[$i]['id_poliza'], '08');
                                    $p_ago = ($p_ago1[0]['SUM(prima_com)'] == 0) ? 0 : $p_ago1[0]['SUM(prima_com)'];
                                    $a_ago = ($p_ago1[0]['SUM(prima_com)'] == 0) ? '-' : $p_ago1[0]['YEAR(f_pago_prima)'];
                                    $p_sep1 = $obj->get_prima_cob_d($polizas[$i]['id_poliza'], '09');
                                    $p_sep = ($p_sep1[0]['SUM(prima_com)'] == 0) ? 0 : $p_sep1[0]['SUM(prima_com)'];
                                    $a_sep = ($p_sep1[0]['SUM(prima_com)'] == 0) ? '-' : $p_sep1[0]['YEAR(f_pago_prima)'];
                                    $p_oct1 = $obj->get_prima_cob_d($polizas[$i]['id_poliza'], '10');
                                    $p_oct = ($p_oct1[0]['SUM(prima_com)'] == 0) ? 0 : $p_oct1[0]['SUM(prima_com)'];
                                    $a_oct = ($p_oct1[0]['SUM(prima_com)'] == 0) ? '-' : $p_oct1[0]['YEAR(f_pago_prima)'];
                                    $p_nov1 = $obj->get_prima_cob_d($polizas[$i]['id_poliza'], '11');
                                    $p_nov = ($p_nov1[0]['SUM(prima_com)'] == 0) ? 0 : $p_nov1[0]['SUM(prima_com)'];
                                    $a_nov = ($p_nov1[0]['SUM(prima_com)'] == 0) ? '-' : $p_nov1[0]['YEAR(f_pago_prima)'];
                                    $p_dic1 = $obj->get_prima_cob_d($polizas[$i]['id_poliza'], '12');
                                    $p_dic = ($p_dic1[0]['SUM(prima_com)'] == 0) ? 0 : $p_dic1[0]['SUM(prima_com)'];
                                    $a_dic = ($p_dic1[0]['SUM(prima_com)'] == 0) ? '-' : $p_dic1[0]['YEAR(f_pago_prima)'];

                                    $p_t = $p_ene + $p_feb + $p_mar + $p_abr + $p_may + $p_jun + $p_jul + $p_ago + $p_sep + $p_oct + $p_nov + $p_dic;

                                    $p_dif = ($polizas[$i]['prima'] - $p_t);
                                    $ppendiente = number_format($p_dif, 2);
                                    if ($ppendiente >= -0.10 && $ppendiente <= 0.10) {
                                        $ppendiente = 0;
                                    }

                                    $totalpsMes = $totalpsMes + $polizas[$i]['prima'];
                                    $totalpcMes = $totalpcMes + $p_t;
                                    $totaldifMes = $totaldifMes + ($polizas[$i]['prima'] - $p_t);

                                    $newDesde = date("d/m/Y", strtotime($polizas[$i]['f_desdepoliza']));
                                ?>

                                    <tr>
                                        <td><?= $mes_arr[$datemes]; ?></td>

                                        <?php if ($no_renov[0]['no_renov'] != 1) {
                                            if ($polizas[$i]['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                                <td style="color: #2B9E34;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool; ?>"><?= $polizas[$i]['cod_poliza']; ?></td>
                                            <?php } else { ?>
                                                <td style="color: #E54848;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool; ?>"><?= $polizas[$i]['cod_poliza']; ?></td>
                                            <?php }
                                        } else { ?>
                                            <td style="color: #4a148c;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool; ?>"><?= $polizas[$i]['cod_poliza']; ?></td>
                                        <?php } ?>

                                        <td data-toggle="tooltip" data-placement="top" title="<?= $tool; ?>"><?= $polizas[$i]['nombre_t'] . " " . $polizas[$i]['apellido_t']; ?></td>
                                        
                                        <td data-toggle="tooltip" data-placement="top" title="<?= $tool; ?>"><?= $newDesde; ?></td>
                                        <td data-toggle="tooltip" data-placement="top" title="<?= $tool; ?>"><?= $polizas[$i]['nomcia']; ?></td>
                                        <td data-toggle="tooltip" data-placement="top" title="<?= $tool; ?>"><?= $polizas[$i]['nramo']; ?></td>

                                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="Prima Suscrita" nowrap><?= '$ ' . number_format($polizas[$i]['prima'], 2); ?></td>
                                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="Prima Total" nowrap><?= '$ ' . number_format($p_t, 2); ?></td>

                                        <?php if ($ppendiente > 0) { ?>
                                            <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#F53333;font-size: 16px" data-toggle="tooltip" data-placement="top" title="<?= $tool; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                                        <?php }
                                        if ($ppendiente == 0) { ?>
                                            <td style="background-color: #D9D9D9 ;color:black;text-align: right;font-weight: bold;" data-toggle="tooltip" data-placement="top" title="<?= $tool; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                                        <?php }
                                        if ($ppendiente < 0) { ?>
                                            <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#2B9E34;font-size: 16px" data-toggle="tooltip" data-placement="top" title="<?= $tool; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                                        <?php } ?>

                                        <?php if ($p_ene > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Enero Año: ' . $a_ene; ?>" nowrap><?= '$ ' . number_format($p_ene, 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Enero Año: ' . $a_ene; ?>" nowrap><?= '$ ' . number_format($p_ene, 2); ?></td>
                                        <?php } ?>

                                        <?php if ($p_feb > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Febrero Año: ' . $a_feb; ?>" nowrap><?= '$ ' . number_format($p_feb, 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Febrero Año: ' . $a_feb; ?>" nowrap><?= '$ ' . number_format($p_feb, 2); ?></td>
                                        <?php } ?>

                                        <?php if ($p_mar > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Marzo Año: ' . $a_mar; ?>" nowrap><?= '$ ' . number_format($p_mar, 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Marzo Año: ' . $a_mar; ?>" nowrap><?= '$ ' . number_format($p_mar, 2); ?></td>
                                        <?php } ?>

                                        <?php if ($p_abr > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Abril Año: ' . $a_abr; ?>" nowrap><?= '$ ' . number_format($p_abr, 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Abril Año: ' . $a_abr; ?>" nowrap><?= '$ ' . number_format($p_abr, 2); ?></td>
                                        <?php } ?>

                                        <?php if ($p_may > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Mayo Año: ' . $a_may; ?>" nowrap><?= '$ ' . number_format($p_may, 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Mayo Año: ' . $a_may; ?>" nowrap><?= '$ ' . number_format($p_may, 2); ?></td>
                                        <?php } ?>

                                        <?php if ($p_jun > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Junio Año: ' . $a_jun; ?>" nowrap><?= '$ ' . number_format($p_jun, 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Junio Año: ' . $a_jun; ?>" nowrap><?= '$ ' . number_format($p_jun, 2); ?></td>
                                        <?php } ?>

                                        <?php if ($p_jul > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Julio Año: ' . $a_jul; ?>" nowrap><?= '$ ' . number_format($p_jul, 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Julio Año: ' . $a_jul; ?>" nowrap><?= '$ ' . number_format($p_jul, 2); ?></td>
                                        <?php } ?>

                                        <?php if ($p_ago > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Agosto Año: ' . $a_ago; ?>" nowrap><?= '$ ' . number_format($p_ago, 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Agosto Año: ' . $a_ago; ?>" nowrap><?= '$ ' . number_format($p_ago, 2); ?></td>
                                        <?php } ?>

                                        <?php if ($p_sep > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Septiembre Año: ' . $a_sep; ?>" nowrap><?= '$ ' . number_format($p_sep, 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Septiembre Año: ' . $a_sep; ?>" nowrap><?= '$ ' . number_format($p_sep, 2); ?></td>
                                        <?php } ?>

                                        <?php if ($p_oct > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Octubre Año: ' . $a_oct; ?>" nowrap><?= '$ ' . number_format($p_oct, 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Octubre Año: ' . $a_oct; ?>" nowrap><?= '$ ' . number_format($p_oct, 2); ?></td>
                                        <?php } ?>

                                        <?php if ($p_nov > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Noviembre Año: ' . $a_nov; ?>" nowrap><?= '$ ' . number_format($p_nov, 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Noviembre Año: ' . $a_nov; ?>" nowrap><?= '$ ' . number_format($p_nov, 2); ?></td>
                                        <?php } ?>

                                        <?php if ($p_dic > 0) { ?>
                                            <td style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Diciembre Año: ' . $a_dic; ?>" nowrap><?= '$ ' . number_format($p_dic, 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Diciembre Año: ' . $a_dic; ?>" nowrap><?= '$ ' . number_format($p_dic, 2); ?></td>
                                        <?php } ?>

                                    </tr>
                                <?php } ?>

                            </tbody>

                            <tr class="no-tocar">
                                <td colspan="6" style="background-color: #F53333;color: white;font-weight: bold">Total: <font size=4><?= sizeof($polizas); ?></font>
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

                            <tfoot>
                                <tr>
                                    <th>Mes Desde Seg</th>
                                    <th>N° Póliza</th>
                                    <th>Nombre Titular</th>
                                    <th>F Desde Seguro</th>
                                    <th>Cía</th>
                                    <th>Ramo</th>
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
                    </div>
                    <!--   -----------------TABLA PARA EXCEL-----------------   -->

                </div>

                <!--   TABLA PARA USUARIOS QUE SON ASESORES   -->
            <?php } ?>
        </div>





        <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'footer.php'; ?>

        <script src="../assets/view/b_poliza.js"></script>
</body>

</html>