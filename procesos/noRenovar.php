<?php
require_once "../Model/Poliza.php";
$obj= new Poliza();

$datos = array(
    $_POST['id_polizaNR'],
    $_POST['id_usuarioNR'],
    $_POST['no_renov'],
    $_POST['f_hastaNR'],
);

echo $obj->agregarNoRenov($datos);