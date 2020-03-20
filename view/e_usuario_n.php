<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
require_once '../Controller/Poliza.php';

$id_usuario = $_POST['id_usuario'];

$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$ci = $_POST['ci'];
$zprod = $_POST['zprod'];

$seudonimo = $_POST['seudonimo'];
$clave = $_POST['clave'];
$id_permiso = $_POST['id_permiso'];

$activo = $_POST['activo'];
$asesor = $_POST['asesor'];

if ($id_permiso == '3') {

    $asesor1 = $obj->get_element_by_id('ena', 'cod', $asesor);
    $nombre_a = $asesor1[0]['idnom'];

    if (sizeof($asesor1) == null) {
        $asesor1 = $obj->get_element_by_id('enp', 'cod', $asesor);
        $nombre_a = $asesor1[0]['nombre'];
    }

    if (sizeof($asesor1) == null) {
        $asesor1 = $obj->get_element_by_id('enr', 'cod', $asesor);
        $nombre_a = $asesor1[0]['nombre'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . '\layout\header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . '\layout\navigation.php'; ?>
    <br><br><br><br><br><br>

    <div>
        <div class="card">

            <?php if ($_SESSION['id_permiso'] != 3) { ?>
                <div class="card-header p-5 animated bounceInDown">
                    <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                        <- Regresar</a> <br><br>
                            <div class="ml-5 mr-5">
                                <h1 class="font-weight-bold text-center"><i class="fas fa-user" aria-hidden="true"></i>&nbsp;Previsualizar Edición del Usuario</h1>
                            </div>
                            <br>

                            <div class="col-md-10 mx-auto">
                                <form class="form-horizontal" id="frmnuevo">
                                    <div class="table-responsive-xl">
                                        <table class="table" width="100%">
                                            <thead class="heavy-rain-gradient">
                                                <tr>
                                                    <th class="text-black font-weight-bold">Nombre Usuario</th>
                                                    <th class="text-black font-weight-bold">Apellido</th>
                                                    <th class="text-black font-weight-bold">Cédula</th>
                                                    <th class="text-black font-weight-bold">Z Producc</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="nombre" readonly value="<?= utf8_encode($nombre); ?>">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="apellido" readonly value="<?= utf8_encode($apellido); ?>">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="ci" readonly value="<?= $ci; ?>">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="zprod" readonly value="<?= $zprod; ?>">
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr class="heavy-rain-gradient">
                                                    <th class="text-black font-weight-bold">Seudónimo</th>
                                                    <th class="text-black font-weight-bold">Clave</th>
                                                    <th class="text-black font-weight-bold">Permiso</th>
                                                    <th class="text-black font-weight-bold">Activo</th>
                                                </tr>
                                                <?php
                                                if ($id_permiso == 1) {
                                                    $permiso = 'Administrador';
                                                }
                                                if ($id_permiso == 2) {
                                                    $permiso = 'Usuario';
                                                }
                                                if ($id_permiso == 3) {
                                                    $permiso = 'Asesor';
                                                }
                                                if ($activo == 0) {
                                                    $estado = 'Inactivo';
                                                } else {
                                                    $estado = 'Activo';
                                                }
                                                ?>
                                                <tr>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="seudonimo" readonly value="<?= $seudonimo; ?>">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="clave" readonly value="<?= $clave; ?>">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="permiso" readonly value="<?= $permiso; ?>">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="activo" readonly value="<?= $estado; ?>">
                                                        </div>
                                                    </td>
                                                </tr>

                                                <?php if ($id_permiso == '3') { ?>
                                                    <tr class="heavy-rain-gradient">
                                                        <th colspan="4" class="text-black font-weight-bold">Asesor Asociado</th>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4">
                                                            <div class="input-group md-form my-n1 grey lighten-2">
                                                                <input type="text" class="form-control" name="nombre_a" readonly value="<?= utf8_encode($nombre_a); ?>">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php } ?>

                                            </tbody>
                                        </table>
                                    </div>

                                    <center>
                                        <a name="enlace" href="e_usuario_nn.php?id_usuario=<?= $id_usuario; ?>&nombre=<?= $nombre; ?>&apellido=<?= $apellido; ?>&ci=<?= $ci; ?>&zprod=<?= $zprod; ?>&seudonimo=<?= $seudonimo; ?>&clave=<?= $clave; ?>&id_permiso=<?= $id_permiso; ?>&asesor=<?= $asesor; ?>&activo=<?= $activo; ?>" class="btn blue-gradient btn-lg btn-rounded">Confirmar</a>
                                    </center>
                                </form>
                            </div>

                            <br><br><br>
                </div>
            <?php } ?>
        </div>





        <?php require_once dirname(__DIR__) . '\layout\footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . '\layout\footer.php'; ?>

        <script>

        </script>

        <script src="../assets/view/b_poliza.js"></script>
</body>

</html>