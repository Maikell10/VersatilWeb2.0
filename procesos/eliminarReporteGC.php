<?php
require_once "../Model/Poliza.php";
$obj = new Poliza();

echo $obj->eliminarReporteGC($_POST['id_rep_gc']);
