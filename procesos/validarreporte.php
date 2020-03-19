<?php

require_once "../Model/Poliza.php";

$obj = new Poliza();

echo json_encode($obj->obtenReporte($_POST['f_hasta'], $_GET['cia']));
