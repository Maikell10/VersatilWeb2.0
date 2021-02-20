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

$cia = (isset($_GET["cia"]) != null) ? $_GET["cia"] : '';
if (!$cia == '') {
    $cia_para_recibir_via_url = stripslashes($cia);
    $cia_para_recibir_via_url = urldecode($cia_para_recibir_via_url);
    $cia = unserialize($cia_para_recibir_via_url);
}

$asesor_g = (isset($_GET["asesor"]) != null) ? $_GET["asesor"] : '';
if (!$asesor_g == '') {
    $asesor_para_recibir_via_url = stripslashes($asesor_g);
    $asesor_para_recibir_via_url = urldecode($asesor_para_recibir_via_url);
    $asesor_g = unserialize($asesor_para_recibir_via_url);
}


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
                    <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                        <- Regresar</a> <br><br>
                            <div class="ml-5 mr-5">
                                <h1 class="font-weight-bold">Cargar Pago(s) de GC por Asesor</h1>
                                <h2>Año: <font style="font-weight:bold" class="text-danger"><?= $_GET['anio'];
                                                                                            if ($_GET['mes'] == null) {
                                                                                            } else {
                                                                                            ?></font>
                                    Mes: <font style="font-weight:bold" class="text-danger"><?= $mes_arr[$_GET['mes'] - 1];
                                                                                            } ?></font>
                                </h2>
                            </div>
                            <br><br><br>
                </div>

                <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden>

                    <center><a onclick="generarR()" class="btn blue-gradient btn-lg" data-toggle="tooltip" data-placement="right" title="Generar Reporte para la Búsqueda Actual" style="color:white">Cargar Pago(s)</a></center>

                    <br>


                    <form action="../../procesos/agregarGC.php" name="form1">
                    <input type="hidden" id="desde" name="desde" value="<?= $desde;?>">
                    <input type="hidden" id="hasta" name="hasta" value="<?= $hasta;?>">
                    <input type="hidden" id="cia" name="cia" value="<?= $ciaEnv;?>">
                    <input type="hidden" id="asesor" name="asesor" value="<?= $asesorEnv;?>">

                    <div class="table-responsive-xl">
                        <table class="table table-hover table-striped table-bordered" id="mytableGC" style="cursor: pointer;">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th>Asesor</th>
                                    <th>Mes Pago GC</th>
                                    <th>F Pago GC</th>
                                    <th style="background-color: #E54848; color: white">GC Pagada</th>
                                    <th>%GC Asesor</th>
                                    <th>Referencia</th>
                                    <th>F Transferencia</th>
                                    <th>Monto Pago</th>
                                    <th hidden>id</th>
                                    <th hidden>cod_asesor</th>
                                    <th hidden>F Pago GC</th>
                                    <th hidden>cia env</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $Arr[] = null;
                                $cantdistinct_a = ($distinct_a == null) ? 0 : sizeof($distinct_a);
                                $totalpoliza = 0;
                                for ($a = 0; $a < $cantdistinct_a; $a++) {
                                    $distinct_fpgc = $obj->get_distinct_fgc_by_filtro_by_a($desde, $hasta, $cia, $distinct_a[$a]['cod_vend']);
                                ?>
                                    <tr>
                                        <?php
                                        if ($distinct_a[$a]['act'] == 0) {
                                        ?>
                                            <td rowspan="<?= sizeof($distinct_fpgc); ?>" style="background-color: #D9D9D9;font-weight: bold" class="text-danger align-middle"><?= $distinct_a[$a]['nombre'] . ' (' . $distinct_a[$a]['cod_vend'] . ')'; ?></td>
                                        <?php
                                        }
                                        if ($distinct_a[$a]['act'] == 1) {
                                        ?>
                                            <td rowspan="<?= sizeof($distinct_fpgc); ?>" style="background-color: #D9D9D9;font-weight: bold" class="text-success align-middle"><?= $distinct_a[$a]['nombre'] . ' (' . $distinct_a[$a]['cod_vend'] . ')'; ?></td>
                                        <?php
                                        }

                                        for ($b = 0; $b < sizeof($distinct_fpgc); $b++) {

                                            $poliza = $obj->get_gc_by_filtro_by_a_by_fpgc($cia, $distinct_a[$a]['cod_vend'], $distinct_fpgc[$b]['f_pago_gc']);

                                            $totalpoliza = $totalpoliza + sizeof($poliza);

                                            $totalgc = 0;
                                            $totalcomision = 0;
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
                                            }

                                            $originalFPagoGC = $distinct_fpgc[$b]['f_pago_gc'];
                                            $newFPagoGC = date("d/m/Y", strtotime($originalFPagoGC));

                                            $originalFPagoGCMes = $distinct_fpgc[$b]['f_pago_gc'];
                                            $newFPagoGCMes = date("m", strtotime($originalFPagoGCMes));

                                            $cia_para_enviar_via_url = serialize($_GET['cia']);
                                            $ciaEnv = urlencode($cia_para_enviar_via_url);
                                        ?>

                                            <td nowrap><?= $mes_arr[$newFPagoGCMes - 1]; ?></td>
                                            <td nowrap><?= $newFPagoGC; ?></td>
                                            
                                            <?php if ($totalgc < 0) { ?>
                                                <td style="color: #E54848;text-align: right;background-color: #D9D9D9;font-weight: bold"><?= "$ " . number_format($totalgc, 2); ?></td>
                                            <?php } else { ?>
                                                <td style="text-align: right;background-color: #D9D9D9;font-weight: bold"><?= "$ " . number_format($totalgc, 2); ?></td>
                                            <?php } ?>

                                            <td nowrap align="center"><?= number_format(($totalgc * 100) / $totalcomision, 0) . " %"; ?></td>

                                            <td>
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control" id="ref_<?= $distinct_a[$a]['cod_vend'];?>_<?= $distinct_fpgc[$b]['f_pago_gc'];?>" name="ref_<?= $distinct_a[$a]['cod_vend'];?>_<?= $distinct_fpgc[$b]['f_pago_gc'];?>">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control datepicker" id="ftransf<?= $distinct_a[$a]['cod_vend'];?>_<?= $distinct_fpgc[$b]['f_pago_gc'];?>" name="ftransf<?= $distinct_a[$a]['cod_vend'];?>_<?= $distinct_fpgc[$b]['f_pago_gc'];?>" required>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="input-group md-form my-n1">
                                                    <input type="number" class="form-control" id="montop<?= $distinct_a[$a]['cod_vend'];?>_<?= $distinct_fpgc[$b]['f_pago_gc'];?>" name="montop<?= $distinct_a[$a]['cod_vend'];?>_<?= $distinct_fpgc[$b]['f_pago_gc'];?>">
                                                </div>
                                            </td>

                                            <td hidden><?= $poliza[0]['id_poliza']; ?></td>
                                            <td hidden><?= $distinct_a[$a]['cod_vend']; ?></td>
                                            <td hidden><?= $distinct_fpgc[$b]['f_pago_gc']; ?></td>
                                            <td hidden><?= $ciaEnv; ?></td>
                                    </tr>
                            <?php
                                        }
                                    }
                            ?>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th>Asesor</th>
                                    <th>Mes Pago GC</th>
                                    <th>F Pago GC</th>
                                    <th>GC Pagada</th>
                                    <th>%GC Asesor</th>
                                    <th>Referencia</th>
                                    <th>F Transferencia</th>
                                    <th>Monto Pago</th>
                                    <th hidden>id</th>
                                    <th hidden>cod_asesor</th>
                                    <th hidden>F Pago GC</th>
                                    <th hidden>cia env</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <input type="hidden" id="tPoliza" name="tPoliza" value="<?= $totalpoliza;?>">
                    </form>




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

        <script>
            function generarR() {

                alertify.confirm('!!', '¿Desea Generar la GC para la búsqueda actual?',
                    function() {
                        document.form1.submit();
                        //window.location.replace("../../procesos/agregarGC.php?desde=<?= $desde; ?>&hasta=<?= $hasta; ?>&cia=<?= $ciaEnv; ?>&asesor=<?= $asesorEnv; ?>&tPoliza=<?= $totalpoliza; ?>");
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