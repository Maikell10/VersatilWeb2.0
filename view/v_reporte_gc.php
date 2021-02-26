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

$count_faltante_pago_gc = $obj->get_count_a_reporte_gc_h_restante_by_id($_GET["id_rep_gc"]);
if($count_faltante_pago_gc[0]['COUNT(DISTINCT cod_vend)'] != 0) {
    $count_faltante_pago_gc = $count_faltante_pago_gc[0]['COUNT(DISTINCT cod_vend)'];
} else {
    $count_faltante_pago_gc = 0;
}
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

                                <?php if($count_faltante_pago_gc != 0) { ?>
                                    <h3 class="font-weight-bold float-right">
                                        Hay <font class="text-danger"><?= $count_faltante_pago_gc;?></font> Asesor(es) sin Pagar
                                    </h3>
                                <?php } ?>
                                
                                
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
                                    <th>Diferencia</th>
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
                                $totalmontodif = 0;
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

                                            $monto_PGC = 0;
                                            $cant_pago_gc_h = ($pago_gc_h != 0) ? sizeof($pago_gc_h) : 0 ;
                                            for ($e=0; $e < $cant_pago_gc_h; $e++) { 
                                                $monto_PGC = $monto_PGC + $pago_gc_h[$e]['montop'];
                                            }

                                            $totalmontop = $totalmontop + $monto_PGC;

                                            $monto_dif = $totalgc-$monto_PGC;
                                            if($monto_dif < 0.10 && $monto_dif > -0.10) {
                                                $monto_dif = 0;
                                            }

                                            $totalmontodif = $totalmontodif + $monto_dif;
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
                                            <td align="right"><?= '$' . number_format($monto_PGC,2); ?></td>

                                            <?php if ($monto_dif == 0) { ?>
                                                <td align="right" style="font-weight: bold;"><?= '$' . number_format($monto_dif, 2); ?></td>
                                            <?php } if ($monto_dif > 0) { ?>
                                                <td style="text-align: right;font-weight: bold;color:#F53333"><?= '$' . number_format($monto_dif, 2); ?></td>
                                            <?php } if ($monto_dif < 0) { ?>
                                                <td style="text-align: right;font-weight: bold;color:#2B9E34"><?= '$' . number_format($monto_dif, 2); ?></td>
                                            <?php } ?>
                                            

                                            <?php if ($_SESSION['id_permiso'] == 1) { ?>
                                                <td style="text-align: center;">
                                                    <a onclick="crearPagoA(<?= $_GET['id_rep_gc']; ?>,'<?= $distinct_a[$a]['cod_vend']; ?>','<?= $originalFPagoGC; ?>','<?= $monto_dif;?>')" data-toggle="tooltip" data-placement="top" title="Añadir Pago" class="btn blue-gradient btn-rounded btn-sm"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>
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
                                    <font size=4><?= '$ ' . number_format($totalmontop, 2); ?></font>
                                </td>

                                <td align="right" style="font-weight: bold">
                                    <font size=4><?= '$ ' . number_format($totalmontodif, 2); ?></font>
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
                                    <th>Diferencia</th>
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
                                    <th style="background-color: #4285F4; color: white">Mes Pago GC</th>
                                    <th style="background-color: #4285F4; color: white">F Pago GC</th>
                                    <th style="background-color: #4285F4; color: white">Prima Cobrada</th>
                                    <th style="background-color: #4285F4; color: white">Comisión Cobrada</th>
                                    <th style="background-color: #E54848; color: white">GC Pagada</th>
                                    <th style="background-color: #4285F4; color: white">Referencia</th>
                                    <th style="background-color: #4285F4; color: white">F Transf</th>
                                    <th style="background-color: #4285F4; color: white">Monto Pagado</th>
                                    <th style="background-color: #4285F4; color: white">Diferencia</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $cantdistinct_a = ($distinct_a == null) ? 0 : sizeof($distinct_a);
                                $totalmontop = 0;
                                $totalmontodif = 0;
                                $totalgcT = 0;
                                $totalcomisionT = 0;
                                $totalprimacomT = 0;
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

                                            $monto_PGC = 0;
                                            $cant_pago_gc_h = ($pago_gc_h != 0) ? sizeof($pago_gc_h) : 0 ;
                                            for ($e=0; $e < $cant_pago_gc_h; $e++) { 
                                                $monto_PGC = $monto_PGC + $pago_gc_h[$e]['montop'];
                                            }

                                            $totalmontop = $totalmontop + $monto_PGC;

                                            $monto_dif = $totalgc-$pago_gc_h[0]['montop'];
                                            if($monto_dif < 0.10 && $monto_dif > -0.10) {
                                                $monto_dif = 0;
                                            }

                                            $totalmontodif = $totalmontodif + $monto_dif;
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

                                            <?php if ($monto_dif == 0) { ?>
                                                <td align="right" style="font-weight: bold;"><?= '$' . number_format($monto_dif, 2); ?></td>
                                            <?php } if ($monto_dif > 0) { ?>
                                                <td style="text-align: right;font-weight: bold;color:#F53333"><?= '$' . number_format($monto_dif, 2); ?></td>
                                            <?php } if ($monto_dif < 0) { ?>
                                                <td style="text-align: right;font-weight: bold;color:#2B9E34"><?= '$' . number_format($monto_dif, 2); ?></td>
                                            <?php } ?>
                                    </tr>
                            <?php }
                                    } ?>
                            <tr class="blue-gradient text-white" id="no-tocar">
                                <td colspan="3" style="background-color: #4285F4;color: white;font-weight: bold">Total General</td>

                                <td nowrap align="right" style="background-color: #4285F4;color: white;font-weight: bold">
                                    <font size=4><?= "$ " . number_format($totalprimacomT, 2); ?></font>
                                </td>

                                <td nowrap align="right" style="background-color: #4285F4;color: white;font-weight: bold">
                                    <font size=4><?= "$ " . number_format($totalcomisionT, 2); ?></font>
                                </td>

                                <td nowrap align="right" style="background-color: #4285F4;color: white;font-weight: bold">
                                    <font size=4><?= "$ " . number_format($totalgcT, 2); ?></font>
                                </td>

                                <td colspan="2" style="background-color: #4285F4;color: white;font-weight: bold"></td>

                                <td align="right" style="background-color: #4285F4;color: white;font-weight: bold">
                                    <font size=4><?= '$ ' . number_format($totalmontop, 2); ?></font>
                                </td>

                                <td align="right" style="background-color: #4285F4;color: white;font-weight: bold">
                                    <font size=4><?= '$ ' . number_format($totalmontodif, 2); ?></font>
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
                                    <th>Diferencia</th>
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
                        <h5 class="modal-title" id="exampleModalLabel">Añadir Pago GC al Asesor: <font id="asesor_modal" class="text-danger font-weight-bold"></font>
                        </h5>
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
                                                        <input type="text" class="form-control datepicker" id="ftransf" name="ftransf" required value="<?= date('d-m-Y');?>" />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control" id="montop" name="montop" required>
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
                                                <td hidden>
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control" id="ftransf1" name="ftransf1" value="<?= date('d-m-Y');?>">
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
            $(document).ready(function() {
                var today = new Date();
                $('#mes').val(today.getMonth() + 1);
                $('#mes').change();

                var ftransf = $('#ftransf1').val();

                $('#ftransf').pickadate('picker').set('select', ftransf);
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