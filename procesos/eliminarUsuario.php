<?php
require_once "../Model/Poliza.php";
$obj = new Poliza();

echo $obj->eliminarUsuario($_POST['idusuario']);
