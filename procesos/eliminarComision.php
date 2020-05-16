<?php

require_once "../Model/Poliza.php";
$obj = new Poliza();

echo $obj->eliminarComision($_POST['id_comision'], $_GET['idusuario'], $_GET['num_poliza'], $_GET['f_hasta_rep'], $_GET['cia']);
