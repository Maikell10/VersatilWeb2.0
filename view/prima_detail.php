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
    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'navigation.php'; ?>
    <br><br><br><br><br><br>

    <div>
        <div class="card">

            <div id="carga" class="d-flex justify-content-center align-items-center">
                <div class="spinner-grow text-info" style="width: 7rem; height: 7rem;"></div>
            </div>

         
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

                    <center id="excel"><a class="btn dusty-grass-gradient" href="excel/e_cobranza.php?session=<?= $_SESSION['id_permiso']; ?>&yhuejd=<?= $_SESSION['id_usuario']; ?>&desdeP=<?= $_GET['desdeP']; ?>&hastaP=<?= $_GET['hastaP']; ?>&desdeP_submit=<?= $_GET['desdeP_submit']; ?>&hastaP_submit=<?= $_GET['hastaP_submit']; ?>&ramo=<?= $ramoEnv; ?>&cia=<?= $ciaEnv; ?>&fpago=<?= $fpagoEnv; ?>&asesor=<?= $asesorEnv; ?>" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../assets/img/excel.png" width="60" alt=""></a></center>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered mx-auto" id="tablePD">
                            <thead class="blue-gradient text-white text-center">
                                <tr>
                                    <th>N° Póliza</th>
                                    <th>Nombre Titular</th>
                                    <th hidden>F Desde Seguro</th>
                                    <th hidden>Cía</th>
                                    <th hidden>Ramo</th>
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

                                        <td hidden><?= $newDesde1[$i]; ?></td>
                                        <td hidden><?= $nomcia1[$i]; ?></td>
                                        <td hidden><?= $nramo1[$i]; ?></td>

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
                                        <td hidden><?= $idpoliza1[$i]; ?></td>
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

                                        <td hidden><?= $newDesde1a[$i]; ?></td>
                                        <td hidden><?= $nomcia1a[$i]; ?></td>
                                        <td hidden><?= $nramo1a[$i]; ?></td>

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
                                        <td hidden><?= $idpoliza1a[$i]; ?></td>
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

                                        <td hidden><?= $newDesde1b[$i]; ?></td>
                                        <td hidden><?= $nomcia1b[$i]; ?></td>
                                        <td hidden><?= $nramo1b[$i]; ?></td>

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
                                        <td hidden><?= $idpoliza1b[$i]; ?></td>
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

                                        <td hidden><?= $newDesde1c[$i]; ?></td>
                                        <td hidden><?= $nomcia1c[$i]; ?></td>
                                        <td hidden><?= $nramo1c[$i]; ?></td>

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
                                        <td hidden><?= $idpoliza1c[$i]; ?></td>
                                    </tr>
                                <?php } ?>

                            </tbody>


                            <tr class="no-tocar">
                                <td colspan="2" style="background-color: #F53333;color: white;font-weight: bold">Total: <font size=4><?= $cantPolizas; ?></font>
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
                                    <th hidden>F Desde Seguro</th>
                                    <th hidden>Cía</th>
                                    <th hidden>Ramo</th>
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

                </div>

                <!--   TABLA PARA USUARIOS QUE SON ASESORES   -->
            
        </div>





        <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'footer.php'; ?>

        <script src="../assets/view/b_poliza.js"></script>

        <script>
            /*
            if (<?= $totalpoliza; ?> > 280) {
                $('#excel').attr('hidden', 'true');
            }  */
        </script>
</body>

</html>