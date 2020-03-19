<?php

require_once "../Model/Poliza.php";

$obj = new Poliza();

echo json_encode($obj->obtenSumaReporte($_POST['id_rep_com']));
