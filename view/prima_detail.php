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
$fpago = (isset($_POST["fpago"]) != null) ? $_POST["fpago"] : '';
$cia = (isset($_POST["cia"]) != null) ? $_POST["cia"] : '';
$ramo = (isset($_POST["ramo"]) != null) ? $_POST["ramo"] : '';

$fechaMax = $obj->get_fecha_max_prima_d($anio);
$fechaMax = date('m', strtotime($fechaMax[0]["MAX(f_desdepoliza)"]));

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
                                    <th style="background-color: #E54848;">Prima Suscrita</th>
                                    <th style="background-color: #E54848;">Prima Total</th>
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
                                for ($i = 0; $i < $fechaMax; $i++) {
                                    $polizas = $obj->get_poliza_total_by_filtro_detalle_p($fpago, $anio, $cia, $ramo, $i + 1);
                                ?>
                                    <tr>
                                        <td rowspan="<?= sizeof($polizas); ?>" style="background-color: #D9D9D9"><?= $mes_arr[$i]; ?></td>

                                        <?php foreach ($polizas as $poliza) {
                                            $p_ene = $obj->get_prima_cob_d($poliza['id_poliza'], '01');
                                            $p_ene = ($p_ene[0]['SUM(prima_com)'] == 0) ? 0 : $p_ene[0]['SUM(prima_com)'];
                                            $p_feb = $obj->get_prima_cob_d($poliza['id_poliza'], '02');
                                            $p_feb = ($p_feb[0]['SUM(prima_com)'] == 0) ? 0 : $p_feb[0]['SUM(prima_com)'];
                                            $p_mar = $obj->get_prima_cob_d($poliza['id_poliza'], '03');
                                            $p_mar = ($p_mar[0]['SUM(prima_com)'] == 0) ? 0 : $p_mar[0]['SUM(prima_com)'];
                                            $p_abr = $obj->get_prima_cob_d($poliza['id_poliza'], '04');
                                            $p_abr = ($p_abr[0]['SUM(prima_com)'] == 0) ? 0 : $p_abr[0]['SUM(prima_com)'];
                                            $p_may = $obj->get_prima_cob_d($poliza['id_poliza'], '05');
                                            $p_may = ($p_may[0]['SUM(prima_com)'] == 0) ? 0 : $p_may[0]['SUM(prima_com)'];
                                            $p_jun = $obj->get_prima_cob_d($poliza['id_poliza'], '06');
                                            $p_jun = ($p_jun[0]['SUM(prima_com)'] == 0) ? 0 : $p_jun[0]['SUM(prima_com)'];
                                            $p_jul = $obj->get_prima_cob_d($poliza['id_poliza'], '07');
                                            $p_jul = ($p_jul[0]['SUM(prima_com)'] == 0) ? 0 : $p_jul[0]['SUM(prima_com)'];
                                            $p_ago = $obj->get_prima_cob_d($poliza['id_poliza'], '08');
                                            $p_ago = ($p_ago[0]['SUM(prima_com)'] == 0) ? 0 : $p_ago[0]['SUM(prima_com)'];
                                            $p_sep = $obj->get_prima_cob_d($poliza['id_poliza'], '09');
                                            $p_sep = ($p_sep[0]['SUM(prima_com)'] == 0) ? 0 : $p_sep[0]['SUM(prima_com)'];
                                            $p_oct = $obj->get_prima_cob_d($poliza['id_poliza'], '10');
                                            $p_oct = ($p_oct[0]['SUM(prima_com)'] == 0) ? 0 : $p_oct[0]['SUM(prima_com)'];
                                            $p_nov = $obj->get_prima_cob_d($poliza['id_poliza'], '11');
                                            $p_nov = ($p_nov[0]['SUM(prima_com)'] == 0) ? 0 : $p_nov[0]['SUM(prima_com)'];
                                            $p_dic = $obj->get_prima_cob_d($poliza['id_poliza'], '12');
                                            $p_dic = ($p_dic[0]['SUM(prima_com)'] == 0) ? 0 : $p_dic[0]['SUM(prima_com)'];

                                            $p_t = $p_ene + $p_feb + $p_mar + $p_abr + $p_may + $p_jun + $p_jul + $p_ago + $p_sep + $p_oct + $p_nov + $p_dic;

                                            $totalprima = $totalprima + $poliza['prima'];

                                            $newDesde = date("d/m/Y", strtotime($poliza['f_desdepoliza']));

                                            if ($poliza['f_hastapoliza'] >= date("Y-m-d")) {
                                        ?>
                                                <td style="color: #2B9E34;font-weight: bold" data-toggle="tooltip" data-placement="right" title="Cía: <?= $poliza['nomcia']; ?> Ramo: <?= $poliza['nramo']; ?>"><?= $poliza['cod_poliza']; ?></td>
                                            <?php
                                            } else {
                                            ?>
                                                <td style="color: #E54848;font-weight: bold" data-toggle="tooltip" data-placement="right" title="Cía: <?= $poliza['nomcia']; ?> Ramo: <?= $poliza['nramo']; ?>"><?= $poliza['cod_poliza']; ?></td>
                                            <?php } ?>

                                            <td data-toggle="tooltip" data-placement="right" title="Cía: <?= $poliza['nomcia']; ?> Ramo: <?= $poliza['nramo']; ?>"><?= utf8_encode($poliza['nombre_t'] . " " . $poliza['apellido_t']); ?></td>
                                            <td data-toggle="tooltip" data-placement="right" title="Cía: <?= $poliza['nomcia']; ?> Ramo: <?= $poliza['nramo']; ?>"><?= $newDesde; ?></td>
                                            <td data-toggle="tooltip" data-placement="right" title="Cía: <?= $poliza['nomcia']; ?> Ramo: <?= $poliza['nramo']; ?>" nowrap><?= '$ ' . number_format($poliza['prima'], 2); ?></td>
                                            <td data-toggle="tooltip" data-placement="right" title="Cía: <?= $poliza['nomcia']; ?> Ramo: <?= $poliza['nramo']; ?>" nowrap><?= '$ ' . number_format($p_t, 2); ?></td>
                                            <td data-toggle="tooltip" data-placement="right" title="Cía: <?= $poliza['nomcia']; ?> Ramo: <?= $poliza['nramo']; ?>" nowrap><?= '$ ' . number_format($poliza['prima'] - $p_t, 2); ?></td>
                                            <td data-toggle="tooltip" data-placement="right" title="Cía: <?= $poliza['nomcia']; ?> Ramo: <?= $poliza['nramo']; ?>" nowrap><?= '$ ' . number_format($p_ene, 2); ?></td>
                                            <td data-toggle="tooltip" data-placement="right" title="Cía: <?= $poliza['nomcia']; ?> Ramo: <?= $poliza['nramo']; ?>" nowrap><?= '$ ' . number_format($p_feb, 2); ?></td>
                                            <td data-toggle="tooltip" data-placement="right" title="Cía: <?= $poliza['nomcia']; ?> Ramo: <?= $poliza['nramo']; ?>" nowrap><?= '$ ' . number_format($p_mar, 2); ?></td>
                                            <td data-toggle="tooltip" data-placement="right" title="Cía: <?= $poliza['nomcia']; ?> Ramo: <?= $poliza['nramo']; ?>" nowrap><?= '$ ' . number_format($p_abr, 2); ?></td>
                                            <td data-toggle="tooltip" data-placement="right" title="Cía: <?= $poliza['nomcia']; ?> Ramo: <?= $poliza['nramo']; ?>" nowrap><?= '$ ' . number_format($p_may, 2); ?></td>
                                            <td data-toggle="tooltip" data-placement="right" title="Cía: <?= $poliza['nomcia']; ?> Ramo: <?= $poliza['nramo']; ?>" nowrap><?= '$ ' . number_format($p_jun, 2); ?></td>
                                            <td data-toggle="tooltip" data-placement="right" title="Cía: <?= $poliza['nomcia']; ?> Ramo: <?= $poliza['nramo']; ?>" nowrap><?= '$ ' . number_format($p_jul, 2); ?></td>
                                            <td data-toggle="tooltip" data-placement="right" title="Cía: <?= $poliza['nomcia']; ?> Ramo: <?= $poliza['nramo']; ?>" nowrap><?= '$ ' . number_format($p_ago, 2); ?></td>
                                            <td data-toggle="tooltip" data-placement="right" title="Cía: <?= $poliza['nomcia']; ?> Ramo: <?= $poliza['nramo']; ?>" nowrap><?= '$ ' . number_format($p_sep, 2); ?></td>
                                            <td data-toggle="tooltip" data-placement="right" title="Cía: <?= $poliza['nomcia']; ?> Ramo: <?= $poliza['nramo']; ?>" nowrap><?= '$ ' . number_format($p_oct, 2); ?></td>
                                            <td data-toggle="tooltip" data-placement="right" title="Cía: <?= $poliza['nomcia']; ?> Ramo: <?= $poliza['nramo']; ?>" nowrap><?= '$ ' . number_format($p_nov, 2); ?></td>
                                            <td data-toggle="tooltip" data-placement="right" title="Cía: <?= $poliza['nomcia']; ?> Ramo: <?= $poliza['nramo']; ?>" nowrap><?= '$ ' . number_format($p_dic, 2); ?></td>
                                            <td data-toggle="tooltip" data-placement="right" title="Cía: <?= $poliza['nomcia']; ?> Ramo: <?= $poliza['nramo']; ?>" hidden><?= $poliza['id_poliza']; ?></td>
                                    </tr>

                                <?php } ?>
                                <tr class="no-tocar">
                                    <td colspan="20" style="background-color: #F53333;color: white;font-weight: bold">Total <?= $mes_arr[$i]; ?>: <font size=4 color="aqua"><?= sizeof($polizas); ?></font>
                                    </td>
                                </tr>
                            <?php
                                    $totalpoliza = $totalpoliza + sizeof($poliza);
                                } ?>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th>Mes Desde Seg</th>
                                    <th>N° Póliza</th>
                                    <th>Nombre Titular</th>
                                    <th>F Desde Seguro</th>
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