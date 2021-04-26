<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

require_once '../Controller/Poliza.php';

$cia = $_GET['cia'];
$ramo = $_GET['ramo'];
$t_poliza = $_GET['t_poliza'];
$asesor = $_GET['asesor'];

$filtroTPoliza = $_GET['filtroTPoliza'];
$filtroCia = $_GET['filtroCia'];
$filtroRamo = $_GET['filtroRamo'];
$filtroAsesor = $_GET['filtroAsesor'];

$copiaAsesor = $_GET['copiaAsesor'];

if (!$cia == '') {
    $cia_para_recibir_via_url = stripslashes($cia);
    $cia_para_recibir_via_url = urldecode($cia_para_recibir_via_url);
    $cia = unserialize($cia_para_recibir_via_url);
}

if (!$ramo == '') {
    $ramo_para_recibir_via_url = stripslashes($ramo);
    $ramo_para_recibir_via_url = urldecode($ramo_para_recibir_via_url);
    $ramo = unserialize($ramo_para_recibir_via_url);
}

if (!$t_poliza == '') {
    $t_poliza_para_recibir_via_url = stripslashes($t_poliza);
    $t_poliza_para_recibir_via_url = urldecode($t_poliza_para_recibir_via_url);
    $t_poliza = unserialize($t_poliza_para_recibir_via_url);
}

if (!$asesor == '') {
    $asesor_para_recibir_via_url = stripslashes($asesor);
    $asesor_para_recibir_via_url = urldecode($asesor_para_recibir_via_url);
    $asesor = unserialize($asesor_para_recibir_via_url);
}

$mensajeC1 = $obj->agregarMensajeC1($filtroTPoliza, $filtroCia, $filtroRamo, $filtroAsesor, $copiaAsesor);
if($mensajeC1 == 1) {
    $mensajeC1 = $obj->get_last_element('mensaje_c1', 'id_mensaje_c1');

    $titulares = $obj->get_birthdays_filter($asesor, $cia, $ramo, $t_poliza);
    for ($i=0; $i < sizeof($titulares); $i++) { 
        $mensajeC2 = $obj->agregarMensajeC2($mensajeC1[0]['id_mensaje_c1'], $titulares[$i]['id_titular']);
    }
    
    $origen = "../assets/img/tarjeta_birthday.png";
    
    $destino = '../assets/img/crm/'; 
    copy($origen, $destino . "" . $mensajeC1[0]['id_mensaje_c1'] . ".jpg");
} else {
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
                    <h1 class="font-weight-bold"><i class="fas fa-plus" aria-hidden="true"></i> Programar Mensaje de Cumpleaños a Clientes</h1>
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
                
                alertify.confirm('Mensaje Programado con Exito!', '¿Desea Cargar un nuevo Mensaje?',
                    function() {
                        window.location.replace("../view/crm/b_mensaje.php");
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