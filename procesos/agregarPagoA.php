<?php
require_once "../Model/Poliza.php";

$obj = new Poliza();

$newftransf = date("Y-m-d", strtotime($_POST['ftransf']));

$datos = array(
    $_POST['id_rep_gc_modal'],
    $_POST['ref'],
	$newftransf,
	$_POST['montop'],
    $_POST['cod_vend_modal'],
    $_POST['f_pago_gc_modal'],
);

$pago = $obj->get_pagoA($_POST['id_rep_gc_modal'], $newftransf, $_POST['montop'], $_POST['ref'], $_POST['cod_vend_modal'], $_POST['f_pago_gc_modal']);

if($pago == 0) {
	echo $obj->agregarPagoA($datos);
}