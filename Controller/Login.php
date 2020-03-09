<?php
require_once dirname(__DIR__) . '\constants.php';

session_start();
$user = $_GET['username'];
$pass = $_GET['password'];
$permiso = 0;

require_once dirname(__DIR__) . '\Model\User.php';

$obj = new User();
$datos = $obj->getUsersByUsername($user);

if ($datos[0]['clave_usuario'] != null) {
    if ($pass == $datos[0]['clave_usuario']) {
        $_SESSION['seudonimo'] = $user;
        $_SESSION['id_usuario'] = $datos[0]['id_usuario'];

        $permiso = $datos[0]['id_permiso'];
        if ($datos[0]['activo'] == 0) {
            header("Location: ../login.php?m=3");
            exit();
        }
    } else {
        header("Location: ../login.php?m=1");
        exit();
    }
    if ($permiso == 1) {
        $_SESSION['id_permiso'] = $permiso;
        header("Location: ../view/");
    }
    if ($permiso == 2) {
        $_SESSION['id_permiso'] = $permiso;
        header("Location: ../view/");
    }
    if ($permiso == 3) {
        $_SESSION['id_permiso'] = $permiso;
        header("Location: ../view/");
    }
}else {
    header("Location: ../login.php?m=2");
}


