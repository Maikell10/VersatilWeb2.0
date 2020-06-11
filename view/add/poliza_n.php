<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'add/poliza_n';

require_once '../../Controller/Poliza.php';
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


            <div class="card-header p-5 animated bounceInDown">
                <div class="ml-5 mr-5 text-center">
                    <h1 class="font-weight-bold"><i class="fas fa-plus" aria-hidden="true"></i> Crear Póliza</h1>
                </div>
                <br>

                <div class="col-md-10 mx-auto">


                </div>
                <br><br><br>
            </div>

        </div>





        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer.php'; ?>

        <script>
            $(document).ready(function() {
                alertify.defaults.theme.ok = "btn blue-gradient";
                alertify.defaults.theme.cancel = "btn young-passion-gradient text-white";
                alertify.defaults.theme.input = "form-control";

                alertify.confirm('Desea Cargar la Póliza en PDF?', '¿Desea Cargar la Póliza en PDF?',
                    function() {
                        window.location.replace("subir_pdf.php?cond=1&id_poliza=<?= $u_p; ?>");
                        alertify.success('Ok')
                    },
                    function() {

                        window.location.replace("poliza_nn.php");
                        alertify.error('No realizó carga en pdf')
                    }).set('labels', {
                    ok: 'Sí',
                    cancel: 'No'
                }).set({
                    transition: 'zoom'
                }).show();
            });
        </script>

        <script src="../../assets/view/b_poliza.js"></script>
</body>

</html>