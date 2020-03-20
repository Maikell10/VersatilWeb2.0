<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}

$pag = 'e_cliente';
require_once '../Controller/Cliente.php';

$id_titular = $_GET['id_titular'];
$nombre = $_GET['nombre'];
$apellido = $_GET['apellido'];
$ci = $_GET['ci'];
$f_nac = $_GET['f_nac'];
$cel = $_GET['cel'];
$telf = $_GET['telf'];
$email = $_GET['email'];
$direcc = $_GET['direcc'];

$f_nac_1 = date("Y-m-d", strtotime($_GET['f_nac']));

$cliente = $obj->editarCliente($id_titular, $nombre, $apellido, $ci, $f_nac_1, $cel, $telf, $email, $direcc);

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
                        <h1 class="font-weight-bold text-center"><i class="fas fa-user" aria-hidden="true"></i>&nbsp;Editando Cliente
                        </h1>
                    </div>
        </div>

        <div class="card-body p-5 animated bounceInUp" id="tablaLoad">

        </div>


    </div>



    <?php require_once dirname(__DIR__) . '\layout\footer_b.php'; ?>

    <?php require_once dirname(__DIR__) . '\layout\footer.php'; ?>

    <script src="../assets/view/b_cliente.js"></script>

    <script>
        $(document).ready(function() {
            alertify.defaults.theme.ok = "btn blue-gradient";
            alertify.defaults.theme.cancel = "btn young-passion-gradient text-white";
            alertify.defaults.theme.input = "form-control";

            alertify.alert('Cliente Editado con Exito!', 'Cliente Editado Satisfactoriamente',
                function() {
                    alertify.success('Ok');
                    window.close();
                });
        });
    </script>

</body>

</html>