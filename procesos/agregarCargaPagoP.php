<?php
require_once "../Model/Poliza.php";
$obj= new Poliza();

$datos = array(
    $_POST['id_gc_h_p'],
    $_POST['id_usuarioS'],
    $_POST['n_transf'],
    $_POST['n_banco'],
    $_POST['f_pago_gc_r'],
    $_POST['monto_p']
);

echo $obj->agregarCargaPagoP($datos);
