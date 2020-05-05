<?php
require_once "../Model/Cliente.php";
$obj = new Cliente();

echo $obj->eliminarCliente($_POST['id_titular']);
