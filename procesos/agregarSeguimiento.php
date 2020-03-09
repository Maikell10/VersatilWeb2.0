<?php
require_once "../Model/Poliza.php";
$obj= new Poliza();

$datos = array(
    $_POST['id_polizaS'],
    $_POST['comentarioS'],
    $_POST['id_usuarioS']
);

echo $obj->agregarSeguimiento($datos);
