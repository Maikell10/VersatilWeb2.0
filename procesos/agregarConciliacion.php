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

echo $obj->agregarConciliacion($datos);
