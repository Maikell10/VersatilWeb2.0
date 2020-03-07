<?php 
	require_once "../class/clases.php";
	$obj= new Trabajo();

	$datos=array(
		$_POST['idena'],
		$_POST['nombreU'],
		$_POST['codigoU'],
		$_POST['ciU'],
		$_POST['refcuentaU']
				);

	echo $obj->actualizarAsesor($datos);
	

 ?>