<?php
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
  header("Location: login.php");
  exit();
}

set_time_limit(300);
require_once "../class/clases.php";

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



$obj1 = new Trabajo();
$distinct_a = $obj1->get_gc_by_filtro_distinct_a_carga($desde, $hasta, $cia, $asesor);


//Ordeno los ejecutivos de menor a mayor alfabéticamente
$Ejecutivo[sizeof($distinct_a)] = null;
$codEj[sizeof($distinct_a)] = null;

for ($i = 0; $i < sizeof($distinct_a); $i++) {
  $obj111 = new Trabajo();
  $asesor1 = $obj111->get_element_by_id('ena', 'cod', $distinct_a[$i]['cod_vend']);
  $nombre = $asesor1[0]['idnom'];

  if (sizeof($asesor1) == null) {
    $ob3 = new Trabajo();
    $asesor1 = $ob3->get_element_by_id('enp', 'cod', $distinct_a[$i]['cod_vend']);
    $nombre = $asesor1[0]['nombre'];
  }

  if (sizeof($asesor1) == null) {
    $ob3 = new Trabajo();
    $asesor1 = $ob3->get_element_by_id('enr', 'cod', $distinct_a[$i]['cod_vend']);
    $nombre = $asesor1[0]['nombre'];
  }

  $Ejecutivo[$i] = $nombre;
  $codEj[$i] = $distinct_a[$i]['cod_vend'];
}

asort($Ejecutivo);
$x = array();
foreach ($Ejecutivo as $key => $value) {
  $x[count($x)] = $key;
}

for ($a = 1; $a <= sizeof($distinct_a); $a++) {
  utf8_encode($Ejecutivo[$x[$a]]);
  $codEj[$x[$a]] . "  --  ";
}



$obj4 = new Trabajo();
$gc_h = $obj4->agregarGCh($fhoy, $desde, $hasta, $tPoliza);

$obj3 = new Trabajo();
$ultimo_id_gc = $obj3->get_last_element('gc_h', 'id_gc_h');
$u_id_gc = ($ultimo_id_gc[0]['id_gc_h']);



for ($a = 1; $a <= sizeof($distinct_a); $a++) {


  $obj2 = new Trabajo();
  $poliza = $obj2->get_gc_by_filtro_by_a($desde, $hasta, $cia, $codEj[$x[$a]]);


  for ($i = 0; $i < sizeof($poliza); $i++) {

    $obj5 = new Trabajo();
    $gc_h_comision = $obj5->agregarGChComision($u_id_gc, $poliza[$i]['id_comision']);
  }
}



header('Location: ../Admin/gc/b_gc.php');
