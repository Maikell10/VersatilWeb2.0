<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

require_once '../Controller/Poliza.php';

$distinct_a = $obj->get_a_reporte_gc_h($_GET["id_rep_gc"]);

$distinct_total_p = $obj->get_distinct_reporte_gc_h($_GET["id_rep_gc"]);
$contDistinctTP = ($distinct_total_p == 0) ? 0 : count($distinct_total_p);

$dateGenerada = date("d/m/Y", strtotime($distinct_a[0]['f_hoy_h']));
$dateReporte = date("d/m/Y", strtotime($distinct_a[0]['f_desde_h'])) . ' a ' . date("d/m/Y", strtotime($distinct_a[0]['f_hasta_h']));
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

            <?php if ($_SESSION['id_permiso'] != 3) { ?>
                <div class="card-header p-5 animated bounceInDown" id="headerload" hidden="true">
                    <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                        <- Regresar</a> <br><br>
                            <div class="ml-5 mr-5">
                                <h1 class="font-weight-bold ">Resultado de Búsqueda de GC Pagada por Asesor</h1>
                                <h2>N° GC Generada: <font style="font-weight:bold"><?= $_GET['id_rep_gc']; ?></font>
                                </h2>
                                <h2>Fecha de la Generación de la GC: <font style="font-weight:bold"><?= $dateGenerada; ?></font>
                                </h2>
                                <h3>Fecha Reporte GC: <font style="font-weight:bold"><?= $dateReporte; ?></font>
                                </h3>
                            </div>
                </div>


                <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">

                    <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('tableExcelGC', 'GC Pagada por Asesor')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../assets/img/excel.png" width="60" alt=""></a></center>

                    <center><a href="v_reporte_gc_copia.php?id_rep_gc=<?= $_GET['id_rep_gc']; ?>" class="btn blue-gradient btn-lg" data-toggle="tooltip" data-placement="right" title="Ver Detalles para la Búsqueda Actual" style="color:white" target="_blank">Detalle</a></center>

                    <div class="table-responsive-xl">
                        <table class="table table-hover table-striped table-bordered" id="tableRepGCView1" style="cursor: pointer;">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th>Asesor</th>
                                    <th>Mes Pago GC</th>
                                    <th>F Pago GC</th>
                                    <th>Prima Cobrada</th>
                                    <th>Comisión Cobrada</th>
                                    <th style="background-color: #E54848; color: white">GC Pagada</th>
                                    <th>Referencia</th>
                                    <th>F Transf</th>
                                    <th>Monto Pagado</th>
                                    <th>Acciones</th>
                                    <th hidden>id</th>
                                    <th hidden>cod_vend</th>
                                    <th hidden>f_pago_gc</th>
                                </tr>
                            </thead>

                            <tbody style="cursor: pointer;">
                                <?php
                                $cantdistinct_a = ($distinct_a == null) ? 0 : sizeof($distinct_a);
                                $totalmontop = 0;
                                for ($a = 0; $a < $cantdistinct_a; $a++) {
                                    $distinct_fpgc = $obj->get_reporte_gc_h_distinct_fp($_GET['id_rep_gc'], $distinct_a[$a]['cod_vend']);
                                ?>
                                    <tr>
                                        <?php if ($distinct_a[$a]['act'] == 0) { ?>
                                            <td rowspan="<?= sizeof($distinct_fpgc); ?>" style="background-color: #D9D9D9;font-weight: bold" class="text-danger align-middle"><?= $distinct_a[$a]['nombre'] . ' (' . $distinct_a[$a]['cod_vend'] . ')'; ?></td>
                                        <?php }
                                        if ($distinct_a[$a]['act'] == 1) { ?>
                                            <td rowspan="<?= sizeof($distinct_fpgc); ?>" style="background-color: #D9D9D9;font-weight: bold" class="text-success align-middle"><?= $distinct_a[$a]['nombre'] . ' (' . $distinct_a[$a]['cod_vend'] . ')'; ?></td>
                                        <?php }
                                        
                                        for ($b = 0; $b < sizeof($distinct_fpgc); $b++) {
                                            $totalprimacom = 0;
                                            $totalcomision = 0;
                                            $totalgc = 0;

                                            $poliza = $obj->get_reporte_gc_h_fp($_GET['id_rep_gc'], $distinct_a[$a]['cod_vend'], $distinct_fpgc[$b]['f_pago_gc']);

                                            for ($i = 0; $i < sizeof($poliza); $i++) {
                                                $totalprimacom = $totalprimacom + $poliza[$i]['prima_com'];
                                                $totalcomision = $totalcomision + $poliza[$i]['comision'];
                                                $totalgc = $totalgc + (($poliza[$i]['comision'] * $poliza[$i]['per_gc']) / 100);

                                                $totalprimacomT = $totalprimacomT + $poliza[$i]['prima_com'];
                                                $totalcomisionT = $totalcomisionT + $poliza[$i]['comision'];
                                                $totalgcT = $totalgcT + (($poliza[$i]['comision'] * $poliza[$i]['per_gc']) / 100);

                                                $currency = ($poliza[$i]['currency'] == 1) ? "$ " : "Bs ";
                                                $nombretitu = $poliza[$i]['nombre_t'] . " " . $poliza[$i]['apellido_t'];

                                                $newDesde = date("d/m/Y", strtotime($poliza[$i]['f_desdepoliza']));
                                                $newHasta = date("d/m/Y", strtotime($poliza[$i]['f_hastapoliza']));
                                                $tooltip = 'Fecha Desde: ' . $newDesde . ' | Fecha Hasta: ' . $newHasta;

                                                $no_renov = $obj->verRenov1($poliza[$i]['id_poliza']);
                                            }

                                            $originalFPagoGC = $distinct_fpgc[$b]['f_pago_gc'];
                                            $newFPagoGC = date("d/m/Y", strtotime($originalFPagoGC));

                                            $originalFPagoGCMes = $distinct_fpgc[$b]['f_pago_gc'];
                                            $newFPagoGCMes = date("m", strtotime($originalFPagoGCMes));

                                            $pago_gc_h = $obj->get_gc_h_pago($_GET['id_rep_gc'], $distinct_a[$a]['cod_vend'], $distinct_fpgc[$b]['f_pago_gc']);

                                            $originalFPagoGCH = $pago_gc_h[0]['ftransf'];
                                            if ($originalFPagoGCH != '0000-00-00' && $pago_gc_h != 0) {
                                                $newFPagoGCH = date("d/m/Y", strtotime($originalFPagoGCH));
                                            } else {
                                                $newFPagoGCH = '';
                                            }

                                            $totalmontop = $totalmontop + $pago_gc_h[0]['montop'];
                                        ?>

                                            <td nowrap><?= $mes_arr[$newFPagoGCMes - 1]; ?></td>
                                            <td nowrap><?= $newFPagoGC; ?></td>

                                            <td align="right"><?= "$ " . number_format($totalprimacom, 2); ?></td>
                                            <td align="right"><?= "$ " . number_format($totalcomision, 2); ?></td>


                                            <?php if ($totalgc < 0) { ?>
                                                <td style="color: #E54848;text-align: right;background-color: #D9D9D9;font-weight: bold"><?= "$ " . number_format($totalgc, 2); ?></td>
                                            <?php } else { ?>
                                                <td style="text-align: right;background-color: #D9D9D9;font-weight: bold"><?= "$ " . number_format($totalgc, 2); ?></td>
                                            <?php } ?>

                                            <td><?= $pago_gc_h[0]['ref']; ?></td>
                                            <td><?= $newFPagoGCH; ?></td>
                                            <td align="right"><?= '$' . number_format($pago_gc_h[0]['montop'], 2); ?></td>

                                            <?php if ($_SESSION['id_permiso'] == 1) { ?>
                                                <td style="text-align: center;">
                                                    <a onclick="crearPagoA(<?= $_GET['id_rep_gc']; ?>,'<?= $distinct_a[$a]['cod_vend']; ?>','<?= $originalFPagoGC;?>')" data-toggle="tooltip" data-placement="top" title="Añadir Pago" class="btn blue-gradient btn-rounded btn-sm"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>
                                                </td>
                                            <?php } ?>



                                            <td hidden><?= $_GET['id_rep_gc']; ?></td>
                                            <td hidden><?= $distinct_a[$a]['cod_vend']; ?></td>
                                            <td hidden><?= $originalFPagoGC; ?></td>
                                    </tr>
                            <?php }
                                    } ?>
                            <tr style="background-color: #2FA4E7;color: white;font-weight: bold" class="blue-gradient text-white" id="no-tocar">
                                <td colspan="3" style="font-weight: bold">Total General</td>

                                <td nowrap align="right" style="font-weight: bold">
                                    <font size=4><?= "$ " . number_format($totalprimacomT, 2); ?></font>
                                </td>

                                <td nowrap align="right" style="font-weight: bold">
                                    <font size=4><?= "$ " . number_format($totalcomisionT, 2); ?></font>
                                </td>

                                <td nowrap align="right" style="font-weight: bold">
                                    <font size=4><?= "$ " . number_format($totalgcT, 2); ?></font>
                                </td>

                                <td colspan="2"></td>

                                <td align="right" style="font-weight: bold">
                                    <font size=4><?= '$ ' . number_format($totalmontop,2); ?></font>
                                </td>

                                <td></td>

                            </tr>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th>Asesor</th>
                                    <th>Mes Pago GC</th>
                                    <th>F Pago GC</th>
                                    <th>Prima Cobrada</th>
                                    <th>Comisión Cobrada</th>
                                    <th>GC Pagada</th>
                                    <th>Referencia</th>
                                    <th>F Transf</th>
                                    <th>Monto Pagado</th>
                                    <th>Acciones</th>
                                    <th hidden>id</th>
                                    <th hidden>cod_vend</th>
                                    <th hidden>f_pago_gc</th>
                                </tr>
                            </tfoot>
                        </table>


                        <table class="table table-hover table-striped table-bordered" id="tableExcelGC" width="100%" hidden>
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th style="background-color: #4285F4; color: white">Asesor</th>
                                    <th style="background-color: #4285F4; color: white">Ramo</th>
                                    <th style="background-color: #4285F4; color: white">-</th>
                                    <th style="background-color: #4285F4; color: white">N° Póliza</th>
                                    <th style="background-color: #4285F4; color: white">F Hasta Seguro</th>
                                    <th style="background-color: #4285F4; color: white">Nombre Titular</th>
                                    <th style="background-color: #4285F4; color: white">Cía</th>
                                    <th style="background-color: #4285F4; color: white">Prima Cobrada</th>
                                    <th style="background-color: #4285F4; color: white">F Prima</th>
                                    <th style="background-color: #4285F4; color: white">Comisión Cobrada</th>
                                    <th style="background-color: #4285F4; color: white">% Com</th>
                                    <th style="background-color: #4285F4; color: white">F Rep Com</th>
                                    <th style="background-color: #E54848; color: white">GC Pagada</th>
                                    <th style="background-color: #4285F4; color: white">%GC Asesor</th>
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



                                            $newFPagoP = date("d/m/Y", strtotime($poliza[$i]['f_pago_prima']));
                                            $newFRep = date("d/m/Y", strtotime($poliza[$i]['f_hasta_rep']));

                                            $no_renov = $obj->verRenov1($poliza[$i]['id_poliza']);
                                        ?>

                                            <td><?= ($poliza[$i]['nramo']); ?></td>

                                            <?php if ($poliza[$i]['id_tpoliza'] == 1) { ?>
                                                <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Nueva">N<span hidden>ueva</span></td>
                                            <?php }
                                            if ($poliza[$i]['id_tpoliza'] == 2) { ?>
                                                <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Renovación">R<span hidden>enovacion</span></td>
                                            <?php }
                                            if ($poliza[$i]['id_tpoliza'] == 3) { ?>
                                                <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Traspaso de Cartera">T<span hidden>raspaso de Cartera</span></td>
                                            <?php } ?>

                                            <?php if ($no_renov[0]['no_renov'] != 1) {
                                                if ($poliza[$i]['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                                    <td style="color: #2B9E34;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $poliza[$i]['cod_poliza']; ?></td>
                                                <?php } else { ?>
                                                    <td style="color: #E54848;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $poliza[$i]['cod_poliza']; ?></td>
                                                <?php }
                                            } else { ?>
                                                <td style="color: #4a148c;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $poliza[$i]['cod_poliza']; ?></td>
                                            <?php } ?>


                                            <td><?= $newHasta; ?></td>
                                            <td><?= ($nombretitu); ?></td>
                                            <td nowrap><?= ($poliza[$i]['nomcia']); ?></td>
                                            <td align="right"><?= "$ " . number_format($poliza[$i]['prima_com'], 2); ?></td>
                                            <td nowrap><?= $newFPagoP; ?></td>
                                            <td align="right"><?= "$ " . number_format($poliza[$i]['comision'], 2); ?></td>
                                            <td align="center"><?= number_format(($poliza[$i]['comision'] * 100) / $poliza[$i]['prima_com'], 0) . " %"; ?></td>
                                            <td nowrap><?= $newFRep; ?></td>
                                            <?php if ((($poliza[$i]['comision'] * $poliza[$i]['per_gc']) / 100) < 0) { ?>
                                                <td style="color: #E54848;text-align: right;background-color: #D9D9D9;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= "$ " . number_format(($poliza[$i]['comision'] * $poliza[$i]['per_gc']) / 100, 2); ?></td>
                                            <?php } else { ?>
                                                <td style="text-align: right;background-color: #D9D9D9;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= "$ " . number_format(($poliza[$i]['comision'] * $poliza[$i]['per_gc']) / 100, 2); ?></td>

                                            <?php } ?>

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
                                <tr>
                                    <td colspan="7" style="background-color: #F53333;color: white;font-weight: bold">Total de <?= $nombre; ?>: <font size=4 color="aqua"><?= sizeof($poliza); ?></font>
                                    </td>
                                    <td nowrap align="right" style="background-color: #F53333;color: white;font-weight: bold">
                                        <font size=4><?= "$ " . number_format($totalprimacom, 2); ?></font>
                                    </td>
                                    <td style="background-color: #F53333;color: white;font-weight: bold"></td>
                                    <td nowrap align="right" style="background-color: #F53333;color: white;font-weight: bold">
                                        <font size=4><?= "$ " . number_format($totalcomision, 2); ?></font>
                                    </td>

                                    <td nowrap align="center" style="background-color: #F53333;color: white;font-weight: bold">
                                        <font size=4><?= number_format($total_per_com, 0) . " %"; ?></font>
                                    </td>
                                    <td style="background-color: #F53333;color: white;font-weight: bold"></td>
                                    <td nowrap align="right" style="background-color: #F53333;color: white;font-weight: bold">
                                        <font size=4><?= "$ " . number_format($totalgc, 2); ?></font>
                                    </td>

                                    <td nowrap align="center" style="background-color: #F53333;color: white;font-weight: bold">
                                        <font size=4><?= number_format(($totalgc * 100) / $totalcomision, 0) . " %"; ?></font>
                                    </td>
                                </tr>
                            <?php $totalpoliza = $totalpoliza + sizeof($poliza);
                                } ?>
                            <tr class="no-tocar">
                                <td style="background-color: #4285F4; color: white;font-weight: bold" colspan="7">Total General</td>

                                <td nowrap align="right" style="background-color: #4285F4; color: white;font-weight: bold">
                                    <font size=4><?= "$ " . number_format($totalprimacomT, 2); ?></font>
                                </td>
                                <td style="background-color: #4285F4"></td>
                                <td nowrap align="right" style="background-color: #4285F4; color: white;font-weight: bold">
                                    <font size=4><?= "$ " . number_format($totalcomisionT, 2); ?></font>
                                </td>

                                <td nowrap align="center" style="background-color: #4285F4; color: white;font-weight: bold">
                                    <font size=4><?= number_format(($totalcomisionT * 100) / $totalprimacomT, 2) . " %"; ?></font>
                                </td>
                                <td style="background-color: #4285F4; color: white;font-weight: bold"></td>
                                <td nowrap align="right" style="background-color: #4285F4; color: white;font-weight: bold">
                                    <font size=4><?= "$ " . number_format($totalgcT, 2); ?></font>
                                </td>

                                <td nowrap align="center" style="background-color: #4285F4; color: white;font-weight: bold">
                                    <font size=4><?= number_format(($totalgcT * 100) / $totalcomisionT, 2) . " %"; ?></font>
                                </td>
                            </tr>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th>Asesor</th>
                                    <th>Ramo</th>
                                    <th>-</th>
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
                        <!-- <h1 class="font-weight-bold text-center text-danger"><?php echo $totalpoliza; ?></h1> -->
                        <h1 class="font-weight-bold text-center text-danger"><?php echo $contDistinctTP; ?></h1>

                    </div>

                </div>

                <!--   TABLA PARA USUARIOS QUE SON ASESORES   -->
            <?php }
            if ($_SESSION['id_permiso'] == 3) {
            } ?>


        </div>





        <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'footer.php'; ?>

        <!-- Modal CONCILIACION -->
        <div class="modal fade" id="agregarpagoA" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Añadir Pago GC al Asesor: <font id="asesor_modal" class="text-danger font-weight-bold"></font></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="frmnuevoPA" autocomplete="off">

                            <div class="form-row">
                                <table class="table table-hover table-striped table-bordered" id="iddatatable">
                                    <thead class="blue-gradient text-white">
                                        <tr>
                                            <th>Fecha de Transferencia *</th>
                                            <th>Monto Pagado *</th>
                                            <th>Referencia *</th>
                                            <th hidden>id_rep</th>
                                            <th hidden>cod_vend</th>
                                            <th hidden>f_pago_gc</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <div class="form-group col-md-12">
                                            <tr style="background-color: white">
                                                <td>
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control datepicker" id="ftransf" name="ftransf" required />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group md-form my-n1">
                                                        <input type="number" class="form-control" id="montop" name="montop" required>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control" id="ref" name="ref" onkeyup="mayus(this);" />
                                                    </div>
                                                </td>
                                                <td hidden>
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control" id="id_rep_gc_modal" name="id_rep_gc_modal">
                                                    </div>
                                                </td>
                                                <td hidden>
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control" id="cod_vend_modal" name="cod_vend_modal">
                                                    </div>
                                                </td>
                                                <td hidden>
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control" id="f_pago_gc_modal" name="f_pago_gc_modal">
                                                    </div>
                                                </td>
                                            </tr>
                                        </div>
                                    </tbody>
                                </table>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="btnAgregarpagoA" class="btn aqua-gradient">Agregar Pago</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="../assets/view/b_poliza.js"></script>

        <script>
            $(document).ready(function () {
                var today = new Date();
                $('#mes').val(today.getMonth()+1);
                $('#mes').change();
            });
            //Abrir picker en un modal
            var $input = $('.datepicker').pickadate({
                // Strings and translations
                monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre',
                    'Noviembre', 'Diciembre'
                ],
                monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dec'],
                weekdaysFull: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sabado'],
                weekdaysShort: ['Dom', 'Lun', 'Mart', 'Mierc', 'Jue', 'Vie', 'Sab'],
                showMonthsShort: undefined,
                showWeekdaysFull: undefined,

                // Buttons
                today: 'Hoy',
                clear: 'Borrar',
                close: 'Cerrar',

                // Accessibility labels
                labelMonthNext: 'Próximo Mes',
                labelMonthPrev: 'Mes Anterior',
                labelMonthSelect: 'Seleccione un Mes',
                labelYearSelect: 'Seleccione un Año',

                // Formats
                dateFormat: 'dd-mm-yyyy',
                format: 'dd-mm-yyyy',
                formatSubmit: 'yyyy-mm-dd',
            });
            var picker = $input.pickadate('picker');

            $(window).on('shown.bs.modal', function() {
                picker.close();
            });
        </script>
</body>

</html>