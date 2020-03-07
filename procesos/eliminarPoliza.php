<?php 	
	require_once "../Model/Poliza.php";
	$obj= new Poliza();

	echo $obj->eliminarPoliza($_POST['idpoliza']);
 ?>