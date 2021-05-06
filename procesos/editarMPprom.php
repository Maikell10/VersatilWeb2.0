<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

require_once '../Controller/Poliza.php';

$id_mensaje_p1 = $_GET['id_mensaje_p1'];

$fEnvio = $_GET['fEnvio'];
$fEnvio = date('Y-m-d', strtotime($fEnvio));
$frecuencia = $_GET['frecuencia'];

// --------- EDITAR ----
$mensajeP1 = $obj->updateMensajeP1($id_mensaje_p1, $fEnvio, $frecuencia);
if($mensajeP1 != 1) {
    echo "<script type=\"text/javascript\">
            alert('Ocurrió un error en la carga. Actualiza el navegador e intenta de nuevo');
            window.history.back();
        </script>";
    exit;
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'navigation.php'; ?>
    <br><br><br><br><br><br>

    <div>
        <div class="card">


            <div class="card-header p-5 animated bounceInDown">
                <div class="ml-5 mr-5 text-center">
                    <h1 class="font-weight-bold"><i class="fas fa-plus" aria-hidden="true"></i> Re-Programar Mensaje de Promoción a Clientes</h1>
                </div>
                <br>

                <div class="col-md-10 mx-auto">


                </div>
                <br><br><br>
            </div>

        </div>





        <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'footer.php'; ?>

        <script src="../../assets/view/b_poliza.js"></script>

        <script>
            $(document).ready(function() {
                alertify.defaults.theme.ok = "btn blue-gradient";
                alertify.defaults.theme.cancel = "btn young-passion-gradient text-white";
                alertify.defaults.theme.input = "form-control";
                
                alertify.confirm('Promoción Re-Programada con Exito!', '¿Desea Cargar una nueva Promoción?',
                    function() {
                        window.location.replace("../view/crm.php");
                        alertify.success('Ok')
                    },
                    function() {
                        window.location.replace("../");
                        alertify.error('Cancel')
                    }).set('labels', {
                    ok: 'Sí',
                    cancel: 'No'
                }).set({
                    transition: 'zoom'
                }).show();
            });
        </script>
</body>

</html>