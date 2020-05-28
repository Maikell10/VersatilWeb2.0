<?php

require_once "../Model/Poliza.php";
$obj = new Poliza();

echo $obj->eliminarConciliacion($_POST['id_conciliacion']);
