<?php

require_once "../Model/Poliza.php";

$obj = new Poliza();

echo json_encode($obj->obtenPolizaE_pendiente($_POST['num_poliza'], $_GET['id_poliza']));