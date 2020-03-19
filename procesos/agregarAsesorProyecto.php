<?php
require_once "../Model/Asesor.php";
$obj = new Asesor();

$datos = array(
	$_POST['id_proyecto'],
	$_POST['cod_proyecto'],
	$_POST['nombre_a'],
	$_POST['num_cuenta'],
	$_POST['banco'],
	$_POST['tipo_cuenta'],
	$_POST['email'],
	$_POST['id'],
	$_POST['cel'],
	$_POST['obs'],
	$_POST['currency'],
	$_POST['monto_a']
);

echo $obj->agregarAsesorProyecto($datos);
