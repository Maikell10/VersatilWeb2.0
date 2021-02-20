<?php require_once '../../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'poliza_uv';

require_once '../../../Controller/Poliza.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . DS . '..' . DS . '..' . DS . 'layout' . DS . 'header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . DS . '..' . DS . '..' . DS . 'layout' . DS . 'navigation.php'; ?>
    <br><br><br><br><br><br>

    <div>
        <div class="card">

            <div id="carga" class="d-flex justify-content-center align-items-center">
                <div class="spinner-grow text-info" style="width: 7rem; height: 7rem;"></div>
            </div>

            <?php if ($_SESSION['id_permiso'] != 3) { ?>
                <div class="card-header p-5 animated bounceInDown" id="headerload" hidden="true">
                    <div class="ml-5 mr-5 text-center">
                        <h1 class="font-weight-bold ">Pólizas de la Selección</h1>
                        <h3>Pólizas con Prima Cobrada del Mes: <?= $mes_arr[$_GET['mes']-1];?></h3>
                        <h3>Año: <?= $_GET['anio'];?></h3>
                    </div>
                </div>
                <hr />


                <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">



                    <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('UtilGrafPolE', 'Listado de Pólizas')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../../../assets/img/excel.png" width="60" alt=""></a></center>

                    <div class="table-responsive-xl">
                        <table class="table table-hover table-striped table-bordered" id="UtilGrafPol" width="100%">
                            <thead class="blue-gradient text-white text-center">
                                <tr>
                                    <th hidden>f_poliza</th>
                                    <th hidden>id</th>
                                    <th style="width: 5px">-</th>
                                    <th>N° Póliza</th>
                                    <th>Nombre Asesor</th>
                                    <th>Cía</th>
                                    <th>F Desde Seguro</th>
                                    <th>F Hasta Seguro</th>
                                    <th>Prima Suscrita</th>
                                    <th>F Pago Prima</th>
                                    <th>Prima Cobrada</th>
                                    <th>Nombre Titular</th>
                                    <th>PDF</th>
                                </tr>
                            </thead>

                            <tbody>
                            <?php
                            $totalprima = 0;
                            $primacT = 0;
                            for ($i=0; $i < sizeof($polizasC); $i++) { 
                                $polizas = $obj->get_poliza_total_by_filtro_utilidad_v($polizasC[$i]['id_poliza']);

                                $totalCantPV = 0;
                                foreach ($polizas as $poliza) {
                                    $currency = ($poliza['currency'] == 1) ? "$ " : "Bs ";

                                    $totalsuma = $totalsuma + $poliza['sumaasegurada'];
                                    $totalprima = $totalprima + $poliza['prima'];

                                    $newDesde = date("Y/m/d", strtotime($poliza['f_desdepoliza']));
                                    $newHasta = date("Y/m/d", strtotime($poliza['f_hastapoliza']));

                                    $nombre = $poliza['nombre_t'] . ' ' . $poliza['apellido_t'];

                                    $primac = $obj->obetnComisionesUtilidadG($poliza['id_poliza'],$mes,$anio);
                                    $primacT = $primacT + $primac[0]['SUM(prima_com)'];

                                    $ppendiente = $poliza['prima'] - $primac[0]['SUM(prima_com)'];
                                    $ppendiente = number_format($ppendiente, 2);
                                    if ($ppendiente >= -0.10 && $ppendiente <= 0.10) {
                                        $ppendiente = 0;
                                    }

                                    $no_renov = $obj->verRenov1($poliza['id_poliza']);

                                    $f_pago_prima = $obj->get_f_pago_prima_by_filtro_utilidad_v($poliza['id_poliza']);
                                    $newFPagoP = date("Y/m/d", strtotime($f_pago_prima[0]['f_pago_prima']));
                            ?>

                                    <tr style="cursor: pointer;">
                                        <td hidden><?= $poliza['f_poliza']; ?></td>
                                        <td hidden><?= $poliza['id_poliza']; ?></td>

                                        <?php if ($poliza['id_tpoliza'] == 1) { ?>
                                            <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Nueva">N</td>
                                        <?php } if ($poliza['id_tpoliza'] == 2) { ?>
                                            <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Renovación">R</td>
                                        <?php } if ($poliza['id_tpoliza'] == 3) { ?>
                                            <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Traspaso de Cartera">T</td>
                                        <?php } ?>

                                        <?php if ($no_renov[0]['no_renov'] != 1) {
                                            if ($poliza['f_hastapoliza'] >= date("Y-m-d")) {
                                                $primaSV = $primaSV + $poliza['prima'];
                                                $primaCV = $primaCV + $primac[0]['SUM(prima_com)'];
                                                $totalCantPV = $totalCantPV +1;
                                        ?>
                                                <td style="color: #2B9E34;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                            <?php } else { ?>
                                                <td style="color: #E54848;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                            <?php }
                                        } else { ?>
                                            <td style="color: #4a148c;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                        <?php } ?>

                                        <td><?= $poliza['nombre'] . ' (' . $poliza['codvend'] . ')'; ?></td>
                                        <td><?= $poliza['nomcia']; ?></td>
                                        <td><?= $newDesde; ?></td>
                                        <td><?= $newHasta; ?></td>
                                        <td class="text-right"><?= $currency . number_format($poliza['prima'], 2); ?></td>

                                        <td><?= $newFPagoP; ?></td>

                                        <td style="text-align: right"><?= $currency . number_format($primac[0]['SUM(prima_com)'], 2); ?></td>


                                        <td><?= ($nombre); ?></td>

                                        <?php if ($poliza['pdf'] == 1) { ?>
                                            <td class="text-center"><a href="../../download.php?id_poliza=<?= $poliza['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../../../assets/img/pdf-logo.png" width="25" id="pdf"></a></td>
                                            <?php } else {
                                            if ($poliza['nramo'] == 'Vida') {
                                                $vRenov = $obj->verRenov3($poliza['id_poliza']);
                                                if ($vRenov != 0) {
                                                    if ($vRenov[0]['pdf'] != 0) {
                                                        $poliza_pdf_vida = $obj->get_pdf_vida_id($vRenov[0]['id_poliza']); ?>
                                                        <td class="text-center"><a href="../../download.php?id_poliza=<?= $poliza_pdf_vida[0]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../../../assets/img/pdf-logo.png" width="25" id="pdf"></a></td>
                                                    <?php } else { 
                                                        $poliza_pdf_vida = $obj->get_pdf_vida($vRenov[0]['cod_poliza'], $poliza['id_cia'], $poliza['f_hastapoliza']);
                                                        if ($poliza_pdf_vida[0]['pdf'] == 1) {  ?>
                                                        <td class="text-center"><a href="../../download.php?id_poliza=<?= $poliza_pdf_vida[0]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../../../assets/img/pdf-logo.png" width="25" id="pdf"></a></td>
                                                        <?php } else { ?>
                                                            <td></td>
                                                    <?php } }
                                                } else {
                                                    $poliza_pdf_vida = $obj->get_pdf_vida($poliza['cod_poliza'], $poliza['id_cia'], $poliza['f_hastapoliza']);
                                                    if ($poliza_pdf_vida[0]['pdf'] == 1) { ?>
                                                        <td class="text-center"><a href="../../download.php?id_poliza=<?= $poliza_pdf_vida[0]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../../../assets/img/pdf-logo.png" width="25" id="pdf"></a></td>
                                                    <?php } else { ?>
                                                        <td></td>
                                                <?php }
                                                }
                                            } else { ?>
                                                <td></td>
                                            <?php } ?>
                                        <?php } ?>
                                        
                                    </tr>

                            <?php } } ?>
                            </tbody>

                            <tfoot class="text-center">
                                <tr>
                                    <th hidden>f_poliza</th>
                                    <th hidden>id</th>
                                    <th>-</th>
                                    <th>N° Póliza</th>
                                    <th>Nombre Asesor</th>
                                    <th>Cía</th>
                                    <th>F Desde Seguro</th>
                                    <th>F Hasta Seguro</th>
                                    <th style="font-weight: bold" class="text-right">Prima Suscrita $<?= number_format($totalprima, 2); ?></th>
                                    <th>F Pago Prima</th>
                                    <th style="font-weight: bold" class="text-right">Prima Cobrada $<?= number_format($primacT,2);?></th>
                                    <th>Nombre Titular</th>
                                    <th>PDF</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="table-responsive-xl" hidden>
                        <table class="table table-hover table-striped table-bordered" id="UtilGrafPolE" width="100%">
                            <thead class="blue-gradient text-white text-center">
                                <tr>
                                    <th class="text-center" style="background-color: #4285F4; color: white"></th>
                                    <th class="text-center" style="background-color: #4285F4; color: white">N° Póliza</th>
                                    <th class="text-center" style="background-color: #4285F4; color: white">Nombre Asesor</th>
                                    <th class="text-center" style="background-color: #4285F4; color: white">Cía</th>
                                    <th class="text-center" style="background-color: #4285F4; color: white">F Desde Seguro</th>
                                    <th class="text-center" style="background-color: #4285F4; color: white">F Hasta Seguro</th>
                                    <th class="text-center" style="background-color: #4285F4; color: white">Prima Suscrita</th>
                                    <th class="text-center" style="background-color: #4285F4; color: white">F Pago Prima</th>
                                    <th class="text-center" style="background-color: #4285F4; color: white">Prima Cobrada</th>
                                    <th class="text-center" style="background-color: #4285F4; color: white">Nombre Titular</th>
                                </tr>
                            </thead>

                            <tbody>
                            <?php
                            $totalprima = 0;
                            $primacT = 0;
                            for ($i=0; $i < sizeof($polizasC); $i++) { 
                                $polizas = $obj->get_poliza_total_by_filtro_utilidad_v($polizasC[$i]['id_poliza']);

                                $totalCantPV = 0;
                                foreach ($polizas as $poliza) {
                                    $currency = ($poliza['currency'] == 1) ? "$ " : "Bs ";

                                    $totalsuma = $totalsuma + $poliza['sumaasegurada'];
                                    $totalprima = $totalprima + $poliza['prima'];

                                    $newDesde = date("Y/m/d", strtotime($poliza['f_desdepoliza']));
                                    $newHasta = date("Y/m/d", strtotime($poliza['f_hastapoliza']));

                                    $nombre = $poliza['nombre_t'] . ' ' . $poliza['apellido_t'];

                                    $primac = $obj->obetnComisionesUtilidadG($poliza['id_poliza'],$mes,$anio);
                                    $primacT = $primacT + $primac[0]['SUM(prima_com)'];

                                    $ppendiente = $poliza['prima'] - $primac[0]['SUM(prima_com)'];
                                    $ppendiente = number_format($ppendiente, 2);
                                    if ($ppendiente >= -0.10 && $ppendiente <= 0.10) {
                                        $ppendiente = 0;
                                    }

                                    $no_renov = $obj->verRenov1($poliza['id_poliza']);

                                    $f_pago_prima = $obj->get_f_pago_prima_by_filtro_utilidad_v($poliza['id_poliza']);
                                    $newFPagoP = date("Y/m/d", strtotime($f_pago_prima[0]['f_pago_prima']));
                            ?>

                                    <tr style="cursor: pointer;">

                                        <?php if ($poliza['id_tpoliza'] == 1) { ?>
                                            <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Nueva">Nueva</td>
                                        <?php } if ($poliza['id_tpoliza'] == 2) { ?>
                                            <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Renovación">Renovacion</td>
                                        <?php } if ($poliza['id_tpoliza'] == 3) { ?>
                                            <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Traspaso de Cartera">Traspaso de Cartera</td>
                                        <?php } ?>

                                        <?php if ($no_renov[0]['no_renov'] != 1) {
                                            if ($poliza['f_hastapoliza'] >= date("Y-m-d")) {
                                                $primaSV = $primaSV + $poliza['prima'];
                                                $primaCV = $primaCV + $primac[0]['SUM(prima_com)'];
                                                $totalCantPV = $totalCantPV +1;
                                        ?>
                                                <td style="color: #2B9E34;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                            <?php } else { ?>
                                                <td style="color: #E54848;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                            <?php }
                                        } else { ?>
                                            <td style="color: #4a148c;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                        <?php } ?>

                                        <td><?= $poliza['nombre'] . ' (' . $poliza['codvend'] . ')'; ?></td>
                                        <td><?= $poliza['nomcia']; ?></td>
                                        <td><?= $newDesde; ?></td>
                                        <td><?= $newHasta; ?></td>
                                        <td style="text-align: right"><?= $currency . number_format($poliza['prima'], 2); ?></td>

                                        <td><?= $newFPagoP; ?></td>

                                        <td style="text-align: right"><?= $currency . number_format($primac[0]['SUM(prima_com)'], 2); ?></td>

                                        <td><?= ($nombre); ?></td>
                                        
                                    </tr>

                            <?php } } ?>
                            </tbody>

                            <tfoot class="text-center">
                                <tr>
                                    <th>-</th>
                                    <th>N° Póliza</th>
                                    <th>Nombre Asesor</th>
                                    <th>Cía</th>
                                    <th>F Desde Seguro</th>
                                    <th>F Hasta Seguro</th>
                                    <th style="font-weight: bold" class="text-right">Prima Suscrita $<?= number_format($totalprima, 2); ?></th>
                                    <th>F Pago Prima</th>
                                    <th style="font-weight: bold" class="text-right">Prima Cobrada $<?= number_format($primacT,2);?></th>
                                    <th>Nombre Titular</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>


                    <p class="h1 text-center">Total de Prima Suscrita</p>
                    <p class="h1 text-center text-danger">$ <?php echo number_format($totalprima, 2); ?></p>

                    <p class="h1 text-center">Total de Pólizas</p>
                    <p class="h1 text-center text-danger"><?php echo sizeof($polizasC); ?></p>
                </div>


            <?php } ?>
        </div>





        <?php require_once dirname(__DIR__) . DS . '..' . DS . '..' . DS . 'layout' . DS . 'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . DS . '..' . DS . '..' . DS . 'layout' . DS . 'footer.php'; ?>

        <script src="../../../assets/view/b_poliza.js"></script>

</body>

</html>