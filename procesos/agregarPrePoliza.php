<?php
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
  header("Location: ../login.php");
  exit();
}

require_once "../Model/Poliza.php";
$obj = new Poliza();

$fhoy = date("Y-m-d");
$nuevafecha = strtotime('+1 year', strtotime($fhoy));
$nuevafecha = date('Y-m-d', $nuevafecha);

$usuario = $obj->get_element_by_id('usuarios', 'seudonimo', $_SESSION['seudonimo']);
$veh = $obj->agregarVehiculo('-', '-', '-', '-', '-', '-', '-', '-', $_POST['num_poliza']);

$recibo = $obj->agregarRecibo(
  $_POST['num_poliza'],
  $fhoy,
  $nuevafecha,
  0,
  'CONTADO',
  1,
  0,
  0,
  0,
  $_POST['num_poliza'],
  1,
  0
);

$usuario = $obj->get_element_by_id('usuarios', 'seudonimo', $_SESSION['seudonimo']);
$z_produc = '';
if (utf8_encode($usuario[0]['z_produccion']) == 'PANAMÁ') {
  $z_produc = 1;
}
if (utf8_encode($usuario[0]['z_produccion']) == 'CARACAS') {
  $z_produc = 2;
}

$datos = array(
  $_POST['num_poliza'],
  $_POST['idcia'],
  $fhoy,
  $z_produc,
  $usuario[0]['id_usuario'],
  $nuevafecha,
);

echo $obj->agregarPrePoliza($datos);

$ultimo_id_p = $obj->get_last_element('poliza', 'id_poliza');
$u_id_p = $ultimo_id_p[0]['id_poliza'];

$asegurado = $obj->agregarAsegurado($_POST['asegurado'], $u_id_p, 0);
