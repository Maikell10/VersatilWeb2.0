<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

require_once '../../Controller/Poliza.php';

$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$ci = $_POST['ci'];

$zprod = $_POST['zprod'];
$seudonimo = $_POST['seudonimo'];
$clave = $_POST['clave'];
$id_permiso = $_POST['id_permiso'];

$asesor = $_POST['asesor'];
$permiso_user = '';

if ($id_permiso == '1') {
    $permiso_user = 'ADMINISTRADOR';
}
if ($id_permiso == '2') {
    $permiso_user = 'USUARIO';
}
if ($id_permiso == '3') {
    $permiso_user = 'ASESOR';

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
    <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'navigation.php'; ?>
    <br><br><br><br><br><br>

    <div>
        <div class="card">

            <?php if ($_SESSION['id_permiso'] != 3) { ?>
                <div class="card-header p-5 animated bounceInDown">
                    <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                        <- Regresar</a> <br><br>
                            <div class="ml-5 mr-5">
                                <h1 class="font-weight-bold text-center"><i class="fas fa-user" aria-hidden="true"></i>&nbsp;Previsualizar Nuevo Usuario</h1>
                            </div>
                            <br>

                            <div class="col-md-10 mx-auto">
                                <form class="form-horizontal" id="frmnuevo" autocomplete="off" action="usuario_n.php" method="POST">
                                    <div class="table-responsive-xl">
                                        <table class="table" width="100%">
                                            <thead class="heavy-rain-gradient">
                                                <tr>
                                                    <th class="text-black font-weight-bold">Nombre del Usuario *</th>
                                                    <th class="text-black font-weight-bold">Apellido *</th>
                                                    <th class="text-black font-weight-bold">Cédula *</th>
                                                    <th class="text-black font-weight-bold">Z Producc *</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="nombre" readonly="readonly" value="<?= $nombre; ?>">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="apellido" readonly="readonly" value="<?= $apellido; ?>">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="ci" readonly="readonly" value="<?= $ci; ?>">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="zprod" readonly="readonly" value="<?= $zprod; ?>">
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr class="heavy-rain-gradient">
                                                    <th colspan="2" class="text-black font-weight-bold">Seudónimo</th>
                                                    <th class="text-black font-weight-bold">Clave</th>
                                                    <th class="text-black font-weight-bold">Permisos</th>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="seudonimo" readonly="readonly" value="<?= $seudonimo; ?>">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="clave" readonly="readonly" value="<?= $clave; ?>">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="permiso" readonly="readonly" value="<?= $permiso_user; ?>">
                                                        </div>
                                                    </td>
                                                </tr>

                                                <?php if ($id_permiso == '3') {
                                                ?>
                                                    <tr class="heavy-rain-gradient">
                                                        <th colspan="4" class="text-black font-weight-bold">Asesor Asociado</th>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4">
                                                            <div class="input-group md-form my-n1 grey lighten-2">
                                                                <input type="text" class="form-control" name="nombre_a" readonly="readonly" value="<?= utf8_encode($nombre_a); ?>">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <center>
                                        <a name="enlace" href="usuario_n.php?nombre=<?= $nombre; ?>&apellido=<?= $apellido; ?>&ci=<?= $ci; ?>&zprod=<?= $zprod; ?>&seudonimo=<?= $seudonimo; ?>&clave=<?= $clave; ?>&id_permiso=<?= $id_permiso; ?>&asesor=<?= $asesor; ?>" class="btn blue-gradient btn-lg btn-rounded">Confirmar</a>
                                    </center>
                                </form>
                            </div>

                            <br><br><br>
                </div>
            <?php } ?>
        </div>





        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer.php'; ?>

        <script>

        </script>

        <script src="../../assets/view/b_poliza.js"></script>
</body>

</html>