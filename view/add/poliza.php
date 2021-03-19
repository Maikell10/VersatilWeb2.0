<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'add/poliza';

require_once '../../Controller/Poliza.php';
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
                <div class="ml-5 mr-5 text-center">
                    <h1 class="font-weight-bold"><i class="fas fa-book" aria-hidden="true"></i> Previsualizar Nueva Póliza</h1>
                </div>
                <br>

                <div class="col-md-10 mx-auto">
                    <form class="form-horizontal" id="frmnuevo" action="poliza.php" method="post">
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
                                    <div class="form-group col-md-12">
                                        <tr class="grey lighten-3">
                                            <td colspan="2">
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control" id="n_poliza" name="n_poliza" value="<?= $n_poliza; ?>" style="background-color:rgba(26, 197, 26, 0.932);color:white" readonly>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" id="desdeP" name="desdeP" class="form-control" value="<?= $fdesdeP; ?>" readonly>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" id="hastaP" name="hastaP" class="form-control" value="<?= $fhastaP; ?>" readonly>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control" name="tipo_poliza" readonly value="<?= $tipo_poliza_print; ?>">
                                                </div>
                                            </td>
                                        </tr>

                                        <tr class="heavy-rain-gradient">
                                            <td class="text-black font-weight-bold">Ramo</td>
                                            <td colspan="2" class="text-black font-weight-bold">Compañía</td>
                                            <td class="text-black font-weight-bold">Tipo de Cuenta</td>
                                            <th hidden>Tipo de Cobertura</th>
                                            <th class="text-black font-weight-bold">Frecuencia de Renovación</th>
                                        </tr>
                                        <tr class="grey lighten-3">
                                            <td>
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control" name="ramo" readonly value="<?= $nombre_ramo[0]['nramo']; ?>">
                                                </div>
                                            </td>
                                            <td colspan="2">
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control" name="cia" readonly value="<?= $nombre_cia[0]['nomcia']; ?>" style="background-color:rgba(26, 197, 26, 0.932);color:white">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control" name="t_cuenta" readonly value="<?= $t_cuenta; ?>">
                                                </div>
                                            </td>
                                            <td hidden><input type="text" class="form-control" name="t_cobertura" readonly value="<?= $t_cobertura; ?>"></td>
                                            <td>
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control" name="frec_renov" readonly value="<?= $frec_renov_w; ?>">
                                                </div>
                                            </td>
                                        </tr>

                                        <tr class="heavy-rain-gradient">
                                            <th class="text-black font-weight-bold">Suma Asegurada</th>
                                            <th class="text-black font-weight-bold">Prima Suscrita</th>
                                            <th class="text-black font-weight-bold">Periocidad de Pago</th>
                                            <th class="text-black font-weight-bold">N° de Cuotas</th>
                                            <th class="text-black font-weight-bold">Monto Cuotas</th>
                                        </tr>
                                        <tr class="grey lighten-3">
                                            <td>
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control" name="sumaA" readonly value="<?= $currencyl . number_format($sumaA, 2); ?>">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control" name="prima" readonly value="<?= $currencyl . number_format($prima, 2); ?>" style="background-color:rgba(228, 66, 66, 0.87);color:white">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control" name="f_pago" readonly value="<?= $f_pago; ?>">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control" name="n_cuotas" readonly value="<?= $n_cuotas; ?>">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group md-form my-n1">
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
                                        <tr class="grey lighten-3">
                                            <td colspan="2">
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control" name="forma_pago" readonly value="<?= $forma_pago; ?>">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control" name="n_tarjeta" readonly value="<?= $n_tarjeta; ?>">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control" name="cvv" readonly value="<?= $cvv; ?>">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control" name="fechaV" readonly value="<?= $fechaV; ?>">
                                                </div>
                                            </td>
                                        </tr>

                                        <tr class="heavy-rain-gradient">
                                            <th class="text-black font-weight-bold" colspan="3">Nombre Tarjetahabiente</th>
                                            <th class="text-black font-weight-bold" colspan="2">Banco</th>
                                        </tr>
                                        <tr class="grey lighten-3">
                                            <td colspan="3">
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control" name="titular_tarjeta" readonly value="<?= $titular_tarjeta; ?>">
                                                </div>
                                            </td>
                                            <td colspan="2">
                                                <div class="input-group md-form my-n1">
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
                                        <tr class="grey lighten-3">
                                            <td colspan="2">
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control" name="n_recibo" readonly value="<?= $n_recibo; ?>">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control" name="desde_recibo" readonly value="<?= $fdesde_recibo; ?>">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control" name="hasta_recibo" readonly value="<?= $fhasta_recibo; ?>">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control" name="z_produc" readonly value="<?= $z_produc; ?>">
                                                </div>
                                            </td>
                                        </tr>

                                        <?php if ($cia_pref[0]['per_gc_sum'] != null && $ramo != 35 && $as == 0) { ?>
                                            <tr class="heavy-rain-gradient">
                                                <th class="text-black font-weight-bold">Cía Preferencial</th>
                                                <th class="text-black font-weight-bold" colspan="2">% GC Base Asesor</th>
                                                <th class="text-black font-weight-bold" colspan="2">% GC Preferencial del Asesor por Cía</th>
                                            </tr>
                                            <tr class="grey lighten-3">
                                                <td>
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control" name="asesor" readonly value="<?= 'Sí'; ?>">
                                                    </div>
                                                </td>
                                                <td colspan="2">
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control" name="asesor" readonly value="<?= $per_gc - $cia_pref[0]['per_gc_sum']; ?>" style="background-color:rgba(26, 197, 26, 0.932);color:white">
                                                    </div>
                                                </td>
                                                <td colspan="3">
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control" name="asesor" readonly value="<?= $cia_pref[0]['per_gc_sum']; ?>" style="background-color:rgba(26, 197, 26, 0.932);color:white">
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php }
                                        if ($as == 0) {
                                        ?>

                                            <tr class="heavy-rain-gradient">
                                                <th class="text-black font-weight-bold" colspan="3">Asesor</th>
                                                <th class="text-black font-weight-bold" colspan="2">% GC Asesor</th>
                                            </tr>
                                            <tr class="grey lighten-3">
                                                <td colspan="3">
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control" name="asesor" readonly value="<?= $u[0] . " => " . $u[1]; ?>" style="background-color:rgba(26, 197, 26, 0.932);color:white">
                                                    </div>
                                                </td>
                                                <?php if ($_SESSION['id_permiso'] == 1) { ?>
                                                    <td colspan="2" style="background-color:white">
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" onChange="document.links.enlace.href='poliza_n.php?n_poliza=<?= $n_poliza; ?>&fhoy=<?= $fhoy; ?>&emisionP=<?= $femisionP; ?>&t_cobertura=<?= $t_cobertura; ?>&desdeP=<?= $fdesdeP; ?>&hastaP=<?= $fhastaP; ?>&currency=<?= $currency; ?>&tipo_poliza=<?= $tipo_poliza; ?>&sumaA=<?= $sumaA; ?>&z_produc=<?= $z_produc; ?>&asesor=<?= $u[0]; ?>&ramo=<?= $ramo; ?>&cia=<?= $cia; ?>&titular=<?= $titular; ?>&n_recibo=<?= $n_recibo; ?>&desde_recibo=<?= $fdesde_recibo; ?>&hasta_recibo=<?= $fhasta_recibo; ?>&prima=<?= $prima; ?>&f_pago=<?= $f_pago; ?>&n_cuotas=<?= $n_cuotas; ?>&monto_cuotas=<?= $monto_cuotas; ?>&tomador=<?= $tomador; ?>&placa=<?= $placa; ?>&tipo=<?= $tipo; ?>&marca=<?= $marca; ?>&modelo=<?= $modelo; ?>&anio=<?= $anio; ?>&color=<?= $color; ?>&serial=<?= $serial; ?>&categoria=<?= $categoria; ?>&t_cuenta=<?= $_POST['t_cuenta']; ?>&obs=<?= $obs; ?>&forma_pago=<?= $_POST['forma_pago']; ?>&n_tarjeta=<?= $n_tarjeta; ?>&cvv=<?= $cvv; ?>&fechaV=<?= $fechaV; ?>&titular_tarjeta=<?= $titular_tarjeta; ?>&bancoT=<?= $bancoT; ?>&alert=<?= $alert; ?>&id_tarjeta=<?= $id_tarjeta; ?>&frec_renov=<?= $frec_renov; ?>&condTar=<?= $condTar; ?>&asesor_ind='+this.value+'';" class="form-control validanumericos" name="per_gc" value="<?= $per_gc; ?>" require data-toggle="tooltip" data-placement="bottom" title="Ingrese % de GC del Asesor (Sólo números)">
                                                        </div>
                                                    </td>
                                                <?php } else { ?>
                                                    <td colspan="2">
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" name="per_gc" value="<?= $per_gc; ?>" readonly>
                                                        </div>
                                                    </td>
                                                <?php } ?>
                                            </tr>
                                        <?php }
                                        if ($as == 1 && $tipo_r == 1) {
                                        ?>

                                            <tr class="heavy-rain-gradient">
                                                <th class="text-black font-weight-bold" colspan="3">Referidor</th>
                                                <th class="text-black font-weight-bold" colspan="2">Monto GC Referidor</th>
                                            </tr>
                                            <tr class="grey lighten-3">
                                                <td colspan="3">
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control" name="asesor" readonly value="<?= $u[0] . " => " . $u[1]; ?>" style="background-color:rgba(26, 197, 26, 0.932);color:white">
                                                    </div>
                                                </td>
                                                <?php if ($_SESSION['id_permiso'] == 1) { ?>
                                                    <td colspan="2" style="background-color:white">
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" onChange="document.links.enlace.href='poliza_n.php?n_poliza=<?= $n_poliza; ?>&fhoy=<?= $fhoy; ?>&emisionP=<?= $femisionP; ?>&t_cobertura=<?= $t_cobertura; ?>&desdeP=<?= $fdesdeP; ?>&hastaP=<?= $fhastaP; ?>&currency=<?= $currency; ?>&tipo_poliza=<?= $tipo_poliza; ?>&sumaA=<?= $sumaA; ?>&z_produc=<?= $z_produc; ?>&asesor=<?= $u[0]; ?>&ramo=<?= $ramo; ?>&cia=<?= $cia; ?>&titular=<?= $titular; ?>&n_recibo=<?= $n_recibo; ?>&desde_recibo=<?= $fdesde_recibo; ?>&hasta_recibo=<?= $fhasta_recibo; ?>&prima=<?= $prima; ?>&f_pago=<?= $f_pago; ?>&n_cuotas=<?= $n_cuotas; ?>&monto_cuotas=<?= $monto_cuotas; ?>&tomador=<?= $tomador; ?>&placa=<?= $placa; ?>&tipo=<?= $tipo; ?>&marca=<?= $marca; ?>&modelo=<?= $modelo; ?>&anio=<?= $anio; ?>&color=<?= $color; ?>&serial=<?= $serial; ?>&categoria=<?= $categoria; ?>&t_cuenta=<?= $_POST['t_cuenta']; ?>&obs=<?= $obs; ?>&forma_pago=<?= $_POST['forma_pago']; ?>&n_tarjeta=<?= $n_tarjeta; ?>&cvv=<?= $cvv; ?>&fechaV=<?= $fechaV; ?>&titular_tarjeta=<?= $titular_tarjeta; ?>&bancoT=<?= $bancoT; ?>&alert=<?= $alert; ?>&id_tarjeta=<?= $id_tarjeta; ?>&frec_renov=<?= $frec_renov; ?>&condTar=<?= $condTar; ?>&asesor_ind='+this.value+'';" class="form-control validanumericos" name="per_gc" value="<?= $per_gc; ?>" require data-toggle="tooltip" data-placement="bottom" title="Ingrese Monto de GC del Referidor (Sólo números)">
                                                        </div>
                                                    </td>
                                                <?php } else { ?>
                                                    <td colspan="2">
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" name="per_gc" value="<?= $per_gc; ?>" readonly>
                                                        </div>
                                                    </td>
                                                <?php } ?>
                                            </tr>
                                        <?php }
                                        if ($as == 1 && $tipo_r == 2) {
                                        ?>

                                            <tr class="heavy-rain-gradient">
                                                <th class="text-black font-weight-bold" colspan="3">Referidor</th>
                                                <th class="text-black font-weight-bold" colspan="2">% GC Referidor</th>
                                            </tr>
                                            <tr class="grey lighten-3">
                                                <td colspan="3">
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control" name="asesor" readonly value="<?= $u[0] . " => " . $u[1]; ?>" style="background-color:rgba(26, 197, 26, 0.932);color:white">
                                                    </div>
                                                </td>
                                                <?php if ($_SESSION['id_permiso'] == 1) { ?>
                                                    <td colspan="2" style="background-color:white">
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" onChange="document.links.enlace.href='poliza_n.php?n_poliza=<?= $n_poliza; ?>&fhoy=<?= $fhoy; ?>&emisionP=<?= $femisionP; ?>&t_cobertura=<?= $t_cobertura; ?>&desdeP=<?= $fdesdeP; ?>&hastaP=<?= $fhastaP; ?>&currency=<?= $currency; ?>&tipo_poliza=<?= $tipo_poliza; ?>&sumaA=<?= $sumaA; ?>&z_produc=<?= $z_produc; ?>&asesor=<?= $u[0]; ?>&ramo=<?= $ramo; ?>&cia=<?= $cia; ?>&titular=<?= $titular; ?>&n_recibo=<?= $n_recibo; ?>&desde_recibo=<?= $fdesde_recibo; ?>&hasta_recibo=<?= $fhasta_recibo; ?>&prima=<?= $prima; ?>&f_pago=<?= $f_pago; ?>&n_cuotas=<?= $n_cuotas; ?>&monto_cuotas=<?= $monto_cuotas; ?>&tomador=<?= $tomador; ?>&placa=<?= $placa; ?>&tipo=<?= $tipo; ?>&marca=<?= $marca; ?>&modelo=<?= $modelo; ?>&anio=<?= $anio; ?>&color=<?= $color; ?>&serial=<?= $serial; ?>&categoria=<?= $categoria; ?>&t_cuenta=<?= $_POST['t_cuenta']; ?>&obs=<?= $obs; ?>&forma_pago=<?= $_POST['forma_pago']; ?>&n_tarjeta=<?= $n_tarjeta; ?>&cvv=<?= $cvv; ?>&fechaV=<?= $fechaV; ?>&titular_tarjeta=<?= $titular_tarjeta; ?>&bancoT=<?= $bancoT; ?>&alert=<?= $alert; ?>&id_tarjeta=<?= $id_tarjeta; ?>&frec_renov=<?= $frec_renov; ?>&condTar=<?= $condTar; ?>&asesor_ind='+this.value+'';" class="form-control validanumericos" name="per_gc" value="<?= $per_gc; ?>" require data-toggle="tooltip" data-placement="bottom" title="Ingrese % de GC del Referidor (Sólo números)">
                                                        </div>
                                                    </td>
                                                <?php } else { ?>
                                                    <td colspan="2">
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" name="per_gc" value="<?= $per_gc; ?>" readonly>
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
                                            <tr class="grey lighten-3">
                                                <td colspan="3">
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control" name="asesor" readonly value="<?= $u[0] . " => " . $u[1]; ?>" style="background-color:rgba(26, 197, 26, 0.932);color:white">
                                                    </div>
                                                </td>
                                                <?php if ($_SESSION['id_permiso'] == 1) { ?>
                                                    <td colspan="2" style="background-color:white">
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" onChange="document.links.enlace.href='poliza_n.php?n_poliza=<?= $n_poliza; ?>&fhoy=<?= $fhoy; ?>&emisionP=<?= $femisionP; ?>&t_cobertura=<?= $t_cobertura; ?>&desdeP=<?= $fdesdeP; ?>&hastaP=<?= $fhastaP; ?>&currency=<?= $currency; ?>&tipo_poliza=<?= $tipo_poliza; ?>&sumaA=<?= $sumaA; ?>&z_produc=<?= $z_produc; ?>&asesor=<?= $u[0]; ?>&ramo=<?= $ramo; ?>&cia=<?= $cia; ?>&titular=<?= $titular; ?>&n_recibo=<?= $n_recibo; ?>&desde_recibo=<?= $fdesde_recibo; ?>&hasta_recibo=<?= $fhasta_recibo; ?>&prima=<?= $prima; ?>&f_pago=<?= $f_pago; ?>&n_cuotas=<?= $n_cuotas; ?>&monto_cuotas=<?= $monto_cuotas; ?>&tomador=<?= $tomador; ?>&placa=<?= $placa; ?>&tipo=<?= $tipo; ?>&marca=<?= $marca; ?>&modelo=<?= $modelo; ?>&anio=<?= $anio; ?>&color=<?= $color; ?>&serial=<?= $serial; ?>&categoria=<?= $categoria; ?>&t_cuenta=<?= $_POST['t_cuenta']; ?>&obs=<?= $obs; ?>&forma_pago=<?= $_POST['forma_pago']; ?>&n_tarjeta=<?= $n_tarjeta; ?>&cvv=<?= $cvv; ?>&fechaV=<?= $fechaV; ?>&titular_tarjeta=<?= $titular_tarjeta; ?>&bancoT=<?= $bancoT; ?>&alert=<?= $alert; ?>&id_tarjeta=<?= $id_tarjeta; ?>&frec_renov=<?= $frec_renov; ?>&condTar=<?= $condTar; ?>&asesor_ind='+this.value+'';" class="form-control validanumericos" name="per_gc" value="<?= $per_gc; ?>" require data-toggle="tooltip" data-placement="bottom" title="Ingrese Monto de GC del Proyecto (Sólo números)">
                                                        </div>
                                                    </td>
                                                <?php } else { ?>
                                                    <td colspan="2">
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" name="per_gc" value="<?= $per_gc; ?>" readonly>
                                                        </div>
                                                    </td>
                                                <?php } ?>
                                            </tr>
                                        <?php }
                                        if ($as == 2 && $tipo_r == 2) {
                                        ?>

                                            <tr class="heavy-rain-gradient">
                                                <th class="text-black font-weight-bold" colspan="3">Proyecto</th>
                                                <th class="text-black font-weight-bold" colspan="2">% GC Proyecto</th>
                                            </tr>
                                            <tr class="grey lighten-3">
                                                <td colspan="3">
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control" name="asesor" readonly value="<?= $u[0] . " => " . $u[1]; ?>" style="background-color:rgba(26, 197, 26, 0.932);color:white">
                                                    </div>
                                                </td>
                                                <?php if ($_SESSION['id_permiso'] == 1) { ?>
                                                    <td colspan="2" style="background-color:white">
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" onChange="document.links.enlace.href='poliza_n.php?n_poliza=<?= $n_poliza; ?>&fhoy=<?= $fhoy; ?>&emisionP=<?= $femisionP; ?>&t_cobertura=<?= $t_cobertura; ?>&desdeP=<?= $fdesdeP; ?>&hastaP=<?= $fhastaP; ?>&currency=<?= $currency; ?>&tipo_poliza=<?= $tipo_poliza; ?>&sumaA=<?= $sumaA; ?>&z_produc=<?= $z_produc; ?>&asesor=<?= $u[0]; ?>&ramo=<?= $ramo; ?>&cia=<?= $cia; ?>&titular=<?= $titular; ?>&n_recibo=<?= $n_recibo; ?>&desde_recibo=<?= $fdesde_recibo; ?>&hasta_recibo=<?= $fhasta_recibo; ?>&prima=<?= $prima; ?>&f_pago=<?= $f_pago; ?>&n_cuotas=<?= $n_cuotas; ?>&monto_cuotas=<?= $monto_cuotas; ?>&tomador=<?= $tomador; ?>&placa=<?= $placa; ?>&tipo=<?= $tipo; ?>&marca=<?= $marca; ?>&modelo=<?= $modelo; ?>&anio=<?= $anio; ?>&color=<?= $color; ?>&serial=<?= $serial; ?>&categoria=<?= $categoria; ?>&t_cuenta=<?= $_POST['t_cuenta']; ?>&obs=<?= $obs; ?>&forma_pago=<?= $_POST['forma_pago']; ?>&n_tarjeta=<?= $n_tarjeta; ?>&cvv=<?= $cvv; ?>&fechaV=<?= $fechaV; ?>&titular_tarjeta=<?= $titular_tarjeta; ?>&bancoT=<?= $bancoT; ?>&alert=<?= $alert; ?>&id_tarjeta=<?= $id_tarjeta; ?>&frec_renov=<?= $frec_renov; ?>&condTar=<?= $condTar; ?>&asesor_ind='+this.value+'';" class="form-control validanumericos" name="per_gc" value="<?= $per_gc; ?>" require data-toggle="tooltip" data-placement="bottom" title="Ingrese % de GC del Proyecto (Sólo números)">
                                                        </div>
                                                    </td>
                                                <?php } else { ?>
                                                    <td colspan="2">
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" name="per_gc" value="<?= $per_gc; ?>" readonly>
                                                        </div>
                                                    </td>
                                                <?php } ?>
                                            </tr>
                                        <?php } ?>






                                        <tr class="heavy-rain-gradient">
                                            <th class="text-black font-weight-bold" colspan="2">N° ID Titular</th>
                                            <th class="text-black font-weight-bold" colspan="3">Nombre(s) y Apellido(s) Titular</th>
                                        </tr>
                                        <tr class="grey lighten-3">
                                            <td colspan="2">
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control" name="titular" readonly value="<?= $titular; ?>">
                                                </div>
                                            </td>
                                            <td colspan="3">
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control" name="n_titular" readonly value="<?= $idtitular[0]['nombre_t'] . " " . $idtitular[0]['apellido_t']; ?>" style="background-color:rgba(26, 197, 26, 0.932);color:white">
                                                </div>
                                            </td>
                                        </tr>

                                        <tr class="heavy-rain-gradient">
                                            <th class="text-black font-weight-bold" colspan="2">N° ID Tomador</th>
                                            <th class="text-black font-weight-bold" colspan="3">Nombre(s) y Apellido(s) Tomador</th>
                                        </tr>
                                        <tr class="grey lighten-3">
                                            <td colspan="2">
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control" name="tomador" readonly value="<?= $idtomador[0]['ci']; ?>">
                                                </div>
                                            </td>
                                            <td colspan="3">
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control" name="n_tomador" readonly value="<?= $idtomador[0]['nombre_t'] . " " . $idtomador[0]['apellido_t']; ?>">
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
                                            <tr class="grey lighten-3">
                                                <td>
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control" name="placa" readonly value="<?= $placa; ?>">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control" name="marca" readonly value="<?= $marca; ?>">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control" name="modelo" readonly value="<?= $modelo; ?>">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control" name="tipo" readonly value="<?= $tipo; ?>">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control" name="anio" readonly value="<?= $anio; ?>">
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>

                                        <tr class="heavy-rain-gradient">
                                            <th class="text-black font-weight-bold" colspan="5">Observaciones</th>
                                        </tr>
                                        <tr class="grey lighten-3">
                                            <td colspan="5">
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control" name="obs" readonly value="<?= $obs; ?>">
                                                </div>
                                            </td>
                                        </tr>

                                    </div>
                                </tbody>
                            </table>
                        </div>



                        <center>
                            <a name="enlace" href="poliza_n.php?n_poliza=<?= $n_poliza; ?>&fhoy=<?= $fhoy; ?>&emisionP=<?= $femisionP; ?>&t_cobertura=<?= $t_cobertura; ?>&desdeP=<?= $fdesdeP; ?>&hastaP=<?= $fhastaP; ?>&currency=<?= $currency; ?>&tipo_poliza=<?= $tipo_poliza; ?>&sumaA=<?= $sumaA; ?>&z_produc=<?= $z_produc; ?>&asesor=<?= $u[0]; ?>&ramo=<?= $ramo; ?>&cia=<?= $cia; ?>&titular=<?= $titular; ?>&n_recibo=<?= $n_recibo; ?>&desde_recibo=<?= $fdesde_recibo; ?>&hasta_recibo=<?= $fhasta_recibo; ?>&prima=<?= $prima; ?>&f_pago=<?= $f_pago; ?>&n_cuotas=<?= $n_cuotas; ?>&monto_cuotas=<?= $monto_cuotas; ?>&tomador=<?= $tomador; ?>&placa=<?= $placa; ?>&tipo=<?= $tipo; ?>&marca=<?= $marca; ?>&modelo=<?= $modelo; ?>&anio=<?= $anio; ?>&color=<?= $color; ?>&serial=<?= $serial; ?>&categoria=<?= $categoria; ?>&asesor_ind=<?= $per_gc; ?>&t_cuenta=<?= $_POST['t_cuenta']; ?>&obs=<?= $obs; ?>&forma_pago=<?= $_POST['forma_pago']; ?>&n_tarjeta=<?= $n_tarjeta; ?>&cvv=<?= $cvv; ?>&fechaV=<?= $fechaV; ?>&titular_tarjeta=<?= $titular_tarjeta; ?>&bancoT=<?= $bancoT; ?>&alert=<?= $alert; ?>&id_tarjeta=<?= $id_tarjeta; ?>&frec_renov=<?= $frec_renov; ?>&condTar=<?= $condTar; ?>" class="btn blue-gradient btn-lg btn-rounded">Confirmar</a>
                        </center>

                    </form>


                </div>
                <br><br><br>
            </div>

        </div>





        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer.php'; ?>

        <script src="../../assets/view/b_poliza.js"></script>

        <script>



        </script>
</body>

</html>