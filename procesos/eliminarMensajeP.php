<?php
require_once "../Model/Poliza.php";
$obj = new Poliza();

$ar="../assets/img/crm/prom/". $_POST['id_mensaje_p1'] .".jpg";
unlink($ar);

echo $obj->eliminarMensajeP($_POST['id_mensaje_p1']);
