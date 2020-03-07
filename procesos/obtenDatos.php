<?php 
	
	require_once "../class/clases.php";
	
	$obj= new Trabajo();

	echo json_encode($obj->obtenDatos($_POST['idena']));

 ?>