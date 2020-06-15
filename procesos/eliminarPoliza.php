<?php
require_once "../Model/Poliza.php";
$obj = new Poliza();

echo $obj->eliminarPoliza($_POST['idpoliza'], $_GET['idusuario'], $_GET['num_poliza'], $_GET['cliente']);


$id_poliza = $poliza[0]['id_poliza'] . ".pdf";
$archivo = './' . $id_poliza;

//190.140.224.69                    
$ftp_server = "186.75.241.90";
$port = 21;
$ftp_usuario = "usuario";
$ftp_pass = "20127247";
$con_id = @ftp_connect($ftp_server, $port) or die("Unable to connect to server.");
$lr = ftp_login($con_id, $ftp_usuario, $ftp_pass);

if ((!$con_id) || (!$lr)) {
    //echo "no se pudo conectar";
} else {
    # Cambiamos al directorio especificado
    if (ftp_chdir($con_id, '')) {

        // Obtener los archivos contenidos en el directorio actual
        $contents = ftp_nlist($con_id, ".");

        //print_r($contents);
        //echo sizeof($contents);

        // intentar eliminar el archivo $file
        if (ftp_delete($con_id, $archivo)) {
            //echo "$file se ha eliminado satisfactoriamente\n";
        } else {
            //echo "No se pudo eliminar \n";
        }
    }
}
