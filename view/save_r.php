<?php

session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}

require_once '../Controller/Poliza.php';
require_once '../Dropbox/terceros/dropbox/vendor/autoload.php';
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;

$dropboxKey ="t1ddzra2rhbuzou";
$dropboxSecret ="eg0nujcek0f394h";
$dropboxToken="Nsp1_XYNsRAAAAAAAAAAAba53aJwNEYmg9Bau0UN3cEXdcWC75REkk_l-ibNUhKm";

$app = new DropboxApp($dropboxKey,$dropboxSecret,$dropboxToken);
$dropbox = new Dropbox($app);

if(!empty($_FILES)){
	$reporte = $_POST['id_rep_com'];
    $id_rep_com = $_POST['id_rep_com'] . "rep.pdf";

    //$nombre = uniqid();
    $nombre = $reporte;
    $tempfile = $_FILES['archivo']['tmp_name'];
    $ext = explode(".",$_FILES['archivo']['name']);
    $ext = end($ext);
	$nombredropbox = "/" .$nombre . "rep." .$ext;

   	try{
        $file = $dropbox->simpleUpload( $tempfile,$nombredropbox, ['autorename' => true]);
        //ajax request
        //http_response_code(200);
		//echo "archivo subido";
		
		$reporte;
        $pdf_update = $obj->update_reporte_pdf($reporte);
   	}catch(\exception $e){
        print_r($e);
        
   	}
}



$url = "v_reporte_com.php?id_rep_com=$reporte&m=2";
header("Location: " . $url);
