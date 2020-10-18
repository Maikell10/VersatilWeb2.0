<?php

require_once "../Model/Poliza.php";

$obj = new Poliza();

echo json_encode($obj->update_user($_GET['id_user'], $_POST['cod_vend']));