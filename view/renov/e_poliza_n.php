<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);
//$pag = 'v_poliza';

require_once '../../Controller/Poliza.php';

//----Obtengo el permiso del usuario
$permiso = $_SESSION['id_permiso'];
//----------------------

$id_poliza = $_POST['id_poliza'];
$n_poliza = $_POST['n_poliza'];
$fhoy = date("Y-m-d");
$femisionP = $fhoy;
$t_cobertura = 0;
$fdesdeP = $_POST['desdeP'];
$fhastaP = $_POST['hastaP'];
$currency = $_POST['currency'];
$tipo_poliza = $_POST['tipo_poliza'];
$sumaA = $_POST['sumaA'];
$z_produc = $_POST['z_produc'];
$codasesor = $_POST['asesor'];
$u = explode('=', $codasesor);
$ramo = $_POST['ramo'];
$cia = $_POST['cia'];
$titular = $_POST['titular'];

$forma_pago = 'PAGO VOLUNTARIO';
if ($_POST['forma_pago'] == 1) {
    $forma_pago = 'ACH (CARGO EN CUENTA)';
}
if ($_POST['forma_pago'] == 2) {
    $forma_pago = 'TARJETA DE CREDITO / DEBITO';
}
$n_tarjeta = $_POST['n_tarjeta'];
if ($_POST['n_tarjeta'] == null || $_POST['n_tarjeta'] == 0) {
    $n_tarjeta = 'N/A';
}
$cvv = $_POST['cvv'];
if ($_POST['cvv'] == null) {
    $cvv = 'N/A';
}
$fechaV = $_POST['fechaV'];
if ($_POST['fechaV'] == null || $_POST['fechaV'] == '01-01-2000') {
    $fechaV = 'N/A';
}
$titular_tarjeta = $_POST['titular_tarjeta'];
if ($_POST['titular_tarjeta'] == null) {
    $titular_tarjeta = 'N/A';
}
$bancoT = $_POST['bancoT'];
if ($_POST['bancoT'] == null) {
    $bancoT = 'N/A';
}
$alert = $_POST['alert'];
$id_tarjeta = $_POST['id_tarjeta'];

$condTar = 0;
//if ($alert == 1) {
if ($cvv != $_POST['cvv_h'] || $fechaV != $_POST['fechaV_h'] || $titular_tarjeta != $_POST['titular_tarjeta_h'] || $bancoT != $_POST['bancoT_h']) {
    $condTar = 1;
    $alert = 1;
}
//}
$n_recibo = $_POST['n_recibo'];
$fdesde_recibo = $_POST['desde_recibo'];
$fhasta_recibo = $_POST['hasta_recibo'];
$prima = $_POST['prima'];
$f_pago = $_POST['f_pago'];

$n_cuotas = $_POST['n_cuotas'];
if ($f_pago == 1) {
    $n_cuotas = 1;
    $monto_cuotas = $prima;
} else {
    $monto_cuotas = $prima / $n_cuotas;
}

$tomador = $_POST['tomador'];
$titular = $_POST['titular'];

$obs_p = $_POST['obs_p'];

$idtomador = $obj->get_id_cliente($tomador);

$idtitular = $obj->get_id_cliente($titular);

$tipo_poliza_print = "";
if ($tipo_poliza == 1) {
    $tipo_poliza_print = "Primer Año";
}
if ($tipo_poliza == 2) {
    $tipo_poliza_print = "Renovación";
}
if ($tipo_poliza == 3) {
    $tipo_poliza_print = "Traspaso de Cartera";
}
if ($tipo_poliza == 4) {
    $tipo_poliza_print = "Anexos";
}
if ($tipo_poliza == 5) {
    $tipo_poliza_print = "Revalorización";
}

$nombre_ramo = $obj->get_element_by_id('dramo', 'cod_ramo', $ramo);
$nombre_cia = $obj->get_element_by_id('dcia', 'idcia', $cia);

if ($f_pago == 1) {
    $f_pago = 'CONTADO';
}
if ($f_pago == 2) {
    $f_pago = 'FRACCIONADO';
}
if ($f_pago == 3) {
    $f_pago = 'FINANCIADO';
}

$currencyl = ($currency == 1) ? "$ " : "Bs ";

if ($_POST['t_cuenta'] == 1) {
    $t_cuenta = 'Individual';
} else {
    $t_cuenta = 'Colectivo';
}


$newDesdeP = date("d/m/Y", strtotime($_POST['desdeP']));
$newHastaP = date("d/m/Y", strtotime($_POST['hastaP']));
$newDesdeR = date("d/m/Y", strtotime($_POST['desde_recibo']));
$newHastaR = date("d/m/Y", strtotime($_POST['hasta_recibo']));
$newDesdePc = date("Y/m/d", strtotime($_POST['desdeP']));

if ($sumaA == "") {
    $sumaA = 0;
}

if ($nombre_cia[0]['preferencial'] == 1) {
}


