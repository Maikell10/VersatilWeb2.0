<?php
require_once "../Model/Poliza.php";
$obj = new Poliza();

echo $obj->eliminarAsesor($_POST['idasesor'], $_GET['a']);
