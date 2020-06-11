<?php
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

require_once "../Model/Asesor.php";
$obj = new Asesor();

$datos = array(
    $_POST['cod'],
    $_POST['nombre_r'],
    $_POST['num_cuenta'],
    $_POST['banco'],
    $_POST['tipo_cuenta'],
    $_POST['email'],
    $_POST['id_r'],
    $_POST['cel'],
    $_POST['obs'],
    $_POST['pago'],
    $_POST['cuenta'],
    $_POST['currency'],
    $_POST['monto'],
    $_POST['f_pago']
);

$obj->agregarReferidor($datos);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'header.php'; ?>
</head>

<body class="profile-page ">
    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'navigation.php'; ?>
    <br><br><br><br><br><br>

    <div>
        <div class="card">
            <div class="card-header p-5 animated bounceInDown">
                <div class="ml-5 mr-5 text-center">
                    <h1 class="font-weight-bold"><i class="fas fa-plus" aria-hidden="true"></i> Cargando</h1>
                </div>
                <br>

                <div class="col-md-10 mx-auto">


                </div>
                <br><br><br>
            </div>

        </div>
    </div>


    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'footer_b.php'; ?>

    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'footer.php'; ?>







    <script>
        $(document).ready(function() {
            alertify.defaults.theme.ok = "btn blue-gradient";
            alertify.defaults.theme.cancel = "btn young-passion-gradient text-white";
            alertify.defaults.theme.input = "form-control";

            alertify.alert('Exito!', 'Referidor agregado satisfactoriamente, será redirigido a la página principal', function() {
                window.location.replace("../");
                alertify.success('Ok');
            });
        });
    </script>



</body>

</html>