$asesor_ind = $obj->get_element_by_id('ena', 'cod', $u[0]);
$as = 0;
$por_gc = ($ramo == 35) ? $asesor_ind[0]['gc_viajes_renov'] : $asesor_ind[0]['nopre1_renov'];

if ($asesor_ind[0]['nopre1_renov'] == null) {
    //buscar en referidor";
    $asesor_ind_r = $obj->get_element_by_id('enr', 'cod', $u[0]);
    $por_gc = $asesor_ind_r[0]['monto'];
    $as = 1;
    if ($asesor_ind_r[0]['currency'] == '$') {
        $tipo_r = 1;
    }
    if ($asesor_ind_r[0]['currency'] == '%') {
        $tipo_r = 2;
    }
}
if ($asesor_ind[0]['nopre1_renov'] == null && $asesor_ind_r[0]['monto'] == null) {
    //echo "es nulo, buscar en proyecto";
    $asesor_ind = $obj->get_element_by_id('enp', 'cod', $u[0]);
    $as = 2;
    if ($asesor_ind[0]['currency'] == '$') {
        $tipo_r = 1;
    }
    if ($asesor_ind[0]['currency'] == '%') {
        $tipo_r = 2;
    }
}


$placa = $_POST['placa'];
$tipo = $_POST['tipo'];
$marca = $_POST['marca'];
$modelo = $_POST['modelo'];
$anio = $_POST['anio'];
$color = '-';
$serial = '-';
$categoria = '-';

$cia_pref = $obj->get_per_gc_cia_pref($newDesdePc, $cia, $u[0]);
if ($cia_pref[0]['per_gc_sum'] != null && $ramo != 35) {
    $por_gc = $por_gc + $cia_pref[0]['per_gc_sum'];
}


