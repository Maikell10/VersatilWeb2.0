<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}
require_once '../../Controller/Poliza.php';

$primat_com = $_POST['primat_com'];
$primat_comt = $_POST['primat_comt'];

$comt = $_POST['comt'];
$comtt = $_POST['comtt'];
$f_hasta = $_POST['f_hasta'];
$f_pagoGc = $_POST['f_pagoGc'];
$id_rep = $_POST['id_rep'];

$i = 0;
$_POST['n_poliza' . $i];
$nom_titu0 = $_POST['nom_titu0'];
$f_pago0 = $_POST['f_pago0'];
$prima = $_POST['prima0'];
$comision = $_POST['comision0'];
$asesor0 = $_POST['asesor0'];
$codasesor0 = $_POST['codasesor0'];


$idcia = $_POST['cia'];
$cant_poliza = $_POST['cant_poliza'];

$cia = $obj->get_element_by_id('dcia', 'idcia', $idcia);

$historial = 0;
if ($id_rep == 0) {
} else {
    $historial = 1;
    $historialC = $obj->get_comision($id_rep);
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


            <div class="card-header p-5 animated bounceInDown">
                <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                    <- Regresar</a> <br><br>
                        <div class="ml-5 mr-5 text-center">
                            <h1 class="font-weight-bold"><i class="fas fa-info-circle" aria-hidden="true"></i>&nbsp;Vista Previa de la Carga para la Compañía: <?= $cia[0]['nomcia']; ?></h1>
                        </div>
                        <br>

                        <div class="col-md-11 mx-auto">
                            <form action="comision_n.php" class="form-horizontal" method="POST" id="frmnuevo">
                                <div class="table-responsive-xl">
                                    <table class="table table-striped table-hover" width="100%" id="iddatatable">
                                        <thead>
                                            <tr class="heavy-rain-gradient">
                                                <th colspan="2" class="text-black font-weight-bold">Fecha Pago GC *</th>
                                                <th colspan="2" class="text-black font-weight-bold">Fecha Hasta *</th>
                                                <th colspan="2" class="text-black font-weight-bold">Total Prima Cobrada</th>
                                                <th class="text-black font-weight-bold">Total Comision Cobrada</th>
                                                <th hidden>id reporte</th>
                                                <th hidden>cia</th>
                                                <th hidden>cant_poliza</th>
                                                <th hidden>prima_comt</th>
                                                <th hidden>comt</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="2">
                                                    <div class="input-group md-form my-n1 grey lighten-2">
                                                        <input type="text" class="form-control" id="f_pagoGc" name="f_pagoGc" value="<?= $f_pagoGc; ?>" readonly>
                                                    </div>
                                                </td>
                                                <td colspan="2">
                                                    <div class="input-group md-form my-n1 grey lighten-2">
                                                        <input type="text" class="form-control" id="f_hasta" name="f_hasta" value="<?= $f_hasta; ?>" readonly>
                                                    </div>
                                                </td>
                                                <td colspan="2">
                                                    <div class="input-group md-form my-n1 grey lighten-2">
                                                        <input type="text" class="form-control" id="primat_com" name="primat_com" value="<?= $primat_com; ?>" readonly>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group md-form my-n1 grey lighten-2">
                                                        <input type="text" class="form-control" id="comt" name="comt" value="<?= $comt; ?>" readonly>
                                                    </div>
                                                </td>

                                                <td hidden><input type="text" class="form-control" id="id_rep" name="id_rep" value="<?= $id_rep; ?>"></td>
                                                <td hidden><input type="text" class="form-control" id="cia" name="cia" value="<?= $idcia; ?>"></td>
                                                <td hidden><input type="text" class="form-control" id="cant_poliza" name="cant_poliza" value="<?= $cant_poliza; ?>"></td>

                                                <td hidden><input type="text" class="form-control" id="primat_comt" name="primat_comt" value="<?= $_POST['primat_comt']; ?>"></td>
                                                <td hidden><input type="text" class="form-control" id="comtt" name="comtt" value="<?= $_POST['comtt']; ?>"></td>
                                            </tr>
                                            <tr class="heavy-rain-gradient text-nowrap">
                                                <th class="text-black font-weight-bold">N° de Póliza *</th>
                                                <th class="text-black font-weight-bold">Nombre Titular</th>
                                                <th class="text-black font-weight-bold">Fecha de Pago de la Prima *</th>
                                                <th class="text-black font-weight-bold">Prima Sujeta a Comisión *</th>
                                                <th class="text-black font-weight-bold">Comisión *</th>
                                                <th hidden>Comisión *</th>
                                                <th class="text-black font-weight-bold">% Comisión</th>
                                                <th class="text-black font-weight-bold">Asesor - Ejecutivo</th>
                                                <th hidden>Cod Asesor - Ejecutivo</th>
                                                <th hidden>id_poliza</th>
                                            </tr>
                                            <?php
                                            if ($historial == 1) {
                                                for ($i = 0; $i < sizeof($historialC); $i++) {
                                                    $totalprima = $totalprima + $historialC[$i]['prima_com'];
                                                    $totalComision = $totalComision + $historialC[$i]['comision'];

                                                    $asesor = $obj->get_asesor_por_cod($historialC[$i]['cod_vend']);
                                                    $nombrea = $asesor[0]['nombre'];
                                                    $nombre = $historialC[$i]['nombre_t'] . " " . $historialC[$i]['apellido_t'];
                                                    $newFPP = date("d/m/Y", strtotime($historialC[$i]['f_pago_prima']));
                                            ?>
                                                    <tr>
                                                        <td><?= $historialC[$i]['num_poliza']; ?></td>
                                                        <td><?= $nombre; ?></td>
                                                        <td><?= $newFPP; ?></td>
                                                        <td style="background-color: #E54848;color: white" align="right"><?= number_format($historialC[$i]['prima_com'], 2); ?></td>
                                                        <td align="right"><?= number_format($historialC[$i]['comision'], 2); ?></td>
                                                        <td style="text-align: center;"><?= number_format(($historialC[$i]['comision'] * 100) / $historialC[$i]['prima_com'], 2) . " %"; ?></td>
                                                        <td><?= $nombrea; ?></td>
                                                        <th hidden>id_poliza</th>
                                                        <th hidden>id_poliza</th>
                                                    </tr>
                                                <?php }
                                            }
                                            for ($i = 0; $i < $cant_poliza; $i++) {
                                                $totalprima = $totalprima + $_POST['prima' . $i];
                                                $totalComision = $totalComision + $_POST['comision' . $i];
                                                ?>
                                                <tr class="heavy-rain-gradient">
                                                    <td colspan="7"></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" id="<?= 'n_poliza' . $i; ?>" name="<?= 'n_poliza' . $i; ?>" value="<?= $_POST['n_poliza' . $i]; ?>" readonly>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" id="<?= 'nom_titu' . $i; ?>" name="<?= 'nom_titu' . $i; ?>" value="<?= $_POST['nom_titu' . $i]; ?>" readonly>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" id="<?= 'f_pago' . $i; ?>" name="<?= 'f_pago' . $i; ?>" value="<?= $_POST['f_pago' . $i]; ?>" readonly>
                                                        </div>
                                                    </td>
                                                    <td style="background-color: #E54848">
                                                        <div class="input-group md-form my-n1">
                                                            <input style="background-color: #E54848;color: white;text-align: right" type="text" class="form-control" id="<?= 'prima' . $i; ?>" name="<?= 'prima' . $i; ?>" value="<?= $_POST['prima' . $i]; ?>" readonly>
                                                        </div>
                                                    </td>


                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" id="comi" name="comi" value="<?= number_format($_POST['comision' . $i], 2); ?>" readonly style="text-align: right">
                                                        </div>
                                                    </td>
                                                    <td hidden><input type="text" class="form-control" id="<?= 'comision' . $i; ?>" name="<?= 'comision' . $i; ?>" value="<?= $_POST['comision' . $i]; ?>" readonly style="text-align: right"></td>

                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input style="text-align: center;" type="text" class="form-control" id="<?= 'comisionPor' . $i; ?>" name="<?= 'comisionPor' . $i; ?>" value="<?= $_POST['comisionPor' . $i]; ?>" readonly>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" id="<?= 'asesor' . $i; ?>" name="<?= 'asesor' . $i; ?>" value="<?= $_POST['asesor' . $i]; ?>" readonly>
                                                        </div>
                                                    </td>



                                                    <td hidden><input type="text" class="form-control" id="<?= 'codasesor' . $i; ?>" name="<?= 'codasesor' . $i; ?>" value="<?= $_POST['codasesor' . $i]; ?>"></td>

                                                    <td hidden><input type="text" class="form-control" id="<?= 'id_poliza' . $i; ?>" name="<?= 'id_poliza' . $i; ?>" value="<?= $_POST['id_poliza' . $i]; ?>"></td>
                                                </tr>

                                            <?php } ?>

                                            <tr class="heavy-rain-gradient">
                                                <td colspan="3" class="text-black font-weight-bold">Total</td>
                                                <td class="text-black font-weight-bold text-right"><?= number_format($totalprima, 2); ?></td>
                                                <td class="text-black font-weight-bold text-right"><?= number_format($totalComision, 2); ?></td>
                                                <td colspan="2" class="text-black font-weight-bold"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <center>
                                    <button type="submit" id="btnForm" class="btn blue-gradient btn-lg btn-rounded">Confirmar</button>
                                </center>
                            </form>
                        </div>
                        <br><br><br>
            </div>

        </div>





        <?php require_once dirname(__DIR__) . '\..\layout\footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . '\..\layout\footer.php'; ?>

        <script>
            $(document).ready(function() {
                alertify.defaults.theme.ok = "btn blue-gradient";
                alertify.defaults.theme.cancel = "btn young-passion-gradient text-white";
                alertify.defaults.theme.input = "form-control";

                if (<?= $_SESSION['creado']; ?> == '0') {
                    alertify.alert('Reporte ya Creado!', 'El Reporte ya fue creado',
                        function() {
                            history.go(-2)
                        });
                }

                $('#btnForm').click(function(e) {
                    e.preventDefault();
                    alertify.confirm('Atención!', '¿Está Seguro de Cargar las Comisiones?',
                        function() {
                            $('#frmnuevo').submit();
                        },
                        function() {
                            alertify.error('No se ha enviado la comisión');
                        }).set('labels', {
                        ok: 'Sí',
                        cancel: 'No'
                    }).set({
                        transition: 'zoom'
                    }).show();
                });
            });
        </script>

        <script src="../../assets/view/b_poliza.js"></script>
</body>

</html>