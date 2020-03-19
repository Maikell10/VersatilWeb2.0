<?php

require_once "../Model/Poliza.php";

$obj = new Poliza();

echo json_encode($obj->obetnComisiones($_POST['id_poliza']));