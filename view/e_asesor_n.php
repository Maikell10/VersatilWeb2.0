<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}

require_once '../Controller/Asesor.php';

$id_asesor = $_POST['id_asesor'];
$a = $_POST['a'];
$id = $_POST['id'];
$nombre = $_POST['nombre'];
$cel = $_POST['cel'];
$email = $_POST['email'];
$banco = $_POST['banco'];
$tipo_cuenta = $_POST['tipo_cuenta'];
$num_cuenta = $_POST['num_cuenta'];
$obs = $_POST['obs'];
$f_nac_a = $_POST['f_nac_a'];
$act = $_POST['act'];
$pago = $_POST['pago'];
$f_pago = $_POST['f_pago'];
$monto = $_POST['monto'];
$estatus = ($act == 1) ? 'Activo' : 'Inactivo';

$nopre1 = $_POST['nopre1'];
$nopre1_renov = $_POST['nopre1_renov'];
$gc_viajes = $_POST['gc_viajes'];
$gc_viajes_renov = $_POST['gc_viajes_renov'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . '\layout\header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . '\layout\navigation.php'; ?>
    <br><br><br><br><br><br>

    <div class="card">


        <div class="card-header p-5 animated bounceInDown">
            <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                <- Regresar</a> <br><br>
                    <div class="ml-5 mr-5">
                        <h1 class="font-weight-bold text-center"><i class="fas fa-user" aria-hidden="true"></i>&nbsp;Previsualizar Edición del Asesor
                        </h1>
                    </div>
        </div>

        <div class="card-body p-5 animated bounceInUp" id="tablaLoad">
            <form class="form-horizontal" id="frmnuevo">
                <div class="table-responsive-xl">
                    <table class="table table-hover table-striped table-bordered" width="100%">
                        <thead class="heavy-rain-gradient">
                            <th class="text-black font-weight-bold">ID Asesor</th>
                            <th class="text-black font-weight-bold">Nombre Asesor</th>
                            <th class="text-black font-weight-bold">E-Mail</th>
                            <th class="text-black font-weight-bold">Cel</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="input-group md-form my-n1 grey lighten-2">
                                        <input type="text" class="form-control" name="id" readonly="readonly" value="<?= $id; ?>">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group md-form my-n1 grey lighten-2">
                                        <input type="text" class="form-control" name="nombre" readonly="readonly" value="<?= $nombre; ?>">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group md-form my-n1 grey lighten-2">
                                        <input type="text" class="form-control" name="email" readonly="readonly" value="<?= $email; ?>">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group md-form my-n1 grey lighten-2">
                                        <input type="text" class="form-control" name="cel" readonly="readonly" value="<?= $cel; ?>">
                                    </div>
                                </td>
                            </tr>

                            <tr class="heavy-rain-gradient">
                                <th class="text-black font-weight-bold">Banco</th>
                                <th class="text-black font-weight-bold">Tipo de Cuenta</th>
                                <?php if ($a == 2) { ?>
                                    <th>Monto</th>
                                <?php } ?>
                                <th colspan="2" class="text-black font-weight-bold">N Cuenta</th>
                            </tr>
                            <tr style="background-color: white">
                                <td>
                                    <div class="input-group md-form my-n1 grey lighten-2">
                                        <input type="text" class="form-control" name="banco" readonly="readonly" value="<?= $banco; ?>">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group md-form my-n1 grey lighten-2">
                                        <input type="text" class="form-control" name="tipo_cuenta" readonly="readonly" value="<?= $tipo_cuenta; ?>">
                                    </div>
                                </td>
                                <?php if ($a == 2) { ?>
                                    <td>
                                        <div class="input-group md-form my-n1 grey lighten-2">
                                            <input type="text" class="form-control" name="monto" readonly="readonly" value="<?= $monto; ?>">
                                        </div>
                                    </td>
                                <?php } ?>
                                <td colspan="2">
                                    <div class="input-group md-form my-n1 grey lighten-2">
                                        <input type="text" class="form-control" name="num_cuenta" readonly="readonly" value="<?= $num_cuenta; ?>">
                                    </div>
                                </td>
                            </tr>

                            <?php if ($a == 3) { ?>
                                <tr class="heavy-rain-gradient">
                                    <th class="text-black font-weight-bold">Forma de Pago</th>
                                    <th class="text-black font-weight-bold">Frecuencia de Pago</th>
                                    <th colspan="2" class="text-black font-weight-bold">Monto</th>
                                </tr>
                                <tr style="background-color: white">
                                    <td>
                                        <div class="input-group md-form my-n1 grey lighten-2">
                                            <input type="text" class="form-control" name="f_pago" readonly="readonly" value="<?= $f_pago; ?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group md-form my-n1 grey lighten-2">
                                            <input type="text" class="form-control" name="pago" readonly="readonly" value="<?= $pago; ?>">
                                        </div>
                                    </td>
                                    <td colspan="2">
                                        <div class="input-group md-form my-n1 grey lighten-2">
                                            <input type="text" class="form-control" name="monto" readonly="readonly" value="<?= $monto; ?>">
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>

                            <tr class="heavy-rain-gradient">
                                <th colspan="2" class="text-black font-weight-bold">Observaciones</th>
                                <th class="text-black font-weight-bold">Fecha de Nacimiento</th>
                                <th class="text-black font-weight-bold">Estatus</th>
                            </tr>
                            <tr style="background-color: white">
                                <td colspan="2">
                                    <div class="input-group md-form my-n1 grey lighten-2">
                                        <input type="text" class="form-control" name="obs" readonly="readonly" value="<?= $obs; ?>">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group md-form my-n1 grey lighten-2">
                                        <input type="text" class="form-control" name="f_nac_a" readonly="readonly" value="<?= $f_nac_a; ?>">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group md-form my-n1 grey lighten-2">
                                        <input type="text" class="form-control" name="act" readonly="readonly" value="<?= $estatus; ?>">
                                    </div>
                                </td>
                            </tr>

                            <?php
                            if ($nopre1 != null) {
                            ?>
                                <tr class="heavy-rain-gradient">
                                    <th class="text-black font-weight-bold">%GC (Nuevo)</th>
                                    <th class="text-black font-weight-bold">%GC (Renovación)</th>
                                    <th class="text-black font-weight-bold">%GC Viajes (Nuevo)</th>
                                    <th class="text-black font-weight-bold">%GC Viajes (Renovación)</th>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="input-group md-form my-n1 grey lighten-2">
                                            <input type="text" class="form-control" name="nopre1" readonly value="<?= $nopre1; ?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group md-form my-n1 grey lighten-2">
                                            <input type="text" class="form-control" name="nopre1_renov" readonly value="<?= $nopre1_renov; ?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group md-form my-n1 grey lighten-2">
                                            <input type="text" class="form-control" name="gc_viajes" readonly value="<?= $gc_viajes; ?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group md-form my-n1 grey lighten-2">
                                            <input type="text" class="form-control" name="gc_viajes_renov" readonly value="<?= $gc_viajes_renov; ?>">
                                        </div>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>


                        </tbody>
                    </table>
                </div>
                <hr>
                <center>
                    <a name="enlace" href="e_asesor_nn.php?id_asesor=<?= $id_asesor; ?>&nombre=<?= $nombre; ?>&email=<?= $email; ?>&id=<?= $id; ?>&cel=<?= $cel; ?>&banco=<?= $banco; ?>&tipo_cuenta=<?= $tipo_cuenta; ?>&num_cuenta=<?= $num_cuenta; ?>&obs=<?= $obs; ?>&f_nac_a=<?= $f_nac_a; ?>&a=<?= $a; ?>&act=<?= $act; ?>&nopre1=<?= $nopre1; ?>&nopre1_renov=<?= $nopre1_renov; ?>&gc_viajes=<?= $gc_viajes; ?>&gc_viajes_renov=<?= $gc_viajes_renov; ?>&pago=<?= $pago; ?>&f_pago=<?= $f_pago; ?>&monto=<?= $monto; ?>" class="btn blue-gradient btn-lg btn-rounded">Confirmar</a>
                </center>
                <hr>
            </form>
        </div>


    </div>



    <?php require_once dirname(__DIR__) . '\layout\footer_b.php'; ?>

    <?php require_once dirname(__DIR__) . '\layout\footer.php'; ?>

    <script src="../assets/view/b_poliza.js"></script>

</body>

</html>