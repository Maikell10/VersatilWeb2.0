<?php

session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}

require_once '../Controller/Poliza.php';


$reporte = $_POST['id_rep_com'];
$id_rep_com = $_POST['id_rep_com'] . "rep.pdf";
$nombre = $id_rep_com;
$guardado = $_FILES['archivo']['tmp_name'];

$permitidos = array("application/pdf");
$limite_kb = 400;

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
            $reporte;
            $pdf_update = $obj->update_reporte_pdf($reporte);
        } else {
            //echo "No ha sido posible subir el fichero";
        }
    } else
        echo "No existe el directorio especificado";
}



$url = "v_reporte_com.php?id_rep_com=$reporte&m=2";
header("Location: " . $url);
