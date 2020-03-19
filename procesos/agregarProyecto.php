<?php
require_once "../Model/Asesor.php";
$obj = new Asesor();

$datos = array(
	$_POST['cod'],
	$_POST['nombre_l'],
	$_POST['pago'],
	$_POST['cuenta']
);

echo $obj->agregarProyecto($datos);
