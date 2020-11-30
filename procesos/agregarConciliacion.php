<?php
require_once "../Model/Poliza.php";

$obj = new Poliza();

$newfc = date("Y-m-d", strtotime($_POST['fc_new']));

$datos = array(
	$newfc,
	$_POST['mc_new'],
	$_POST['id_reporte'],
	$_POST['coment_new'],
);

$conciliacion = $obj->get_conciliacion($_POST['id_reporte'], $newfc, $_POST['mc_new'], $_POST['coment_new']);

if($conciliacion == 0) {
	echo $obj->agregarConciliacion($datos);
}