<?php 
	
	require_once "../class/clases.php";
	$obj= new Trabajo();

	echo $obj->eliminarUsuario($_POST['idusuario']);

 ?>