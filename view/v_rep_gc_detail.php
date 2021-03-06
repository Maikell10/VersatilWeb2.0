<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);
//$pag = 'b_reportes';

require_once '../Controller/Poliza.php';

$id_rep_gc = $_GET['id_rep_gc'];
$cod_asesor = $_GET['asesor'];
$f_pago_gc = $_GET['f_pago_gc'];

$originalFPagoGC = $f_pago_gc;
$newFPagoGC = date("d/m/Y", strtotime($originalFPagoGC));

$asesor_gc = $obj->get_ejecutivo_by_cod($cod_asesor);

$pago_gc_a = $obj->get_gc_h_pago($id_rep_gc, $cod_asesor, $f_pago_gc);
$cant_pago_gc_a = ($pago_gc_a != 0) ? sizeof($pago_gc_a) : 0;
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
                    <div class="ml-5 mr-5">
                        <h1 class="font-weight-bold">Resultado de Búsqueda de GC Pagada del Asesor:</h1>
                        <h2 class="text-danger font-weight-bold"><?= $asesor_gc[0]['nombre'] . ' (' . $asesor_gc[0]['cod'] . ')'; ?></h2>
                        <h2>Fecha Pago GC: <font style="font-weight:bold" class="text-danger"><?= $newFPagoGC; ?></font>
                        </h2>
                    </div>
                    <br><br><br>
                </div>

                <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden>

                    <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('tableGCEX', 'GC a Pagar por Asesor')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../assets/img/excel.png" width="60" alt=""></a></center>

                    <div class="table-responsive-xl">
                        <table class="table table-hover table-striped table-bordered" id="mytable1" style="cursor: pointer;">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th>Asesor</th>
                                    <th>-</th>
                                    <th>N° Póliza</th>
                                    <th>Nombre Titular</th>
                                    <th>Cía</th>
                                    <th>F Pago Prima</th>
                                    <th>F Pago GC</th>
                                    <th>Prima Cobrada</th>
                                    <th>Comisión Cobrada</th>
                                    <th>% Com</th>
                                    <th style="background-color: #E54848; color: white">GC Pagada</th>
                                    <th>%GC Asesor</th>
                                    <th hidden>id</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $totalprimacom = 0;
                                $totalcomision = 0;
                                $totalgc = 0;

                                $poliza = $obj->get_gc_by_a_by_fpgc($id_rep_gc, $cod_asesor, $f_pago_gc);
                                ?>
                                <tr>
                                    <?php
                                    if ($asesor_gc[0]['act'] == 0) {
                                    ?>
                                        <td rowspan="<?= sizeof($poliza); ?>" style="background-color: #D9D9D9;font-weight: bold" class="text-danger align-middle"><?= $asesor_gc[0]['nombre'] . ' (' . $asesor_gc[0]['cod'] . ')'; ?></td>
                                    <?php
                                    }
                                    if ($asesor_gc[0]['act'] == 1) {
                                    ?>
                                        <td rowspan="<?= sizeof($poliza); ?>" style="background-color: #D9D9D9;font-weight: bold" class="text-success align-middle"><?= $asesor_gc[0]['nombre'] . ' (' . $asesor_gc[0]['cod'] . ')'; ?></td>
                                    <?php
                                    }

                                    for ($i = 0; $i < sizeof($poliza); $i++) {
                                        $Arr[] = $poliza[$i]['id_comision'];

                                        $totalsuma = $totalsuma + $poliza[$i]['sumaasegurada'];
                                        $totalprima = $totalprima + $poliza[$i]['prima'];

                                        $totalprimacom = $totalprimacom + $poliza[$i]['prima_com'];
                                        $totalcomision = $totalcomision + $poliza[$i]['comision'];
                                        $totalgc = $totalgc + (($poliza[$i]['comision'] * $poliza[$i]['per_gc']) / 100);

                                        $totalprimacomT = $totalprimacomT + $poliza[$i]['prima_com'];
                                        $totalcomisionT = $totalcomisionT + $poliza[$i]['comision'];
                                        $totalgcT = $totalgcT + (($poliza[$i]['comision'] * $poliza[$i]['per_gc']) / 100);

                                        $newDesde = date("d/m/Y", strtotime($poliza[$i]['f_desdepoliza']));
                                        $newHasta = date("d/m/Y", strtotime($poliza[$i]['f_hastapoliza']));
                                        $tooltip = 'Fecha Desde: ' . $newDesde . ' | Fecha Hasta: ' . $newHasta;

                                        $no_renov = $obj->verRenov1($poliza[$i]['id_poliza']);

                                        if ($poliza[$i]['currency'] == 1) {
                                            $currency = "$ ";
                                        } else {
                                            $currency = "Bs ";
                                        }

                                        if ($poliza[$i]['id_titular'] == 0) {
                                            $titular_pre = $obj->get_element_by_id('titular_pre_poliza', 'id_poliza', $poliza[$i]['id_poliza']);
                                            $nombretitu = $titular_pre[0]['asegurado'];
                                        } else {
                                            $nombretitu = $poliza[$i]['nombre_t'] . " " . $poliza[$i]['apellido_t'];
                                        } ?>

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

                                        <?php
                                        $originalFPago = $poliza[$i]['f_pago_prima'];
                                        $newFPago = date("d/m/Y", strtotime($originalFPago));

                                        $originalFPagoGC = $poliza[$i]['f_pago_gc'];
                                        $newFPagoGC = date("d/m/Y", strtotime($originalFPagoGC));
                                        ?>

                                        <td data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= ($nombretitu); ?></td>
                                        <td nowrap data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= ($poliza[$i]['nomcia']); ?></td>
                                        <td nowrap data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $newFPago; ?></td>

                                        <td nowrap data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $newFPagoGC; ?></td>

                                        <?php if ($poliza[$i]['prima_com'] < 0) { ?>
                                            <td style="color: #E54848;text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= "$ " . number_format($poliza[$i]['prima_com'], 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= "$ " . number_format($poliza[$i]['prima_com'], 2); ?></td>
                                        <?php } ?>

                                        <?php if ($poliza[$i]['comision'] < 0) { ?>
                                            <td style="color: #E54848;text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= "$ " . number_format($poliza[$i]['comision'], 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= "$ " . number_format($poliza[$i]['comision'], 2); ?></td>
                                        <?php } ?>

                                        <td align="center" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= number_format(($poliza[$i]['comision'] * 100) / $poliza[$i]['prima_com'], 0) . " %"; ?></td>

                                        <?php if ((($poliza[$i]['comision'] * $poliza[$i]['per_gc']) / 100) < 0) { ?>
                                            <td style="color: #E54848;text-align: right;background-color: #D9D9D9;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= "$ " . number_format(($poliza[$i]['comision'] * $poliza[$i]['per_gc']) / 100, 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right;background-color: #D9D9D9;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= "$ " . number_format(($poliza[$i]['comision'] * $poliza[$i]['per_gc']) / 100, 2); ?></td>
                                        <?php } ?>

                                        <td nowrap align="center" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= number_format($poliza[$i]['per_gc'], 0) . " %"; ?></td>
                                        <td hidden><?= $poliza[$i]['id_poliza']; ?></td>
                                </tr>
                            <?php
                                    }

                                    if (number_format($totalprimacom, 2) == 0.00) {
                                        $totalprimacom = 0;
                                    }
                                    if ($totalcomision == 0) {
                                        $totalcomision = 1;
                                    }

                                    if ($totalprimacom == 0) {
                                        $total_per_com = 0;
                                    } else {
                                        $total_per_com = ($totalcomision * 100) / $totalprimacom;
                                    }
                            ?>
                            <?php
                            $totalpoliza = $totalpoliza + sizeof($poliza);

                            $var1 = 0;
                            if ($totalprimacomT != 0) {
                                $var1 = number_format(($totalcomisionT * 100) / $totalprimacomT, 2);
                            }
                            $var2 = 0;
                            if ($totalcomisionT != 0) {
                                $var2 = number_format(($totalgcT * 100) / $totalcomisionT, 2);
                            }
                            ?>
                            <tr style="background-color: #2FA4E7;color: white;font-weight: bold" id="no-tocar" class="blue-gradient text-white">
                                <td colspan="7" style="font-weight: bold">Total General</td>

                                <td align="right" style="font-weight: bold">
                                    <font size=4><?= "$ " . number_format($totalprimacomT, 2); ?></font>
                                </td>
                                <td align="right" style="font-weight: bold">
                                    <font size=4><?= "$ " . number_format($totalcomisionT, 2); ?></font>
                                </td>

                                <td nowrap align="center" style="font-weight: bold">
                                    <font size=4><?= $var1 . " %"; ?></font>
                                </td>

                                <td align="right" style="font-weight: bold">
                                    <font size=4><?= "$ " . number_format($totalgcT, 2); ?></font>
                                </td>

                                <td nowrap align="center" style="font-weight: bold">
                                    <font size=4><?= $var2 . " %"; ?></font>
                                </td>
                            </tr>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th>Asesor</th>
                                    <th>-</th>
                                    <th>N° Póliza</th>
                                    <th>Nombre Titular</th>
                                    <th>Cía</th>
                                    <th>F Pago Prima</th>
                                    <th>F Pago GC</th>
                                    <th>Prima Cobrada</th>
                                    <th>Comisión Cobrada</th>
                                    <th>% Com</th>
                                    <th>GC Pagada</th>
                                    <th>%GC Asesor</th>
                                    <th hidden>id</th>
                                </tr>
                            </tfoot>
                        </table>

                        <h1 class="font-weight-bold text-center">Total de Prima Cobrada</h1>
                        <h1 class="font-weight-bold text-center text-danger">$ <?php echo number_format($totalprimacomT, 2); ?></h1>

                        <h1 class="font-weight-bold text-center">Total de Pólizas</h1>
                        <h1 class="font-weight-bold text-center text-danger"><?php echo $totalpoliza; ?></h1>
                    </div>


                    <?php if ($cant_pago_gc_a != 0) { ?>
                        <h1 class="font-weight-bold text-black-50">Pagos GC</h1>
                        <div class="table-responsive-xl">
                            <table class="table table-hover table-striped table-bordered" id="mytable" style="cursor: pointer;">
                                <thead class="blue-gradient text-white">
                                    <tr>
                                        <th>Fecha Transferencia</th>
                                        <th>Referencia</th>
                                        <th style="background-color: #E54848; color: white">Monto Pagado</th>
                                        <th>Acciones</th>
                                        <th hidden>id</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    for ($i = 0; $i < $cant_pago_gc_a; $i++) {
                                        $newFTransf = date("d-m-Y", strtotime($pago_gc_a[$i]['ftransf']));
                                    ?>
                                        <tr>
                                            <td class="align-middle"><?= $newFTransf; ?></td>
                                            <td class="align-middle"><?= $pago_gc_a[$i]['ref']; ?></td>
                                            <td class="text-right font-weight-bold align-middle" style="background-color: #D9D9D9"><?= '$ ' . number_format($pago_gc_a[$i]['montop'], 2); ?></td>

                                            <td style="text-align: center;" class="p-0">
                                                <button onclick="editarPagoGCn('<?= $pago_gc_a[$i]['id_gc_h_pago']; ?>','<?= $pago_gc_a[$i]['ref']; ?>','<?= $pago_gc_a[$i]['montop']; ?>','<?= $newFTransf; ?>')" data-toggle="tooltip" data-placement="top" title="Editar" class="btn btn-success btn-sm">&nbsp;<i class="fas fa-pencil-alt" aria-hidden="true"></i></button>

                                                <button onclick="eliminarPagoGCn('<?= $pago_gc_a[$i]['id_gc_h_pago']; ?>')" data-toggle="tooltip" data-placement="top" title="Eliminar" class="btn btn-danger btn-sm">&nbsp;<i class="fas fa-trash-alt" aria-hidden="true"></i></button>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>




                    <div class="table-responsive-xl" hidden>
                        <table class="table table-hover table-striped table-bordered" id="tableGCEX">
                            <thead>
                                <tr>
                                    <th style="background-color: #4285F4; color: white">Asesor</th>
                                    <th style="background-color: #4285F4; color: white">Ramo</th>
                                    <th style="background-color: #4285F4; color: white">-</th>
                                    <th style="background-color: #4285F4; color: white">N° Póliza</th>
                                    <th style="background-color: #4285F4; color: white">F Hasta Seguro</th>
                                    <th style="background-color: #4285F4; color: white">Nombre Titular</th>
                                    <th style="background-color: #4285F4; color: white">Cía</th>
                                    <th style="background-color: #4285F4; color: white">F Pago Prima</th>
                                    <th style="background-color: #4285F4; color: white">F Pago GC</th>
                                    <th style="background-color: #4285F4; color: white">Prima Cobrada</th>
                                    <th style="background-color: #4285F4; color: white">Comisión Cobrada</th>
                                    <th style="background-color: #4285F4; color: white">% Com</th>
                                    <th style="background-color: #4285F4; color: white">F Rep Com</th>
                                    <th style="background-color: #E54848; color: white">GC Pagada</th>
                                    <th style="background-color: #4285F4; color: white">%GC Asesor</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $totalprimacomT = 0;
                                $totalcomisionT = 0;
                                $totalgcT = 0;
                                $totalpoliza = 0;

                                $totalprimacom = 0;
                                $totalcomision = 0;
                                $totalgc = 0;

                                $poliza = $obj->get_gc_by_a_by_fpgc($id_rep_gc, $cod_asesor, $f_pago_gc);
                                ?>
                                <tr>
                                    <?php if ($asesor_gc[0]['act'] == 0) { ?>
                                        <td rowspan="<?= sizeof($poliza); ?>" style="background-color: #D9D9D9;font-weight: bold;color: #dc3545"><?= $asesor_gc[0]['nombre'] . ' (' . $asesor_gc[0]['cod'] . ')'; ?></td>
                                    <?php }
                                    if ($asesor_gc[0]['act'] == 1) { ?>
                                        <td rowspan="<?= sizeof($poliza); ?>" style="background-color: #D9D9D9;font-weight: bold; color: #28a745"><?= $asesor_gc[0]['nombre'] . ' (' . $asesor_gc[0]['cod'] . ')'; ?></td>
                                    <?php }
                                    for ($i = 0; $i < sizeof($poliza); $i++) {
                                        $totalsuma = $totalsuma + $poliza[$i]['sumaasegurada'];
                                        $totalprima = $totalprima + $poliza[$i]['prima'];

                                        $totalprimacom = $totalprimacom + $poliza[$i]['prima_com'];
                                        $totalcomision = $totalcomision + $poliza[$i]['comision'];
                                        $totalgc = $totalgc + (($poliza[$i]['comision'] * $poliza[$i]['per_gc']) / 100);

                                        $totalprimacomT = $totalprimacomT + $poliza[$i]['prima_com'];
                                        $totalcomisionT = $totalcomisionT + $poliza[$i]['comision'];
                                        $totalgcT = $totalgcT + (($poliza[$i]['comision'] * $poliza[$i]['per_gc']) / 100);

                                        $newDesde = date("d/m/Y", strtotime($poliza[$i]['f_desdepoliza']));
                                        $newHasta = date("d/m/Y", strtotime($poliza[$i]['f_hastapoliza']));
                                        $newFRepCom = date("d/m/Y", strtotime($poliza[$i]['f_hasta_rep']));

                                        $no_renov = $obj->verRenov1($poliza[$i]['id_poliza']);

                                        if ($poliza[$i]['currency'] == 1) {
                                            $currency = "$ ";
                                        } else {
                                            $currency = "Bs ";
                                        }

                                        if ($poliza[$i]['id_titular'] == 0) {
                                            $titular_pre = $obj->get_element_by_id('titular_pre_poliza', 'id_poliza', $poliza[$i]['id_poliza']);
                                            $nombretitu = $titular_pre[0]['asegurado'];
                                        } else {
                                            $nombretitu = $poliza[$i]['nombre_t'] . " " . $poliza[$i]['apellido_t'];
                                        } ?>

                                        <td nowrap><?= ($poliza[$i]['nramo']); ?></td>

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
                                                <td style="color: #2B9E34;font-weight: bold"><?= $poliza[$i]['cod_poliza']; ?></td>
                                            <?php } else { ?>
                                                <td style="color: #E54848;font-weight: bold"><?= $poliza[$i]['cod_poliza']; ?></td>
                                            <?php }
                                        } else { ?>
                                            <td style="color: #4a148c;font-weight: bold"><?= $poliza[$i]['cod_poliza']; ?></td>
                                        <?php } ?>

                                        <?php
                                        $originalFPago = $poliza[$i]['f_pago_prima'];
                                        $newFPago = date("d/m/Y", strtotime($originalFPago));

                                        $originalFPagoGC = $poliza[$i]['f_pago_gc'];
                                        $newFPagoGC = date("d/m/Y", strtotime($originalFPagoGC));
                                        ?>

                                        <td nowrap><?= $newHasta; ?></td>
                                        <td><?= ($nombretitu); ?></td>
                                        <td nowrap><?= ($poliza[$i]['nomcia']); ?></td>
                                        <td nowrap><?= $newFPago; ?></td>

                                        <td nowrap><?= $newFPagoGC; ?></td>

                                        <?php if ($poliza[$i]['prima_com'] < 0) { ?>
                                            <td style="color: #E54848;text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= "$ " . number_format($poliza[$i]['prima_com'], 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= "$ " . number_format($poliza[$i]['prima_com'], 2); ?></td>
                                        <?php } ?>

                                        <?php if ($poliza[$i]['comision'] < 0) { ?>
                                            <td style="color: #E54848;text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= "$ " . number_format($poliza[$i]['comision'], 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= "$ " . number_format($poliza[$i]['comision'], 2); ?></td>
                                        <?php } ?>

                                        <td align="center" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= number_format(($poliza[$i]['comision'] * 100) / $poliza[$i]['prima_com'], 0) . " %"; ?></td>

                                        <td nowrap><?= $newFRepCom; ?></td>

                                        <?php if ((($poliza[$i]['comision'] * $poliza[$i]['per_gc']) / 100) < 0) { ?>
                                            <td style="color: #E54848;text-align: right;background-color: #D9D9D9;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= "$ " . number_format(($poliza[$i]['comision'] * $poliza[$i]['per_gc']) / 100, 2); ?></td>
                                        <?php } else { ?>
                                            <td style="text-align: right;background-color: #D9D9D9;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= "$ " . number_format(($poliza[$i]['comision'] * $poliza[$i]['per_gc']) / 100, 2); ?></td>

                                        <?php } ?>

                                        <td nowrap align="center"><?= number_format($poliza[$i]['per_gc'], 0) . " %"; ?></td>
                                </tr>
                            <?php }
                                    if (number_format($totalprimacom, 2) == 0.00) {
                                        $totalprimacom = 0;
                                    }
                                    if ($totalcomision == 0) {
                                        $totalcomision = 1;
                                    }

                                    if ($totalprimacom == 0) {
                                        $total_per_com = 0;
                                    } else {
                                        $total_per_com = ($totalcomision * 100) / $totalprimacom;
                                    }

                                    $totalpoliza = $totalpoliza + sizeof($poliza);


                                    $var1 = 0;
                                    if ($totalprimacomT != 0) {
                                        $var1 = number_format(($totalcomisionT * 100) / $totalprimacomT, 2);
                                    }
                                    $var2 = 0;
                                    if ($totalcomisionT != 0) {
                                        $var2 = number_format(($totalgcT * 100) / $totalcomisionT, 2);
                                    }

                            ?>
                            <tr id="no-tocar">
                                <td colspan="9" style="background-color: #4285F4;color: white;font-weight: bold">Total General</td>

                                <td align="right" style="background-color: #4285F4;color: white;font-weight: bold">
                                    <font size=4><?= "$ " . number_format($totalprimacomT, 2); ?></font>
                                </td>
                                <td align="right" style="background-color: #4285F4;color: white;font-weight: bold">
                                    <font size=4><?= "$ " . number_format($totalcomisionT, 2); ?></font>
                                </td>
                                <td nowrap align="center" style="background-color: #4285F4;color: white;font-weight: bold">
                                    <font size=4><?= $var1 . " %"; ?></font>
                                </td>
                                <td style="background-color: #4285F4;color: white;"></td>
                                <td align="right" style="background-color: #4285F4;color: white;font-weight: bold">
                                    <font size=4><?= "$ " . number_format($totalgcT, 2); ?></font>
                                </td>
                                <td nowrap align="center" style="background-color: #4285F4;color: white;font-weight: bold">
                                    <font size=4><?= $var2 . " %"; ?></font>
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
                                    <th>F Pago Prima</th>
                                    <th>F Pago GC</th>
                                    <th>Prima Cobrada</th>
                                    <th>Comisión Cobrada</th>
                                    <th>% Com</th>
                                    <th>F Rep Com</th>
                                    <th>GC Pagada</th>
                                    <th>%GC Asesor</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>

                <?php //print_r($Arr);
                //echo sizeof($Arr)-1;
                ?>

                <!--   TABLA PARA USUARIOS QUE SON ASESORES   -->
            <?php }
            if ($_SESSION['id_permiso'] == 3) {
            } ?>


        </div>





        <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'footer.php'; ?>

        <!-- Modal TARJETA -->
        <div class="modal fade" id="editarPagoGCn" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="exampleModalLabel">Editar Pago GC</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <form id="frmnuevoC" autocomplete="off">

                            <div class="table-responsive">
                                <table class="table table-hover table-striped table-bordered">
                                    <thead class="blue-gradient text-white">
                                        <tr>
                                            <th hidden>id</th>
                                            <th>Fecha Transferencia</th>
                                            <th>Referencia</th>
                                            <th>Monto Pagado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td hidden><input type="text" id="id_gc_h_pago" name="id_gc_h_pago"></td>
                                            <td>
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control datepicker" id="ftransf" name="ftransf" required />
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control" id="ref" name="ref" />
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control" id="montop" name="montop" />
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" id="btnEditarPagoGCn" class="btn btn-success btn-block">Editar Pago GC</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="../assets/view/b_poliza.js"></script>
        <script src="../assets/view/modalN.js"></script>

        <script>
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