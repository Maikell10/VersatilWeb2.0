<?php
require_once "../Model/Poliza.php";
$obj = new Poliza();

echo $obj->eliminarRamo($_POST['id_cod_ramo']);
