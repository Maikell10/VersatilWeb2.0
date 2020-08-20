<?php
require_once 'terceros/dropbox/vendor/autoload.php';

use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;

$dropboxKey = "t1ddzra2rhbuzou";
$dropboxSecret = "eg0nujcek0f394h";
$dropboxToken = "sl.Afj0LjT8rFo3YlHvubZqcwgg3bYU3ugD0uFcHk9FzYbmICVKZNqiOrpd-h0IU9SKwb-aa1ZvmHfqWJWIhcFZqtGZA-K1Djhtyykt9em1yySDLQdTcssQj9iBRjvx-5DyZHV6jmnmJpI";


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
