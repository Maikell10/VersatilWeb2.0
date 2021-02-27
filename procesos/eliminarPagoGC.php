<?php
require_once "../Model/Poliza.php";
$obj = new Poliza();

echo $obj->eliminarPagoGC($_POST['id_gc_h_pago']);
