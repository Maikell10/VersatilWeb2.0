<?php
require_once 'terceros/dropbox/vendor/autoload.php';

use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;

$dropboxKey = "t1ddzra2rhbuzou";
$dropboxSecret = "eg0nujcek0f394h";
$dropboxToken = "Nsp1_XYNsRAAAAAAAAAAAba53aJwNEYmg9Bau0UN3cEXdcWC75REkk_l-ibNUhKm";


$app = new DropboxApp($dropboxKey, $dropboxSecret, $dropboxToken);
$dropbox = new Dropbox($app);


$file = $dropbox->download("/1500.pdf");

//File Contents
$contents = $file->getContents();


//Save file contents to disk
file_put_contents(__DIR__ . "/1500.pdf", $contents);

//Downloaded File Metadata
$metadata = $file->getMetadata();
//print_r($metadata);
//Name
$metadata->getName();


$id_poliza = $_GET['id_poliza'] . ".pdf";
$archivo = './' . $id_poliza;


$mi_pdf = fopen("./1500.pdf", "r");
if (!$mi_pdf) {
    echo "<p>No puedo abrir el archivo para lectura</p>";
    exit;
}
header('Content-type: application/pdf');
fpassthru($mi_pdf); // Esto hace la magia
//fclose ("polizas/".$id_poliza);
fclose($mi_pdf);

unlink("./1500.pdf");
exit;
