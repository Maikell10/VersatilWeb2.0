<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);
//$pag = 'b_reportes';

require_once '../../Controller/Poliza.php';

$cod_asesor = $_GET['cod_asesor'];
$anio = $_GET['anio'];
$mes = $_GET['mes'];

$desde = $_GET['anio'] . "-" . $_GET['mes'] . "-01";
$hasta = $_GET['anio'] . "-" . $_GET['mes'] . "-31";

$asesor_gc = $obj->get_ejecutivo_by_cod($cod_asesor);


$poliza = $obj->get_gc_exist_by_p_by_fpgc($cod_asesor, $mes, $anio);
$cant_asesor = ($poliza == 0) ? 0 : sizeof($poliza) ;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'navigation.php'; ?>
    <br><br><br><br><br><br>

    <div>
        <div class="card">

            <div id="carga" class="d-flex justify-content-center align-items-center">
                <div class="spinner-grow text-info" style="width: 7rem; height: 7rem;"></div>
            </div>

            <?php if ($_SESSION['id_permiso'] != 3) { ?>
                <div class="card-header p-5 animated bounceInDown" id="headerload" hidden="true">
                    <div class="ml-5 mr-5">
                        <h1 class="font-weight-bold">Resultado de Búsqueda de GC a Pagar del Asesor:</h1>
                        <h2 class="text-danger font-weight-bold"><?= $asesor_gc[0]['nombre'] . ' (' . $asesor_gc[0]['cod'] . ')'; ?></h2>
                        <h2>Mes Desde Póliza: <font style="font-weight:bold" class="text-danger"><?= $mes_arr[$mes - 1]; ?></font></h2>
                        <h2>Año Desde Póliza: <font style="font-weight:bold" class="text-danger"><?= $anio; ?></font></h2>
                    </div>
                    <br><br><br>
                </div>

                <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden>

                    <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('tableGCEX', 'GC a Pagar por Asesor')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../../assets/img/excel.png" width="60" alt=""></a></center>

                    <div class="table-responsive-xl">
                        <table class="table table-hover table-striped table-bordered" id="mytableR" style="cursor: pointer;">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th>Asesor</th>
                                    <th>T Póliza</th>
                                    <th>N° Póliza</th>
                                    <th>Fecha Desde Seg</th>
                                    <th>Nombre Titular</th>
                                    <th>Cía</th>
                                    <th>F Pago Prima</th>
                                    <th>Prima Cobrada</th>
                                    <th>Prima Suscrita</th>
                                    <th style="background-color: #E54848; color: white">Monto GC</th>
                                    <th hidden>id</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <?php
                                    if ($asesor_gc[0]['act'] == 0) {
                                    ?>
                                        <td rowspan="<?= $cant_asesor; ?>" style="background-color: #D9D9D9;font-weight: bold" class="text-danger"><?= $asesor_gc[0]['nombre']; ?></td>
                                    <?php
                                    }
                                    if ($asesor_gc[0]['act'] == 1) {
                                    ?>
                                        <td rowspan="<?= $cant_asesor; ?>" style="background-color: #D9D9D9;font-weight: bold" class="text-success"><?= $asesor_gc[0]['nombre']; ?></td>
                                        <?php
                                    }
                                    for ($i = 0; $i < $cant_asesor; $i++) {

                                        $fecha_pago_prima = $obj->get_comision_by_poliza_id($poliza[$i]['id_poliza']);
                                        $originalFPagoP = $fecha_pago_prima[0]['f_pago_prima'];
                                        $newFPagoP = date("d/m/Y", strtotime($originalFPagoP));
                                        if($newFPagoP == '01/01/1970') {
                                            $newFPagoP = 'Sin Pago';
                                        }

                                        $monto_pago_prima = $obj->get_comision_by_poliza_id_monto($poliza[$i]['id_poliza']);
                                        $totalmontoP = $totalmontoP + $monto_pago_prima[0]['prima_com'];
                                        $totalmontoPT = $totalmontoPT + $monto_pago_prima[0]['prima_com'];

                                        $totalprima = $totalprima + $poliza[$i]['prima'];
                                        $totalprimaT = $totalprimaT + $poliza[$i]['prima'];
                                        $totalprimaF = $totalprimaF + $poliza[$i]['prima'];

                                        $totalmonto = $totalmonto + $poliza[$i]['per_gc'];
                                        $totalmontoT = $totalmontoT + $poliza[$i]['per_gc'];

                                        $originalDesde = $poliza[$i]['f_desdepoliza'];
                                        $newDesde = date("d/m/Y", strtotime($originalDesde));
                                        $originalHasta = $poliza[$i]['f_hastapoliza'];
                                        $newHasta = date("d/m/Y", strtotime($originalHasta));

                                        if ($poliza[$i]['id_titular'] == 0) {
                                            $titular_pre = $obj->get_element_by_id('titular_pre_poliza', 'id_poliza', $poliza[$i]['id_poliza']);
                                            $nombretitu = $titular_pre[0]['asegurado'];
                                        } else {
                                            $nombretitu = $poliza[$i]['nombre_t'] . " " . $poliza[$i]['apellido_t'];
                                        }

                                        if ($poliza[$i]['id_tpoliza'] == 1) { ?>
                                            <td class="align-middle" style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Nueva">N<span hidden>ueva</span></td>
                                        <?php } if ($poliza[$i]['id_tpoliza'] == 2) { ?>
                                            <td class="align-middle" style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Renovación">R<span hidden>enovacion</span></td>
                                        <?php } if ($poliza[$i]['id_tpoliza'] == 3) { ?>
                                            <td class="align-middle" style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Traspaso de Cartera">T<span hidden>raspaso de Cartera</span></td>
                                        <?php }

                                        if ($poliza[$i]['f_hastapoliza'] >= date("Y-m-d")) {
                                        ?>
                                            <td style="color: #2B9E34"><?= $poliza[$i]['cod_poliza']; ?></td>
                                        <?php
                                        } else {
                                        ?>
                                            <td style="color: #E54848"><?= $poliza[$i]['cod_poliza']; ?></td>
                                        <?php } ?>

                                        <td><?= $newDesde; ?></td>
                                        <td><?= $nombretitu; ?></td>
                                        <td nowrap><?= ($poliza[$i]['nomcia']); ?></td>

                                        <td nowrap><?= $newFPagoP; ?></td>

                                        <td align="right"><?= "$ " . number_format($monto_pago_prima[0]['prima_com'], 2); ?></td>
                                        <td align="right"><?= "$ " . number_format($poliza[$i]['prima'], 2); ?></td>

                                        <?php if($asesor_gc[0]['currency'] === '$'){ ?>
                                            <td align="right" style="text-align: right;background-color: #D9D9D9;font-weight: bold"><?= "$ " . number_format($poliza[$i]['per_gc'], 2); ?></td>
                                        <?php }else{ ?>

                                        <?php } ?>

                                        <td hidden><?= $poliza[$i]['id_poliza']; ?></td>
                                </tr>
                                <?php } ?>
                            
                                <tr id="no-tocar">
                                    <td style="background-color:#2FA4E7;color:white;font-weight: bold" colspan="7">Total General</td>
                                    <td align="right" style="background-color: #2FA4E7;color: white;font-weight: bold">
                                        <font size=4><?= "$ " . number_format($totalmontoPT, 2); ?></font>
                                    </td>
                                    <td align="right" style="background-color: #2FA4E7;color: white;font-weight: bold">
                                        <font size=4><?= "$ " . number_format($totalprimaT, 2); ?></font>
                                    </td>
                                    <td align="right" style="background-color: #2FA4E7;color: white;font-weight: bold">
                                        <font size=4><?= "$ " . number_format($totalmontoT, 2); ?></font>
                                    </td>
                                </tr>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th>Asesor</th>
                                    <th>T Póliza</th>
                                    <th>N° Póliza</th>
                                    <th>Fecha Desde Seg</th>
                                    <th>Nombre Titular</th>
                                    <th>Cía</th>
                                    <th>F Pago Prima</th>
                                    <th>Prima Cobrada</th>
                                    <th>Prima Suscrita</th>
                                    <th>Monto GC</th>
                                    <th hidden>id</th>
                                </tr>
                            </tfoot>
                        </table>

                        <h1 class="font-weight-bold text-center">Total de Prima Cobrada</h1>
                        <h1 class="font-weight-bold text-center text-danger">$ <?php echo number_format($totalmontoPT, 2); ?></h1>

                        <h1 class="font-weight-bold text-center">Total de Pólizas</h1>
                        <h1 class="font-weight-bold text-center text-danger"><?php echo $cant_asesor; ?></h1>
                    </div>





                    <div class="table-responsive-xl" hidden>
                        <table class="table table-hover table-striped table-bordered" id="tableGCEX">
                            <thead>
                                <tr>
                                    <th style="background-color: #4285F4; color: white">Asesor</th>
                                    <th style="background-color: #4285F4; color: white">T Póliza</th>
                                    <th style="background-color: #4285F4; color: white">N° Póliza</th>
                                    <th style="background-color: #4285F4; color: white">Fecha Desde Seg</th>
                                    <th style="background-color: #4285F4; color: white">Nombre Titular</th>
                                    <th style="background-color: #4285F4; color: white">Cía</th>
                                    <th style="background-color: #4285F4; color: white">F Pago Prima</th>
                                    <th style="background-color: #4285F4; color: white">Prima Cobrada</th>
                                    <th style="background-color: #4285F4; color: white">Prima Suscrita</th>
                                    <th style="background-color: #E54848; color: white">Monto GC</th>
                                </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <?php
                                $totalmontoPT = 0;
                                $totalprimaT = 0;
                                $totalmontoT = 0;
                                if ($asesor_gc[0]['act'] == 0) {
                                ?>
                                    <td rowspan="<?= $cant_asesor; ?>" style="background-color: #D9D9D9;font-weight: bold" class="text-danger"><?= $asesor_gc[0]['nombre']; ?></td>
                                <?php
                                }
                                if ($asesor_gc[0]['act'] == 1) {
                                ?>
                                    <td rowspan="<?= $cant_asesor; ?>" style="background-color: #D9D9D9;font-weight: bold" class="text-success"><?= $asesor_gc[0]['nombre']; ?></td>
                                    <?php
                                }
                                for ($i = 0; $i < $cant_asesor; $i++) {

                                    $fecha_pago_prima = $obj->get_comision_by_poliza_id($poliza[$i]['id_poliza']);
                                    $originalFPagoP = $fecha_pago_prima[0]['f_pago_prima'];
                                    $newFPagoP = date("d/m/Y", strtotime($originalFPagoP));
                                    if($newFPagoP == '01/01/1970') {
                                        $newFPagoP = 'Sin Pago';
                                    }

                                    $monto_pago_prima = $obj->get_comision_by_poliza_id_monto($poliza[$i]['id_poliza']);
                                    $totalmontoP = $totalmontoP + $monto_pago_prima[0]['prima_com'];
                                    $totalmontoPT = $totalmontoPT + $monto_pago_prima[0]['prima_com'];

                                    $totalprima = $totalprima + $poliza[$i]['prima'];
                                    $totalprimaT = $totalprimaT + $poliza[$i]['prima'];
                                    $totalprimaF = $totalprimaF + $poliza[$i]['prima'];

                                    $totalmonto = $totalmonto + $poliza[$i]['per_gc'];
                                    $totalmontoT = $totalmontoT + $poliza[$i]['per_gc'];

                                    $originalDesde = $poliza[$i]['f_desdepoliza'];
                                    $newDesde = date("d/m/Y", strtotime($originalDesde));
                                    $originalHasta = $poliza[$i]['f_hastapoliza'];
                                    $newHasta = date("d/m/Y", strtotime($originalHasta));

                                    if ($poliza[$i]['id_titular'] == 0) {
                                        $titular_pre = $obj->get_element_by_id('titular_pre_poliza', 'id_poliza', $poliza[$i]['id_poliza']);
                                        $nombretitu = $titular_pre[0]['asegurado'];
                                    } else {
                                        $nombretitu = $poliza[$i]['nombre_t'] . " " . $poliza[$i]['apellido_t'];
                                    }

                                    if ($poliza[$i]['id_tpoliza'] == 1) { ?>
                                        <td class="align-middle" style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Nueva">N<span hidden>ueva</span></td>
                                    <?php } if ($poliza[$i]['id_tpoliza'] == 2) { ?>
                                        <td class="align-middle" style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Renovación">R<span hidden>enovacion</span></td>
                                    <?php } if ($poliza[$i]['id_tpoliza'] == 3) { ?>
                                        <td class="align-middle" style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Traspaso de Cartera">T<span hidden>raspaso de Cartera</span></td>
                                    <?php }

                                    if ($poliza[$i]['f_hastapoliza'] >= date("Y-m-d")) {
                                    ?>
                                        <td style="color: #2B9E34"><?= $poliza[$i]['cod_poliza']; ?></td>
                                    <?php
                                    } else {
                                    ?>
                                        <td style="color: #E54848"><?= $poliza[$i]['cod_poliza']; ?></td>
                                    <?php } ?>

                                    <td><?= $newDesde; ?></td>
                                    <td><?= $nombretitu; ?></td>
                                    <td nowrap><?= ($poliza[$i]['nomcia']); ?></td>

                                    <td nowrap><?= $newFPagoP; ?></td>

                                    <td align="right"><?= "$ " . number_format($monto_pago_prima[0]['prima_com'], 2); ?></td>
                                    <td align="right"><?= "$ " . number_format($poliza[$i]['prima'], 2); ?></td>

                                    <?php if($asesor_gc[0]['currency'] === '$'){ ?>
                                        <td align="right" style="text-align: right;background-color: #D9D9D9;font-weight: bold"><?= "$ " . number_format($poliza[$i]['per_gc'], 2); ?></td>
                                    <?php }else{ ?>

                                    <?php } ?>
                                </tr>
                                <?php } ?>
                            
                                <tr id="no-tocar">
                                    <td style="background-color:#2FA4E7;color:white;font-weight: bold" colspan="7">Total General</td>
                                    <td align="right" style="background-color: #2FA4E7;color: white;font-weight: bold">
                                        <font size=4><?= "$ " . number_format($totalmontoPT, 2); ?></font>
                                    </td>
                                    <td align="right" style="background-color: #2FA4E7;color: white;font-weight: bold">
                                        <font size=4><?= "$ " . number_format($totalprimaT, 2); ?></font>
                                    </td>
                                    <td align="right" style="background-color: #2FA4E7;color: white;font-weight: bold">
                                        <font size=4><?= "$ " . number_format($totalmontoT, 2); ?></font>
                                    </td>
                                </tr>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th>Asesor</th>
                                    <th>T Póliza</th>
                                    <th>N° Póliza</th>
                                    <th>Fecha Desde Seg</th>
                                    <th>Nombre Titular</th>
                                    <th>Cía</th>
                                    <th>F Pago Prima</th>
                                    <th>Prima Cobrada</th>
                                    <th>Prima Suscrita</th>
                                    <th>Monto GC</th>
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





        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer.php'; ?>

        <script src="../../assets/view/b_poliza.js"></script>
        <script src="../../assets/view/modalN.js"></script>

</body>

</html>