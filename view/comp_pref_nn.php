<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
require_once '../Controller/Poliza.php';

$id_cia = $_POST['id_cia'];
$desdeP = $_POST['desdeP'];
$hastaP = $_POST['hastaP'];
$fdesdeP = date("Y-m-d", strtotime($desdeP));
$fhastaP = date("Y-m-d", strtotime($hastaP));

$asesor = $obj->get_element('ena', 'idnom');
$cant_a = sizeof($asesor);
for ($i = 0; $i < sizeof($asesor); $i++) {
    $cia_pref = $obj->agregarCiaPref($id_cia, $fdesdeP, $fhastaP, $asesor[$i]['cod'], $_POST['gc_asesor' . $i]);
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
                        <h1 class="font-weight-bold text-center">Creando Preferencial de la Cía</h1>
                    </div>
        </div>

        <div class="card-body p-5 animated bounceInUp" id="tablaLoad">

        </div>

    </div>



    <?php require_once dirname(__DIR__) . '\layout\footer_b.php'; ?>

    <?php require_once dirname(__DIR__) . '\layout\footer.php'; ?>

    <script src="../assets/view/b_comp.js"></script>

    <script>
        $(document).ready(function() {
            alertify.defaults.theme.ok = "btn blue-gradient";
            alertify.defaults.theme.cancel = "btn young-passion-gradient text-white";
            alertify.defaults.theme.input = "form-control";

            alertify.confirm('Cía Preferencial Cargada con Exito!', '¿Desea Cargar una nueva Cía Preferencial?',
                function() {
                    window.location.replace("b_comp.php?cond=1");
                    alertify.success('Ok')
                },
                function() {
                    window.location.replace("./");
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