<?php 
	
	require_once "../class/clases.php";
	$obj= new Trabajo();

	echo $obj->eliminarRepCom($_POST['id_rep_com']);

	
 ?>