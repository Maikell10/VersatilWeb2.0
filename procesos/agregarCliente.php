<?php 
	require_once "../class/clases.php";
	$obj= new Trabajo();

	$datos=array(
		$_POST['r_sNew'],
		$_POST['id_new_titular'],
		$_POST['nT_new'],
		$_POST['aT_new'],
		$_POST['cT_new'],
		$_POST['tT_new'],
		$_POST['t1T_new'],
		$_POST['sT_new'],
		$_POST['ecT_new'],
		$_POST['fnT_new'],
		$_POST['dT_new'],
		$_POST['d1T_new'],
		$_POST['eT_new'],
		$_POST['e1T_new'],
		$_POST['oT_new'],
		$_POST['iT_new'],
				);

	echo $obj->agregarCliente($datos);
	

 ?>