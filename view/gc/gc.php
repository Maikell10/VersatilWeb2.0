<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}

//$pag = 'b_reportes';

require_once '../../Controller/Poliza.php';

$cia = (isset($_GET["cia"]) != null) ? $_GET["cia"] : '';
$asesor_g = (isset($_GET["asesor"]) != null) ? $_GET["asesor"] : '';


$mes = $_GET['mes'];
$desde = $_GET['anio'] . "-" . $_GET['mes'] . "-01";
$hasta = $_GET['anio'] . "-" . $_GET['mes'] . "-31";

if ($_GET['mes'] == '02') {
    $hasta = $_GET['anio'] . "-" . $_GET['mes'] . "-28";
}
if ($_GET['mes'] == '04' || $_GET['mes'] == '06' || $_GET['mes'] == '09' || $_GET['mes'] == '11') {
    $hasta = $_GET['anio'] . "-" . $_GET['mes'] . "-30";
}

if ($mes == null) {
    $mesD = 01;
    $mesH = 12;
    $desde = $_GET['anio'] . "-" . $mesD . "-01";
    $hasta = $_GET['anio'] . "-" . $mesH . "-31";
}

$anio = $_GET['anio'];
if ($anio == null) {
    $fechaMin = $obj->get_fecha_min_max('MIN', 'f_pago_gc', 'rep_com');
    $desde = $fechaMin[0]['MIN(f_pago_gc)'];

    $fechaMax = $obj->get_fecha_min_max('MAX', 'f_pago_gc', 'rep_com');
    $hasta = $fechaMax[0]['MAX(f_pago_gc)'];
}

$distinct_a = $obj->get_gc_by_filtro_distinct_a_carga($desde, $hasta, $cia, $asesor_g);

$asesorB = $asesor_g;

if (!$asesor_g == '') {
    $asesor_para_enviar_via_url = serialize($asesor_g);
    $asesorEnv = urlencode($asesor_para_enviar_via_url);
} else {
    $asesorEnv = '';
}

