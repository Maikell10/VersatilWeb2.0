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
$asesor = (isset($_GET["asesor"]) != null) ? $_GET["asesor"] : '';

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

$distinct_a = $obj->get_gc_r_by_filtro_distinct_a_carga($desde, $hasta, $cia, $asesor);

//Ordeno los ejecutivos de menor a mayor alfabéticamente
$Ejecutivo[sizeof($distinct_a)] = null;
$codEj[sizeof($distinct_a)] = null;

for ($i = 0; $i < sizeof($distinct_a); $i++) {

    $asesor1 = $obj->get_element_by_id('enr', 'cod', $distinct_a[$i]['codvend']);
    $nombre = $asesor1[0]['nombre'];

    $Ejecutivo[$i] = $nombre;
    $codEj[$i] = $distinct_a[$i]['codvend'];
}

asort($Ejecutivo);
$x = array();
foreach ($Ejecutivo as $key => $value) {
    $x[count($x)] = $key;
}

$asesorB = $asesor;

if (!$asesor == '') {
    $asesor_para_enviar_via_url = serialize($asesor);
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

$asesorB = ($asesorB == 0) ? 0 : count($asesorB) ;
//recorremos el array de asesor seleccionado
for ($i = 0; $i < $asesorB; $i++) {
    //echo "<br>"  . $asesorB[$i];    
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
                                <h1 class="font-weight-bold">Resultado de Búsqueda de GC a Pagar por Referidor</h1>
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

                    <center><a href="gc_r_copia.php?anio=<?= $_GET['anio']; ?>&mes=<?= $_GET['mes']; ?>&cia=<?= $ciaEnv; ?>&asesor=<?= $asesorEnv; ?>" class="btn blue-gradient btn-lg" data-toggle="tooltip" data-placement="right" title="Cargar Pagos para la Búsqueda Actual" style="color:white">Cargar Pago(s)</a></center>

                    <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('mytableRE', 'GC a Pagar por Asesor')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../../assets/img/excel.png" width="60" alt=""></a></center>

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
                                <?php
                                $totalprimaT = 0;
                                $totalmontoT = 0;
                                $totalprimaF = 0;
                                $totalmontoPT = 0;
                                for ($a = 1; $a <= sizeof($distinct_a); $a++) {

                                    $totalprima = 0;
                                    $totalmonto = 0;
                                    $totalmontoP = 0;

                                    $asesor = $obj->get_element_by_id('enr', 'cod', $codEj[$x[$a]]);
                                    $nombre = $asesor[0]['nombre'].' ('.$asesor[0]['cod'].')';

                                    $poliza = $obj->get_gc_r_by_filtro_by_a($desde, $hasta, $cia, $codEj[$x[$a]]);

                                ?>
                                    <tr>
                                        <?php
                                        if ($asesor[0]['act'] == 0) {
                                        ?>
                                            <td rowspan="<?= sizeof($poliza); ?>" style="background-color: #D9D9D9;font-weight: bold" class="text-danger"><?= $nombre; ?></td>
                                        <?php
                                        }
                                        if ($asesor[0]['act'] == 1) {
                                        ?>
                                            <td rowspan="<?= sizeof($poliza); ?>" style="background-color: #D9D9D9;font-weight: bold" class="text-success"><?= $nombre; ?></td>
                                            <?php
                                        }
                                        for ($i = 0; $i < sizeof($poliza); $i++) {

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
                                            <?php
                                            }
                                            ?>

                                            <td><?= $newDesde; ?></td>
                                            <td><?= $nombretitu; ?></td>
                                            <td nowrap><?= ($poliza[$i]['nomcia']); ?></td>

                                            <td nowrap><?= $newFPagoP; ?></td>

                                            <td align="right"><?= "$ " . number_format($monto_pago_prima[0]['prima_com'], 2); ?></td>
                                            <td align="right"><?= "$ " . number_format($poliza[$i]['prima'], 2); ?></td>

                                            <?php if($asesor[0]['currency'] === '$'){ ?>
                                                <td align="right" style="text-align: right;background-color: #D9D9D9;font-weight: bold"><?= "$ " . number_format($poliza[$i]['per_gc'], 2); ?></td>
                                            <?php }else{ ?>

                                            <?php } ?>

                                            <td hidden><?= $poliza[$i]['id_poliza']; ?></td>
                                    </tr>
                                <?php
                                        }
                                ?>
                                <tr id="no-tocar">
                                    <td colspan="7" style="background-color: #F53333;color: white;font-weight: bold">Total de <?= $nombre; ?>: <font size=4 color="aqua"><?= sizeof($poliza); ?></font>
                                    </td>
                                    <td align="right" style="background-color: #F53333;color: white;font-weight: bold">
                                        <font size=4><?= "$ " . number_format($totalmontoP, 2); ?></font>
                                    </td>
                                    <td align="right" style="background-color: #F53333;color: white;font-weight: bold">
                                        <font size=4><?= "$ " . number_format($totalprima, 2); ?></font>
                                    </td>
                                    <td align="right" style="background-color: #F53333;color: white;font-weight: bold">
                                        <font size=4><?= "$ " . number_format($totalmonto, 2); ?></font>
                                    </td>

                                </tr>
                            <?php
                                    $totalpoliza = $totalpoliza + sizeof($poliza);
                                }
                            ?>
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
                        <h1 class="font-weight-bold text-center text-danger">$ <?php echo number_format($totalprimaF, 2); ?></h1>

                        <h1 class="font-weight-bold text-center">Total de Pólizas</h1>
                        <h1 class="font-weight-bold text-center text-danger"><?php echo $totalpoliza; ?></h1>
                    </div>

                    <div class="table-responsive-xl" hidden>
                        <table class="table table-hover table-striped table-bordered" id="mytableRE" style="cursor: pointer;">
                            <thead class="blue-gradient text-white">
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
                                <?php
                                $totalprimaT = 0;
                                $totalmontoT = 0;
                                $totalprimaF = 0;
                                $totalmontoPT = 0;

                                for ($a = 1; $a <= sizeof($distinct_a); $a++) {

                                    $totalprima = 0;
                                    $totalmonto = 0;
                                    $totalmontoP = 0;

                                    $asesor = $obj->get_element_by_id('enr', 'cod', $codEj[$x[$a]]);
                                    $nombre = $asesor[0]['nombre'].' ('.$asesor[0]['cod'].')';

                                    $poliza = $obj->get_gc_r_by_filtro_by_a($desde, $hasta, $cia, $codEj[$x[$a]]);

                                ?>
                                    <tr>
                                        <?php
                                        if ($asesor[0]['act'] == 0) {
                                        ?>
                                            <td rowspan="<?= sizeof($poliza); ?>" style="background-color: #D9D9D9;font-weight: bold;color: #dc3545"><?= $nombre; ?></td>
                                        <?php
                                        }
                                        if ($asesor[0]['act'] == 1) {
                                        ?>
                                            <td rowspan="<?= sizeof($poliza); ?>" style="background-color: #D9D9D9;font-weight: bold; color: #28a745"><?= $nombre; ?></td>
                                            <?php
                                        }
                                        for ($i = 0; $i < sizeof($poliza); $i++) {

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
                                            <?php
                                            }

                                            ?>

                                            <td><?= $newDesde; ?></td>
                                            <td><?= $nombretitu; ?></td>
                                            <td nowrap><?= ($poliza[$i]['nomcia']); ?></td>
                                            <td nowrap><?= $newFPagoP; ?></td>
                                            <td align="right"><?= "$ " . number_format($monto_pago_prima[0]['prima_com'], 2); ?></td>
                                            <td align="right"><?= "$ " . number_format($poliza[$i]['prima'], 2); ?></td>

                                            <?php if($asesor[0]['currency'] === '$'){ ?>
                                                <td align="right" style="text-align: right;background-color: #D9D9D9;font-weight: bold"><?= "$ " . number_format($poliza[$i]['per_gc'], 2); ?></td>
                                            <?php }else{ ?>

                                            <?php } ?>

                                    </tr>
                                <?php
                                        }
                                ?>
                                <tr id="no-tocar">
                                    <td colspan="7" style="background-color: #F53333;color: white;font-weight: bold">Total de <?= $nombre; ?>: <font size=4 color="aqua"><?= sizeof($poliza); ?></font>
                                    </td>
                                    <td align="right" style="background-color: #F53333;color: white;font-weight: bold">
                                        <font size=4><?= "$ " . number_format($totalmontoP, 2); ?></font>
                                    </td>
                                    <td align="right" style="background-color: #F53333;color: white;font-weight: bold">
                                        <font size=4><?= "$ " . number_format($totalprima, 2); ?></font>
                                    </td>
                                    <td align="right" style="background-color: #F53333;color: white;font-weight: bold">
                                        <font size=4><?= "$ " . number_format($totalmonto, 2); ?></font>
                                    </td>

                                </tr>
                            <?php
                                    $totalpoliza = $totalpoliza + sizeof($poliza);
                                }
                            ?>
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

                        <h1 class="font-weight-bold text-center">Total de Prima Cobrada</h1>
                        <h1 class="font-weight-bold text-center text-danger">$ <?php echo number_format($totalprimaF, 2); ?></h1>

                        <h1 class="font-weight-bold text-center">Total de Pólizas</h1>
                        <h1 class="font-weight-bold text-center text-danger"><?php echo $totalpoliza; ?></h1>
                    </div>

                </div>

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
            function generarRR() {
                alertify.confirm('!!', '¿Desea Generar la GC para la búsqueda actual?',
                    function() {
                        window.location.replace("../../procesos/agregarGC_R.php?desde=<?= $desde; ?>&hasta=<?= $hasta; ?>&cia=<?= $ciaEnv; ?>&asesor=<?= $asesorEnv; ?>");
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