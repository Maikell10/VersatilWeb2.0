<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

require_once '../Controller/Asesor.php';

$id_asesor = $_GET['id_asesor'];
$a = $_GET['a'];

if ($a == 1) {
    $asesor = $obj->get_element_by_id('ena', 'idena', $id_asesor);
    $nombre = $asesor[0]['idnom'];
}

if ($a == 2) {
    $asesor = $obj->get_element_by_id('enp', 'id_enp', $id_asesor);
    $nombre = $asesor[0]['nombre'];
}

if ($a == 3) {
    $asesor = $obj->get_element_by_id('enr', 'id_enr', $id_asesor);
    $nombre = $asesor[0]['nombre'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'navigation.php'; ?>
    <br><br><br><br><br><br>

    <div class="card">


        <div class="card-header p-5 animated bounceInDown">
            <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                <- Regresar</a> <br><br>
                    <div class="ml-5 mr-5">
                        <h1 class="font-weight-bold">Asesor: <?= utf8_encode($nombre); ?></h1>
                        <h2 class="title">Cod: <?= $asesor[0]['cod']; ?></h2>
                    </div>
        </div>

        <div class="card-body p-5 animated bounceInUp" id="tablaLoad">
            <form class="form-horizontal" id="frmnuevo" action="e_asesor_n.php" method="POST" autocomplete="off">
                <div class="table-responsive-xl">
                    <table class="table table-hover table-striped table-bordered" width="100%">
                        <thead class="blue-gradient text-white">
                            <th>ID Asesor</th>
                            <th>Nombre Asesor</th>
                            <th>E-Mail</th>
                            <th>Cel</th>
                            <th hidden>id</th>
                            <th hidden>a</th>
                        </thead>
                        <tbody>
                            <tr style="background-color: white">
                                <td>
                                    <div class="input-group md-form my-n1">
                                        <input type="number" step="0.01" class="form-control" name="id" required value="<?= $asesor[0]['id']; ?>">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group md-form my-n1">
                                        <input type="text" class="form-control" name="nombre" required value="<?= utf8_encode($nombre); ?>" onkeyup="mayus(this);">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group md-form my-n1">
                                        <input type="text" class="form-control" name="email" required value="<?= $asesor[0]['email']; ?>">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group md-form my-n1">
                                        <input type="text" class="form-control" name="cel" required value="<?= $asesor[0]['cel']; ?>">
                                    </div>
                                </td>
                                <td hidden><input type="text" class="form-control" name="id_asesor" required value="<?= $id_asesor; ?>"></td>
                                <td hidden><input type="text" class="form-control" name="a" required value="<?= $a; ?>"></td>
                            </tr>

                            <tr class="blue-gradient text-white">
                                <th>Banco</th>
                                <th>Tipo de Cuenta</th>
                                <?php if ($a == 2) { ?>
                                    <th>Monto</th>
                                <?php } ?>
                                <th colspan="2">N Cuenta</th>
                            </tr>
                            <tr style="background-color: white">
                                <td>
                                    <div class="input-group md-form my-n1">
                                        <input type="text" class="form-control" name="banco" required value="<?= $asesor[0]['banco']; ?>">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group md-form my-n1">
                                        <input type="text" class="form-control" name="tipo_cuenta" required value="<?= $asesor[0]['tipo_cuenta']; ?>">
                                    </div>
                                </td>
                                <?php if ($a == 2) { ?>
                                    <td>
                                        <div class="input-group md-form my-n1">
                                            <input type="number" class="form-control" name="monto" required value="<?= $asesor[0]['monto']; ?>">
                                        </div>
                                    </td>
                                <?php } ?>
                                <td colspan="2">
                                    <div class="input-group md-form my-n1">
                                        <input type="text" class="form-control" name="num_cuenta" required value="<?= $asesor[0]['num_cuenta']; ?>">
                                    </div>
                                </td>
                            </tr>

                            <?php if ($a == 3) { ?>
                                <tr class="blue-gradient text-white">
                                    <th>Forma de Pago</th>
                                    <th>Frecuencia de Pago</th>
                                    <th colspan="2">Monto</th>
                                </tr>
                                <tr style="background-color: white">
                                    <td><select class="mdb-select md-form colorful-select dropdown-primary my-n2" name="f_pago" id="f_pago">
                                            <option value="EFECTIVO">EFECTIVO</option>
                                            <option value="TRANSFERENCIA">TRANSFERENCIA</option>
                                        </select>
                                    </td>
                                    <td><select class="mdb-select md-form colorful-select dropdown-primary my-n2" name="pago" id="pago">
                                            <option value="UNICO">UNICO</option>
                                            <option value="PORCENTUAL">PORCENTUAL</option>
                                        </select>
                                    </td>
                                    <td colspan="2">
                                        <div class="input-group md-form my-n1">
                                            <input type="number" class="form-control validanumericos3" id="monto" name="monto" required data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio [Sólo introducir números y punto (.) como separador decimal]" value="<?= $asesor[0]['monto']; ?>">
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>

                            <tr class="blue-gradient text-white">
                                <th colspan="2">Observaciones</th>
                                <th>Fecha de Nacimiento</th>
                                <th>Estatus</th>
                            </tr>
                            <tr style="background-color: white">
                                <td colspan="2">
                                    <div class="input-group md-form my-n1">
                                        <input onkeyup="mayus(this);" type="text" class="form-control" name="obs" value="<?= $asesor[0]['obs']; ?>">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group md-form my-n1">
                                        <input type="text" id="f_nac_a" name="f_nac_a" class="form-control datepicker" required data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio" autocomplete="off" value="<?= date("d-m-Y", strtotime($asesor[0]['f_nac_a'])); ?>">
                                    </div>
                                </td>
                                <td><select name="act" id="act" class="mdb-select md-form colorful-select dropdown-primary my-n2">
                                        <option value="0">Inactivo</option>
                                        <option value="1">Activo</option>
                                    </select>
                                </td>
                                <td hidden><input type="text" class="form-control" id="act_e" value="<?= $asesor[0]['act']; ?>"></td>
                            </tr>

                            <?php
                            if ($asesor[0]['nopre1'] != null) {
                            ?>
                                <tr class="blue-gradient text-white">
                                    <th>%GC (Nuevo)</th>
                                    <th>%GC (Renovación)</th>
                                    <th>%GC Viajes (Nuevo)</th>
                                    <th>%GC Viajes (Renovación)</th>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="input-group md-form my-n1">
                                            <input type="text" class="form-control validanumericos" name="nopre1" required value="<?= $asesor[0]['nopre1']; ?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group md-form my-n1">
                                            <input type="text" class="form-control validanumericos1" name="nopre1_renov" required value="<?= $asesor[0]['nopre1_renov']; ?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group md-form my-n1">
                                            <input type="text" class="form-control validanumericos2" name="gc_viajes" required value="<?= $asesor[0]['gc_viajes']; ?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group md-form my-n1">
                                            <input type="text" class="form-control validanumericos3" name="gc_viajes_renov" required value="<?= $asesor[0]['gc_viajes_renov']; ?>">
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
                    <button type="submit" style="width: 100%" data-toggle="tooltip" title="Previsualizar" class="btn dusty-grass-gradient btn-lg btn-block">Previsualizar Edición &nbsp;<i class="fas fa-check" aria-hidden="true"></i></button>
                </center>
                <hr>
            </form>
        </div>


    </div>



    <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'footer_b.php'; ?>

    <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'footer.php'; ?>

    <script src="../assets/view/b_poliza.js"></script>

    <script>
        $(document).ready(function() {

            $("#act option[value=" + $('#act_e').val() + "]").attr("selected", true);

        });
    </script>

</body>

</html>