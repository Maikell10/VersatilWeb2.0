<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'e_cliente';
require_once '../Controller/Poliza.php';

$id_poliza = $_GET['id_poliza'];
$poliza_f = $obj->get_element_by_id('poliza', 'id_poliza', $id_poliza);


$n_poliza = $_GET['n_poliza'];
$fhoy = $poliza_f[0]['f_poliza'];
$femisionP = $poliza_f[0]['f_emi'];
$t_cobertura = $_GET['t_cobertura'];
$fdesdeP = $_GET['desdeP'];
$fhastaP = $_GET['hastaP'];
$fdesdeP = date("Y-m-d", strtotime($fdesdeP));
$fhastaP = date("Y-m-d", strtotime($fhastaP));
$currency = $_GET['currency'];
$tipo_poliza = $_GET['tipo_poliza'];
$sumaA = $_GET['sumaA'];
$z_produc = $_GET['z_produc'];
if ($z_produc == "PANAMA") {
    $z_produc = 1;
} else {
    $z_produc = 2;
}
$codasesor = $_GET['asesor'];
$ramo = $_GET['ramo'];
$cia = $_GET['cia'];
$titular = $_GET['titular'];
$tomador = $_GET['tomador'];
$t_cuenta = $_GET['t_cuenta'];
$asesor_ind = $_GET['per_gc'];
if ($per_gc == null) {
    $per_gc = 0;
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

$obs_p = $_GET['obs_p'];

$per_gc_antigo = $poliza_f[0]['per_gc'];

$campos = $_GET['campos'];
if ($per_gc_antigo != $asesor_ind) {
    $campos = $campos . '%GC. ';
}

$forma_pago = $_GET['forma_pago'];
$n_tarjeta = $_GET['n_tarjeta'];
if($n_tarjeta == 'N/A'){
    $n_tarjeta = 0;
}
$cvv = $_GET['cvv'];
$fechaV = $_GET['fechaV'];
$fechaVP = date("Y-m-d", strtotime($fechaV));
$titular_tarjeta = $_GET['titular_tarjeta'];
$bancoT = $_GET['bancoT'];

$id_tarjeta = $_GET['id_tarjeta'];
$n_tarjeta_h = $_GET['n_tarjeta_h'];

//Editar la tarjeta si la forma de pago es por tarjeta
if ($forma_pago == 2) {
    if ($_GET['alert'] == 1) {


        if ($_GET['condTar'] == 0) {
            if ($_GET['id_tarjeta'] != 0) {
                $id_tarjeta = $obj->get_element_by_id('tarjeta', 'id_tarjeta', $_GET['id_tarjeta']);
                $id_tarjeta = $id_tarjeta[0]['id_tarjeta'];
            }
        }
        if ($_GET['condTar'] == 1) {
            $tarjeta = $obj->agregarTarjeta($n_tarjeta, $cvv, $fechaVP, $titular_tarjeta, $bancoT);

            if($tarjeta !== false) {
                $id_tarjeta = $obj->get_last_element('tarjeta', 'id_tarjeta');

                $id_tarjeta = $id_tarjeta[0]['id_tarjeta'];
            } else {
                echo "<script type=\"text/javascript\">
                        alert('Ocurrió un error en la carga de la Tarjeta. Actualiza el navegador e intenta de nuevo');
                        window.history.back();
                    </script>";
                exit;
            }
        }
    }
    
    if ($_GET['alert'] == 0) {
        if ($_GET['condTar'] == 1) {
            if ($_GET['id_tarjeta'] != 0) {
                
                $id_tarjeta = $obj->updateTarjeta($n_tarjeta, $cvv, $fechaVP, $titular_tarjeta, $bancoT, $_GET['id_tarjeta']);
                
                if($tarjeta !== false) {
                    $id_tarjeta = $_GET['id_tarjeta'];
                } else {
                    echo "<script type=\"text/javascript\">
                            alert('Ocurrió un error en la edición de la Tarjeta. Actualiza el navegador e intenta de nuevo');
                            window.history.back();
                        </script>";
                    exit;
                }
            }
        }
    }
}

$poliza = $obj->editarPoliza($id_poliza, $n_poliza, $fhoy, $t_cobertura, $fdesdeP, $fhastaP, $currency, $tipo_poliza, $sumaA, $z_produc, $codasesor, $ramo, $cia, $idtitular[0]['id_titular'], $idtomador[0]['id_titular'], $asesor_ind, $t_cuenta, $obs_p, $prima, $frec_renov);

if ($forma_pago != 2) {
    $recibo = $obj->editarRecibo($id_poliza, $n_recibo, $fdesde_recibo, $fhasta_recibo, $prima, $f_pago, $n_cuotas, $monto_cuotas, $idtomador[0]['id_titular'], $idtitular[0]['id_titular'], $n_poliza, $forma_pago, 'no');
} else {
    $recibo = $obj->editarRecibo($id_poliza, $n_recibo, $fdesde_recibo, $fhasta_recibo, $prima, $f_pago, $n_cuotas, $monto_cuotas, $idtomador[0]['id_titular'], $idtitular[0]['id_titular'], $n_poliza, $forma_pago, $id_tarjeta);
}

$vehiculo = $obj->editarVehiculo($id_poliza, $placa, $tipo, $marca, $modelo, $anio, $serial, $color, $categoria, $n_recibo);

$asesorCom = $obj->editarAsesorCom($id_poliza, $codasesor);

$tipo_poliza_print = "";
if ($tipo_poliza == 1) {
    $tipo_poliza_print = "Primer Año";
}

//$campos=$_GET['campos'];
if ($campos != '') {
    $editP = $obj->agregarEditP($id_poliza, $campos, $_SESSION['seudonimo']);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'navigation.php'; ?>
    <br><br><br><br><br><br>

    <div class="card">


        <div class="card-header p-5 animated bounceInDown">
            <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                <- Regresar</a> <br><br>
                    <div class="ml-5 mr-5">
                        <h1 class="font-weight-bold text-center"><i class="fas fa-user" aria-hidden="true"></i>&nbsp;Editando Póliza
                        </h1>
                    </div>
        </div>

        <div class="card-body p-5 animated bounceInUp" id="tablaLoad">

        </div>


    </div>



    <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'footer_b.php'; ?>

    <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'footer.php'; ?>

    <script src="../assets/view/b_poliza.js"></script>

    <script>
        $(document).ready(function() {
            alertify.defaults.theme.ok = "btn blue-gradient";
            alertify.defaults.theme.cancel = "btn young-passion-gradient text-white";
            alertify.defaults.theme.input = "form-control";

            alertify.alert('Póliza Editada con Exito!', 'Póliza Editada Satisfactoriamente',
                function() {
                    alertify.success('Ok');
                    window.close();
                });
        });
    </script>

</body>

</html>