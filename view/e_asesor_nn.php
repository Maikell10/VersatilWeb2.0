<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}

require_once '../Controller/Asesor.php';

$id_asesor = $_GET['id_asesor'];
$a = $_GET['a'];
$id = $_GET['id'];
$nombre = $_GET['nombre'];
$cel = $_GET['cel'];
$email = $_GET['email'];
$banco = $_GET['banco'];
$tipo_cuenta = $_GET['tipo_cuenta'];
$num_cuenta = $_GET['num_cuenta'];
$obs = $_GET['obs'];
$f_nac_a = date("Y-m-d", strtotime($_GET['f_nac_a']));
$act = $_GET['act'];
$pago = $_GET['pago'];
$f_pago = $_GET['f_pago'];
$monto = $_GET['monto'];
$nopre1 = $_GET['nopre1'];
$nopre1_renov = $_GET['nopre1_renov'];
$gc_viajes = $_GET['gc_viajes'];
$gc_viajes_renov = $_GET['gc_viajes_renov'];

if ($nopre1 != null) {
    $asesor = $obj->editarAsesorA($id_asesor, $id, $nombre, $cel, $email, $banco, $tipo_cuenta, $num_cuenta, $obs, $act, $nopre1, $nopre1_renov, $gc_viajes, $gc_viajes_renov,$f_nac_a);
}

if ($nopre1 == null) {
    $asesor = $obj->editarAsesor($id_asesor, $a, $id, $nombre, $cel, $email, $banco, $tipo_cuenta, $num_cuenta, $obs, $act, $pago, $f_pago, $monto,$f_nac_a);
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

    <div class="card">


        <div class="card-header p-5 animated bounceInDown">
            <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                <- Regresar</a> <br><br>
                    <div class="ml-5 mr-5">
                        <h1 class="font-weight-bold text-center"><i class="fas fa-user" aria-hidden="true"></i>&nbsp;Editando Asesor
                        </h1>
                    </div>
        </div>

        <div class="card-body p-5 animated bounceInUp" id="tablaLoad">

        </div>


    </div>



    <?php require_once dirname(__DIR__) . '\layout\footer_b.php'; ?>

    <?php require_once dirname(__DIR__) . '\layout\footer.php'; ?>

    <script src="../assets/view/b_poliza.js"></script>

    <script>
        $(document).ready(function() {
            alertify.defaults.theme.ok = "btn blue-gradient";
            alertify.defaults.theme.cancel = "btn young-passion-gradient text-white";
            alertify.defaults.theme.input = "form-control";

            alertify.alert('Asesor Editado con Exito!', 'Asesor Editado Satisfactoriamente',
                function() {
                    alertify.success('Ok');
                    window.close();
                });
        });
    </script>

</body>

</html>