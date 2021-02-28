<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

require_once '../../Controller/Poliza.php';

$_SESSION['creado'] = 0;

$id_rep = $_POST['id_rep'];
$fhastaP = $_POST['f_hasta'];
$f_pagoGcP = $_POST['f_pagoGc'];
$fhasta = date("Y-m-d", strtotime($fhastaP));
$f_pagoGc = date("Y-m-d", strtotime($f_pagoGcP));
$idcia = $_POST['cia'];
$cant_poliza = $_POST['cant_poliza'];

$comentario_rep = $_POST['comentario_rep'];

$prima_comt = number_format($_POST['primat_comt'], 2, '.', '');
$comt = number_format($_POST['comtt'], 2, '.', '');


if ($id_rep == 0) {

    $rep_com = $obj->agregarRepCom($fhasta, $f_pagoGc, $idcia, $prima_comt, $comt, $comentario_rep);
    $rep_comU = $obj->get_last_element('rep_com', 'id_rep_com');

    for ($i = 0; $i < $cant_poliza; $i++) {
        $f_pago = date("Y-m-d", strtotime($_POST['f_pago' . $i]));

        if ($_POST['id_poliza' . $i] == '0') {
            $id_poliza_u = $obj->get__last_poliza_by_id($_POST['n_poliza' . $i]);

            $comision = $obj->agregarCom($rep_comU[0]['id_rep_com'], $_POST['n_poliza' . $i], $_POST['codasesor' . $i], $f_pago, $_POST['prima' . $i], $_POST['comision' . $i], $id_poliza_u[0]['id_poliza']);
        } else {
            $comision = $obj->agregarCom($rep_comU[0]['id_rep_com'], $_POST['n_poliza' . $i], $_POST['codasesor' . $i], $f_pago, $_POST['prima' . $i], $_POST['comision' . $i], $_POST['id_poliza' . $i]);
        }
    }

    $id_rep = $rep_comU[0]['id_rep_com'];
} else {

    for ($i = 0; $i < $cant_poliza; $i++) {

        $f_pago = date("Y-m-d", strtotime($_POST['f_pago' . $i]));

        if ($_POST['id_poliza' . $i] == '0') {
            $id_poliza_u = $obj->get__last_poliza_by_id($_POST['n_poliza' . $i]);

            $comision = $obj->agregarCom($id_rep, $_POST['n_poliza' . $i], $_POST['codasesor' . $i], $f_pago, $_POST['prima' . $i], $_POST['comision' . $i], $id_poliza_u[0]['id_poliza']);
        } else {
            $comision = $obj->agregarCom($id_rep, $_POST['n_poliza' . $i], $_POST['codasesor' . $i], $f_pago, $_POST['prima' . $i], $_POST['comision' . $i], $_POST['id_poliza' . $i]);
        }
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


            <div class="card-header p-5 animated bounceInDown"> <br><br>
                <div class="ml-5 mr-5 text-center">
                    <h1 class="font-weight-bold">Carga de Reporte de Comisiones</h1>
                </div>
                <br><br><br><br>
            </div>

            <input type="hidden" class="form-control" id="id_reporte" name="id_reporte" value="<?= $id_rep;?>">
        </div>





        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer.php'; ?>

        <script>
            $(document).ready(function() {
                alertify.defaults.theme.ok = "btn blue-gradient";
                alertify.defaults.theme.cancel = "btn young-passion-gradient text-white";
                alertify.defaults.theme.input = "form-control";

                rep = $('#id_reporte').val()

                alertify.confirm('Reporte de Comisiones Cargado con Exito!', '¿Desea Cargar la Conciliación Bancaria?',
                    function() {
                        window.location.replace("crear_conciliacion.php?id_rep_com=" + rep);
                        alertify.success('Ok')
                    },
                    function() {
                        setTimeout(()=>{
                            alertify.confirm('Reporte de Comisiones Cargado con Exito!', '¿Desea Cargar un nuevo Reporte?',
                                function() {
                                    window.location.replace("crear_comision.php?cond=1");
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
                        }, 1)
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