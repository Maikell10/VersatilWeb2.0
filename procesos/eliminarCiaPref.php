<?php 
	
	require_once "../class/clases.php";
	$obj= new Trabajo();

	echo $obj->eliminarCiaPref($_POST['idcia'],$_POST['f_desde'],$_POST['f_hasta']);

 ?>