<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}

//$pag = 'b_poliza1';

require_once '../Controller/Poliza.php';

$anio = (isset($_POST["anio"]) != null) ? $_POST["anio"] : '';
$mes = (isset($_POST["mes"]) != null) ? $_POST["mes"] : '';
$fpago = (isset($_POST["fpago"]) != null) ? $_POST["fpago"] : '';
$cia = (isset($_POST["cia"]) != null) ? $_POST["cia"] : '';
$asesor = (isset($_POST["asesor"]) != null) ? $_POST["asesor"] : '';

if ($mes == null) {
    $fechaMax = $obj->get_fecha_max_prima_d($anio, $fpago, $cia, $asesor);
    $fechaMax = date('m', strtotime($fechaMax[0]["MAX(f_desdepoliza)"]));
    $estado = 0;

    for ($i = 0; $i < $fechaMax; $i++) {
        $polizas = $obj->get_poliza_total_by_filtro_detalle_p($fpago, $anio, $cia, $asesor, $i + 1);

        for ($a = 0; $a < sizeof($polizas); $a++) {

            $p_ene1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '01');
            $p_ene = ($p_ene1[0]['SUM(prima_com)'] == 0) ? 0 : $p_ene1[0]['SUM(prima_com)'];
            $a_ene = ($p_ene1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_ene1[0]['YEAR(f_pago_prima)'];
            $p_feb1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '02');
            $p_feb = ($p_feb1[0]['SUM(prima_com)'] == 0) ? 0 : $p_feb1[0]['SUM(prima_com)'];
            $a_feb = ($p_feb1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_feb1[0]['YEAR(f_pago_prima)'];
            $p_mar1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '03');
            $p_mar = ($p_mar1[0]['SUM(prima_com)'] == 0) ? 0 : $p_mar1[0]['SUM(prima_com)'];
            $a_mar = ($p_mar1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_mar1[0]['YEAR(f_pago_prima)'];
            $p_abr1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '04');
            $p_abr = ($p_abr1[0]['SUM(prima_com)'] == 0) ? 0 : $p_abr1[0]['SUM(prima_com)'];
            $a_abr = ($p_abr1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_abr1[0]['YEAR(f_pago_prima)'];
            $p_may1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '05');
            $p_may = ($p_may1[0]['SUM(prima_com)'] == 0) ? 0 : $p_may1[0]['SUM(prima_com)'];
            $a_may = ($p_may1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_may1[0]['YEAR(f_pago_prima)'];
            $p_jun1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '06');
            $p_jun = ($p_jun1[0]['SUM(prima_com)'] == 0) ? 0 : $p_jun1[0]['SUM(prima_com)'];
            $a_jun = ($p_jun1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_jun1[0]['YEAR(f_pago_prima)'];
            $p_jul1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '07');
            $p_jul = ($p_jul1[0]['SUM(prima_com)'] == 0) ? 0 : $p_jul1[0]['SUM(prima_com)'];
            $a_jul = ($p_jul1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_jul1[0]['YEAR(f_pago_prima)'];
            $p_ago1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '08');
            $p_ago = ($p_ago1[0]['SUM(prima_com)'] == 0) ? 0 : $p_ago1[0]['SUM(prima_com)'];
            $a_ago = ($p_ago1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_ago1[0]['YEAR(f_pago_prima)'];
            $p_sep1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '09');
            $p_sep = ($p_sep1[0]['SUM(prima_com)'] == 0) ? 0 : $p_sep1[0]['SUM(prima_com)'];
            $a_sep = ($p_sep1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_sep1[0]['YEAR(f_pago_prima)'];
            $p_oct1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '10');
            $p_oct = ($p_oct1[0]['SUM(prima_com)'] == 0) ? 0 : $p_oct1[0]['SUM(prima_com)'];
            $a_oct = ($p_oct1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_oct1[0]['YEAR(f_pago_prima)'];
            $p_nov1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '11');
            $p_nov = ($p_nov1[0]['SUM(prima_com)'] == 0) ? 0 : $p_nov1[0]['SUM(prima_com)'];
            $a_nov = ($p_nov1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_nov1[0]['YEAR(f_pago_prima)'];
            $p_dic1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '12');
            $p_dic = ($p_dic1[0]['SUM(prima_com)'] == 0) ? 0 : $p_dic1[0]['SUM(prima_com)'];
            $a_dic = ($p_dic1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_dic1[0]['YEAR(f_pago_prima)'];


            $p_enero[] = $p_ene;
            $a_enero[] = $a_ene;
            $p_febrero[] = $p_feb;
            $a_febrero[] = $a_feb;
            $p_marzo[] = $p_mar;
            $a_marzo[] = $a_mar;
            $p_abril[] = $p_abr;
            $a_abril[] = $a_abr;
            $p_mayo[] = $p_may;
            $a_mayo[] = $a_may;
            $p_junio[] = $p_jun;
            $a_junio[] = $a_jun;
            $p_julio[] = $p_jul;
            $a_julio[] = $a_jul;
            $p_agosto[] = $p_ago;
            $a_agosto[] = $a_ago;
            $p_septiempre[] = $p_sep;
            $a_septiempre[] = $a_sep;
            $p_octubre[] = $p_oct;
            $a_octubre[] = $a_oct;
            $p_noviembre[] = $p_nov;
            $a_noviembre[] = $a_nov;
            $p_diciembre[] = $p_dic;
            $a_diciembre[] = $a_dic;

            $p_t = $p_ene + $p_feb + $p_mar + $p_abr + $p_may + $p_jun + $p_jul + $p_ago + $p_sep + $p_oct + $p_nov + $p_dic;

            $totalprima = $totalprima + $polizas[$a]['prima'];

            $tool[] = 'Cía: ' . $polizas[$a]['nomcia'] . ' Ramo: ' . $polizas[$a]['nramo'];

            $cod_poliza[] = $polizas[$a]['cod_poliza'];
            $ciente[] = $polizas[$a]['nombre_t'] . " " . $polizas[$a]['apellido_t'];
            $newDesde[] = date("d/m/Y", strtotime($polizas[$a]['f_desdepoliza']));
            $nomcia[] = $polizas[$a]['nomcia'];
            $nramo[] = $polizas[$a]['nramo'];
            $prima_s[] = $polizas[$a]['prima'];
            $p_tt[] = $p_t;
            $p_dif[] = ($polizas[$a]['prima'] - $p_t);

            $f_hasta_poliza[] = $polizas[$a]['f_hastapoliza'];

            $idpoliza[] = $polizas[$a]['id_poliza'];
        }

        arsort($p_dif, SORT_NUMERIC);

        foreach ($p_dif as $key => $value) {
            $cod_poliza1[] = $cod_poliza[$key];
            $ciente1[] = $ciente[$key];
            $newDesde1[] = $newDesde[$key];
            $nomcia1[] = $nomcia[$key];
            $nramo1[] = $nramo[$key];
            $prima_s1[] = $prima_s[$key];
            $p_tt1[] = $p_tt[$key];
            $tool1[] = $tool[$key];
            $p_dif1[] = $value;

            $p_enero1[] = $p_enero[$key];
            $a_enero1[] = $a_enero[$key];
            $p_febrero1[] = $p_febrero[$key];
            $a_febrero1[] = $a_febrero[$key];
            $p_marzo1[] = $p_marzo[$key];
            $a_marzo1[] = $a_marzo[$key];
            $p_abril1[] = $p_abril[$key];
            $a_abril1[] = $a_abril[$key];
            $p_mayo1[] = $p_mayo[$key];
            $a_mayo1[] = $a_mayo[$key];
            $p_junio1[] = $p_junio[$key];
            $a_junio1[] = $a_junio[$key];
            $p_julio1[] = $p_julio[$key];
            $a_julio1[] = $a_julio[$key];
            $p_agosto1[] = $p_agosto[$key];
            $a_agosto1[] = $a_agosto[$key];
            $p_septiempre1[] = $p_septiempre[$key];
            $a_septiempre1[] = $a_septiempre[$key];
            $p_octubre1[] = $p_octubre[$key];
            $a_octubre1[] = $a_octubre[$key];
            $p_noviembre1[] = $p_noviembre[$key];
            $a_noviembre1[] = $a_noviembre[$key];
            $p_diciembre1[] = $p_diciembre[$key];
            $a_diciembre1[] = $a_diciembre[$key];

            $f_hasta_poliza1[] = $f_hasta_poliza[$key];
            $idpoliza1[] = $idpoliza[$key];
        }
        unset($p_dif, $nomcia, $cod_poliza, $ciente, $newDesde, $nramo, $prima_s, $p_tt, $tool, $p_enero, $p_febrero, $p_marzo, $p_abril, $p_mayo, $p_junio, $p_julio, $p_agosto, $p_septiempre, $p_octubre, $p_noviembre, $p_diciembre, $f_hasta_poliza, $idpoliza, $a_enero, $a_febrero, $a_marzo, $a_abril, $a_mayo, $a_junio, $a_julio, $a_agosto, $a_septiempre, $a_octubre, $a_noviembre, $a_diciembre);
    }
} else {
    $fechaMax = $mes;
    $estado = 1;

    $polizas = $obj->get_poliza_total_by_filtro_detalle_p($fpago, $anio, $cia, $asesor, $fechaMax);
    for ($a = 0; $a < sizeof($polizas); $a++) {
        $p_ene1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '01');
        $p_ene = ($p_ene1[0]['SUM(prima_com)'] == 0) ? 0 : $p_ene1[0]['SUM(prima_com)'];
        $a_ene = ($p_ene1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_ene1[0]['YEAR(f_pago_prima)'];
        $p_feb1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '02');
        $p_feb = ($p_feb1[0]['SUM(prima_com)'] == 0) ? 0 : $p_feb1[0]['SUM(prima_com)'];
        $a_feb = ($p_feb1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_feb1[0]['YEAR(f_pago_prima)'];
        $p_mar1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '03');
        $p_mar = ($p_mar1[0]['SUM(prima_com)'] == 0) ? 0 : $p_mar1[0]['SUM(prima_com)'];
        $a_mar = ($p_mar1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_mar1[0]['YEAR(f_pago_prima)'];
        $p_abr1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '04');
        $p_abr = ($p_abr1[0]['SUM(prima_com)'] == 0) ? 0 : $p_abr1[0]['SUM(prima_com)'];
        $a_abr = ($p_abr1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_abr1[0]['YEAR(f_pago_prima)'];
        $p_may1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '05');
        $p_may = ($p_may1[0]['SUM(prima_com)'] == 0) ? 0 : $p_may1[0]['SUM(prima_com)'];
        $a_may = ($p_may1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_may1[0]['YEAR(f_pago_prima)'];
        $p_jun1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '06');
        $p_jun = ($p_jun1[0]['SUM(prima_com)'] == 0) ? 0 : $p_jun1[0]['SUM(prima_com)'];
        $a_jun = ($p_jun1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_jun1[0]['YEAR(f_pago_prima)'];
        $p_jul1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '07');
        $p_jul = ($p_jul1[0]['SUM(prima_com)'] == 0) ? 0 : $p_jul1[0]['SUM(prima_com)'];
        $a_jul = ($p_jul1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_jul1[0]['YEAR(f_pago_prima)'];
        $p_ago1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '08');
        $p_ago = ($p_ago1[0]['SUM(prima_com)'] == 0) ? 0 : $p_ago1[0]['SUM(prima_com)'];
        $a_ago = ($p_ago1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_ago1[0]['YEAR(f_pago_prima)'];
        $p_sep1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '09');
        $p_sep = ($p_sep1[0]['SUM(prima_com)'] == 0) ? 0 : $p_sep1[0]['SUM(prima_com)'];
        $a_sep = ($p_sep1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_sep1[0]['YEAR(f_pago_prima)'];
        $p_oct1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '10');
        $p_oct = ($p_oct1[0]['SUM(prima_com)'] == 0) ? 0 : $p_oct1[0]['SUM(prima_com)'];
        $a_oct = ($p_oct1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_oct1[0]['YEAR(f_pago_prima)'];
        $p_nov1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '11');
        $p_nov = ($p_nov1[0]['SUM(prima_com)'] == 0) ? 0 : $p_nov1[0]['SUM(prima_com)'];
        $a_nov = ($p_nov1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_nov1[0]['YEAR(f_pago_prima)'];
        $p_dic1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '12');
        $p_dic = ($p_dic1[0]['SUM(prima_com)'] == 0) ? 0 : $p_dic1[0]['SUM(prima_com)'];
        $a_dic = ($p_dic1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_dic1[0]['YEAR(f_pago_prima)'];


        $p_enero[] = $p_ene;
        $a_enero[] = $a_ene;
        $p_febrero[] = $p_feb;
        $a_febrero[] = $a_feb;
        $p_marzo[] = $p_mar;
        $a_marzo[] = $a_mar;
        $p_abril[] = $p_abr;
        $a_abril[] = $a_abr;
        $p_mayo[] = $p_may;
        $a_mayo[] = $a_may;
        $p_junio[] = $p_jun;
        $a_junio[] = $a_jun;
        $p_julio[] = $p_jul;
        $a_julio[] = $a_jul;
        $p_agosto[] = $p_ago;
        $a_agosto[] = $a_ago;
        $p_septiempre[] = $p_sep;
        $a_septiempre[] = $a_sep;
        $p_octubre[] = $p_oct;
        $a_octubre[] = $a_oct;
        $p_noviembre[] = $p_nov;
        $a_noviembre[] = $a_nov;
        $p_diciembre[] = $p_dic;
        $a_diciembre[] = $a_dic;

        $p_t = $p_ene + $p_feb + $p_mar + $p_abr + $p_may + $p_jun + $p_jul + $p_ago + $p_sep + $p_oct + $p_nov + $p_dic;

        $totalprima = $totalprima + $polizas[$a]['prima'];

        $tool[] = 'Cía: ' . $polizas[$a]['nomcia'] . ' Ramo: ' . $polizas[$a]['nramo'];

        $cod_poliza[] = $polizas[$a]['cod_poliza'];
        $ciente[] = $polizas[$a]['nombre_t'] . " " . $polizas[$a]['apellido_t'];
        $newDesde[] = date("d/m/Y", strtotime($polizas[$a]['f_desdepoliza']));
        $nomcia[] = $polizas[$a]['nomcia'];
        $nramo[] = $polizas[$a]['nramo'];
        $prima_s[] = $polizas[$a]['prima'];
        $p_tt[] = $p_t;
        $p_dif[] = ($polizas[$a]['prima'] - $p_t);

        $f_hasta_poliza[] = $polizas[$a]['f_hastapoliza'];

        $idpoliza[] = $polizas[$a]['id_poliza'];
    }
    arsort($p_dif, SORT_NUMERIC);

    foreach ($p_dif as $key => $value) {
        $cod_poliza1[] = $cod_poliza[$key];
        $ciente1[] = $ciente[$key];
        $newDesde1[] = $newDesde[$key];
        $nomcia1[] = $nomcia[$key];
        $nramo1[] = $nramo[$key];
        $prima_s1[] = $prima_s[$key];
        $p_tt1[] = $p_tt[$key];
        $tool1[] = $tool[$key];
        $p_dif1[] = $value;

        $p_enero1[] = $p_enero[$key];
        $a_enero1[] = $a_enero[$key];
        $p_febrero1[] = $p_febrero[$key];
        $a_febrero1[] = $a_febrero[$key];
        $p_marzo1[] = $p_marzo[$key];
        $a_marzo1[] = $a_marzo[$key];
        $p_abril1[] = $p_abril[$key];
        $a_abril1[] = $a_abril[$key];
        $p_mayo1[] = $p_mayo[$key];
        $a_mayo1[] = $a_mayo[$key];
        $p_junio1[] = $p_junio[$key];
        $a_junio1[] = $a_junio[$key];
        $p_julio1[] = $p_julio[$key];
        $a_julio1[] = $a_julio[$key];
        $p_agosto1[] = $p_agosto[$key];
        $a_agosto1[] = $a_agosto[$key];
        $p_septiempre1[] = $p_septiempre[$key];
        $a_septiempre1[] = $a_septiempre[$key];
        $p_octubre1[] = $p_octubre[$key];
        $a_octubre1[] = $a_octubre[$key];
        $p_noviembre1[] = $p_noviembre[$key];
        $a_noviembre1[] = $a_noviembre[$key];
        $p_diciembre1[] = $p_diciembre[$key];
        $a_diciembre1[] = $a_diciembre[$key];

        $f_hasta_poliza1[] = $f_hasta_poliza[$key];
        $idpoliza1[] = $idpoliza[$key];
    }
    unset($p_dif, $nomcia, $cod_poliza, $ciente, $newDesde, $nramo, $prima_s, $p_tt, $tool, $p_enero, $p_febrero, $p_marzo, $p_abril, $p_mayo, $p_junio, $p_julio, $p_agosto, $p_septiempre, $p_octubre, $p_noviembre, $p_diciembre, $f_hasta_poliza, $idpoliza, $a_enero, $a_febrero, $a_marzo, $a_abril, $a_mayo, $a_junio, $a_julio, $a_agosto, $a_septiempre, $a_octubre, $a_noviembre, $a_diciembre);
}

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



                    <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('tablePD', 'Listado de Pólizas')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../assets/img/excel.png" width="60" alt=""></a></center>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered" id="tablePD">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th>Mes Desde Seg</th>
                                    <th>N° Póliza</th>
                                    <th>Nombre Titular</th>
                                    <th>F Desde Seguro</th>
                                    <th hidden>Cía</th>
                                    <th hidden>Ramo</th>
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
                                                if ($f_hasta_poliza1[$c] >= date("Y-m-d")) {
                                            ?>
                                                    <td style="color: #2B9E34;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$c]; ?>"><?= $cod_poliza1[$c]; ?></td>
                                                <?php
                                                } else {
                                                ?>
                                                    <td style="color: #E54848;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$c]; ?>"><?= $cod_poliza1[$c]; ?></td>
                                                <?php } ?>

                                                <td data-toggle="tooltip" data-placement="top" title="<?= $tool1[$c]; ?>"><?= $ciente1[$c]; ?></td>
                                                <td data-toggle="tooltip" data-placement="top" title="<?= $tool1[$c]; ?>"><?= $newDesde1[$c]; ?></td>
                                                <td hidden><?= $nomcia1[$c]; ?></td>
                                                <td hidden><?= $nramo1[$c]; ?></td>
                                                <td data-toggle="tooltip" data-placement="top" title="Prima Suscrita" nowrap><?= '$ ' . number_format($prima_s1[$c], 2); ?></td>
                                                <td data-toggle="tooltip" data-placement="top" title="Prima Total" nowrap><?= '$ ' . number_format($p_tt1[$c], 2); ?></td>
                                                <td style="background-color: #ED7D31;color:white" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$c]; ?>" nowrap><?= '$ ' . number_format($p_dif1[$c], 2); ?></td>
                                                <td data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Enero Año: ' . $a_enero1[$c]; ?>" nowrap><?= '$ ' . number_format($p_enero1[$c], 2); ?></td>
                                                <td data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Febrero Año: ' . $a_febrero1[$c]; ?>" nowrap><?= '$ ' . number_format($p_febrero1[$c], 2); ?></td>
                                                <td data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Marzo Año: ' . $a_marzo1[$c]; ?>" nowrap><?= '$ ' . number_format($p_marzo1[$c], 2); ?></td>
                                                <td data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Abril Año: ' . $a_abril1[$c]; ?>" nowrap><?= '$ ' . number_format($p_abril1[$c], 2); ?></td>
                                                <td data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Mayo Año: ' . $a_mayo1[$c]; ?>" nowrap><?= '$ ' . number_format($p_mayo1[$c], 2); ?></td>
                                                <td data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Junio Año: ' . $a_junio1[$c]; ?>" nowrap><?= '$ ' . number_format($p_junio1[$c], 2); ?></td>
                                                <td data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Julio Año: ' . $a_julio1[$c]; ?>" nowrap><?= '$ ' . number_format($p_julio1[$c], 2); ?></td>
                                                <td data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Agosto Año: ' . $a_agosto1[$c]; ?>" nowrap><?= '$ ' . number_format($p_agosto1[$c], 2); ?></td>
                                                <td data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Septiembre Año: ' . $a_septiempre1[$c]; ?>" nowrap><?= '$ ' . number_format($p_septiempre1[$c], 2); ?></td>
                                                <td data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Octubre Año: ' . $a_octubre1[$c]; ?>" nowrap><?= '$ ' . number_format($p_octubre1[$c], 2); ?></td>
                                                <td data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Noviembre Año: ' . $a_noviembre1[$c]; ?>" nowrap><?= '$ ' . number_format($p_noviembre1[$c], 2); ?></td>
                                                <td data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Diciembre Año: ' . $a_diciembre1[$c]; ?>" nowrap><?= '$ ' . number_format($p_diciembre1[$c], 2); ?></td>
                                                <td hidden><?= $idpoliza1[$c]; ?></td>
                                        </tr>

                                    <?php
                                                $totalpsMes = $totalpsMes + $prima_s1[$c];
                                                $totalpcMes = $totalpcMes + $p_tt1[$c];
                                                $totaldifMes = $totaldifMes + $p_dif1[$c];
                                                $c++;
                                            } ?>
                                    <tr class="no-tocar">
                                        <td colspan="4" style="background-color: #F53333;color: white;font-weight: bold">Total <?= $mes_arr[$i]; ?>: <font size=4 color="aqua"><?= sizeof($polizas); ?></font>
                                        </td>
                                        <td nowrap style="background-color: #F53333;color: white;font-weight: bold">
                                            <font size=4 color="aqua"><?= '$ ' . number_format($totalpsMes, 2); ?></font>
                                        </td>
                                        <td nowrap style="background-color: #F53333;color: white;font-weight: bold">
                                            <font size=4 color="aqua"><?= '$ ' . number_format($totalpcMes, 2); ?></font>
                                        </td>
                                        <td nowrap style="background-color: #F53333;color: white;font-weight: bold">
                                            <font size=4 color="aqua"><?= '$ ' . number_format($totaldifMes, 2); ?></font>
                                        </td>
                                        <td colspan="12" style="background-color: #F53333;color: white;font-weight: bold">
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
                                        if ($f_hasta_poliza1[$c] >= date("Y-m-d")) {
                                    ?>
                                            <td style="color: #2B9E34;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$c]; ?>"><?= $cod_poliza1[$c]; ?></td>
                                        <?php
                                        } else {
                                        ?>
                                            <td style="color: #E54848;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$c]; ?>"><?= $cod_poliza1[$c]; ?></td>
                                        <?php } ?>

                                        <td data-toggle="tooltip" data-placement="top" title="<?= $tool1[$c]; ?>"><?= $ciente1[$c]; ?></td>
                                        <td data-toggle="tooltip" data-placement="top" title="<?= $tool1[$c]; ?>"><?= $newDesde1[$c]; ?></td>
                                        <td hidden><?= $nomcia1[$c]; ?></td>
                                        <td hidden><?= $nramo1[$c]; ?></td>
                                        <td data-toggle="tooltip" data-placement="top" title="Prima Suscrita" nowrap><?= '$ ' . number_format($prima_s1[$c], 2); ?></td>
                                        <td data-toggle="tooltip" data-placement="top" title="Prima Total" nowrap><?= '$ ' . number_format($p_tt1[$c], 2); ?></td>
                                        <td style="background-color: #ED7D31;color:white" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$c]; ?>" nowrap><?= '$ ' . number_format($p_dif1[$c], 2); ?></td>
                                        <td data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Enero Año: ' . $a_enero1[$c]; ?>" nowrap><?= '$ ' . number_format($p_enero1[$c], 2); ?></td>
                                        <td data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Febrero Año: ' . $a_febrero1[$c]; ?>" nowrap><?= '$ ' . number_format($p_febrero1[$c], 2); ?></td>
                                        <td data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Marzo Año: ' . $a_marzo1[$c]; ?>" nowrap><?= '$ ' . number_format($p_marzo1[$c], 2); ?></td>
                                        <td data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Abril Año: ' . $a_abril1[$c]; ?>" nowrap><?= '$ ' . number_format($p_abril1[$c], 2); ?></td>
                                        <td data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Mayo Año: ' . $a_mayo1[$c]; ?>" nowrap><?= '$ ' . number_format($p_mayo1[$c], 2); ?></td>
                                        <td data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Junio Año: ' . $a_junio1[$c]; ?>" nowrap><?= '$ ' . number_format($p_junio1[$c], 2); ?></td>
                                        <td data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Julio Año: ' . $a_julio1[$c]; ?>" nowrap><?= '$ ' . number_format($p_julio1[$c], 2); ?></td>
                                        <td data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Agosto Año: ' . $a_agosto1[$c]; ?>" nowrap><?= '$ ' . number_format($p_agosto1[$c], 2); ?></td>
                                        <td data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Septiembre Año: ' . $a_septiempre1[$c]; ?>" nowrap><?= '$ ' . number_format($p_septiempre1[$c], 2); ?></td>
                                        <td data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Octubre Año: ' . $a_octubre1[$c]; ?>" nowrap><?= '$ ' . number_format($p_octubre1[$c], 2); ?></td>
                                        <td data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Noviembre Año: ' . $a_noviembre1[$c]; ?>" nowrap><?= '$ ' . number_format($p_noviembre1[$c], 2); ?></td>
                                        <td data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Diciembre Año: ' . $a_diciembre1[$c]; ?>" nowrap><?= '$ ' . number_format($p_diciembre1[$c], 2); ?></td>
                                        <td hidden><?= $idpoliza1[$c]; ?></td>
                                </tr>

                            <?php $c++;
                                    } ?>
                            <tr class="no-tocar">
                                <td colspan="22" style="background-color: #F53333;color: white;font-weight: bold">Total <?= $mes_arr[$i]; ?>: <font size=4 color="aqua"><?= sizeof($polizas); ?></font>
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
                                    <th hidden>Cía</th>
                                    <th hidden>Ramo</th>
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
                </div>

                <!--   TABLA PARA USUARIOS QUE SON ASESORES   -->
            <?php } ?>
        </div>





        <?php require_once dirname(__DIR__) . '\layout\footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . '\layout\footer.php'; ?>

        <script src="../assets/view/b_poliza.js"></script>
</body>

</html>