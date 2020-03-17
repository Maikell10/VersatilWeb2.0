<?php

require_once "../Model/Poliza.php";

$obj = new Poliza();

echo json_encode($obj->obtenPolizaTarjeta($_POST['id_tarjeta']));
