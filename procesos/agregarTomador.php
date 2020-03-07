<?php 
	require_once "../class/clases.php";
	$obj= new Trabajo();

	$datos=array(
		$_POST['r_sNewT'],
		$_POST['id_new_titularT'],
		$_POST['nT_newT'],
		$_POST['aT_newT'],
		$_POST['cT_newT'],
		$_POST['tT_newT'],
		$_POST['t1T_newT'],
		$_POST['sT_newT'],
		$_POST['ecT_newT'],
		$_POST['fnT_newT'],
		$_POST['dT_newT'],
		$_POST['d1T_newT'],
		$_POST['eT_newT'],
		$_POST['e1T_newT'],
		$_POST['oT_newT'],
		$_POST['iT_newT'],
				);

	echo $obj->agregarCliente($datos);
	

 ?>