$fdesdeP1 = $_POST['desdeP1'];
$fhastaP1 = $_POST['hastaP1'];

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

            <div class="card-header p-5 animated bounceInDown">
                <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                    <- Regresar</a> <br><br>
                        <div class="ml-5 mr-5">
                            <h1 class="font-weight-bold text-center"><i class="fas fa-book" aria-hidden="true"></i>&nbsp;Previsualizar Pre-Renovación de la Póliza N° <?= $n_poliza; ?></h1>
                        </div>
            </div>

            <!-- Comienzo tabla -->
            <form class="form-horizontal" id="frmnuevo" action="e_poliza_n.php" method="post">
                <div class="card-body p-5">
                    <div class="table-responsive-xl">
                        <table class="table" width="100%">
                            <thead class="heavy-rain-gradient">
                                <tr>
                                    <th colspan="2" class="text-black font-weight-bold">N° de Póliza</th>
                                    <th class="text-black font-weight-bold">Fecha Desde Seguro</th>
                                    <th class="text-black font-weight-bold">Fecha Hasta Seguro</th>
                                    <th class="text-black font-weight-bold">Tipo de Póliza</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="2">
                                        <div class="input-group md-form my-n1 grey lighten-2">
                                            <input type="text" class="form-control" name="n_poliza" readonly value="<?= $n_poliza; ?>" style="background-color:rgba(26, 197, 26, 0.932);color:white">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group md-form my-n1 grey lighten-2">
                                            <input type="text" class="form-control" name="desdeP" readonly value="<?= $newDesdeP; ?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group md-form my-n1 grey lighten-2">
                                            <input type="text" class="form-control" name="hastaP" readonly value="<?= $newHastaP; ?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group md-form my-n1 grey lighten-2">
                                            <input type="text" class="form-control" name="tipo_poliza" readonly value="<?= $tipo_poliza_print; ?>">
                                        </div>
                                    </td>
                                </tr>

                                <tr class="heavy-rain-gradient">
                                    <th colspan="2" class="text-black font-weight-bold">Ramo</th>
                                    <th colspan="2" class="text-black font-weight-bold">Compañía</th>
                                    <th class="text-black font-weight-bold">Tipo Cuenta</th>
                                    <th hidden>Tipo de Cobertura</th>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="input-group md-form my-n1 grey lighten-2">
                                            <input type="text" class="form-control" name="ramo" readonly value="<?= ($nombre_ramo[0]['nramo']); ?>">
                                        </div>
                                    </td>
                                    <td colspan="2">
                                        <div class="input-group md-form my-n1 grey lighten-2">
                                            <input type="text" class="form-control" name="cia" readonly value="<?= ($nombre_cia[0]['nomcia']); ?>" style="background-color:rgba(26, 197, 26, 0.932);color:white">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group md-form my-n1 grey lighten-2">
                                            <input type="text" class="form-control" name="t_cuenta" readonly value="<?= $t_cuenta; ?>">
                                        </div>
                                    </td>
                                    <td hidden><input type="text" class="form-control" name="t_cobertura" readonly value="<?= $t_cobertura; ?>"></td>
                                </tr>

                                <tr class="heavy-rain-gradient">
                                    <th class="text-black font-weight-bold">Suma Asegurada</th>
                                    <th class="text-black font-weight-bold">Prima Suscrita</th>
                                    <th class="text-black font-weight-bold">Periocidad de Pago</th>
                                    <th class="text-black font-weight-bold">N° de Cuotas</th>
                                    <th class="text-black font-weight-bold">Monto Cuotas</th>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="input-group md-form my-n1 grey lighten-2">
                                            <input type="text" class="form-control" name="sumaA" readonly value="<?= $currencyl . number_format($sumaA, 2); ?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group md-form my-n1 grey lighten-2">
                                            <input type="text" class="form-control" name="prima" readonly value="<?= $currencyl . number_format($prima, 2); ?>" style="background-color:rgba(228, 66, 66, 0.87);color:white">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group md-form my-n1 grey lighten-2">
                                            <input type="text" class="form-control" name="f_pago" readonly value="<?= $f_pago; ?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group md-form my-n1 grey lighten-2">
                                            <input type="text" class="form-control" name="n_cuotas" readonly value="<?= $n_cuotas; ?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group md-form my-n1 grey lighten-2">
                                            <input type="text" class="form-control" name="monto_cuotas" readonly value="<?= $currencyl . number_format($monto_cuotas, 2); ?>">
                                        </div>
                                    </td>
                                </tr>

                                <tr class="heavy-rain-gradient">
                                    <th class="text-black font-weight-bold" colspan="2">Forma de Pago</th>
                                    <th class="text-black font-weight-bold">Nº Tarjeta</th>
                                    <th class="text-black font-weight-bold">CVV</th>
                                    <th class="text-black font-weight-bold">Fecha de Vencimiento</th>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="input-group md-form my-n1 grey lighten-2">
                                            <input type="text" class="form-control" name="forma_pago" readonly value="<?= $forma_pago; ?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group md-form my-n1 grey lighten-2">
                                            <input type="text" class="form-control" name="n_tarjeta" readonly value="<?= $n_tarjeta; ?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group md-form my-n1 grey lighten-2">
                                            <input type="text" class="form-control" name="cvv" readonly value="<?= $cvv; ?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group md-form my-n1 grey lighten-2">
                                            <input type="text" class="form-control" name="fechaV" readonly value="<?= $fechaV; ?>">
                                        </div>
                                    </td>
                                </tr>

                                <tr class="heavy-rain-gradient">
                                    <th class="text-black font-weight-bold" colspan="3">Nombre Tarjetahabiente</th>
                                    <th class="text-black font-weight-bold" colspan="2">Banco</th>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <div class="input-group md-form my-n1 grey lighten-2">
                                            <input type="text" class="form-control" name="titular_tarjeta" readonly value="<?= $titular_tarjeta; ?>">
                                        </div>
                                    </td>
                                    <td colspan="2">
                                        <div class="input-group md-form my-n1 grey lighten-2">
                                            <input type="text" class="form-control" name="bancoT" readonly value="<?= $bancoT; ?>">
                                        </div>
                                    </td>
                                </tr>

                                <tr class="heavy-rain-gradient">
                                    <th class="text-black font-weight-bold" colspan="2">N° Recibo</th>
                                    <th class="text-black font-weight-bold">Fecha Desde Recibo</th>
                                    <th class="text-black font-weight-bold">Fecha Hasta Recibo</th>
                                    <th class="text-black font-weight-bold">Zona de Produc</th>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="input-group md-form my-n1 grey lighten-2">
                                            <input type="text" class="form-control" name="n_recibo" readonly value="<?= $n_recibo; ?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group md-form my-n1 grey lighten-2">
                                            <input type="text" class="form-control" name="desde_recibo" readonly value="<?= $newDesdeR; ?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group md-form my-n1 grey lighten-2">
                                            <input type="text" class="form-control" name="hasta_recibo" readonly value="<?= $newHastaR; ?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group md-form my-n1 grey lighten-2">
                                            <input type="text" class="form-control" name="z_produc" readonly value="<?= $z_produc; ?>">
                                        </div>
                                    </td>
                                </tr>

                                <?php if ($cia_pref[0]['per_gc_sum'] != null && $ramo != 35) { ?>
                                    <tr class="heavy-rain-gradient">
                                        <th class="text-black font-weight-bold">Cía Preferencial</th>
                                        <th class="text-black font-weight-bold" colspan="2">% GC Base Asesor</th>
                                        <th class="text-black font-weight-bold" colspan="2">% GC Preferencial del Asesor por Cía</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="input-group md-form my-n1 grey lighten-2">
                                                <input type="text" class="form-control" name="asesor" readonly value="<?= 'Sí'; ?>">
                                            </div>
                                        </td>
                                        <td colspan="2">
                                            <div class="input-group md-form my-n1 grey lighten-2">
                                                <input type="text" class="form-control" name="asesor" readonly value="<?= $por_gc - $cia_pref[0]['per_gc_sum']; ?>" style="background-color:rgba(26, 197, 26, 0.932);color:white">
                                            </div>
                                        </td>
                                        <td colspan="3">
                                            <div class="input-group md-form my-n1 grey lighten-2">
                                                <input type="text" class="form-control" name="asesor" readonly value="<?= $cia_pref[0]['per_gc_sum']; ?>" style="background-color:rgba(26, 197, 26, 0.932);color:white">
                                            </div>
                                        </td>
                                    </tr>
                                <?php }
                                if ($as == 0) { ?>
                                    <tr class="heavy-rain-gradient">
                                        <th class="text-black font-weight-bold" colspan="3">Asesor</th>
                                        <th class="text-black font-weight-bold" colspan="2">% GC Asesor</th>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <div class="input-group md-form my-n1 grey lighten-2">
                                                <input type="text" class="form-control" name="asesor" readonly value="<?= $u[0] . " => " . $u[1]; ?>" style="background-color:rgba(26, 197, 26, 0.932);color:white">
                                            </div>
                                        </td>
                                        <?php if ($permiso == 1) { ?>

                                            <td colspan="2" style="background-color:white">
                                                <div class="input-group md-form my-n1"><input type="text" onChange="document.links.enlace.href='e_poliza_nn.php?t_cuenta=<?= $_POST['t_cuenta']; ?>&id_poliza=<?= $id_poliza; ?>&n_poliza=<?= $n_poliza; ?>&fhoy=<?= $fhoy; ?>&emisionP=<?= $femisionP; ?>&t_cobertura=<?= $t_cobertura; ?>&desdeP=<?= $fdesdeP; ?>&hastaP=<?= $fhastaP; ?>&currency=<?= $currency; ?>&tipo_poliza=<?= $tipo_poliza; ?>&sumaA=<?= $sumaA; ?>&z_produc=<?= $z_produc; ?>&asesor=<?= $u[0]; ?>&ramo=<?= $ramo; ?>&cia=<?= $cia; ?>&titular=<?= $titular; ?>&n_recibo=<?= $n_recibo; ?>&desde_recibo=<?= $fdesde_recibo; ?>&hasta_recibo=<?= $fhasta_recibo; ?>&prima=<?= $prima; ?>&f_pago=<?= $f_pago; ?>&n_cuotas=<?= $n_cuotas; ?>&monto_cuotas=<?= $monto_cuotas; ?>&tomador=<?= $tomador; ?>&placa=<?= $placa; ?>&tipo=<?= $tipo; ?>&marca=<?= $marca; ?>&modelo=<?= $modelo; ?>&anio=<?= $anio; ?>&color=<?= $color; ?>&serial=<?= $serial; ?>&categoria=<?= $categoria; ?>&t_cuenta=<?= $_POST['t_cuenta']; ?>&obs_p=<?= $obs_p; ?>&fecha_old=<?= $fhastaP1; ?>&fecha_oldD=<?= $fdesdeP1; ?>&campos=<?= $campos; ?>&forma_pago=<?= $_POST['forma_pago']; ?>&n_tarjeta=<?= $n_tarjeta; ?>&n_tarjeta_h=<?= $n_tarjeta_h; ?>&cvv=<?= $cvv; ?>&fechaV=<?= $fechaV; ?>&titular_tarjeta=<?= $titular_tarjeta; ?>&bancoT=<?= $bancoT; ?>&alert=<?= $alert; ?>&id_tarjeta=<?= $id_tarjeta; ?>&condTar=<?= $condTar; ?>&per_gc='+this.value+'';" class="form-control validanumericos" name="per_gc" value="<?= $por_gc; ?>" require data-toggle="tooltip" data-placement="bottom" title="Ingrese % de GC del Asesor (Sólo números)"></div>
                                            </td>

                                        <?php } else { ?>
                                            <td colspan="2">
                                                <div class="input-group md-form my-n1 grey lighten-2">
                                                    <input type="text" class="form-control" name="per_gc" value="<?= $por_gc; ?>" readonly>
                                                </div>
                                            </td>
                                        <?php    } ?>

                                    </tr>
                                <?php }
                                if ($as == 1 && $tipo_r == 1) {
                                ?>
                                    <tr class="heavy-rain-gradient">
                                        <th class="text-black font-weight-bold" colspan="3">Referidor</th>
                                        <th class="text-black font-weight-bold" colspan="2">Monto GC Referidor</th>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <div class="input-group md-form my-n1 grey lighten-2">
                                                <input type="text" class="form-control" name="asesor" readonly value="<?= $u[0] . " => " . $u[1]; ?>" style="background-color:rgba(26, 197, 26, 0.932);color:white">
                                            </div>
                                        </td>
                                        <?php if ($permiso == 1) { ?>

                                            <td colspan="2" style="background-color:white">
                                                <div class="input-group md-form my-n1"><input type="text" onChange="document.links.enlace.href='e_poliza_nn.php?t_cuenta=<?= $_POST['t_cuenta']; ?>&id_poliza=<?= $id_poliza; ?>&n_poliza=<?= $n_poliza; ?>&fhoy=<?= $fhoy; ?>&emisionP=<?= $femisionP; ?>&t_cobertura=<?= $t_cobertura; ?>&desdeP=<?= $fdesdeP; ?>&hastaP=<?= $fhastaP; ?>&currency=<?= $currency; ?>&tipo_poliza=<?= $tipo_poliza; ?>&sumaA=<?= $sumaA; ?>&z_produc=<?= $z_produc; ?>&asesor=<?= $u[0]; ?>&ramo=<?= $ramo; ?>&cia=<?= $cia; ?>&titular=<?= $titular; ?>&n_recibo=<?= $n_recibo; ?>&desde_recibo=<?= $fdesde_recibo; ?>&hasta_recibo=<?= $fhasta_recibo; ?>&prima=<?= $prima; ?>&f_pago=<?= $f_pago; ?>&n_cuotas=<?= $n_cuotas; ?>&monto_cuotas=<?= $monto_cuotas; ?>&tomador=<?= $tomador; ?>&placa=<?= $placa; ?>&tipo=<?= $tipo; ?>&marca=<?= $marca; ?>&modelo=<?= $modelo; ?>&anio=<?= $anio; ?>&color=<?= $color; ?>&serial=<?= $serial; ?>&categoria=<?= $categoria; ?>&t_cuenta=<?= $_POST['t_cuenta']; ?>&obs_p=<?= $obs_p; ?>&fecha_old=<?= $fhastaP1; ?>&fecha_oldD=<?= $fdesdeP1; ?>&campos=<?= $campos; ?>&forma_pago=<?= $_POST['forma_pago']; ?>&n_tarjeta=<?= $n_tarjeta; ?>&n_tarjeta_h=<?= $n_tarjeta_h; ?>&cvv=<?= $cvv; ?>&fechaV=<?= $fechaV; ?>&titular_tarjeta=<?= $titular_tarjeta; ?>&bancoT=<?= $bancoT; ?>&alert=<?= $alert; ?>&id_tarjeta=<?= $id_tarjeta; ?>&condTar=<?= $condTar; ?>&per_gc='+this.value+'';" class="form-control validanumericos" name="per_gc" value="<?= $por_gc; ?>" require data-toggle="tooltip" data-placement="bottom" title="Ingrese Monto de GC del Referidor (Sólo números)"></div>
                                            </td>

                                        <?php } else { ?>
                                            <td colspan="2">
                                                <div class="input-group md-form my-n1 grey lighten-2">
                                                    <input type="text" class="form-control" name="per_gc" value="<?= $por_gc; ?>" readonly>
                                                </div>
                                            </td>
                                        <?php    } ?>

                                    </tr>
                                <?php
                                }
                                if ($as == 1 && $tipo_r == 2) {
                                ?>
                                    <tr class="heavy-rain-gradient">
                                        <th class="text-black font-weight-bold" colspan="3">Referidor</th>
                                        <th class="text-black font-weight-bold" colspan="2">% GC Referidor</th>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <div class="input-group md-form my-n1 grey lighten-2">
                                                <input type="text" class="form-control" name="asesor" readonly value="<?= $u[0] . " => " . $u[1]; ?>" style="background-color:rgba(26, 197, 26, 0.932);color:white">
                                            </div>
                                        </td>
                                        <?php if ($permiso == 1) { ?>

                                            <td colspan="2" style="background-color:white">
                                                <div class="input-group md-form my-n1"><input type="text" onChange="document.links.enlace.href='e_poliza_nn.php?t_cuenta=<?= $_POST['t_cuenta']; ?>&id_poliza=<?= $id_poliza; ?>&n_poliza=<?= $n_poliza; ?>&fhoy=<?= $fhoy; ?>&emisionP=<?= $femisionP; ?>&t_cobertura=<?= $t_cobertura; ?>&desdeP=<?= $fdesdeP; ?>&hastaP=<?= $fhastaP; ?>&currency=<?= $currency; ?>&tipo_poliza=<?= $tipo_poliza; ?>&sumaA=<?= $sumaA; ?>&z_produc=<?= $z_produc; ?>&asesor=<?= $u[0]; ?>&ramo=<?= $ramo; ?>&cia=<?= $cia; ?>&titular=<?= $titular; ?>&n_recibo=<?= $n_recibo; ?>&desde_recibo=<?= $fdesde_recibo; ?>&hasta_recibo=<?= $fhasta_recibo; ?>&prima=<?= $prima; ?>&f_pago=<?= $f_pago; ?>&n_cuotas=<?= $n_cuotas; ?>&monto_cuotas=<?= $monto_cuotas; ?>&tomador=<?= $tomador; ?>&placa=<?= $placa; ?>&tipo=<?= $tipo; ?>&marca=<?= $marca; ?>&modelo=<?= $modelo; ?>&anio=<?= $anio; ?>&color=<?= $color; ?>&serial=<?= $serial; ?>&categoria=<?= $categoria; ?>&t_cuenta=<?= $_POST['t_cuenta']; ?>&obs_p=<?= $obs_p; ?>&fecha_old=<?= $fhastaP1; ?>&fecha_oldD=<?= $fdesdeP1; ?>&campos=<?= $campos; ?>&forma_pago=<?= $_POST['forma_pago']; ?>&n_tarjeta=<?= $n_tarjeta; ?>&n_tarjeta_h=<?= $n_tarjeta_h; ?>&cvv=<?= $cvv; ?>&fechaV=<?= $fechaV; ?>&titular_tarjeta=<?= $titular_tarjeta; ?>&bancoT=<?= $bancoT; ?>&alert=<?= $alert; ?>&id_tarjeta=<?= $id_tarjeta; ?>&condTar=<?= $condTar; ?>&per_gc='+this.value+'';" class="form-control validanumericos" name="per_gc" value="<?= $por_gc; ?>" require data-toggle="tooltip" data-placement="bottom" title="Ingrese % de GC del Referidor (Sólo números)"></div>
                                            </td>
                                        <?php } else { ?>
                                            <td colspan="2">
                                                <div class="input-group md-form my-n1 grey lighten-2">
                                                    <input type="text" class="form-control" name="per_gc" value="<?= $por_gc; ?>" readonly>
                                                </div>
                                            </td>
                                        <?php } ?>
                                    </tr>
                                    <?php }
                                if ($as == 2 && $tipo_r == 1) {
                                ?>
                                    <tr class="heavy-rain-gradient">
                                        <th class="text-black font-weight-bold" colspan="3">Proyecto</th>
                                        <th class="text-black font-weight-bold" colspan="2">Monto GC Proyecto</th>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <div class="input-group md-form my-n1 grey lighten-2">
                                                <input type="text" class="form-control" name="asesor" readonly value="<?= $u[0] . " => " . $u[1]; ?>" style="background-color:rgba(26, 197, 26, 0.932);color:white">
                                            </div>
                                        </td>
                                        <?php if ($permiso == 1) { ?>

                                            <td colspan="2" style="background-color:white">
                                                <div class="input-group md-form my-n1"><input type="text" onChange="document.links.enlace.href='e_poliza_nn.php?t_cuenta=<?= $_POST['t_cuenta']; ?>&id_poliza=<?= $id_poliza; ?>&n_poliza=<?= $n_poliza; ?>&fhoy=<?= $fhoy; ?>&emisionP=<?= $femisionP; ?>&t_cobertura=<?= $t_cobertura; ?>&desdeP=<?= $fdesdeP; ?>&hastaP=<?= $fhastaP; ?>&currency=<?= $currency; ?>&tipo_poliza=<?= $tipo_poliza; ?>&sumaA=<?= $sumaA; ?>&z_produc=<?= $z_produc; ?>&asesor=<?= $u[0]; ?>&ramo=<?= $ramo; ?>&cia=<?= $cia; ?>&titular=<?= $titular; ?>&n_recibo=<?= $n_recibo; ?>&desde_recibo=<?= $fdesde_recibo; ?>&hasta_recibo=<?= $fhasta_recibo; ?>&prima=<?= $prima; ?>&f_pago=<?= $f_pago; ?>&n_cuotas=<?= $n_cuotas; ?>&monto_cuotas=<?= $monto_cuotas; ?>&tomador=<?= $tomador; ?>&placa=<?= $placa; ?>&tipo=<?= $tipo; ?>&marca=<?= $marca; ?>&modelo=<?= $modelo; ?>&anio=<?= $anio; ?>&color=<?= $color; ?>&serial=<?= $serial; ?>&categoria=<?= $categoria; ?>&t_cuenta=<?= $_POST['t_cuenta']; ?>&obs_p=<?= $obs_p; ?>&fecha_old=<?= $fhastaP1; ?>&fecha_oldD=<?= $fdesdeP1; ?>&campos=<?= $campos; ?>&forma_pago=<?= $_POST['forma_pago']; ?>&n_tarjeta=<?= $n_tarjeta; ?>&n_tarjeta_h=<?= $n_tarjeta_h; ?>&cvv=<?= $cvv; ?>&fechaV=<?= $fechaV; ?>&titular_tarjeta=<?= $titular_tarjeta; ?>&bancoT=<?= $bancoT; ?>&alert=<?= $alert; ?>&id_tarjeta=<?= $id_tarjeta; ?>&condTar=<?= $condTar; ?>&per_gc='+this.value+'';" class="form-control validanumericos" name="per_gc" value="<?= $por_gc; ?>" require data-toggle="tooltip" data-placement="bottom" title="Ingrese Monto de GC del Proyecto (Sólo números)"></div>
                                            </td>

                                        <?php } else { ?>
                                            <td colspan="2">
                                                <div class="input-group md-form my-n1 grey lighten-2">
                                                    <input type="text" class="form-control" name="per_gc" value="<?= $por_gc; ?>" readonly>
                                                </div>
                                            </td>
                                        <?php    } ?>

                                    </tr>
                                <?php
                                }
                                if ($as == 2 && $tipo_r == 2) {
                                ?>
                                    <tr class="heavy-rain-gradient">
                                        <th class="text-black font-weight-bold" colspan="3">Proyecto</th>
                                        <th class="text-black font-weight-bold" colspan="2">% GC Proyecto</th>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <div class="input-group md-form my-n1 grey lighten-2">
                                                <input type="text" class="form-control" name="asesor" readonly value="<?= $u[0] . " => " . $u[1]; ?>" style="background-color:rgba(26, 197, 26, 0.932);color:white">
                                            </div>
                                        </td>
                                        <?php if ($permiso == 1) { ?>

                                            <td colspan="2" style="background-color:white">
                                                <div class="input-group md-form my-n1"><input type="text" onChange="document.links.enlace.href='e_poliza_nn.php?t_cuenta=<?= $_POST['t_cuenta']; ?>&id_poliza=<?= $id_poliza; ?>&n_poliza=<?= $n_poliza; ?>&fhoy=<?= $fhoy; ?>&emisionP=<?= $femisionP; ?>&t_cobertura=<?= $t_cobertura; ?>&desdeP=<?= $fdesdeP; ?>&hastaP=<?= $fhastaP; ?>&currency=<?= $currency; ?>&tipo_poliza=<?= $tipo_poliza; ?>&sumaA=<?= $sumaA; ?>&z_produc=<?= $z_produc; ?>&asesor=<?= $u[0]; ?>&ramo=<?= $ramo; ?>&cia=<?= $cia; ?>&titular=<?= $titular; ?>&n_recibo=<?= $n_recibo; ?>&desde_recibo=<?= $fdesde_recibo; ?>&hasta_recibo=<?= $fhasta_recibo; ?>&prima=<?= $prima; ?>&f_pago=<?= $f_pago; ?>&n_cuotas=<?= $n_cuotas; ?>&monto_cuotas=<?= $monto_cuotas; ?>&tomador=<?= $tomador; ?>&placa=<?= $placa; ?>&tipo=<?= $tipo; ?>&marca=<?= $marca; ?>&modelo=<?= $modelo; ?>&anio=<?= $anio; ?>&color=<?= $color; ?>&serial=<?= $serial; ?>&categoria=<?= $categoria; ?>&t_cuenta=<?= $_POST['t_cuenta']; ?>&obs_p=<?= $obs_p; ?>&fecha_old=<?= $fhastaP1; ?>&fecha_oldD=<?= $fdesdeP1; ?>&campos=<?= $campos; ?>&forma_pago=<?= $_POST['forma_pago']; ?>&n_tarjeta=<?= $n_tarjeta; ?>&n_tarjeta_h=<?= $n_tarjeta_h; ?>&cvv=<?= $cvv; ?>&fechaV=<?= $fechaV; ?>&titular_tarjeta=<?= $titular_tarjeta; ?>&bancoT=<?= $bancoT; ?>&alert=<?= $alert; ?>&id_tarjeta=<?= $id_tarjeta; ?>&condTar=<?= $condTar; ?>&per_gc='+this.value+'';" class="form-control validanumericos" name="per_gc" value="<?= $por_gc; ?>" require data-toggle="tooltip" data-placement="bottom" title="Ingrese % de GC del Proyecto (Sólo números)"></div>
                                            </td>
                                        <?php } else { ?>
                                            <td colspan="2">
                                                <div class="input-group md-form my-n1 grey lighten-2">
                                                    <input type="text" class="form-control" name="per_gc" value="<?= $por_gc; ?>" readonly>
                                                </div>
                                            </td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>





                                <tr class="heavy-rain-gradient">
                                    <th class="text-black font-weight-bold" colspan="2">N° ID Titular</th>
                                    <th class="text-black font-weight-bold" colspan="3">Nombre(s) y Apellido(s) Titular</th>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="input-group md-form my-n1 grey lighten-2">
                                            <input type="text" class="form-control" name="titular" readonly value="<?= $titular; ?>">
                                        </div>
                                    </td>
                                    <td colspan="3">
                                        <div class="input-group md-form my-n1 grey lighten-2">
                                            <input type="text" class="form-control" name="n_titular" readonly value="<?= ($idtitular[0]['nombre_t'] . " " . $idtitular[0]['apellido_t']); ?>" style="background-color:rgba(26, 197, 26, 0.932);color:white">
                                        </div>
                                    </td>
                                </tr>

                                <tr class="heavy-rain-gradient">
                                    <th class="text-black font-weight-bold" colspan="2">N° ID Tomador</th>
                                    <th class="text-black font-weight-bold" colspan="3">Nombre(s) y Apellido(s) Tomador</th>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="input-group md-form my-n1 grey lighten-2">
                                            <input type="text" class="form-control" name="tomador" readonly value="<?= $idtomador[0]['ci']; ?>">
                                        </div>
                                    </td>
                                    <td colspan="3">
                                        <div class="input-group md-form my-n1 grey lighten-2">
                                            <input type="text" class="form-control" name="n_tomador" readonly value="<?= ($idtomador[0]['nombre_t'] . " " . $idtomador[0]['apellido_t']); ?>">
                                        </div>
                                    </td>
                                </tr>

                                <?php if ($ramo == 2 || $ramo == 25) { ?>
                                    <tr class="heavy-rain-gradient">
                                        <th class="text-black font-weight-bold">Placa</th>
                                        <th class="text-black font-weight-bold">Marca</th>
                                        <th class="text-black font-weight-bold">Modelo</th>
                                        <th class="text-black font-weight-bold">Tipo</th>
                                        <th class="text-black font-weight-bold">Año</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="input-group md-form my-n1 grey lighten-2">
                                                <input type="text" class="form-control" name="placa" readonly value="<?= $placa; ?>">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group md-form my-n1 grey lighten-2">
                                                <input type="text" class="form-control" name="marca" readonly value="<?= $marca; ?>">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group md-form my-n1 grey lighten-2">
                                                <input type="text" class="form-control" name="modelo" readonly value="<?= $modelo; ?>">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group md-form my-n1 grey lighten-2">
                                                <input type="text" class="form-control" name="tipo" readonly value="<?= $tipo; ?>">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group md-form my-n1 grey lighten-2">
                                                <input type="text" class="form-control" name="anio" readonly value="<?= $anio; ?>">
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>

                                <tr class="heavy-rain-gradient">
                                    <th class="text-black font-weight-bold" colspan="5">Observaciones</th>
                                </tr>
                                <tr>
                                    <td colspan="5">
                                        <div class="input-group md-form my-n1 grey lighten-2">
                                            <input type="text" class="form-control" name="obs_p" readonly value="<?= $obs_p; ?>">
                                        </div>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                    <center>
                        <a name="enlace" href="e_poliza_nn.php?id_poliza=<?= $id_poliza; ?>&n_poliza=<?= $n_poliza; ?>&fhoy=<?= $fhoy; ?>&emisionP=<?= $femisionP; ?>&t_cobertura=<?= $t_cobertura; ?>&desdeP=<?= $fdesdeP; ?>&hastaP=<?= $fhastaP; ?>&currency=<?= $currency; ?>&tipo_poliza=<?= $tipo_poliza; ?>&sumaA=<?= $sumaA; ?>&z_produc=<?= $z_produc; ?>&asesor=<?= $u[0]; ?>&ramo=<?= $ramo; ?>&cia=<?= $cia; ?>&titular=<?= $titular; ?>&n_recibo=<?= $n_recibo; ?>&desde_recibo=<?= $fdesde_recibo; ?>&hasta_recibo=<?= $fhasta_recibo; ?>&prima=<?= $prima; ?>&f_pago=<?= $f_pago; ?>&n_cuotas=<?= $n_cuotas; ?>&monto_cuotas=<?= $monto_cuotas; ?>&tomador=<?= $tomador; ?>&placa=<?= $placa; ?>&tipo=<?= $tipo; ?>&marca=<?= $marca; ?>&modelo=<?= $modelo; ?>&anio=<?= $anio; ?>&color=<?= $color; ?>&serial=<?= $serial; ?>&categoria=<?= $categoria; ?>&per_gc=<?= $por_gc; ?>&t_cuenta=<?= $_POST['t_cuenta']; ?>&obs_p=<?= $obs_p; ?>&fecha_old=<?= $fhastaP1; ?>&fecha_oldD=<?= $fdesdeP1; ?>&campos=<?= $campos; ?>&forma_pago=<?= $_POST['forma_pago']; ?>&n_tarjeta=<?= $n_tarjeta; ?>&n_tarjeta_h=<?= $n_tarjeta_h; ?>&cvv=<?= $cvv; ?>&fechaV=<?= $fechaV; ?>&titular_tarjeta=<?= $titular_tarjeta; ?>&bancoT=<?= $bancoT; ?>&alert=<?= $alert; ?>&id_tarjeta=<?= $id_tarjeta; ?>&condTar=<?= $condTar; ?>" class="btn blue-gradient btn-lg btn-rounded">Confirmar</a>
                    </center>
                </div>
            </form>
        </div>
    </div>





    <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer_b.php'; ?>

    <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer.php'; ?>

    <script src="../../assets/view/b_poliza.js"></script>

    <script>

    </script>

</body>

</html>