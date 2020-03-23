<?php
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
  header("Location: ../login.php");
  exit();
}

require_once "../Model/Poliza.php";
$obj = new Poliza();

$desde = $_GET['desde'];
$hasta = $_GET['hasta'];
$cia = $_GET['cia'];
$asesor = $_GET['asesor'];
$tPoliza = $_GET['tPoliza'];
$fhoy = date("Y-m-d");


if (!$asesor == '') {
  $asesor_para_recibir_via_url = stripslashes($asesor);
  $asesor_para_recibir_via_url = urldecode($asesor_para_recibir_via_url);
  $asesor = unserialize($asesor_para_recibir_via_url);
}

if (!$cia == '') {
  $cia_para_recibir_via_url = stripslashes($cia);
  $cia_para_recibir_via_url = urldecode($cia_para_recibir_via_url);
  $cia = unserialize($cia_para_recibir_via_url);
}

$anioH = date("Y", strtotime($hasta));
$mesH = date("m", strtotime($hasta));
$diaH = date("d", strtotime($hasta));

if ($mesH == 1 || $mesH == 3 || $mesH == 5 || $mesH == 7 || $mesH == 8 || $mesH == 10 || $mesH == 10) {
  $hasta = $anioH . "-" . $mesH . "-31";
}
if ($mesH == 4 || $mesH == 6 || $mesH == 9 || $mesH == 11) {
  $hasta = $anioH . "-" . $mesH . "-30";
}
if ($mesH == 2) {
  $hasta = $anioH . "-" . $mesH . "-28";
}

$distinct_a = $obj->get_gc_by_filtro_distinct_a_carga($desde, $hasta, $cia, $asesor);

$gc_h = $obj->agregarGCh($fhoy, $desde, $hasta, $tPoliza);

$ultimo_id_gc = $obj->get_last_element('gc_h', 'id_gc_h');
$u_id_gc = ($ultimo_id_gc[0]['id_gc_h']);



for ($a = 0; $a < sizeof($distinct_a); $a++) {

  $poliza = $obj->get_gc_by_filtro_by_a($desde, $hasta, $cia, $distinct_a[$a]['cod_vend']);

  for ($i = 0; $i < sizeof($poliza); $i++) {

    $gc_h_comision = $obj->agregarGChComision($u_id_gc, $poliza[$i]['id_comision']);
  }
}



header('Location: ../view/gc/b_gc.php');
