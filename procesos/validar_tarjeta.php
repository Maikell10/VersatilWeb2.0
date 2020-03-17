<?php

require_once "../Model/Poliza.php";

$obj = new Poliza();

echo json_encode($obj->obtenTarjeta($_POST['n_tarjeta']));
