<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
require_once '../Controller/Poliza.php';

$id_cia = $_GET['id_cia'];

$nombre_cia = $_GET['nombre_cia'];
$rif = $_GET['rif'];
$per_com = $_GET['per_com'];

$nombre1 = utf8_decode($_GET['nombre1']);
$cargo1 = utf8_decode($_GET['cargo1']);
$tel1 = $_GET['tel1'];
$cel1 = $_GET['cel1'];
$email1 = $_GET['email1'];

$nombre2 = utf8_decode($_GET['nombre2']);
$cargo2 = utf8_decode($_GET['cargo2']);
$tel2 = $_GET['tel2'];
$cel2 = $_GET['cel2'];
$email2 = $_GET['email2'];

$nombre3 = utf8_decode($_GET['nombre3']);
$cargo3 = utf8_decode($_GET['cargo3']);
$tel3 = $_GET['tel3'];
$cel3 = $_GET['cel3'];
$email3 = $_GET['email3'];

$nombre4 = utf8_decode($_GET['nombre4']);
$cargo4 = utf8_decode($_GET['cargo4']);
$tel4 = $_GET['tel4'];
$cel4 = $_GET['cel4'];
$email4 = $_GET['email4'];

$nombre5 = utf8_decode($_GET['nombre5']);
$cargo5 = utf8_decode($_GET['cargo5']);
$tel5 = $_GET['tel5'];
$cel5 = $_GET['cel5'];
$email5 = $_GET['email5'];

$cia = $obj->editarCia($id_cia, $nombre_cia, $rif, $per_com);

$e_cia = $obj->eliminarCiaContacto($id_cia);

if ($nombre1 != null) {
    $contacto1 = $obj->agregarContactoCia($id_cia, $nombre1, $cargo1, $tel1, $cel1, $email1);
}
if ($nombre2 != null) {
    $contacto2 = $obj->agregarContactoCia($id_cia, $nombre2, $cargo2, $tel2, $cel2, $email2);
}
if ($nombre3 != null) {
    $contacto3 = $obj->agregarContactoCia($id_cia, $nombre3, $cargo3, $tel3, $cel3, $email3);
}
if ($nombre4 != null) {
    $contacto4 = $obj->agregarContactoCia($id_cia, $nombre4, $cargo4, $tel4, $cel4, $email4);
}
if ($nombre5 != null) {
    $contacto5 = $obj->agregarContactoCia($id_cia, $nombre5, $cargo5, $tel5, $cel5, $email5);
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
                                <h1 class="font-weight-bold text-center"><i class="fa fa-briefcase" aria-hidden="true"></i>&nbsp;Editando Compañía</h1>
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

                alertify.alert('Compañía Editada con Exito!', 'Compañía Editada Satisfactoriamente',
                    function() {
                        alertify.success('Ok');
                        window.close();
                    });

            });
        </script>

        <script src="../assets/view/b_poliza.js"></script>
</body>

</html>