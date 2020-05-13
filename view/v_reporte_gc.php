<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
require_once '../Controller/Poliza.php';

$distinct_a = $obj->get_a_reporte_gc_h($_GET["id_rep_gc"]);

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
                                <h1 class="font-weight-bold ">Resultado de Búsqueda de GC Pagada por Asesor</h1>
                                <h2>N° GC Generada: <font style="font-weight:bold"><?= $_GET['id_rep_gc']; ?></font>
                                </h2>
                            </div>
                </div>


                <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">

                    <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('tableExcelGC', 'GC Pagada por Asesor')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../assets/img/excel.png" width="60" alt=""></a></center>

                    <div class="table-responsive-xl">
                        <table class="table table-hover table-striped table-bordered" id="tableRepGCView" style="cursor: pointer;">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th>Asesor</th>
                                    <th>N° Póliza</th>
                                    <th>Nombre Titular</th>
                                    <th>Cía</th>
                                    <th>Prima Cobrada</th>
                                    <th>F Prima Cobrada</th>
                                    <th>Comisión Cobrada</th>
                                    <th>% Com</th>
                                    <th style="background-color: #E54848; color: white">GC Pagada</th>
                                    <th>%GC Asesor</th>
                                    <th hidden>id</th>
                                </tr>
                            </thead>

                            <tbody style="cursor: pointer;">
                                <?php
                                for ($a = 0; $a < sizeof($distinct_a); $a++) {
                                    $totalprimacom = 0;
                                    $totalcomision = 0;
                                    $totalgc = 0;

                                    $poliza = $obj->get_reporte_gc_h($_GET['id_rep_gc'], $distinct_a[$a]['cod_vend']);
                                ?>
                                    <tr>
                                        <?php if ($distinct_a[$a]['act'] == 0) { ?>
                                            <td rowspan="<?= sizeof($poliza); ?>" style="background-color: #D9D9D9;font-weight: bold" class="text-danger"><?= $distinct_a[$a]['nombre']; ?></td>
                                        <?php }
                                        if ($distinct_a[$a]['act'] == 1) { ?>
                                            <td rowspan="<?= sizeof($poliza); ?>" style="background-color: #D9D9D9;font-weight: bold" class="text-success"><?= $distinct_a[$a]['nombre']; ?></td>
                                        <?php }

                                        for ($i = 0; $i < sizeof($poliza); $i++) {
                                            $totalprimacom = $totalprimacom + $poliza[$i]['prima_com'];
                                            $totalcomision = $totalcomision + $poliza[$i]['comision'];
                                            $totalgc = $totalgc + (($poliza[$i]['comision'] * $poliza[$i]['per_gc']) / 100);

                                            $totalprimacomT = $totalprimacomT + $poliza[$i]['prima_com'];
                                            $totalcomisionT = $totalcomisionT + $poliza[$i]['comision'];
                                            $totalgcT = $totalgcT + (($poliza[$i]['comision'] * $poliza[$i]['per_gc']) / 100);

                                            $currency = ($poliza[$i]['currency'] == 1) ? "$ " : "Bs ";
                                            $nombretitu = $poliza[$i]['nombre_t'] . " " . $poliza[$i]['apellido_t'];

                                            $no_renov = $obj->verRenov1($poliza[$i]['id_poliza']);
                                        ?>

                                            <?php if ($no_renov[0]['no_renov'] != 1) {
                                                if ($poliza[$i]['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                                    <td style="color: #2B9E34;font-weight: bold"><?= $poliza[$i]['cod_poliza']; ?></td>
                                                <?php } else { ?>
                                                    <td style="color: #E54848;font-weight: bold"><?= $poliza[$i]['cod_poliza']; ?></td>
                                                <?php }
                                            } else { ?>
                                                <td style="color: #4a148c;font-weight: bold"><?= $poliza[$i]['cod_poliza']; ?></td>
                                            <?php } ?>

                                            <?php
                                            $originalFPagoP = $poliza[$i]['f_pago_prima'];
                                            $newFPagoP = date("d/m/Y", strtotime($originalFPagoP));
                                            ?>

                                            <td><?= ($nombretitu); ?></td>
                                            <td nowrap><?= ($poliza[$i]['nomcia']); ?></td>
                                            <td align="right"><?= "$ " . number_format($poliza[$i]['prima_com'], 2); ?></td>
                                            <td nowrap><?= $newFPagoP; ?></td>
                                            <td align="right"><?= "$ " . number_format($poliza[$i]['comision'], 2); ?></td>
                                            <td align="center"><?= number_format(($poliza[$i]['comision'] * 100) / $poliza[$i]['prima_com'], 0) . " %"; ?></td>
                                            <td align="right" style="background-color: #D9D9D9;font-weight: bold"><?= "$ " . number_format(($poliza[$i]['comision'] * $poliza[$i]['per_gc']) / 100, 2); ?></td>
                                            <td nowrap align="center"><?= number_format($poliza[$i]['per_gc'], 0) . " %"; ?></td>
                                            <td hidden><?= $poliza[$i]['id_poliza']; ?></td>
                                    </tr>
                                <?php }
                                        $total_per_com = ($totalcomision * 100) / $totalprimacom;
                                        if (number_format($totalprimacom, 2) == 0.00) {
                                            $totalprimacom = 0;
                                            $total_per_com = 0;
                                        }
                                        if ($totalcomision == 0) {
                                            $totalcomision = 1;
                                        }
                                ?>
                                <tr style="background-color: #F53333;color: white;font-weight: bold" id="no-tocar" class="young-passion-gradient text-white">
                                <tr class="young-passion-gradient text-white" id="no-tocar">
                                    <td colspan="4" style="font-weight: bold">Total de <?= $nombre; ?>: <font size=4 color="aqua"><?= sizeof($poliza); ?></font>
                                    </td>
                                    <td nowrap align="right">
                                        <font size=4 style="font-weight: bold"><?= "$ " . number_format($totalprimacom, 2); ?></font>
                                    </td>
                                    <td></td>
                                    <td nowrap align="right" style="font-weight: bold">
                                        <font size=4><?= "$ " . number_format($totalcomision, 2); ?></font>
                                    </td>

                                    <td nowrap align="center" style="font-weight: bold">
                                        <font size=4><?= number_format($total_per_com, 0) . " %"; ?></font>
                                    </td>

                                    <td nowrap align="right" style="font-weight: bold">
                                        <font size=4><?= "$ " . number_format($totalgc, 2); ?></font>
                                    </td>

                                    <td nowrap align="center" style="font-weight: bold">
                                        <font size=4><?= number_format(($totalgc * 100) / $totalcomision, 0) . " %"; ?></font>
                                    </td>
                                </tr>
                            <?php $totalpoliza = $totalpoliza + sizeof($poliza);
                                } ?>
                            <tr style="background-color: #2FA4E7;color: white;font-weight: bold" class="blue-gradient text-white" id="no-tocar">
                                <td colspan="4" style="font-weight: bold">Total General</td>

                                <td nowrap align="right" style="font-weight: bold">
                                    <font size=4><?= "$ " . number_format($totalprimacomT, 2); ?></font>
                                </td>
                                <td></td>
                                <td nowrap align="right" style="font-weight: bold">
                                    <font size=4><?= "$ " . number_format($totalcomisionT, 2); ?></font>
                                </td>

                                <td nowrap align="center" style="font-weight: bold">
                                    <font size=4><?= number_format(($totalcomisionT * 100) / $totalprimacomT, 2) . " %"; ?></font>
                                </td>

                                <td nowrap align="right" style="font-weight: bold">
                                    <font size=4><?= "$ " . number_format($totalgcT, 2); ?></font>
                                </td>

                                <td nowrap align="center" style="font-weight: bold">
                                    <font size=4><?= number_format(($totalgcT * 100) / $totalcomisionT, 2) . " %"; ?></font>
                                </td>
                            </tr>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th>Asesor</th>
                                    <th>N° Póliza</th>
                                    <th>Nombre Titular</th>
                                    <th>Cía</th>
                                    <th>Prima Cobrada</th>
                                    <th>F Prima Cobrada</th>
                                    <th>Comisión Cobrada</th>
                                    <th>% Com</th>
                                    <th>GC Pagada</th>
                                    <th>%GC Asesor</th>
                                    <th hidden>id</th>
                                </tr>
                            </tfoot>
                        </table>


                        <table class="table table-hover table-striped table-bordered" id="tableExcelGC" width="100%" hidden>
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th>Asesor</th>
                                    <th>Ramo</th>
                                    <th>N° Póliza</th>
                                    <th>F Hasta Seguro</th>
                                    <th>Nombre Titular</th>
                                    <th>Cía</th>
                                    <th>Prima Cobrada</th>
                                    <th>F Prima</th>
                                    <th>Comisión Cobrada</th>
                                    <th>% Com</th>
                                    <th>F Rep Com</th>
                                    <th>GC Pagada</th>
                                    <th>%GC Asesor</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $totalpoliza = 0;
                                $totalprimacomT = 0;
                                $totalcomisionT = 0;
                                $totalgcT = 0;
                                for ($a = 0; $a < sizeof($distinct_a); $a++) {
                                    $totalprimacom = 0;
                                    $totalcomision = 0;
                                    $totalgc = 0;

                                    $poliza = $obj->get_reporte_gc_h($_GET['id_rep_gc'], $distinct_a[$a]['cod_vend']);
                                ?>
                                    <tr>
                                        <?php if ($distinct_a[$a]['act'] == 0) { ?>
                                            <td rowspan="<?= sizeof($poliza); ?>" style="background-color: #D9D9D9;font-weight: bold" class="text-danger"><?= $distinct_a[$a]['nombre']; ?></td>
                                        <?php }
                                        if ($distinct_a[$a]['act'] == 1) { ?>
                                            <td rowspan="<?= sizeof($poliza); ?>" style="background-color: #D9D9D9;font-weight: bold" class="text-success"><?= $distinct_a[$a]['nombre']; ?></td>
                                            <?php }

                                        for ($i = 0; $i < sizeof($poliza); $i++) {
                                            $totalprimacom = $totalprimacom + $poliza[$i]['prima_com'];
                                            $totalcomision = $totalcomision + $poliza[$i]['comision'];
                                            $totalgc = $totalgc + (($poliza[$i]['comision'] * $poliza[$i]['per_gc']) / 100);

                                            $totalprimacomT = $totalprimacomT + $poliza[$i]['prima_com'];
                                            $totalcomisionT = $totalcomisionT + $poliza[$i]['comision'];
                                            $totalgcT = $totalgcT + (($poliza[$i]['comision'] * $poliza[$i]['per_gc']) / 100);

                                            $newHasta = date("d/m/Y", strtotime($poliza[$i]['f_hastapoliza']));
                                            $currency = ($poliza[$i]['currency'] == 1) ? "$ " : "Bs ";
                                            $nombretitu = $poliza[$i]['nombre_t'] . " " . $poliza[$i]['apellido_t'];

                                            if ($poliza[$i]['f_hastapoliza'] >= date("Y-m-d")) {
                                            ?>
                                                <td><?= ($poliza[$i]['nramo']); ?></td>
                                                <td style="color: #2B9E34"><?= $poliza[$i]['cod_poliza']; ?></td>
                                            <?php
                                            } else {
                                            ?>
                                                <td><?= ($poliza[$i]['nramo']); ?></td>
                                                <td style="color: #E54848"><?= $poliza[$i]['cod_poliza']; ?></td>
                                            <?php
                                            }

                                            $newFPagoP = date("d/m/Y", strtotime($poliza[$i]['f_pago_prima']));
                                            $newFRep = date("d/m/Y", strtotime($poliza[$i]['f_hasta_rep']));
                                            ?>

                                            <td><?= $newHasta; ?></td>
                                            <td><?= ($nombretitu); ?></td>
                                            <td nowrap><?= ($poliza[$i]['nomcia']); ?></td>
                                            <td align="right"><?= "$ " . number_format($poliza[$i]['prima_com'], 2); ?></td>
                                            <td nowrap><?= $newFPagoP; ?></td>
                                            <td align="right"><?= "$ " . number_format($poliza[$i]['comision'], 2); ?></td>
                                            <td align="center"><?= number_format(($poliza[$i]['comision'] * 100) / $poliza[$i]['prima_com'], 0) . " %"; ?></td>
                                            <td nowrap><?= $newFRep; ?></td>
                                            <td align="right" class="sunny-morning-gradient text-white font-weight-bold"><?= "$ " . number_format(($poliza[$i]['comision'] * $poliza[$i]['per_gc']) / 100, 2); ?></td>
                                            <td nowrap align="center"><?= number_format($poliza[$i]['per_gc'], 0) . " %"; ?></td>
                                    </tr>
                                <?php }
                                        $total_per_com = ($totalcomision * 100) / $totalprimacom;
                                        if (number_format($totalprimacom, 2) == 0.00) {
                                            $totalprimacom = 0;
                                            $total_per_com = 0;
                                        }
                                        if ($totalcomision == 0) {
                                            $totalcomision = 1;
                                        }
                                ?>
                                <tr class="no-tocar young-passion-gradient text-white">
                                    <td colspan="6" style="font-weight: bold">Total de <?= $nombre; ?>: <font size=4 color="aqua"><?= sizeof($poliza); ?></font>
                                    </td>
                                    <td nowrap align="right" style="font-weight: bold">
                                        <font size=4><?= "$ " . number_format($totalprimacom, 2); ?></font>
                                    </td>
                                    <td style="font-weight: bold"></td>
                                    <td nowrap align="right" style="font-weight: bold">
                                        <font size=4><?= "$ " . number_format($totalcomision, 2); ?></font>
                                    </td>

                                    <td nowrap align="center" style="font-weight: bold">
                                        <font size=4><?= number_format($total_per_com, 0) . " %"; ?></font>
                                    </td>
                                    <td style="font-weight: bold"></td>
                                    <td nowrap align="right" style="font-weight: bold">
                                        <font size=4><?= "$ " . number_format($totalgc, 2); ?></font>
                                    </td>

                                    <td nowrap align="center" style="font-weight: bold">
                                        <font size=4><?= number_format(($totalgc * 100) / $totalcomision, 0) . " %"; ?></font>
                                    </td>
                                </tr>
                            <?php $totalpoliza = $totalpoliza + sizeof($poliza);
                                } ?>
                            <tr class="no-tocar blue-gradient text-white">
                                <td style="font-weight: bold" colspan="6">Total General</td>

                                <td nowrap align="right" style="font-weight: bold">
                                    <font size=4><?= "$ " . number_format($totalprimacomT, 2); ?></font>
                                </td>
                                <td></td>
                                <td nowrap align="right" style="font-weight: bold">
                                    <font size=4><?= "$ " . number_format($totalcomisionT, 2); ?></font>
                                </td>

                                <td nowrap align="center" style="font-weight: bold">
                                    <font size=4><?= number_format(($totalcomisionT * 100) / $totalprimacomT, 2) . " %"; ?></font>
                                </td>
                                <td style="font-weight: bold"></td>
                                <td nowrap align="right" style="font-weight: bold">
                                    <font size=4><?= "$ " . number_format($totalgcT, 2); ?></font>
                                </td>

                                <td nowrap align="center" style="font-weight: bold">
                                    <font size=4><?= number_format(($totalgcT * 100) / $totalcomisionT, 2) . " %"; ?></font>
                                </td>
                            </tr>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th>Asesor</th>
                                    <th>Ramo</th>
                                    <th>N° Póliza</th>
                                    <th>F Hasta Seguro</th>
                                    <th>Nombre Titular</th>
                                    <th>Cía</th>
                                    <th>Prima Cobrada</th>
                                    <th>F Prima</th>
                                    <th>Comisión Cobrada</th>
                                    <th>% Com</th>
                                    <th>F Rep Com</th>
                                    <th>GC Pagada</th>
                                    <th>%GC Asesor</th>
                                </tr>
                            </tfoot>
                        </table>

                        <h1 class="font-weight-bold text-center">Total de Prima Cobrada</h1>
                        <h1 class="font-weight-bold text-center text-danger">$ <?php echo number_format($totalprimacomT, 2); ?></h1>

                        <h1 class="font-weight-bold text-center">Total de Pólizas</h1>
                        <h1 class="font-weight-bold text-center text-danger"><?php echo $totalpoliza; ?></h1>
                    </div>

                </div>

                <!--   TABLA PARA USUARIOS QUE SON ASESORES   -->
            <?php }
            if ($_SESSION['id_permiso'] == 3) {
            } ?>


        </div>





        <?php require_once dirname(__DIR__) . '\layout\footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . '\layout\footer.php'; ?>

        <script src="../assets/view/b_poliza.js"></script>
</body>

</html>