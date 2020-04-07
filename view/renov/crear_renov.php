<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}

//$pag = 'v_poliza';

require_once '../../Controller/Poliza.php';

//----Obtengo el permiso del usuario
$permiso = $_SESSION['id_permiso'];
//----------------------

$id_poliza = $_GET['id_poliza'];

$poliza = $obj->get_poliza_total_by_id($id_poliza);

if ($poliza[0]['id_poliza'] == 0) {
    $poliza = $obj->get_poliza_total1_by_id($id_poliza);
}
if ($poliza[0]['id_poliza'] == 0) {
    $poliza = $obj->get_poliza_total2_by_id($id_poliza);
}

$tomador = $obj->get_element_by_id('titular', 'id_titular', $poliza[0]['id_tomador']);
$currency = ($poliza[0]['currency'] == 1) ? "$ " : "Bs ";

$ramo = $obj->get_element('dramo', 'cod_ramo');
$cia = $obj->get_element('dcia', 'nomcia');
$asesor = $obj->get_ejecutivo();
$usuario = $obj->get_element_by_id('usuarios', 'seudonimo', $_SESSION['seudonimo']);
$vehiculo = $obj->get_element_by_id('dveh', 'idveh', $poliza[0]['id_poliza']);
$newfechaV = date("d-m-Y", strtotime($poliza[0]['fechaV']));

$newDesdeP = date("d-m-Y", strtotime($poliza[0]['f_desdepoliza']."+ 1 year"));
$newHastaP = date("d-m-Y", strtotime($poliza[0]['f_hastapoliza']."+ 1 year"));

