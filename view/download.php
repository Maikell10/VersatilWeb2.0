<?php
DEFINE('DS', DIRECTORY_SEPARATOR);
require_once dirname(__DIR__) . DS . 'constants.php';

require_once '../Dropbox/terceros/dropbox/vendor/autoload.php';

use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;

$dropboxKey = constant('DROPBOX_KEY');
$dropboxSecret = constant('DROPBOX_SECRET');
$dropboxToken = constant('DROPBOX_TOKEN');


$app = new DropboxApp($dropboxKey, $dropboxSecret, $dropboxToken);
$dropbox = new Dropbox($app);

if (isset($_GET['id_poliza'])) {
    $file = $dropbox->download("/" . $_GET['id_poliza'] . ".pdf");

    //File Contents
    $contents = $file->getContents();


    //Save file contents to disk
    file_put_contents(__DIR__ . "/" . $_GET['id_poliza'] . ".pdf", $contents);

    //Downloaded File Metadata
    $metadata = $file->getMetadata();
    //print_r($metadata);
    //Name
    $metadata->getName();


    $mi_pdf = fopen("./" . $_GET['id_poliza'] . ".pdf", "r");
    if (!$mi_pdf) {
        echo "<p>No se puede abrir el archivo hay problemas con la nube por los momentos</p>";
        exit;
    }
    header('Content-type: application/pdf');
    fpassthru($mi_pdf); // Esto hace la magia
    //fclose ("polizas/".$id_poliza);
    fclose($mi_pdf);

    unlink("./" . $_GET['id_poliza'] . ".pdf");
    exit;
}

//pdf de los reportes de comision
if (isset($_GET['id_rep_com'])) {
    $file = $dropbox->download("/" . $_GET['id_rep_com'] . "rep.pdf");

    //File Contents
    $contents = $file->getContents();


    //Save file contents to disk
    file_put_contents(__DIR__ . "/" . $_GET['id_rep_com'] . "rep.pdf", $contents);

    //Downloaded File Metadata
    $metadata = $file->getMetadata();
    //print_r($metadata);
    //Name
    $metadata->getName();


    $mi_pdf = fopen("./" . $_GET['id_rep_com'] . "rep.pdf", "r");
    if (!$mi_pdf) {
        echo "<p>No se puede abrir el archivo hay problemas con la nube por los momentos</p>";
        exit;
    }
    header('Content-type: application/pdf');
    fpassthru($mi_pdf); // Esto hace la magia
    //fclose ("polizas/".$id_poliza);
    fclose($mi_pdf);

    unlink("./" . $_GET['id_rep_com'] . "rep.pdf");
    exit;
}