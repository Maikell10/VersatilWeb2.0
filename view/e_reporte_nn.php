<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

require_once '../Controller/Poliza.php';

$id_rep_com = $_GET['id_rep_com'];

$f_rep_1 = date("Y-m-d", strtotime($_GET['f_rep']));
$f_pago_1 = date("Y-m-d", strtotime($_GET['f_pago']));

$primat_com = $_GET['primat_com'];
$comt = $_GET['comt'];

$rep_com = $obj->editarRepCom($id_rep_com, $f_rep_1, $f_pago_1, $primat_com, $comt);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'navigation.php'; ?>
    <br><br><br><br><br><br>

    <div>
        <div class="card">

            <?php if ($_SESSION['id_permiso'] != 3) { ?>
                <div class="card-header p-5 animated bounceInDown">
                    <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                        <- Regresar</a> <br><br>
                            <div class="ml-5 mr-5">
                                <h1 class="font-weight-bold text-center"><i class="fas fa-user" aria-hidden="true"></i>&nbsp;Editando Fechas del Reporte de Comisión</h1>
                            </div>
                            <br>

                            <div class="col-md-10 mx-auto">

                            </div>

                            <br><br><br>
                </div>
            <?php } ?>
        </div>





        <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'footer.php'; ?>

        <script>
            $(document).ready(function() {
                alertify.defaults.theme.ok = "btn blue-gradient";
                alertify.defaults.theme.cancel = "btn young-passion-gradient text-white";
                alertify.defaults.theme.input = "form-control";

                alertify.alert('Reporde Editado con Exito!', 'Reporte Editado Satisfactoriamente',
                    function() {
                        alertify.success('Ok');
                        window.close();
                    });
            });
        </script>

        <script src="../assets/view/b_poliza.js"></script>
</body>

</html>