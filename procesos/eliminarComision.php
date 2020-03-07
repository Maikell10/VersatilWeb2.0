<?php 
	
	require_once "../class/clases.php";
	$obj= new Trabajo();

	echo $obj->eliminarComision($_POST['id_comision']);

	
 ?>