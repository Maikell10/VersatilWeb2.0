<?php
require_once 'Model/User.php';
$obj = new User();

$asesor = $obj->get_element_by_id('ena', 'id', $_POST['cedula']);

if ($asesor[0]['cod']) {
    $usuario = $obj->agregarUsuario($_POST['nombre'], $_POST['apellido'], $_POST['cedula'], 'PANAMA', $_POST['seudonimo'], $_POST['password'], 3, $asesor[0]['cod']);

    $para = 'maikell.ods10@gmail.com';

    $titulo = 'Nuevo usuario registrado en Versatil';

    $mensaje = '<html>' .
        '<head><title>Nuevo usuario registrado en Versatil</title></head>' .
        '<body><h1>Se registró un nuevo usuario Asesor en el sistema de versatil</h1>' .
        'Debe verificar y activar el usuario para que pueda ingresar al sistema' .
        '<hr>' .
        'Enviado desde el sistema de Versatil Seguros' .
        '</body>' .
        '</html>';

    $cabeceras = 'MIME-Version: 1.0' . "\r\n";
    $cabeceras .= 'Content-type: text/html; charset=utf-8' . "\r\n";
    $cabeceras .= 'From: Versatil Seguros<versatil@seguros.com>';

    $enviado = mail($para, $titulo, $mensaje, $cabeceras);


    header("Location: login.php?m=4");
} else {
    header("Location: login.php?m=5");
}
