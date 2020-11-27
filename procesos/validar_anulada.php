<?php

require_once "../Model/Poliza.php";

$obj = new Poliza();

echo json_encode($obj->obetnAnulada($_POST['id_poliza']));