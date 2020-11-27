<?php
DEFINE('DS', DIRECTORY_SEPARATOR);
require_once dirname(__DIR__) . DS . 'constants.php';

require_once '../Controller/Poliza.php';
require_once '../Dropbox/terceros/dropbox/vendor/autoload.php';

use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;

$dropboxKey = constant('DROPBOX_KEY');
$dropboxSecret = constant('DROPBOX_SECRET');
$dropboxToken = constant('DROPBOX_TOKEN');


$app = new DropboxApp($dropboxKey, $dropboxSecret, $dropboxToken);
$dropbox = new Dropbox($app);

require_once "../Model/Poliza.php";
$obj= new Poliza();


$datos = array(
    $_POST['id_polizaNR1'],
    $_POST['id_usuarioNR1'],
    $_POST['no_renov1'],
    $_POST['f_hastaNR1'],
);


$poliza_anular = $obj->noRenov($_POST['id_polizaNR1']);

if ($poliza_anular != 0) {
    $datos = array(
        $poliza_anular[0]['id_poliza_old'],
        $_POST['id_usuarioNR1'],
        $_POST['no_renov1'],
        $poliza_anular[0]['f_hasta_poliza_old'],
    );

    // eliminar la renovacion de la poliza anterior
    $obj->eliminarRenov($poliza_anular[0]['f_hasta_poliza_old']);

    // Eliminar Poliza pre-renovada
    $cliente = $poliza_anular[0]['nombre_t'] . ' ' . $poliza_anular[0]['apellido_t'];
    $obj->eliminarPoliza($_POST['id_polizaNR1'], $_GET['id_usuarioNR1'], $poliza_anular[0]['cod_poliza'], $cliente);
    
    // Eliminar PDF de Poliza pre-renovada
    $file = $dropbox->search('/', $_POST['id_polizaNR1'] . '.pdf');
    $var = $file->getData();
    $nombre_file = $var['matches'][0]['metadata']['name'];
    $nombre_file;
    if ($nombre_file) {
        // Existe PDF
        // Delete PDF
        $dropbox->delete('/' . $_POST['id_polizaNR1'] . '.pdf');
    }
    
    // Anular la Poliza anterior
    echo $obj->agregarNoRenov($datos);
} else {
    echo $obj->agregarNoRenov($datos);
}
