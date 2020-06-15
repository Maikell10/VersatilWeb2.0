<?php
$ftp_server = "186.75.241.90";
$port = 21;
$ftp_usuario = "usuario";
$ftp_pass = "20127247";
$con_id = @ftp_connect($ftp_server, $port) or die("Unable to connect to server.");
$lr = ftp_login($con_id, $ftp_usuario, $ftp_pass);



$id_poliza = $_GET['id_poliza'] . ".pdf";
$archivo = './' . $id_poliza;


if ((!$con_id) || (!$lr)) {
    echo "no se pudo conectar";
} else {

    # Cambiamos al directorio especificado
    if (ftp_chdir($con_id, '')) {



        //-----
        $dir = '.';

        global $con_id;

        if ($dir != ".") {
            if (ftp_chdir($con_id, $dir) == false) {
                echo ("Change Dir Failed: $dir<BR>\r\n");
                return;
            }
            if (!(is_dir($dir)))
                mkdir($dir);
            chdir($dir);
        }

        $contents = ftp_nlist($con_id, ".");
        print_r($contents);
        foreach ($contents as $file) {
            ftp_get($con_id, "polizas/" . $file, $file, FTP_BINARY);
        }
    }
}
