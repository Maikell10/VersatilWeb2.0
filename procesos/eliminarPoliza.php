<?php
require_once "../Model/Poliza.php";
$obj = new Poliza();

echo $obj->eliminarPoliza($_POST['idpoliza'],$_GET['idusuario'],$_GET['num_poliza'],$_GET['cliente']);
