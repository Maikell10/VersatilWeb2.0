<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
require_once '../Controller/Poliza.php';

$id_usuario = $_GET['id_usuario'];

$nombre = $_GET['nombre'];
$apellido = $_GET['apellido'];
$ci = $_GET['ci'];
$zprod = $_GET['zprod'];

$seudonimo = $_GET['seudonimo'];
$clave = $_GET['clave'];
$id_permiso = $_GET['id_permiso'];

$activo = $_GET['activo'];
$asesor = $_GET['asesor'];

if ($id_permiso != '3') {
    $asesor = '';
}

$usuario = $obj->editarUsuario($id_usuario, $nombre, $apellido, $ci, $zprod, $seudonimo, $clave, $id_permiso, $asesor, $activo);

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
                                <h1 class="font-weight-bold text-center"><i class="fas fa-user" aria-hidden="true"></i>&nbsp;Editando Usuario</h1>
                            </div>
                            <br>

                            <div class="col-md-10 mx-auto">

                            </div>

                            <br><br><br>
                </div>
            <?php } ?>
        </div>





        <?php require_once dirname(__DIR__) . '\layout\footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . '\layout\footer.php'; ?>

        <script>
            $(document).ready(function() {
                alertify.defaults.theme.ok = "btn blue-gradient";
                alertify.defaults.theme.cancel = "btn young-passion-gradient text-white";
                alertify.defaults.theme.input = "form-control";

                alertify.alert('Usuario Editado con Exito!', 'Usuario Editado Satisfactoriamente',
                    function() {
                        alertify.success('Ok');
                        window.close();
                    });
            });
        </script>

        <script src="../assets/view/b_poliza.js"></script>
</body>

</html>