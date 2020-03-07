<?php 
	require_once "../class/clases.php";
	$obj= new Trabajo();

	

	echo $obj->eliminarPagoR($_POST['id_poliza']);
	

 ?>