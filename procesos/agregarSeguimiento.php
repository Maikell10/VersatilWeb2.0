<?php
require_once "../Model/Poliza.php";
$obj= new Poliza();

if ($_POST['comentarioSs'] == '0') {
    $datos = array(
        $_POST['id_polizaS'],
        $_POST['comentarioS'],
        $_POST['id_usuarioS']
    );
} else {
    $datos = array(
        $_POST['id_polizaS'],
        $_POST['comentarioSs'],
        $_POST['id_usuarioS']
    );
}

$seg_e = $obj->obtenSeguimientoRep($datos);

if ($seg_e == 0) {
    echo $obj->agregarSeguimiento($datos);
} else {
    $d = new DateTime();
    $d->format('Y-m-d');
    $fecha_seg_e = date("Y-m-d", strtotime($seg_e[0]['created_at']));

    if ($d->format('Y-m-d') == $fecha_seg_e) {
        echo json_encode(0);
    } else {
        echo $obj->agregarSeguimiento($datos);
    }
}



