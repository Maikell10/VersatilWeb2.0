<?php 
	
	require_once "../Model/Poliza.php";
	$obj= new Poliza();

	echo $obj->eliminarCiaPref($_POST['idcia'],$_POST['f_desde'],$_POST['f_hasta']);

 ?>