<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'e_cliente';
require_once '../../Controller/Poliza.php';

$id_poliza = $_GET['id_poliza'];
$fecha_old = $_GET['fecha_old'];
$fecha_oldD = $_GET['fecha_oldD'];

$fdesdeP = $_GET['desdeP'];
$fhastaP = $_GET['hastaP'];
$fdesdeP = date("Y-m-d", strtotime($fdesdeP));
$fhastaP = date("Y-m-d", strtotime($fhastaP));

$poliza_f = $obj->get_poliza_existente($_GET['n_poliza'], $fdesdeP, $fhastaP);

if ($poliza_f[0]['f_hastapoliza'] == $fhastaP && $poliza_f[0]['f_desdepoliza'] == $fdesdeP && $poliza_f[0]['cod_poliza'] == $_GET['n_poliza']) {
    $renov = 0;
} else {
    $renov = 1;

    $n_poliza = $_GET['n_poliza'];
    $fhoy = $_GET['fhoy'];
    $femisionP = $_GET['fhoy'];
    $t_cobertura = $_GET['t_cobertura'];
    $fdesdeP = $_GET['desdeP'];
    $fhastaP = $_GET['hastaP'];
    $fdesdeP = date("Y-m-d", strtotime($fdesdeP));
    $fhastaP = date("Y-m-d", strtotime($fhastaP));
    $currency = $_GET['currency'];
    $tipo_poliza = $_GET['tipo_poliza'];
    $sumaA = $_GET['sumaA'];
    $z_produc = ($_GET['z_produc'] == "PANAMA") ? 1 : 2;
    $codasesor = $_GET['asesor'];
    $ramo = $_GET['ramo'];
    $cia = $_GET['cia'];
    $titular = $_GET['titular'];
    $tomador = $_GET['tomador'];
    $t_cuenta = $_GET['t_cuenta'];
    $asesor_ind = $_GET['per_gc'];
    if ($asesor_ind == null) {
        $asesor_ind = 0;
    }
    $frec_renov = $_GET['frec_renov'];

    $n_recibo = $_GET['n_recibo'];
    $fdesde_recibo = $_GET['desde_recibo'];
    $fhasta_recibo = $_GET['hasta_recibo'];
    $fdesde_recibo = date("Y-m-d", strtotime($fdesde_recibo));
    $fhasta_recibo = date("Y-m-d", strtotime($fhasta_recibo));
    $prima = $_GET['prima'];
    $f_pago = $_GET['f_pago'];

    $n_cuotas = $_GET['n_cuotas'];
    $monto_cuotas = $_GET['monto_cuotas'];

    $tomador = $_GET['tomador'];
    $titular = $_GET['titular'];

    $idtomador = $obj->get_id_cliente($tomador);

    $idtitular = $obj->get_id_cliente($titular);

    $placa = $_GET['placa'];
    $tipo = $_GET['tipo'];
    $marca = $_GET['marca'];
    $modelo = $_GET['modelo'];
    $anio = $_GET['anio'];
    $color = $_GET['color'];
    $serial = $_GET['serial'];
    $categoria = $_GET['categoria'];

    if ($placa == null) {
        $placa = '-';
        $tipo = '-';
        $marca = '-';
        $modelo = '-';
        $anio = '-';
        $color = '-';
        $serial = '-';
        $categoria = '-';
    }

    $obs = $_GET['obs'];

    $forma_pago = $_GET['forma_pago'];
    $n_tarjeta = $_GET['n_tarjeta'];
    $cvv = $_GET['cvv'];
    $fechaV = $_GET['fechaV'];
    $fechaVP = date("Y-m-d", strtotime($fechaV));
    $titular_tarjeta = $_GET['titular_tarjeta'];
    $bancoT = $_GET['bancoT'];
    $id_tarjeta = $_GET['id_tarjeta'];
    if ($forma_pago == 2) {
        if ($_GET['alert'] == 1 || $_GET['condTar'] == 1) {
            $tarjeta = $obj->agregarTarjeta($n_tarjeta, $cvv, $fechaVP, $titular_tarjeta, $bancoT);

            $id_tarjeta = $obj->get_last_element('tarjeta', 'id_tarjeta');

            $id_tarjeta = $id_tarjeta[0]['id_tarjeta'];
        }
    }

    $poliza = $obj->agregarPoliza($n_poliza, $fhoy, $femisionP, $t_cobertura, $fdesdeP, $fhastaP, $currency, $tipo_poliza, $sumaA, $z_produc, $codasesor, $ramo, $cia, $idtitular[0]['id_titular'], $idtomador[0]['id_titular'], $asesor_ind, $t_cuenta, $_SESSION['id_usuario'], $obs, $prima, $frec_renov);

    $recibo = $obj->agregarRecibo($n_recibo, $fdesde_recibo, $fhasta_recibo, $prima, $f_pago, $n_cuotas, $monto_cuotas, $idtomador[0]['id_titular'], $idtitular[0]['id_titular'], $n_poliza, $forma_pago, $id_tarjeta);

    $vehiculo = $obj->agregarVehiculo($placa, $tipo, $marca, $modelo, $anio, $serial, $color, $categoria, $n_recibo);

    $tipo_poliza_print = ($tipo_poliza == 1) ? "Primer Año" : "";

    $ultima_poliza = $obj->get_last_element('poliza', 'id_poliza');
    $u_p = $ultima_poliza[0]['id_poliza'];

    $obj->agregarRenovar($u_p, $id_poliza, $fecha_old);
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

    <div class="card">


        <div class="card-header p-5 animated bounceInDown">
            <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                <- Regresar</a> <br><br>
                    <div class="ml-5 mr-5">
                        <h1 class="font-weight-bold text-center"><i class="fas fa-user" aria-hidden="true"></i>&nbsp;Renovando Póliza
                        </h1>
                    </div>
        </div>

        <div class="card-body p-5 animated bounceInUp" id="tablaLoad">

        </div>


    </div>



    <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer_b.php'; ?>

    <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer.php'; ?>

    <script src="../../assets/view/b_poliza.js"></script>

    <script>
        $(document).ready(function() {
            alertify.defaults.theme.ok = "btn blue-gradient";
            alertify.defaults.theme.cancel = "btn young-passion-gradient text-white";
            alertify.defaults.theme.input = "form-control";

            if (<?= $renov; ?> == '0') {
                Swal.fire({
                icon: 'error',
                title: 'Póliza Existente!',
                text: 'La Póliza ya existe con el Número y fechas de vigencia',
                }).then((result) => {
                    history.go(-2)
                })
            } else {
                alertify.confirm('Desea Cargar la Póliza en PDF?', '¿Desea Cargar la Póliza en PDF?',
                    function() {
                        window.location.replace("../add/subir_pdf.php?cond=2&id_poliza=<?= $u_p; ?>");
                        alertify.success('Ok')
                    },
                    function() {

                        window.location.replace("e_poliza_nnn.php");
                        alertify.error('No realizó carga en pdf')
                    }).set('labels', {
                    ok: 'Sí',
                    cancel: 'No'
                }).set({
                    transition: 'zoom'
                }).show();
            }
        });
    </script>

</body>

</html>