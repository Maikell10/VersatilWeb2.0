<?php 
	
	require_once "../class/clases.php";
	
	$obj= new Trabajo();

	echo json_encode($obj->get_asesor_total($_POST['codena']));

 ?>