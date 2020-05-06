<?php
require_once "../Model/Poliza.php";
$obj= new Poliza();

if ($_POST['comentarioSs'] == '0') {
    $datos = array(
        $_POST['id_polizaS'],
        $_POST['comentarioS'],
        $_POST['id_usuarioS']
    );
} else {
    $datos = array(
        $_POST['id_polizaS'],
        $_POST['comentarioSs'],
        $_POST['id_usuarioS']
    );
}




echo $obj->agregarSeguimiento($datos);