$newDesdeR = date("d-m-Y", strtotime($poliza[0]['f_desderecibo']."+ 1 year"));
$newHastaR = date("d-m-Y", strtotime($poliza[0]['f_hastarecibo']."+ 1 year"));
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

            <div class="card-header p-5 animated bounceInDown">
                <div class="ml-5 mr-5 text-center">
                    <h1 class="font-weight-bold text-danger"><i class="fas fa-book" aria-hidden="true"></i> Renovar Póliza</h1>
                    <h1 class="font-weight-bold">Cliente: <?= utf8_encode($poliza[0]['nombre_t'] . " " . $poliza[0]['apellido_t']); ?></h1>
                    <h2 class="font-weight-bold">Póliza N°: <?= $poliza[0]['cod_poliza']; ?></h2>
                    <?php $asesorr = $poliza[0]['cod'] . " -> " . $poliza[0]['nombre']; ?>
                    <h3 class="font-weight-bold">Asesor: <?= utf8_encode($asesorr); ?></h3>
                </div>
            </div>

            <!-- Comienzo tabla -->
            <form class="form-horizontal" id="frmnuevo" action="e_poliza_n.php" method="post">
                <div class="card-body p-5">
                    <div class="table-responsive-xl">
                        <table class="table" width="100%">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th>N° de Póliza *</th>
                                    <th>Fecha Desde Seguro *</th>
                                    <th>Fecha Hasta Seguro *</th>
                                    <th>Tipo de Póliza *</th>
                                    <th hidden>id Póliza</th>
                                </tr>
                            </thead>
                            <tbody>
                                <div class="form-group col-md-12">
                                    <tr style="background-color: white">
                                        <?php
                                        if ($permiso == 1) {
                                        ?>
                                            <td style="background-color:white">
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control" id="n_poliza" name="n_poliza" value="<?= $poliza[0]['cod_poliza']; ?>">
                                                </div>
                                            </td>
                                        <?php
                                        } else {
                                        ?>
                                            <td>
                                                <div class="input-group md-form my-n1 grey lighten-2">
                                                    <input type="text" class="form-control" id="n_poliza" name="n_poliza" value="<?= $poliza[0]['cod_poliza']; ?>" readonly>
                                                </div>
                                            </td>
                                        <?php
                                        }
                                        ?>
                                        <td>
                                            <div class="input-group md-form my-n1">
                                                <input onchange="cargarFechaDesde(this)" type="text" class="form-control datepicker" id="desdeP" name="desdeP" required autocomplete="off" value="<?= $newDesdeP; ?>">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group md-form my-n1">
                                                <input type="text" class="form-control datepicker" id="hastaP" name="hastaP" required autocomplete="off" value="<?= $newHastaP; ?>">
                                            </div>
                                        </td>
                                        <td><select class="mdb-select md-form colorful-select dropdown-primary my-n2" id="tipo_poliza" name="tipo_poliza" required data-toggle="tooltip" data-placement="bottom" title="Seleccione un elemento de la lista">
                                                <option value="">Seleccione Tipo Póliza</option>
                                                <option value="1">Primer Año</option>
                                                <option value="2">Renovación</option>
                                                <option value="3">Traspaso de Cartera</option>
                                                <option value="4">Anexos</option>
                                                <option value="5">Revalorización</option>
                                            </select>
                                        </td>

                                        <td hidden><input type="text" class="form-control" id="id_poliza" name="id_poliza" value="<?= $id_poliza; ?>"></td>
                                        <td hidden><input type="text" class="form-control" id="id_tpoliza" name="id_tpoliza" value="2"></td>
                                        <!-- Hidden -->
                                        <td hidden><input type="text" class="form-control" id="n_poliza1" name="n_poliza1" value="<?= $poliza[0]['cod_poliza']; ?>"></td>
                                        <td hidden><input type="text" class="form-control" id="desdeP1" name="desdeP1" value="<?= $newDesdeP; ?>"></td>
                                        <td hidden><input type="text" class="form-control" id="hastaP1" name="hastaP1" value="<?= $poliza[0]['f_hastapoliza']; ?>"></td>
                                        <td hidden><input type="text" class="form-control" id="tipo_poliza1" name="tipo_poliza1" value="2"></td>
                                    </tr>
                                </div>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive-xl">
                        <table class="table" width="100%">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th>Ramo *</th>
                                    <th>Compañía *</th>
                                    <th>Tipo de Cuenta</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="background-color: white">
                                    <td><select class="mdb-select md-form colorful-select dropdown-primary my-n2" id="ramo" name="ramo" required data-toggle="tooltip" data-placement="bottom" title="Seleccione un elemento de la lista" searchable="Búsqueda rápida">
                                            <option value="">Seleccione el Ramo</option>
                                            <?php
                                            for ($i = 0; $i < sizeof($ramo); $i++) {
                                            ?>
                                                <option value="<?= $ramo[$i]["cod_ramo"]; ?>"><?= utf8_encode($ramo[$i]["nramo"]); ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td><select class="mdb-select md-form colorful-select dropdown-primary my-n2" id="cia" name="cia" required searchable="Búsqueda rápida">
                                            <option value="">Seleccione Compañía</option>
                                            <?php
                                            for ($i = 0; $i < sizeof($cia); $i++) {
                                            ?>
                                                <option value="<?= $cia[$i]["idcia"]; ?>"><?= ($cia[$i]["nomcia"]); ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td><select class="mdb-select md-form colorful-select dropdown-primary my-n2" id="t_cuenta" name="t_cuenta" required data-toggle="tooltip" data-placement="bottom" title="Seleccione un elemento de la lista">
                                            <option value="1">Individual</option>
                                            <option value="2">Colectivo</option>
                                        </select>
                                    </td>

                                    <td hidden><input type="text" class="form-control" id="ramo_e" name="ramo_e" value="<?= utf8_encode($poliza[0]['id_cod_ramo']); ?>"></td>
                                    <td hidden><input type="text" class="form-control" id="cia_e" name="cia_e" value="<?= utf8_encode($poliza[0]['id_cia']); ?>"></td>

                                    <td hidden><input type="text" class="form-control" id="t_cuenta1" name="t_cuenta1" value="<?= utf8_encode($poliza[0]['t_cuenta']); ?>"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive-xl">
                        <table class="table" width="100%">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th>Moneda</th>
                                    <th>Suma Asegurada</th>
                                    <th style="background-color: #E54848;">Prima Total sin Impuesto *</th>
                                    <th>Periocidad de Pago</th>
                                    <th>Forma de Pago</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="background-color: white">
                                    <td><select class="mdb-select md-form colorful-select dropdown-primary my-n2" id="currency" name="currency" required>
                                            <option value="1">$</option>
                                            <option value="2">BsS</option>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="input-group md-form my-n1">
                                            <input type="text" class="form-control validanumericos" id="sumaA" name="sumaA" data-toggle="tooltip" data-placement="bottom" title="Sólo introducir números y punto (.) como separador decimal" onkeypress="return tabular(event,this)">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group md-form my-n1">
                                            <input type="text" class="form-control validanumericos1" id="prima" name="prima" required data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio [Sólo introducir números y punto (.) como separador decimal]" onkeypress="return tabular(event,this)">
                                        </div>
                                    </td>
                                    <td><select onchange="cargarCuotas(this)" class="mdb-select md-form colorful-select dropdown-primary my-n2" name="f_pago" id="f_pago" required>
                                            <option value="CONTADO">CONTADO</option>
                                            <option value="FRACCIONADO">FRACCIONADO</option>
                                            <option value="FINANCIADO">FINANCIADO</option>
                                        </select>
                                    </td>
                                    <td><select onchange="cargarTarjeta(this)" class="mdb-select md-form colorful-select dropdown-primary my-n2" id="forma_pago" name="forma_pago" required>
                                            <option value="1">ACH (CARGO EN CUENTA)</option>
                                            <option value="2">TARJETA DE CREDITO / DEBITO</option>
                                            <option value="3">PAGO VOLUNTARIO</option>
                                        </select>
                                    </td>

                                    <td hidden><input type="text" class="form-control" id="currency_h" name="currency_h" value="<?= $poliza[0]['currency']; ?>"></td>
                                    <td hidden><input type="text" class="form-control" id="sumaA_h" name="sumaA_h" value="<?= $poliza[0]['sumaasegurada']; ?>"></td>
                                    <td hidden><input type="text" class="form-control" id="prima_h" name="prima_h" value="<?= $poliza[0]['prima']; ?>"></td>
                                    <td hidden><input type="text" class="form-control" id="f_pago_h" name="f_pago_h" value="<?= $poliza[0]['fpago']; ?>"></td>

                                    <td hidden><input type="text" class="form-control" id="forma_pago_e" name="forma_pago_h" value="<?= $poliza[0]['forma_pago']; ?>"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- TABLA REPETIDA -->
                    <div class="table-responsive-xl">
                        <table class="table" width="100%">
                            <tbody>
                                <tr style="background-color: white" hidden>
                                    <td><select class="mdb-select md-form colorful-select dropdown-primary my-n2" id="currency5" name="currency5" required>
                                            <option value="1">$</option>
                                            <option value="2">BsS</option>
                                        </select></td>
                                    <td><select class="mdb-select md-form colorful-select dropdown-primary my-n2" name="f_pago5" id="f_pago5" required>
                                            <option value="CONTADO">CONTADO</option>
                                            <option value="FRACCIONADO">FRACCIONADO</option>
                                            <option value="FINANCIADO">FINANCIADO</option>
                                        </select></td>
                                    <td><select class="mdb-select md-form colorful-select dropdown-primary my-n2" id="forma_pago5" name="forma_pago5" required>
                                            <option value="1">ACH (CARGO EN CUENTA)</option>
                                            <option value="2">TARJETA DE CREDITO / DEBITO</option>
                                            <option value="3">PAGO VOLUNTARIO</option>
                                        </select></td>
                                </tr>
                                <tr class="blue-gradient text-white" hidden id="trTarjeta1">
                                    <th>Nº Tarjeta</th>
                                    <th>CVV</th>
                                    <th>Fecha de Vencimiento</th>
                                    <th>Nombre Tarjetahabiente</th>
                                    <th>Banco</th>
                                    <th hidden>alert</th>
                                    <th hidden>id_tarjeta</th>
                                </tr>
                                <tr style="background-color: white" hidden id="trTarjeta2">
                                    <td>
                                        <div class="input-group md-form my-n1">
                                            <input type="number" step="0.01" class="form-control" onblur="validarTarjeta(this)" id="n_tarjeta" name="n_tarjeta" data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio [Sólo introducir números]" value="<?= $poliza[0]['n_tarjeta']; ?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group md-form my-n1">
                                            <input type="text" class="form-control validanumericos2" id="cvv" name="cvv" data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio [Sólo introducir números]" value="<?= $poliza[0]['cvv']; ?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group md-form my-n1">
                                            <input type="text" class="form-control datepicker" id="fechaV" name="fechaV" data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio" autocomplete="off" value="<?= $newfechaV; ?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group md-form my-n1">
                                            <input type="text" class="form-control" id="titular_tarjeta" name="titular_tarjeta" onkeyup="mayus(this);" data-toggle="tooltip" data-placement="bottom" title="Nombre del Tarjetahabiente" value="<?= $poliza[0]['nombre_titular']; ?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group md-form my-n1">
                                            <input type="text" class="form-control" id="bancoT" name="bancoT" onkeyup="mayus(this);" data-toggle="tooltip" data-placement="bottom" title="Nombre del Banco" value="<?= $poliza[0]['banco']; ?>">
                                        </div>
                                    </td>

                                    <!-- HIDDEN -->
                                    <td hidden><input type="text" class="form-control" id="n_tarjeta_h" name="n_tarjeta_h" value="<?= $poliza[0]['n_tarjeta']; ?>"></td>
                                    <td hidden><input type="text" class="form-control" id="cvv_h" name="cvv_h" value="<?= $poliza[0]['cvv']; ?>"></td>
                                    <td hidden>
                                        <div class="input-group date">
                                            <input type="text" class="form-control datepicker" id="fechaV_h" name="fechaV_h" value="<?= $newfechaV; ?>">
                                        </div>
                                    </td>
                                    <td hidden><input type="text" class="form-control" id="titular_tarjeta_h" name="titular_tarjeta_h" value="<?= $poliza[0]['nombre_titular']; ?>"></td>
                                    <td hidden><input type="text" class="form-control" id="bancoT_h" name="bancoT_h" value="<?= $poliza[0]['banco']; ?>"></td>
                                    <td hidden><input type="text" class="form-control" id="alert" name="alert" value="0"></td>
                                    <td hidden><input type="text" class="form-control" id="id_tarjeta" name="id_tarjeta" value="<?= $poliza[0]['id_tarjeta']; ?>"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive-xl">
                        <table class="table" width="100%">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th>N° Recibo *</th>
                                    <th>Fecha Desde Recibo *</th>
                                    <th>Fecha Hasta Recibo *</th>
                                    <th>Zona de Produc</th>
                                    <th>N° de Cuotas *</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="background-color:white">
                                    <td>
                                        <div class="input-group md-form my-n1">
                                            <input type="text" class="form-control validanumericos3" id="n_recibo" name="n_recibo" value="<?= $poliza[0]['cod_recibo']; ?>" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group md-form my-n1">
                                            <input type="text" class="form-control datepicker" id="desde_recibo" name="desde_recibo" required autocomplete="off" value="<?= $newDesdeR; ?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group md-form my-n1">
                                            <input type="text" class="form-control datepicker" id="hasta_recibo" name="hasta_recibo" required autocomplete="off" value="<?= $newHastaR; ?>">
                                        </div>
                                    </td>

                                    <td>
                                        <div class="input-group md-form my-n1 grey lighten-2">
                                            <input type="text" class="form-control" id="z_produc" name="z_produc" readonly value="<?= utf8_encode($usuario[0]['z_produccion']); ?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group md-form my-n1">
                                            <input type="number" class="form-control validanumericos4" id="n_cuotas" name="n_cuotas" min="1" max="12" required>
                                        </div>
                                    </td>

                                    <td hidden><input type="text" class="form-control" id="n_cuotas_h" name="n_cuotas_h" value="<?= $poliza[0]['ncuotas']; ?>"></td>
                                    <td hidden><input type="text" class="form-control" id="n_recibo1" name="n_recibo1" value="<?= $poliza[0]['cod_recibo']; ?>"></td>
                                    <td hidden><input type="text" class="form-control" id="desde_recibo1" name="desde_recibo1" value="<?= $newDesdeR; ?>"></td>
                                    <td hidden><input type="text" class="form-control" id="hasta_recibo1" name="hasta_recibo1" value="<?= $newHastaR; ?>"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>


                    <div class="col-md-auto col-md-offset-2">
                        <h2 class="title">Datos del Titular</h2>
                    </div>

                    <h2 id="existeT" class="text-success text-center"><strong></strong></h2>
                    <h2 id="no_existeT" class="text-danger text-center"><strong></strong></h2>
                    <div class="table-responsive-xl">
                        <table class="table">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th>Cédula *</th>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="background-color: white">
                                    <td>
                                        <div class="input-group md-form my-n1">
                                            <input onblur="validartitular(this)" type="text" class="form-control validanumericos5" id="titular" name="titular" required data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group md-form my-n1 grey lighten-2">
                                            <input type="text" class="form-control" id="n_titular" name="n_titular" readonly required="true">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group md-form my-n1 grey lighten-2">
                                            <input type="text" class="form-control" id="a_titular" name="a_titular" readonly required="true">
                                        </div>
                                    </td>

                                    <td hidden><input type="text" class="form-control" id="ci_t" name="ci_t" value="<?= $poliza[0]['ci']; ?>"></td>
                                    <td hidden><input type="text" class="form-control" id="nombre_tit" name="nombre_tit" value="<?= utf8_encode($poliza[0]['nombre_t']); ?>"></td>
                                    <td hidden><input type="text" class="form-control" id="apellido_tit" name="apellido_tit" value="<?= utf8_encode($poliza[0]['apellido_t']); ?>"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-auto col-md-offset-2">
                        <h2 class="title">Datos del Tomador</h2>
                    </div>

                    <h2 id="existeTom" class="text-success text-center"><strong></strong></h2>
                    <h2 id="no_existeTom" class="text-danger text-center"><strong></strong></h2>
                    <div class="table-responsive-xl">
                        <table class="table">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th>Cédula *</th>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="background-color: white">
                                    <td>
                                        <div class="input-group md-form my-n1">
                                            <input onblur="validartomador(this)" type="text" class="form-control validanumericos6" id="tomador" name="tomador" required data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group md-form my-n1 grey lighten-2">
                                            <input type="text" class="form-control" id="n_tomador" name="n_tomador" readonly="readonly">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group md-form my-n1 grey lighten-2">
                                            <input type="text" class="form-control" id="a_tomador" name="a_tomador" readonly="readonly">
                                        </div>
                                    </td>

                                    <td hidden><input type="text" class="form-control" id="ci_tom" name="ci_tom" value="<?= $tomador[0]['ci']; ?>"></td>
                                    <td hidden><input type="text" class="form-control" id="nombre_tom" name="nombre_tom" value="<?= utf8_encode($tomador[0]['nombre_t']); ?>"></td>
                                    <td hidden><input type="text" class="form-control" id="apellido_tom" name="apellido_tom" value="<?= utf8_encode($tomador[0]['apellido_t']); ?>"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div id="tablaveh" hidden="true">
                        <h2 class="text-info"><strong>Datos Vehículo</strong></h2>
                        <div class="table-responsive-xl">
                            <table class="table">
                                <thead class="blue-gradient text-white">
                                    <tr>
                                        <th>Placa</th>
                                        <th>Marca</th>
                                        <th>Modelo</th>
                                        <th>Tipo</th>
                                        <th>Año</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <div class="form-group col-md-12">
                                        <tr style="background-color: white">
                                            <td>
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control" id="placa" name="placa">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control" id="marca" name="marca">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control" id="modelo" name="modelo">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control" id="tipo" name="tipo">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control" id="anio" name="anio" placeholder="2019">
                                                </div>
                                            </td>

                                            <td hidden><input type="text" class="form-control" id="placa_h" name="placa_h" value="<?= $vehiculo[0]['placa']; ?>"></td>
                                            <td hidden><input type="text" class="form-control" id="marca_h" name="marca_h" value="<?= $vehiculo[0]['marca']; ?>"></td>
                                            <td hidden><input type="text" class="form-control" id="modelo_h" name="modelo_h" value="<?= $vehiculo[0]['mveh']; ?>"></td>
                                            <td hidden><input type="text" class="form-control" id="tipo_h" name="tipo_h" value="<?= $vehiculo[0]['tveh']; ?>"></td>
                                            <td hidden><input type="text" class="form-control" id="anio_h" name="anio_h" value="<?= $vehiculo[0]['f_veh']; ?>"></td>
                                        </tr>
                                    </div>
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <div class="col-md-auto col-md-offset-2">
                        <h2 class="title">Datos del Asesor</h2>
                    </div>
                    <div class="table-responsive-xl">
                        <table class="table" id="tablaAsesor">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th>Asesor *</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr style="background-color: white">
                                    <td style="text-align:center"><select class="form-control selectpicker" id="asesor" name="asesor" required data-style="btn-white" data-header="Seleccione Cía" data-actions-box="true" data-live-search="true">
                                            <?php
                                            for ($i = 0; $i < sizeof($asesor); $i++) {
                                            ?>
                                                <option value='<?= utf8_encode($asesor[$i]["cod"] . "=" . $asesor[$i]["nombre"]); ?>'><?= utf8_encode($asesor[$i]["nombre"]) . ' (' . $asesor[$i]["cod"] . ')'; ?></option>
                                            <?php } ?>
                                    <td hidden><input type="text" class="form-control" id="asesor_h" name="asesor_h" value="<?= $poliza[0]['cod'] . "=" . $poliza[0]['nombre']; ?>"></td>
                                    </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive-xl">
                        <table class="table">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th>Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="background-color:white">
                                    <td>
                                        <div class="input-group md-form my-n1">
                                            <input type="text" class="form-control validanumericos7" id="obs_p" name="obs_p" value="<?= $poliza[0]['obs_p']; ?>">
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <hr>
                    <button type="submit" style="width: 100%" data-toggle="tooltip" data-placement="bottom" title="Previsualizar" class="btn dusty-grass-gradient btn-lg">Previsualizar Renovación &nbsp;<i class="fas fa-check" aria-hidden="true"></i></button>
                    <hr>

                </div>
            </form>
        </div>
    </div>





    <?php require_once dirname(__DIR__) . '\..\layout\footer_b.php'; ?>

    <?php require_once dirname(__DIR__) . '\..\layout\footer.php'; ?>

    <!-- Modal TITULAR -->
    <div class="modal fade" id="agregarnuevotitular" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Debe Agregar Nuevo Cliente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="frmnuevoT" autocomplete="off">

                        <div class="form-row">
                            <table class="table table-hover table-striped table-bordered" id="iddatatable">
                                <thead class="blue-gradient text-white">
                                    <tr>
                                        <th>Razón Social *</th>
                                        <th colspan="3">N° ID Titular</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <div class="form-group col-md-12">
                                        <tr style="background-color: white">
                                            <td><select class="mdb-select md-form colorful-select dropdown-primary my-n2" name="r_sNew" id="r_sNew" required>
                                                    <option value="">Seleccione</option>
                                                    <option value="PN-">PN-</option>
                                                    <option value="J-">J-</option>
                                                </select>
                                            </td>
                                            <td colspan="3">
                                                <div class="input-group md-form my-n1 grey lighten-2">
                                                    <input type="text" class="form-control" id="id_new_titular" name="id_new_titular" readonly="readonly">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="blue-gradient text-white">
                                            <th colspan="2">Nombre(s) Titular *</th>
                                            <th colspan="2">Apellido(s) Titular</th>
                                        </tr>
                                        <tr style="background-color: white">
                                            <td colspan="2">
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control" id="nT_new" name="nT_new" required onkeyup="mayus(this);">
                                                </div>
                                            </td>
                                            <td colspan="2">
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control" id="aT_new" name="aT_new" required onkeyup="mayus(this);">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="blue-gradient text-white">
                                            <th colspan="2">Celular *</th>
                                            <th colspan="2">Teléfono</th>
                                        </tr>
                                        <tr style="background-color: white">
                                            <td colspan="2">
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control validanumericos8" id="cT_new" name="cT_new" required>
                                                </div>
                                            </td>
                                            <td colspan="2">
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control validanumericos9" id="tT_new" name="tT_new" required>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="blue-gradient text-white">
                                            <th>Fecha de Nacimiento</th>
                                            <th colspan="2">Email *</th>
                                        </tr>
                                        <tr style="background-color: white">
                                            <td>
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control datepicker" id="fnT_new" name="fnT_new" required />
                                                </div>
                                            </td>
                                            <td colspan="2">
                                                <div class="input-group md-form my-n1">
                                                    <input type="email" class="form-control" id="eT_new" name="eT_new" required>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="blue-gradient text-white">
                                            <th colspan=4>Dirección *</th>
                                        </tr>
                                        <tr style="background-color: white">
                                            <td colspan=4>
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control" id="dT_new" name="dT_new" required onkeyup="mayus(this);">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="blue-gradient text-white">
                                            <th colspan=2>Ocupación</th>
                                            <th colspan=2>Ingreso</th>
                                        </tr>
                                        <tr style="background-color: white">
                                            <td colspan=2>
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control" id="oT_new" name="oT_new" required onkeyup="mayus(this);">
                                                </div>
                                            </td>
                                            <td colspan=2>
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control validanumericos10" id="iT_new" name="iT_new" value="0" required>
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
                    <button type="submit" id="btnAgregarnuevo" class="btn aqua-gradient">Agregar nuevo</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal TOMADOR -->
    <div class="modal fade" id="agregarnuevotomador" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Debe Agregar Nuevo Cliente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="frmnuevoTom" autocomplete="off">

                        <div class="form-row">
                            <table class="table table-hover table-striped table-bordered nowrap" id="iddatatable1">
                                <thead class="blue-gradient text-white">
                                    <tr>
                                        <th>Razón Social *</th>
                                        <th colspan="3">N° ID Tomador</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <div class="form-group col-md-12">
                                        <tr style="background-color: white">
                                            <td><select class="mdb-select md-form colorful-select dropdown-primary my-n2" name="r_sNewT" id="r_sNewT" required>
                                                    <option value="">Seleccione</option>
                                                    <option value="PN-">PN-</option>
                                                    <option value="J-">J-</option>
                                                </select>
                                            </td>
                                            <td colspan="3">
                                                <div class="input-group md-form my-n1 grey lighten-2">
                                                    <input type="text" class="form-control" id="id_new_titularT" name="id_new_titularT" readonly="readonly">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="blue-gradient text-white">
                                            <th colspan="2">Nombre(s) Tomador *</th>
                                            <th colspan="2">Apellido(s) Tomador</th>
                                        </tr>
                                        <tr style="background-color: white">
                                            <td colspan="2">
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control" id="nT_newT" name="nT_newT" required onkeyup="mayus(this);">
                                                </div>
                                            </td>
                                            <td colspan="2">
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control" id="aT_newT" name="aT_newT" required onkeyup="mayus(this);">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="blue-gradient text-white">
                                            <th colspan="2">Celular *</th>
                                            <th colspan="2">Teléfono</th>
                                        </tr>
                                        <tr style="background-color: white">
                                            <td colspan="2">
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control validanumericos11" id="cT_newT" name="cT_newT" required>
                                                </div>
                                            </td>
                                            <td colspan="2">
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control validanumericos12" id="tT_newT" name="tT_newT" required>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="blue-gradient text-white">
                                            <th>Fecha de Nacimiento</th>
                                            <th colspan="2">Email *</th>
                                        </tr>
                                        <tr style="background-color: white">
                                            <td>
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control datepicker" id="fnT_newT" name="fnT_newT" required>
                                                </div>
                                            </td>
                                            <td colspan="2">
                                                <div class="input-group md-form my-n1">
                                                    <input type="email" class="form-control" id="eT_newT" name="eT_newT" required>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="blue-gradient text-white">
                                            <th colspan=4>Dirección *</th>
                                        </tr>
                                        <tr style="background-color: white">
                                            <td colspan=4>
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control" id="dT_newT" name="dT_newT" required onkeyup="mayus(this);">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="blue-gradient text-white">
                                            <th colspan=2>Ocupación</th>
                                            <th colspan=2>Ingreso</th>
                                        </tr>
                                        <tr style="background-color: white">
                                            <td colspan=2>
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control" id="oT_newT" name="oT_newT" required onkeyup="mayus(this);">
                                                </div>
                                            </td>
                                            <td colspan=2>
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control validanumericos13" id="iT_newT" name="iT_newT" value="0" required>
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
                    <button type="submit" id="btnAgregarnuevoT" class="btn aqua-gradient">Agregar nuevo</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Tarjetas Existentes-->
    <div class="modal fade" id="tarjetaexistente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel">Seleccione la Tarjeta</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body ">
                    <form id="frmnuevoP">
                        <div class="table-responsive-xl">
                            <a onclick="selecTarjetaNew()" style="color:black" data-toggle="tooltip" data-placement="top" title="Añadir Tarjeta Nueva" class="btn dusty-grass-gradient btn-sm float-right">Añadir Tarjeta Nueva <i class="fa fa-plus" aria-hidden="true"></i></a>
                            <table class="table table-hover table-striped table-bordered" id="tablaPE">
                                <thead class="blue-gradient text-white">
                                    <tr>
                                        <th>Nº de Tarjeta</th>
                                        <th>CVV</th>
                                        <th>F Vencimiento</th>
                                        <th>Nombre Tarjetahabiente</th>
                                        <th>Banco</th>
                                        <th>Póliza(s) Asociada(s)</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="../../assets/view/b_poliza.js"></script>

    <script>
        $(document).ready(function() {

            $("#tipo_poliza option[value=" + $('#tipo_poliza1').val() + "]").attr("selected", true);
            $("#ramo option[value=" + $('#ramo_e').val() + "]").attr("selected", true);
            $("#cia option[value=" + $('#cia_e').val() + "]").attr("selected", true);
            $("#t_cuenta option[value=" + $('#t_cuenta1').val() + "]").attr("selected", true);
            $("#currency option[value=" + $('#currency_h').val() + "]").attr("selected", true);
            $("#f_pago option[value=" + $('#f_pago_h').val() + "]").attr("selected", true);
            $("#forma_pago option[value=" + $('#forma_pago_e').val() + "]").attr("selected", true);
            $("#cia1 option[value=" + $('#cia_e').val() + "]").attr("selected", true);
            $('#titular').val($('#ci_t').val());
            $('#n_titular').val($('#nombre_tit').val());
            $('#a_titular').val($('#apellido_tit').val());
            $('#tomador').val($('#ci_tom').val());
            $('#n_tomador').val($('#nombre_tom').val());
            $('#a_tomador').val($('#apellido_tom').val());
            $('#placa').val($('#placa_h').val());
            $('#marca').val($('#marca_h').val());
            $('#modelo').val($('#modelo_h').val());
            $('#tipo').val($('#tipo_h').val());
            $('#anio').val($('#anio_h').val());
            $('#asesor').val($('#asesor_h').val());
            $('#asesor').change();
            $('#sumaA').val($('#sumaA_h').val());
            $('#prima').val($('#prima_h').val());
            $('#n_cuotas').val($('#n_cuotas_h').val());

            if ($('#n_cuotas').val() == 1) {
                $('#n_cuotas').val(1);
                $("#n_cuotas").attr("readonly", true);
                $("#n_cuotas").attr("class", "form-control validanumericos4 grey lighten-2");
            } else {
                $('#n_cuotas').removeAttr('readonly');
                $("#n_cuotas").attr("class", "form-control validanumericos4");
            }

            if ($('#ramo').val() == 2 || $('#ramo').val() == 25) {
                $('#tablaveh').removeAttr('hidden');
            } else {
                $('#tablaveh').attr('hidden', true);
            }

            if ($('#forma_pago').val() == 2) {
                $('#trTarjeta1').removeAttr('hidden');
                $('#trTarjeta2').removeAttr('hidden');
            } else {
                $('#trTarjeta1').attr('hidden', true);
                $('#trTarjeta2').attr('hidden', true);
            }

            //Abrir picker en un modal
            var $input = $('.datepicker').pickadate({
                // Strings and translations
                monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Augosto', 'Septiembre', 'Octubre',
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

            onload = function() {
                var ele = document.querySelectorAll('.validanumericos')[0];
                var ele1 = document.querySelectorAll('.validanumericos1')[0];
                var ele2 = document.querySelectorAll('.validanumericos2')[0];
                var ele3 = document.querySelectorAll('.validanumericos3')[0];
                var ele4 = document.querySelectorAll('.validanumericos4')[0];
                var ele5 = document.querySelectorAll('.validanumericos5')[0];
                var ele6 = document.querySelectorAll('.validanumericos6')[0];
                var ele7 = document.querySelectorAll('.validanumericos7')[0];
                var ele8 = document.querySelectorAll('.validanumericos8')[0];
                var ele9 = document.querySelectorAll('.validanumericos9')[0];
                var ele10 = document.querySelectorAll('.validanumericos10')[0];
                var ele11 = document.querySelectorAll('.validanumericos11')[0];
                var ele12 = document.querySelectorAll('.validanumericos12')[0];

                ele.onkeypress = function(e) {
                    if (isNaN(this.value + String.fromCharCode(e.charCode)))
                        return false;
                }
                ele1.onkeypress = function(e1) {
                    if (isNaN(this.value + String.fromCharCode(e1.charCode)))
                        return false;
                }
                ele1.onpaste = function(e1) {
                    e1.preventDefault();
                }
                ele2.onkeypress = function(e2) {
                    if (isNaN(this.value + String.fromCharCode(e2.charCode)))
                        return false;
                }
                ele2.onpaste = function(e2) {
                    e2.preventDefault();
                }
                ele3.onkeypress = function(e3) {
                    if (isNaN(this.value + String.fromCharCode(e3.charCode)))
                        return false;
                }
                ele3.onpaste = function(e3) {
                    e3.preventDefault();
                }
                ele4.onkeypress = function(e4) {
                    if (isNaN(this.value + String.fromCharCode(e4.charCode)))
                        return false;
                }
                ele4.onpaste = function(e4) {
                    e4.preventDefault();
                }
                ele5.onkeypress = function(e5) {
                    if (isNaN(this.value + String.fromCharCode(e5.charCode)))
                        return false;
                }
                ele5.onpaste = function(e5) {
                    e5.preventDefault();
                }
                ele6.onkeypress = function(e6) {
                    if (isNaN(this.value + String.fromCharCode(e6.charCode)))
                        return false;
                }
                ele6.onpaste = function(e6) {
                    e6.preventDefault();
                }
                ele7.onkeypress = function(e7) {
                    if (isNaN(this.value + String.fromCharCode(e7.charCode)))
                        return false;
                }
                ele7.onpaste = function(e7) {
                    e7.preventDefault();
                }
                ele8.onkeypress = function(e8) {
                    if (isNaN(this.value + String.fromCharCode(e8.charCode)))
                        return false;
                }
                ele9.onkeypress = function(e9) {
                    if (isNaN(this.value + String.fromCharCode(e9.charCode)))
                        return false;
                }
                ele10.onkeypress = function(e10) {
                    if (isNaN(this.value + String.fromCharCode(e10.charCode)))
                        return false;
                }
                ele11.onkeypress = function(e11) {
                    if (isNaN(this.value + String.fromCharCode(e11.charCode)))
                        return false;
                }
                ele12.onkeypress = function(e12) {
                    if (isNaN(this.value + String.fromCharCode(e12.charCode)))
                        return false;
                }
            }

            $('#btnAgregarnuevo').click(function() {
                if ($("#r_sNew").val().length < 1) {
                    alertify.error("La Razón Social del Cliente es Obligatoria");
                    return false;
                }
                if ($("#id_new_titular").val().length < 1) {
                    alertify.error("El Nº de ID del Cliente es Obligatorio");
                    return false;
                }
                if ($("#nT_new").val().length < 1) {
                    alertify.error("El Nombre del Cliente es Obligatorio");
                    return false;
                }
                if ($("#dT_new").val().length < 1) {
                    alertify.error("La Dirección del Cliente es Obligatorio");
                    return false;
                }

                datos = $('#frmnuevoT').serialize();
                var titular = $('#id_new_titular').val();
                var n_titular = $('#nT_new').val();
                var a_titular = $('#aT_new').val();

                $.ajax({
                    type: "POST",
                    data: datos,
                    url: "../../procesos/agregarCliente.php",
                    success: function(r) {
                        if (r == 1) {
                            $('#frmnuevoT')[0].reset();
                            alertify.success("Agregado con Exito!!");

                            $('#titular').val(titular);
                            $('#titular').removeAttr('hidden');
                            $('#n_titular').val(n_titular);
                            $('#a_titular').val(a_titular);

                            $('#no_existeT').text("");
                            //$("#titular").attr("readonly", true);
                            $('#titular').removeAttr('onblur');

                            $('#tablatomador').removeAttr('hidden');
                            $('#tomador').val(titular);
                            $('#n_tomador').val(n_titular);
                            $('#a_tomador').val(a_titular);

                            $('#agregarnuevotitular').modal('hide');
                        } else {
                            alertify.error("Fallo al agregar!");
                        }
                    }
                });
            });


            $('#btnAgregarnuevoT').click(function() {
                if ($("#r_sNewT").val().length < 1) {
                    alertify.error("La Razón Social del Cliente es Obligatoria");
                    return false;
                }
                if ($("#id_new_titularT").val().length < 1) {
                    alertify.error("El Nº de ID del Cliente es Obligatorio");
                    return false;
                }
                if ($("#nT_newT").val().length < 1) {
                    alertify.error("El Nombre del Cliente es Obligatorio");
                    return false;
                }
                if ($("#dT_newT").val().length < 1) {
                    alertify.error("La Dirección del Cliente es Obligatorio");
                    return false;
                }

                datos = $('#frmnuevoTom').serialize();
                var titular = $('#id_new_titularT').val();
                var n_titular = $('#nT_newT').val();
                var a_titular = $('#aT_newT').val();

                $.ajax({
                    type: "POST",
                    data: datos,
                    url: "../../procesos/agregarTomador.php",
                    success: function(r) {
                        if (r == 1) {
                            $('#frmnuevoTom')[0].reset();
                            alertify.success("Agregado con Exito!!");
                            $('#no_existeTom').text("");

                            $('#tablatomador').removeAttr('hidden');
                            $('#tomador').val(titular);
                            $('#n_tomador').val(n_titular);
                            $('#a_tomador').val(a_titular);

                            $('#agregarnuevotomador').modal('hide');
                        } else {
                            alertify.error("Fallo al agregar!");
                        }
                    }
                });
            });

        });

        function cargarTarjeta(forma_pago) {
            if (forma_pago.value == 2) {
                $('#trTarjeta1').removeAttr('hidden');
                $('#trTarjeta2').removeAttr('hidden');
                $('#n_tarjeta').val('');
                $('#cvv').val('');
                $('#fechaV').val('');
                $('#titular_tarjeta').val('');
                $('#bancoT').val('');
                $('#alert').val('0');
                $('#id_tarjeta').val('0');
            } else {
                $('#trTarjeta1').attr('hidden', true);
                $('#trTarjeta2').attr('hidden', true);
                $('#n_tarjeta').val('');
                $('#cvv').val('');
                $('#fechaV').val('');
                $('#titular_tarjeta').val('');
                $('#bancoT').val('');
                $('#alert').val('0');
                $('#id_tarjeta').val('0');
            }
        }

        function mayus(e) {
            e.value = e.value.toUpperCase();
        }

        function cargarFechaDesde(desdeP) {
            var dia = $("#desdeP").pickadate('picker').get('select', 'dd');
            var mes = $("#desdeP").pickadate('picker').get('select', 'mm');
            var anio = parseInt($("#desdeP").pickadate('picker').get('select', 'yyyy'));

            $('#hastaP').val(dia + '-' + mes + '-' + (anio + 1));

            var desdeP = $('#desdeP').val();
            var hastaP = $('#hastaP').val();

            $('#hastaP').pickadate('picker').set('select', hastaP);
            $('#desde_recibo').pickadate('picker').set('select', desdeP);
            $('#hasta_recibo').pickadate('picker').set('select', hastaP);
        }

        function cargarCuotas(f_pago) {
            if (f_pago.value == 'CONTADO') {
                $('#n_cuotas').val(1);
                $("#n_cuotas").attr("readonly", true);
                $("#n_cuotas").attr("class", "form-control validanumericos4 grey lighten-2");
            } else {
                $('#n_cuotas').removeAttr('readonly');
                $("#n_cuotas").attr("class", "form-control validanumericos4");
            }
        }


        function validartitular(titular) {
            $.ajax({
                type: "POST",
                data: "titular=" + titular.value,
                url: "../../procesos/validartitular.php",
                success: function(r) {
                    datos = jQuery.parseJSON(r);
                    if (datos['nombre_t'] == null) {
                        $('#n_titular').val("");
                        $('#a_titular').val("");

                        $('#id_new_titular').val(titular.value);
                        $('#existeT').text("");
                        $('#no_existeT').text("No Existe Titular");
                        $('#titular').val("");
                        $('#agregarnuevotitular').modal('show');

                        $('#tablatomador').attr("hidden", true);
                    } else {
                        $('#tablatomador').removeAttr('readonly');
                        $('#n_titular').val(datos['nombre_t']);
                        $('#a_titular').val(datos['apellido_t']);
                        $('#existeT').text("Existe Titular");
                        $('#no_existeT').text("");
                        $('#id_new_titular').val("");

                        $('#tablatomador').removeAttr('hidden');
                        $('#tomador').val(titular.value);
                        $('#n_tomador').val(datos['nombre_t']);
                        $('#a_tomador').val(datos['apellido_t']);

                    }
                }
            });
        }

        function validartomador(titular) {
            $.ajax({
                type: "POST",
                data: "titular=" + titular.value,
                url: "../../procesos/validartitular.php",
                success: function(r) {
                    datos = jQuery.parseJSON(r);
                    if (datos['nombre_t'] == null) {
                        $('#n_tomador').val("");
                        $('#a_tomador').val("");

                        $('#id_new_titularT').val(titular.value);
                        $('#existeTom').text("");
                        $('#no_existeTom').text("No Existe Tomador");
                        $('#tomador').val("");
                        $("#tomador").css('color', 'black');

                        $('#agregarnuevotomador').modal('show');
                    } else {
                        $('#n_tomador').val(datos['nombre_t']);
                        $('#a_tomador').val(datos['apellido_t']);
                        $("#btnAggTom").attr("hidden", true);
                        $('#existeTom').text("Existe Tomador");
                        $('#no_existeTom').text("");

                        $('#id_new_titularT').val("");
                        $("#tomador").css('color', 'black');
                    }
                }
            });
        }


        $("#ramo").change(function() {
            if ($('#ramo').val() == 2 || $('#ramo').val() == 25) {
                $('#tablaveh').removeAttr('hidden');
            } else {
                $('#tablaveh').attr('hidden', true);
            }
        });

        async function validarTarjeta(n_tarjeta) {
            $('#alert').val('0');
            $('#id_tarjeta').val('0');
            if ($("#n_tarjeta").val().length < 1) {
                alertify.error("Debe escribir en la casilla para realizar la búsqueda");
                return false;
            }
            $.ajax({
                type: "POST",
                data: "n_tarjeta=" + n_tarjeta.value,
                url: "../../procesos/validar_tarjeta.php",
                success: function(r) {
                    datos = jQuery.parseJSON(r);
                    if (datos == null) {
                        $('#cvv').val('');
                        $('#fechaV').val('');
                        $('#titular_tarjeta').val('');
                        $('#bancoT').val('');
                        $('#cvv_h').val('');
                        $('#fechaV_h').val('');
                        $('#titular_tarjeta_h').val('');
                        $('#bancoT_h').val('');
                        $("#bancoT").css('background-color', 'white');
                        $('#alert').val('1');
                        alertify.success('Número de Tarjeta no existente en la BD');
                    } else {
                        if (datos[0]['n_tarjeta'] == null) {
                            $('#cvv').val('');
                            $('#fechaV').val('');
                            $('#titular_tarjeta').val('');
                            $('#bancoT').val('');
                            $('#cvv_h').val('');
                            $('#fechaV_h').val('');
                            $('#titular_tarjeta_h').val('');
                            $('#bancoT_h').val('');
                            $("#bancoT").css('background-color', 'white');
                            $('#alert').val('1');
                            alertify.success('Número de Tarjeta no existente en la BD');
                        } else {
                            $("#tablaPE  tbody").empty();
                            for (let index = 0; index < datos.length; index++) {
                                $.ajax({
                                    type: "POST",
                                    data: "id_tarjeta=" + datos[index].id_tarjeta,
                                    url: "../../procesos/ver_poliza_tarjeta.php",
                                    success: function(r) {
                                        datos1 = jQuery.parseJSON(r);

                                        var d = new Date();
                                        var strDate = d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate();

                                        var f = new Date(datos[index]['fechaV']);
                                        f.setDate(f.getDate() + 1)
                                        var f_venc = f.getDate() + "-" + (f.getMonth() + 1) + "-" + f.getFullYear();
                                        if (datos1 == null) {
                                            if ((new Date(strDate).getTime() <= new Date(datos[index]['fechaV']).getTime())) {
                                                var htmlTags = '<tr ondblclick="selecTarjeta(' + datos[index]['id_tarjeta'] + ')" style="cursor:pointer">' +
                                                    '<td style="color:green">' + datos[index]['n_tarjeta'] + '</td>' +
                                                    '<td nowrap>' + datos[index]['cvv'] + '</td>' +
                                                    '<td nowrap>' + f_venc + '</td>' +
                                                    '<td>' + datos[index]['nombre_titular'] + '</td>' +
                                                    '<td nowrap>' + datos[index]['banco'] + '</td>' +
                                                    '<td nowrap>No</td>' +

                                                    '<td nowrap><a onclick="selecTarjeta(' + datos[index]['id_tarjeta'] + ')" style="color:black" data-toggle="tooltip" data-placement="top" title="Añadir Tarjeta" class="btn dusty-grass-gradient btn-sm"><i class="fa fa-check-square" ></i></a></td>' +
                                                    '</tr>';
                                            } else {
                                                var htmlTags = '<tr ondblclick="selecTarjeta(' + datos[index]['id_tarjeta'] + ')" style="cursor:pointer">' +
                                                    '<td style="color:red">' + datos[index]['n_tarjeta'] + '</td>' +
                                                    '<td nowrap>' + datos[index]['cvv'] + '</td>' +
                                                    '<td nowrap>' + f_venc + '</td>' +
                                                    '<td>' + datos[index]['nombre_titular'] + '</td>' +
                                                    '<td nowrap>' + datos[index]['banco'] + '</td>' +
                                                    '<td nowrap>No</td>' +

                                                    '<td nowrap><a onclick="selecTarjeta(' + datos[index]['id_tarjeta'] + ')" style="color:black" data-toggle="tooltip" data-placement="top" title="Añadir Tarjeta" class="btn dusty-grass-gradient btn-sm"><i class="fa fa-check-square"></i></a></td>' +
                                                    '</tr>';
                                            }
                                        } else {
                                            if ((new Date(strDate).getTime() <= new Date(datos[index]['fechaV']).getTime())) {
                                                var htmlTags = '<tr ondblclick="selecTarjeta(' + datos[index]['id_tarjeta'] + ')" style="cursor:pointer">' +
                                                    '<td style="color:green">' + datos[index]['n_tarjeta'] + '</td>' +
                                                    '<td nowrap>' + datos[index]['cvv'] + '</td>' +
                                                    '<td nowrap>' + f_venc + '</td>' +
                                                    '<td>' + datos[index]['nombre_titular'] + '</td>' +
                                                    '<td nowrap>' + datos[index]['banco'] + '</td>' +
                                                    '<td nowrap>Sí</td>' +

                                                    '<td nowrap><a onclick="selecTarjeta(' + datos[index]['id_tarjeta'] + ')" style="color:black" data-toggle="tooltip" data-placement="top" title="Añadir Tarjeta" class="btn dusty-grass-gradient btn-sm"><i class="fa fa-check-square" ></i></a><a href="b_polizaT.php?id_tarjeta=' + datos[index]['id_tarjeta'] + '" target="_blank" style="color:white" data-toggle="tooltip" data-placement="top" title="Ver Pólizas" class="btn blue-gradient btn-sm" ><i class="fa fa-eye"></i></a></td>' +
                                                    '</tr>';

                                            } else {

                                                var htmlTags = '<tr ondblclick="selecTarjeta(' + datos[index]['id_tarjeta'] + ')" style="cursor:pointer">' +
                                                    '<td style="color:red">' + datos[index]['n_tarjeta'] + '</td>' +
                                                    '<td nowrap>' + datos[index]['cvv'] + '</td>' +
                                                    '<td nowrap>' + f_venc + '</td>' +
                                                    '<td>' + datos[index]['nombre_titular'] + '</td>' +
                                                    '<td nowrap>' + datos[index]['banco'] + '</td>' +
                                                    '<td nowrap>Sí</td>' +

                                                    '<td nowrap><a onclick="selecTarjeta(' + datos[index]['id_tarjeta'] + ')" style="color:black" data-toggle="tooltip" data-placement="top" title="Añadir Tarjeta" class="btn dusty-grass-gradient btn-sm"><i class="fa fa-check-square"></i></a><a href="b_polizaT.php?id_tarjeta=' + datos[index]['id_tarjeta'] + '" target="_blank" style="color:white" data-toggle="tooltip" data-placement="top" title="Ver Pólizas" class="btn blue-gradient btn-sm" ><i class="fa fa-eye"></i></a></td>' +
                                                    '</tr>';
                                            }
                                        }
                                        $('#tablaPE tbody').append(htmlTags);
                                    }
                                });
                            }
                            $('#tarjetaexistente').modal({
                                backdrop: 'static',
                                keyboard: false
                            });
                            $('#tarjetaexistente').modal('show');
                        }
                    }
                }
            });
        }

        async function selecTarjeta(id_tarjeta) {
            await $.ajax({
                type: "POST",
                data: "id_tarjeta=" + id_tarjeta,
                url: "../../procesos/b_tarjeta.php",
                success: function(r) {
                    datos = jQuery.parseJSON(r);
                    if (datos[0]['id_tarjeta'] == null) {
                        alert('seleccione una tarjeta');
                    } else {
                        $('#n_tarjeta').val(datos[0]['n_tarjeta']);
                        $('#cvv').val(datos[0]['cvv']);
                        $('#titular_tarjeta').val(datos[0]['nombre_titular']);
                        $('#bancoT').val(datos[0]['banco']);
                        $('#n_tarjeta_h').val(datos[0]['n_tarjeta_h']);
                        $('#cvv_h').val(datos[0]['cvv']);
                        $('#titular_tarjeta_h').val(datos[0]['nombre_titular']);
                        $('#bancoT_h').val(datos[0]['banco']);
                        $('#id_tarjeta').val(datos[0]['id_tarjeta']);

                        $('#alert').val('1');

                        //----------DATES-----------
                        $('#fechaV').val(datos[0]['fechaV']);
                        $('#fechaV_h').val(datos[0]['fechaV']);
                        var mydate = new Date($('#fechaV').val());
                        mydate.setDate(mydate.getDate() + 1)
                        if (10 > mydate.getMonth() + 1 > 0) {
                            var mes = '0' + (mydate.getMonth() + 1).toString()
                        } else {
                            var mes = (mydate.getMonth() + 1)
                        }
                        if (10 > mydate.getDate() > 0) {
                            var dia = '0' + (mydate.getDate()).toString()
                        } else {
                            var dia = mydate.getDate()
                        }

                        $('#fechaV').val(mydate.getFullYear() + '-' + mes + '-' + dia);
                        var mydate1 = new Date($('#fechaV_h').val());
                        mydate1.setDate(mydate1.getDate() + 1)
                        if (10 > mydate1.getMonth() + 1 > 0) {
                            var mes1 = '0' + (mydate1.getMonth() + 1).toString()
                        } else {
                            var mes1 = (mydate1.getMonth() + 1)
                        }
                        if (10 > mydate1.getDate() > 0) {
                            var dia1 = '0' + (mydate1.getDate()).toString()
                        } else {
                            var dia1 = mydate1.getDate()
                        }

                        $('#fechaV_h').val(mydate1.getFullYear() + '-' + mes1 + '-' + dia1);
                        var fechaV = ($('#fechaV').val()).split('-').reverse().join('-');
                        var fechaV_h = ($('#fechaV_h').val()).split('-').reverse().join('-');
                        $('#fechaV').val(fechaV);
                        $('#fechaV_h').val(fechaV_h);
                        $('#fechaV').pickadate('picker').set('select', fechaV);
                        $('#fechaV_h').pickadate('picker').set('select', fechaV_h);
                        //-----

                        alertify.success('Tarjeta Existente');
                        $('#tarjetaexistente').modal('hide');
                    }
                }
            });
        }

        function selecTarjetaNew() {
            $('#cvv').val('');
            $('#fechaV').val('');
            $('#titular_tarjeta').val('');
            $('#bancoT').val('');
            $('#cvv_h').val('');
            $('#fechaV_h').val('');
            $('#titular_tarjeta_h').val('');
            $('#bancoT_h').val('');
            $('#tarjetaexistente').modal('hide');
            $('#alert').val('1');
            $('#id_tarjeta').val('0');
            alertify.success('Ingrese los datos faltantes de la Tarjeta');
        }
    </script>

</body>

</html>