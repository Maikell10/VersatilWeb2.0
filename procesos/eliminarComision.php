<?php

require_once "../Model/Poliza.php";
$obj = new Poliza();

echo $obj->eliminarComision($_POST['id_comision']);
