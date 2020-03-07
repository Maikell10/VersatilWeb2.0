<?php

require_once "../class/clases.php";
$obj = new Trabajo();


$datos = array(
    $_POST['id_polizaS'],
    $_POST['comentarioS'],
    $_POST['id_usuarioS']
);

echo $obj->agregarSeguimiento($datos);
