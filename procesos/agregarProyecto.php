<?php 
	require_once "../class/clases.php";
	$obj= new Trabajo();

	$datos=array(
		$_POST['cod'],
		$_POST['nombre_l'],
		$_POST['pago'],
		$_POST['cuenta']
				);

	echo $obj->agregarProyecto($datos);
	

 ?>