<?php
require_once "../Model/Poliza.php";

$obj = new Poliza();

$newftransf = date("Y-m-d", strtotime($_POST['ftransf']));

$datos = array(
    $_POST['id_gc_h_pago'],
    $_POST['ref'],
	$newftransf,
	$_POST['montop'],
);


echo $obj->updatePagoA($datos);