if (!$cia == '') {
    $cia_para_enviar_via_url = serialize($cia);
    $ciaEnv = urlencode($cia_para_enviar_via_url);
} else {
    $ciaEnv = '';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . '\..\layout\header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . '\..\layout\navigation.php'; ?>
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
                                <h1 class="font-weight-bold">Resultado de Búsqueda de GC a Pagar por Asesor</h1>
                                <h2>Año: <font style="font-weight:bold"><?= $_GET['anio'];
                                                                        if ($_GET['mes'] == null) {
                                                                        } else {
                                                                        ?></font>
                                    Mes: <font style="font-weight:bold"><?= $mes_arr[$_GET['mes'] - 1];
                                                                        } ?></font>
                                </h2>
                            </div>
                            <br><br><br>
                </div>

                <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden>

                    <center><a onclick="generarR()" class="btn blue-gradient btn-lg" data-toggle="tooltip" data-placement="right" title="Generar Reporte para la Búsqueda Actual" style="color:white">Generar</a></center>
                    <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('mytable', 'GC a Pagar por Asesor')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../../assets/img/excel.png" width="60" alt=""></a></center>

                    <div class="table-responsive-xl">
                        <table class="table table-hover table-striped table-bordered" id="mytable" style="cursor: pointer;">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th>Asesor</th>
                                    <th>N° Póliza</th>
                                    <th>Nombre Titular</th>
                                    <th>Cía</th>
                                    <th>F Pago Prima</th>
                                    <th>Prima Cobrada</th>
                                    <th>Comisión Cobrada</th>
                                    <th>% Com</th>
                                    <th>GC Pagada</th>
                                    <th>%GC Asesor</th>
                                    <th hidden>id</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $Arr[] = null;
                                for ($a = 0; $a < sizeof($distinct_a); $a++) {
                                    $totalprimacom = 0;
                                    $totalcomision = 0;
                                    $totalgc = 0;

                                    $poliza = $obj->get_gc_by_filtro_by_a($desde, $hasta, $cia, $distinct_a[$a]['cod_vend']);
                                ?>
                                    <tr>
                                        <?php


                                        if ($distinct_a[$a]['act'] == 0) {
                                        ?>
                                            <td rowspan="<?= sizeof($poliza); ?>" style="background-color: #D9D9D9;font-weight: bold" class="text-danger"><?= $distinct_a[$a]['nombre']; ?></td>
                                        <?php
                                        }
                                        if ($distinct_a[$a]['act'] == 1) {
                                        ?>
                                            <td rowspan="<?= sizeof($poliza); ?>" style="background-color: #D9D9D9;font-weight: bold" class="text-success"><?= $distinct_a[$a]['nombre']; ?></td>
                                        <?php
                                        }
                                        ?>

                                        <?php

                                        for ($i = 0; $i < sizeof($poliza); $i++) {
                                            $Arr[]=$poliza[$i]['id_comision'];

                                            $totalsuma = $totalsuma + $poliza[$i]['sumaasegurada'];
                                            $totalprima = $totalprima + $poliza[$i]['prima'];

                                            $totalprimacom = $totalprimacom + $poliza[$i]['prima_com'];
                                            $totalcomision = $totalcomision + $poliza[$i]['comision'];
                                            $totalgc = $totalgc + (($poliza[$i]['comision'] * $poliza[$i]['per_gc']) / 100);

                                            $totalprimacomT = $totalprimacomT + $poliza[$i]['prima_com'];
                                            $totalcomisionT = $totalcomisionT + $poliza[$i]['comision'];
                                            $totalgcT = $totalgcT + (($poliza[$i]['comision'] * $poliza[$i]['per_gc']) / 100);

                                            $originalDesde = $poliza[$i]['f_desdepoliza'];
                                            $newDesde = date("d/m/Y", strtotime($originalDesde));
                                            $originalHasta = $poliza[$i]['f_hastapoliza'];
                                            $newHasta = date("d/m/Y", strtotime($originalHasta));

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



                                            if ($poliza[$i]['f_hastapoliza'] >= date("Y-m-d")) {
                                        ?>
                                                <td style="color: #2B9E34"><?= $poliza[$i]['cod_poliza']; ?></td>
                                            <?php
                                            } else {
                                            ?>
                                                <td style="color: #E54848"><?= $poliza[$i]['cod_poliza']; ?></td>
                                            <?php
                                            }
                                            $originalFPago = $poliza[$i]['f_pago_prima'];
                                            $newFPago = date("d/m/Y", strtotime($originalFPago));




                                            ?>

                                            <td><?= ($nombretitu); ?></td>
                                            <td nowrap><?= ($poliza[$i]['nomcia']); ?></td>
                                            <td nowrap><?= $newFPago; ?></td>
                                            <td align="right"><?= "$ " . number_format($poliza[$i]['prima_com'], 2); ?></td>
                                            <td align="right"><?= "$ " . number_format($poliza[$i]['comision'], 2); ?></td>
                                            <td align="center"><?= number_format(($poliza[$i]['comision'] * 100) / $poliza[$i]['prima_com'], 0) . " %"; ?></td>
                                            <td align="right" style="background-color: #ED7D31;color:white" class="sunny-morning-gradient text-white font-weight-bold"><?= "$ " . number_format(($poliza[$i]['comision'] * $poliza[$i]['per_gc']) / 100, 2); ?></td>
                                            <td nowrap align="center"><?= number_format($poliza[$i]['per_gc'], 0) . " %"; ?></td>
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
                                <tr style="background-color: #F53333;color: white;font-weight: bold" id="no-tocar" class="young-passion-gradient text-white">
                                    <td colspan="5" style="font-weight: bold">Total de <?= $nombre; ?>: <font size=4 color="aqua"><?= sizeof($poliza); ?></font>
                                    </td>
                                    <td align="right" style="font-weight: bold">
                                        <font size=4><?= "$ " . $totalprimacom; ?></font>
                                    </td>
                                    <td align="right" style="font-weight: bold">
                                        <font size=4><?= "$ " . $totalcomision; ?></font>
                                    </td>

                                    <td nowrap align="center" style="font-weight: bold">
                                        <font size=4><?= number_format($total_per_com, 0) . " %"; ?></font>
                                    </td>

                                    <td align="right" style="font-weight: bold">
                                        <font size=4><?= "$ " . number_format($totalgc, 2); ?></font>
                                    </td>

                                    <td nowrap align="center" style="font-weight: bold">
                                        <font size=4><?= number_format(($totalgc * 100) / $totalcomision, 0) . " %"; ?></font>
                                    </td>
                                </tr>
                            <?php
                                    $totalpoliza = $totalpoliza + sizeof($poliza);
                                }

                            ?>
                            <tr style="background-color: #2FA4E7;color: white;font-weight: bold" id="no-tocar" class="blue-gradient text-white">
                                <td colspan="5" style="font-weight: bold">Total General</td>

                                <td align="right" style="font-weight: bold">
                                    <font size=4><?= "$ " . number_format($totalprimacomT, 2); ?></font>
                                </td>
                                <td align="right" style="font-weight: bold">
                                    <font size=4><?= "$ " . number_format($totalcomisionT, 2); ?></font>
                                </td>

                                <td nowrap align="center" style="font-weight: bold">
                                    <font size=4><?= number_format(($totalcomisionT * 100) / $totalprimacomT, 2) . " %"; ?></font>
                                </td>

                                <td align="right" style="font-weight: bold">
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
                                    <th>F Pago Prima</th>
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

                </div>

                <?php //print_r($Arr);
                //echo sizeof($Arr)-1;
                ?>

                <!--   TABLA PARA USUARIOS QUE SON ASESORES   -->
            <?php }
            if ($_SESSION['id_permiso'] == 3) {
            } ?>


        </div>





        <?php require_once dirname(__DIR__) . '\..\layout\footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . '\..\layout\footer.php'; ?>

        <script src="../../assets/view/b_poliza.js"></script>

        <script>
            function generarR() {

                alertify.confirm('!!', '¿Desea Generar la GC para la búsqueda actual?',
                    function() {
                        window.location.replace("../../procesos/agregarGC.php?desde=<?= $desde; ?>&hasta=<?= $hasta; ?>&cia=<?= $ciaEnv; ?>&asesor=<?= $asesorEnv; ?>&tPoliza=<?= $totalpoliza; ?>");
                    },
                    function() {
                        alertify.error('Cancelada')
                    }).set('labels', {
                    ok: 'Sí',
                    cancel: 'No'
                }).set({
                    transition: 'zoom'
                }).show();
            }
        </script>
</body>

</html>