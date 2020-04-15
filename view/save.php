<?php

session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
	header("Location: ../login.php");
	exit();
}

require_once '../Controller/Poliza.php';


$poliza = $_POST['id_poliza'];
$id_poliza = $_POST['id_poliza'] . ".pdf";
$nombre = $id_poliza;
$guardado = $_FILES['archivo']['tmp_name'];

$permitidos = array("application/pdf");
$limite_kb = 200;

$ftp_server = "186.75.241.90";
$port = 21;
$ftp_usuario = "usuario";
$ftp_pass = "20127247";
$con_id = @ftp_connect($ftp_server, $port) or die("Unable to connect to server.");
$lr = ftp_login($con_id, $ftp_usuario, $ftp_pass);

if ((!$con_id) || (!$lr)) {
	//echo "no se pudo conectar";
} else {
	//echo "conectado";


	# Canviamos al directorio especificado
	if (ftp_chdir($con_id, '')) {
		$nombre;
		# Subimos el fichero
		if (@ftp_put($con_id, $nombre, $_FILES["archivo"]["tmp_name"], FTP_BINARY)) { //echo "Fichero subido correctamente";
			$poliza;
			$pdf_update = $obj->update_poliza_pdf($poliza);

			$editP = $obj->agregarEditP($_POST['id_poliza'], 'Carga de archivo PDF', $_SESSION['seudonimo']);
		} else {
			//echo "No ha sido posible subir el fichero";
		}
	} else
		echo "No existe el directorio especificado";


	/*
	$temp=explode(".", $_FILES['archivo']['name']);
	echo $source_file=$guardado;
	$destino="polizas";
	echo $nombre=$id_poliza;
	//ftp_pass($con_id,true);
	$subio=ftp_put($con_id, 'polizas/'.$nombre, $source_file, FTP_BINARY);
	if($subio){
		echo "ARCHIVO SUBIDO CORRECTAMENTE";
	}else{
		echo "error";
	}
	*/
}




if ($_POST['cond'] == 1) {
	$url = "add/poliza_nn.php";
	header("Location: " . $url);
} else {
	$url = "v_poliza.php?id_poliza=$poliza&m=2";
	header("Location: " . $url);
}
