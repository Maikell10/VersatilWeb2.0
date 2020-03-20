<?php

require_once "../Model/Poliza.php";
$obj = new Poliza();

echo $obj->eliminarRepCom($_POST['id_rep_com']);
