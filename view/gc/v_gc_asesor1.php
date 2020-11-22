<?php require_once '../../constants.php';
setlocale(LC_TIME, 'es_ES.UTF-8');
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'v_gc_asesor1';

require_once '../../Controller/Asesor.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'navigation.php'; ?>
    <br><br><br><br><br><br>

    <div class="card">

        <div id="carga" class="d-flex justify-content-center align-items-center">
            <div class="spinner-grow text-info" style="width: 7rem; height: 7rem;"></div>
        </div>


        <div class="card-header p-5 animated bounceInDown" id="headerload" hidden="true">
            <div class="text-center">
                <h1 class="font-weight-bold ">GC de <?= $asesor[0]['nombre'] . ' (' . $asesor[0]['cod'] . ')'; ?></h1>
            </div>
            <br><br><br><br>

            <div class="col-md-8 mx-auto">
                <form action="v_gc_asesor1.php" class="form-horizontal" method="GET">
                    <input type="text" value="<?= $asesor[0]['cod'];?>" name="asesor" hidden>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <div class="md-form">
                                <!--The "from" Date Picker -->
                                <input placeholder="Fecha inicio" type="text" id="startingDate" name="desdeP" class="form-control datepicker" required>
                                <label for="startingDate">Fecha de la Búsqueda (Desde Pago GC):</label>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="md-form">
                                <!--The "to" Date Picker -->
                                <input placeholder="Fecha fin" type="text" id="endingDate" name="hastaP" class="form-control datepicker" required>
                                <label for="endingDate">Fecha de la Búsqueda (Hasta Pago GC):</label>
                            </div>
                        </div>
                    </div>
                    <center><button type="submit" class="btn aqua-gradient btn-rounded btn-lg">Buscar</button></center>
                </form>

                <div id="load" class="d-flex justify-content-center align-items-center" hidden>
                    <div class="spinner-grow text-info" style="width: 9rem; height: 9rem;" id="load1" hidden></div>
                </div>
            </div>

            <br><br><br><br>
        </div>

        <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">
            <center><a class="btn dusty-grass-gradient" href="../excel/e_v_gc_asesor.php?asesor=<?= $_GET['asesor']; ?>" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../../assets/img/excel.png" width="60" alt=""></a></center>

            <?php if (substr($asesor[0]['cod'], 0, 1) == 'P' || substr($asesor[0]['cod'], 0, 1) == 'R') { ?>
                <div class="table-responsive-xl">
                    <table class="table table-hover table-striped table-bordered" id="tablaAc" width="100%">
                        <thead class="blue-gradient text-white text-center">
                            <tr>
                                <th hidden>F Pago GC</th>
                                <th class="align-middle">-</th>
                                <th class="text-nowrap align-middle">F Pago GC</th>
                                <th class="align-middle">N° Póliza</th>
                                <th class="align-middle">Nombre Titular</th>
                                <th class="align-middle">Cía</th>
                                <th class="align-middle">F Pago Prima</th>
                                <th class="align-middle">Prima Cobrada</th>
                                <th class="align-middle">Comisión Cobrada</th>
                                <th class="text-nowrap align-middle">% Com</th>
                                <th class="align-middle" style="background-color: #E54848; color: white">GC Pagada</th>
                                <th class="align-middle">GC Asesor</th>
                                <th hidden>id</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $Arr[] = null;
                            $totalprimacom = 0;
                            $totalcomision = 0;
                            $totalgc = 0;
                            $idpoliza_comp = 0;
                            $totalGCP = 0;
                            for ($i = 0; $i < sizeof($poliza); $i++) {
                                $polizapp = $obj->get_comision_proyecto_by_id($poliza[$i]['id_poliza']);
                                $pGCpago = $polizapp[0]['monto_p'];
                                $newFPagoGC = date("d/m/Y", strtotime($polizapp[0]['f_pago_gc_r']));

                                if($idpoliza_comp != $polizapp[0]['id_poliza']) {
                                    $idpoliza_comp = $polizapp[0]['id_poliza'];
                                    $totalGCP = $totalGCP + $polizapp[0]['monto_p'];
                                }

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
                                }
                            ?>
                                <tr style="cursor: pointer">
                                    <td hidden><?= $polizapp[0]['f_pago_gc_r']; ?></td>

                                    <?php if ($poliza[$i]['id_tpoliza'] == 1) { ?>
                                        <td class="align-middle" style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Nueva">N<span hidden>ueva</span></td>
                                    <?php } if ($poliza[$i]['id_tpoliza'] == 2) { ?>
                                        <td class="align-middle" style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Renovación">R<span hidden>enovacion</span></td>
                                    <?php } if ($poliza[$i]['id_tpoliza'] == 3) { ?>
                                        <td class="align-middle" style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Traspaso de Cartera">T<span hidden>raspaso de Cartera</span></td>
                                    <?php } ?>

                                    <td class="align-middle" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $mes_arr[date('m', strtotime($polizapp[0]['f_pago_gc_r'])) - 1] . ' ' . date('Y', strtotime($polizapp[0]['f_pago_gc_r'])); ?></td>

                                    <?php if ($no_renov[0]['no_renov'] != 1) {
                                        if ($poliza[$i]['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                            <td class="align-middle" style="color: #2B9E34;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $poliza[$i]['cod_poliza']; ?></td>
                                        <?php } else { ?>
                                            <td class="align-middle" style="color: #E54848;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $poliza[$i]['cod_poliza']; ?></td>
                                        <?php }
                                    } else { ?>
                                        <td class="align-middle" style="color: #4a148c;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $poliza[$i]['cod_poliza']; ?></td>
                                    <?php } ?>

                                    <?php
                                    $originalFPago = $poliza[$i]['f_pago_prima'];
                                    $newFPago = date("Y/m/d", strtotime($originalFPago));
                                    ?>
                                    <td class="align-middle" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= ($nombretitu); ?></td>
                                    <td class="align-middle" nowrap data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= ($poliza[$i]['nomcia']); ?></td>
                                    <td class="align-middle" nowrap data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $newFPago; ?></td>

                                    <?php if ($poliza[$i]['prima_com'] < 0) { ?>
                                        <td class="align-middle" style="color: #E54848;text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= "$ " . number_format($poliza[$i]['prima_com'], 2); ?></td>
                                    <?php } else { ?>
                                        <td class="align-middle" style="text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= "$ " . number_format($poliza[$i]['prima_com'], 2); ?></td>
                                    <?php } ?>

                                    <?php if ($poliza[$i]['comision'] < 0) { ?>
                                        <td class="align-middle" style="color: #E54848;text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= "$ " . number_format($poliza[$i]['comision'], 2); ?></td>
                                    <?php } else { ?>
                                        <td class="align-middle" style="text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= "$ " . number_format($poliza[$i]['comision'], 2); ?></td>
                                    <?php } ?>

                                    <td class="align-middle" align="center" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= number_format(($poliza[$i]['comision'] * 100) / $poliza[$i]['prima_com'], 0) . " %"; ?></td>

                                    <?php if ($no_renov[0]['no_renov'] != 1) {
                                        if ($pGCpago < 0) { ?>
                                            <td class="align-middle" style="color: #E54848;text-align: right;background-color: #D9D9D9;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= "$ " . number_format($pGCpago, 2); ?></td>
                                        <?php } else { ?>
                                            <td class="align-middle" style="text-align: right;background-color: #D9D9D9;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= "$ " . number_format($pGCpago, 2); ?></td>

                                        <?php }
                                    } else { ?>
                                        <td class="align-middle" style="color: #4a148c;text-align: right;background-color: #D9D9D9;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= "$ " . number_format($pGCpago, 2); ?></td>
                                    <?php } ?>

                                    <?php if($asesor[0]['currency'] == '$') { ?>
                                        <td class="align-middle" nowrap align="center" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= '$ ' . number_format($poliza[$i]['per_gc'], 0); ?></td>
                                    <?php } else { ?>
                                        <td class="align-middle" nowrap align="center" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= number_format($poliza[$i]['per_gc'], 0) . " %"; ?></td>
                                    <?php } ?>

                                    <td hidden><?= $poliza[$i]['id_poliza']; ?></td>
                                </tr>

                            <?php
                                //$totalGCP = $totalGCP + (($poliza[$i]['comision'] * $poliza[$i]['per_gc']) / 100);
                            }
                            if ($totalcomision == 0) {
                                $totalcomision = 1;
                            }
                            ?>

                        </tbody>
                        <tfoot class="text-center">
                            <tr>
                                <th hidden>F Pago GC</th>
                                <th class="align-middle">-</th>
                                <th class="align-middle">F Pago GC</th>
                                <th class="align-middle">N° Póliza</th>
                                <th class="align-middle">Nombre Titular</th>
                                <th class="align-middle">Cía</th>
                                <th class="align-middle">F Pago Prima</th>
                                <th class="align-middle">Total Prima Cobrada $ <?= number_format($totalprimacomT, 2); ?></th>
                                <th class="align-middle">Total Comisión Cobrada $ <?= number_format($totalcomision, 2); ?></th>
                                <th class="align-middle">% Com</th>
                                <th class="align-middle" style="background-color: #E54848; color: white">Total GC Pagada $ <?= number_format($totalGCP, 2); ?></th>
                                <th class="align-middle">GC Asesor</th>
                                <th hidden>id</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            <?php } else { ?>
                <div class="table-responsive-xl">
                    <table class="table table-hover table-striped table-bordered" id="tablaAc" width="100%">
                        <thead class="blue-gradient text-white text-center">
                            <tr>
                                <th hidden>F Pago GC</th>
                                <th class="align-middle">-</th>
                                <th class="text-nowrap align-middle">F Pago GC</th>
                                <th class="align-middle">N° Póliza</th>
                                <th class="align-middle">Nombre Titular</th>
                                <th class="align-middle">Cía</th>
                                <th class="align-middle">F Pago Prima</th>
                                <th class="align-middle">Prima Cobrada</th>
                                <th class="align-middle">Comisión Cobrada</th>
                                <th class="text-nowrap align-middle">% Com</th>
                                <th class="align-middle" style="background-color: #E54848; color: white">GC Pagada</th>
                                <th class="align-middle">GC Asesor</th>
                                <th hidden>id</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $Arr[] = null;
                            $totalprimacom = 0;
                            $totalcomision = 0;
                            $totalgc = 0;
                            for ($i = 0; $i < sizeof($poliza); $i++) {


                                //$poliza = $obj->get_gc_by_filtro_by_a($desde, $hasta, $cia, $distinct_a[$a]['cod_vend']);

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
                                }


                            ?>
                                <tr style="cursor: pointer">
                                    <td hidden><?= $poliza[$i]['f_pago_gc']; ?></td>

                                    <?php if ($poliza[$i]['id_tpoliza'] == 1) { ?>
                                        <td class="align-middle" style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Nueva">N<span hidden>ueva</span></td>
                                    <?php } if ($poliza[$i]['id_tpoliza'] == 2) { ?>
                                        <td class="align-middle" style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Renovación">R<span hidden>enovacion</span></td>
                                    <?php } if ($poliza[$i]['id_tpoliza'] == 3) { ?>
                                        <td class="align-middle" style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Traspaso de Cartera">T<span hidden>raspaso de Cartera</span></td>
                                    <?php } ?>

                                    <td class="align-middle" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $mes_arr[date('m', strtotime($poliza[$i]['f_pago_gc'])) - 1] . ' ' . date('Y', strtotime($poliza[$i]['f_pago_gc'])); ?></td>

                                    <?php if ($no_renov[0]['no_renov'] != 1) {
                                        if ($poliza[$i]['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                            <td class="align-middle" style="color: #2B9E34;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $poliza[$i]['cod_poliza']; ?></td>
                                        <?php } else { ?>
                                            <td class="align-middle" style="color: #E54848;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $poliza[$i]['cod_poliza']; ?></td>
                                        <?php }
                                    } else { ?>
                                        <td class="align-middle" style="color: #4a148c;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $poliza[$i]['cod_poliza']; ?></td>
                                    <?php } ?>

                                    <?php
                                    $originalFPago = $poliza[$i]['f_pago_prima'];
                                    $newFPago = date("Y/m/d", strtotime($originalFPago));
                                    ?>
                                    <td class="align-middle" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= ($nombretitu); ?></td>
                                    <td class="align-middle" nowrap data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= ($poliza[$i]['nomcia']); ?></td>
                                    <td class="align-middle" nowrap data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $newFPago; ?></td>

                                    <?php if ($poliza[$i]['prima_com'] < 0) { ?>
                                        <td class="align-middle" style="color: #E54848;text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= "$ " . number_format($poliza[$i]['prima_com'], 2); ?></td>
                                    <?php } else { ?>
                                        <td class="align-middle" style="text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= "$ " . number_format($poliza[$i]['prima_com'], 2); ?></td>
                                    <?php } ?>

                                    <?php if ($poliza[$i]['comision'] < 0) { ?>
                                        <td class="align-middle" style="color: #E54848;text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= "$ " . number_format($poliza[$i]['comision'], 2); ?></td>
                                    <?php } else { ?>
                                        <td class="align-middle" style="text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= "$ " . number_format($poliza[$i]['comision'], 2); ?></td>
                                    <?php } ?>

                                    <td class="align-middle" align="center" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= number_format(($poliza[$i]['comision'] * 100) / $poliza[$i]['prima_com'], 0) . " %"; ?></td>

                                    <?php if ($no_renov[0]['no_renov'] != 1) {
                                        if ((($poliza[$i]['comision'] * $poliza[$i]['per_gc']) / 100) < 0) { ?>
                                            <td class="align-middle" style="color: #E54848;text-align: right;background-color: #D9D9D9;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= "$ " . number_format(($poliza[$i]['comision'] * $poliza[$i]['per_gc']) / 100, 2); ?></td>
                                        <?php } else { ?>
                                            <td class="align-middle" style="text-align: right;background-color: #D9D9D9;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= "$ " . number_format(($poliza[$i]['comision'] * $poliza[$i]['per_gc']) / 100, 2); ?></td>

                                        <?php }
                                    } else { ?>
                                        <td class="align-middle" style="color: #4a148c;text-align: right;background-color: #D9D9D9;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= "$ " . number_format(($poliza[$i]['comision'] * $poliza[$i]['per_gc']) / 100, 2); ?></td>
                                    <?php } ?>

                                    <td class="align-middle" nowrap align="center" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= number_format($poliza[$i]['per_gc'], 0) . " %"; ?></td>
                                    <td hidden><?= $poliza[$i]['id_poliza']; ?></td>
                                </tr>

                            <?php
                                $totalGCP = $totalGCP + (($poliza[$i]['comision'] * $poliza[$i]['per_gc']) / 100);
                                //$totalcomision = $totalcomision + $poliza[$i]['comision'];
                            }
                            if ($totalcomision == 0) {
                                $totalcomision = 1;
                            }
                            ?>

                        </tbody>
                        <tfoot class="text-center">
                            <tr>
                                <th hidden>F Pago GC</th>
                                <th class="align-middle">-</th>
                                <th class="align-middle">F Pago GC</th>
                                <th class="align-middle">N° Póliza</th>
                                <th class="align-middle">Nombre Titular</th>
                                <th class="align-middle">Cía</th>
                                <th class="align-middle">F Pago Prima</th>
                                <th class="align-middle">Total Prima Cobrada $ <?= number_format($totalprimacomT, 2); ?></th>
                                <th class="align-middle">Total Comisión Cobrada $ <?= number_format($totalcomision, 2); ?></th>
                                <th class="align-middle">% Com</th>
                                <th class="align-middle" style="background-color: #E54848; color: white">Total GC Pagada $ <?= number_format($totalGCP, 2); ?></th>
                                <th class="align-middle">GC Asesor</th>
                                <th hidden>id</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            <?php } ?>

            <h1 class="font-weight-bold text-center">Total de Prima Cobrada</h1>
            <h1 class="font-weight-bold text-center text-danger">$ <?= number_format($totalprimacomT, 2); ?></h1>

            <h1 class="font-weight-bold text-center">Total de GC Pagada</h1>
            <h1 class="font-weight-bold text-center text-danger">$ <?= number_format($totalGCP, 2); ?></h1>

        </div>

    </div>



    <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer_b.php'; ?>

    <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer.php'; ?>

    <script src="../../assets/view/b_asesor.js"></script>
</body>

</html>