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

$asesor = (isset($_GET["asesor"]) != null) ? $_GET["asesor"] : '';
if (!$asesor == '') {
    $asesor_para_recibir_via_url = stripslashes($asesor);
    $asesor_para_recibir_via_url = urldecode($asesor_para_recibir_via_url);
    $asesor = unserialize($asesor_para_recibir_via_url);
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

$distinct_a = $obj->get_gc_p_by_filtro_distinct_a_carga($desde, $hasta, $cia, $asesor);

//Ordeno los ejecutivos de menor a mayor alfabéticamente
$Ejecutivo[sizeof($distinct_a)] = null;
$codEj[sizeof($distinct_a)] = null;

for ($i = 0; $i < sizeof($distinct_a); $i++) {

    $asesor1 = $obj->get_element_by_id('enp', 'cod', $distinct_a[$i]['codvend']);
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
                                <h1 class="font-weight-bold">Resultado de Búsqueda de GC a Pagar por Proyecto</h1>
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

                    <center><a onclick="generarRR()" class="btn blue-gradient btn-lg" data-toggle="tooltip" data-placement="right" title="Cargar Pagos para la Búsqueda Actual" style="color:white">Cargar Pago(s)</a></center>


                    <form action="../../procesos/agregarGC_P.php" name="form1">
                    <input type="hidden" id="desde" name="desde" value="<?= $desde;?>">
                    <input type="hidden" id="hasta" name="hasta" value="<?= $hasta;?>">
                    <input type="hidden" id="cia" name="cia" value="<?= $ciaEnv;?>">
                    <input type="hidden" id="asesor" name="asesor" value="<?= $asesorEnv;?>">
                    <input type="hidden" id="id_usuarioS" name="id_usuarioS" value="<?= $_SESSION['id_usuario']; ?>">

                    <div class="table-responsive-xl">
                        <table class="table table-hover table-striped table-bordered" id="" style="cursor: pointer;">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th>Asesor</th>
                                    <th>T Póliza</th>
                                    <th>N° Póliza</th>
                                    <th>F Pago Prima</th>
                                    <th>Prima Cobrada</th>
                                    <th>Nº Transferencia</th>
                                    <th>Banco</th>
                                    <th>Fecha</th>
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

                                    $asesor = $obj->get_element_by_id('enp', 'cod', $codEj[$x[$a]]);
                                    $nombre = $asesor[0]['nombre'].' ('.$asesor[0]['cod'].')';

                                    $poliza = $obj->get_gc_p_by_filtro_by_a($desde, $hasta, $cia, $codEj[$x[$a]]);

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

                                            <td nowrap><?= $newFPagoP; ?></td>

                                            <td align="right"><?= "$ " . number_format($monto_pago_prima[0]['prima_com'], 2); ?></td>

                                            <td>
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control" id="n_transf<?= $poliza[$i]['id_poliza'];?>" name="n_transf<?= $poliza[$i]['id_poliza'];?>">
                                                <div>
                                            </td>
                                            <td>
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control" name="n_banco<?= $poliza[$i]['id_poliza'];?>" id="n_banco<?= $poliza[$i]['id_poliza'];?>" onkeyup="mayus(this);">
                                                <div>
                                            </td>
                                            <td>
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control datepicker" id="f_pago_gc_p<?= $poliza[$i]['id_poliza'];?>" name="f_pago_gc_p<?= $poliza[$i]['id_poliza'];?>" required value="<?= date('d-m-Y') ?>">
                                                </div>
                                            </td>

                                            
                                                    
                                                
                                            <?php if($asesor[0]['currency'] === '$'){ 
                                                $totalmonto = $totalmonto + $poliza[$i]['per_gc'];
                                                $totalmontoT = $totalmontoT + $poliza[$i]['per_gc'];
                                            ?>
                                                <td style="text-align: right;font-weight: bold">
                                                    <div class="input-group md-form my-n1">
                                                        <input type="number" class="form-control" name="monto_p<?= $poliza[$i]['id_poliza'];?>" id="monto_p<?= $poliza[$i]['id_poliza'];?>" value="<?= $poliza[$i]['per_gc'];?>">
                                                    </div>
                                                </td>
                                            <?php }else{ 
                                                $totalmonto = $totalmonto + (($poliza[$i]['prima']*$poliza[$i]['per_gc'])/100);
                                                $totalmontoT = $totalmontoT + (($poliza[$i]['prima']*$poliza[$i]['per_gc'])/100);
                                            ?>
                                                <td style="text-align: right;font-weight: bold">
                                                    <div class="input-group md-form my-n1">
                                                        <input type="number" class="form-control" name="monto_p<?= $poliza[$i]['id_poliza'];?>" id="monto_p<?= $poliza[$i]['id_poliza'];?>" value="<?= ($poliza[$i]['prima']*$poliza[$i]['per_gc'])/100;?>">
                                                    </div>
                                                </td>
                                            <?php } ?>
                                            
                                            

                                            <td hidden><?= $poliza[$i]['id_poliza']; ?></td>
                                    </tr>
                                <?php
                                        }
                                ?>
                            <?php
                                    $totalpoliza = $totalpoliza + sizeof($poliza);
                                }
                            ?>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th>Asesor</th>
                                    <th>T Póliza</th>
                                    <th>N° Póliza</th>
                                    <th>F Pago Prima</th>
                                    <th>Prima Cobrada</th>
                                    <th>Nº Transferencia</th>
                                    <th>Banco</th>
                                    <th>Fecha</th>
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

                    </form>

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
                        document.form1.submit();
                        //window.location.replace("../../procesos/agregarGC_P.php?desde=<?= $desde; ?>&hasta=<?= $hasta; ?>&cia=<?= $ciaEnv; ?>&asesor=<?= $asesorEnv; ?>");
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