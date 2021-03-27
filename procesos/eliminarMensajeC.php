<?php
require_once "../Model/Poliza.php";
$obj = new Poliza();

$ar="../assets/img/crm/". $_POST['id_mensaje_c1'] .".jpg";
unlink($ar);

echo $obj->eliminarMensajeC($_POST['id_mensaje_c1']);
