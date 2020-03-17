<?php

require_once "../Model/Poliza.php";

$obj = new Poliza();

echo json_encode($obj->obtenPoliza($_POST['num_poliza']));
