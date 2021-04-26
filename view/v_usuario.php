<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

require_once '../Controller/Poliza.php';

$id_usuario = $_GET['id_usuario'];
$usuario = $obj->get_element_by_id('usuarios', 'id_usuario', $id_usuario);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'header.php'; ?>
    <style>
        .alertify .ajs-header {
            background-color: red;
        }
    </style>
</head>

<body>

    <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'navigation.php'; ?>
    <br><br><br><br><br><br>

    <div class="card">


        <div class="card-header p-5 animated bounceInDown">
            <div class="ml-5 mr-5">
                <h1 class="font-weight-bold">Usuario: <?= utf8_encode($usuario[0]['nombre_usuario'] . " " . $usuario[0]['apellido_usuario']); ?></h1>
                <h2 class="font-weight-bold">
                    Seudónimo: <?= $usuario[0]['seudonimo']; ?>
                    <?php if ($usuario[0]['updated'] == 0) { ?>
                            <span class="badge badge-pill badge-danger">
                            <i class="fa fa-exclamation" aria-hidden="true"></i>
                            </span>
                    <?php } ?>
                </h2>
            </div>
        </div>

        <div class="card-body p-5 animated bounceInUp" id="tablaLoad">
            <div class="table-responsive-xl">
                <table class="table table-hover table-striped table-bordered" width="100%">
                    <thead class="blue-gradient text-white">
                        <th>Nombre Usuario</th>
                        <th>Apellido</th>
                        <th>Cédula</th>
                        <th>Z Producc</th>
                        <th>Email</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= utf8_encode($usuario[0]['nombre_usuario']); ?></td>
                            <td><?= utf8_encode($usuario[0]['apellido_usuario']); ?></td>
                            <td><?= $usuario[0]['cedula_usuario']; ?></td>
                            <td><?= $usuario[0]['z_produccion']; ?></td>
                            <td><?= $usuario[0]['email']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="table-responsive-xl">
                <table class="table table-hover table-striped table-bordered" width="100%">
                    <thead class="blue-gradient text-white">
                        <th>Seudónimo</th>
                        <th>Clave</th>
                        <th>Permiso</th>
                        <th>Activo</th>
                    </thead>
                    <tbody>
                        <tr>
                            <?php
                            if ($usuario[0]['id_permiso'] == 1) {
                                $permiso = 'Administrador';
                            }
                            if ($usuario[0]['id_permiso'] == 2) {
                                $permiso = 'Usuario';
                            }
                            if ($usuario[0]['id_permiso'] == 3) {
                                $permiso = 'Asesor';
                            }

                            if ($usuario[0]['activo'] == 0) {
                                $estado = 'Inactivo';
                            } else {
                                $estado = 'Activo';
                            }
                            ?>
                            <td><?= $usuario[0]['seudonimo']; ?></td>
                            <td><?= $usuario[0]['clave_usuario']; ?></td>
                            <td><?= $permiso; ?></td>
                            <td><?= $estado; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <?php
            if ($usuario[0]['id_permiso'] == 3) {
                $asesor1 = $obj->get_element_by_id('ena', 'cod', $usuario[0]['cod_vend']);
                $nombre_a = $asesor1[0]['idnom'];

                if (sizeof($asesor1) == null) {
                    $asesor1 = $obj->get_element_by_id('enp', 'cod', $usuario[0]['cod_vend']);
                    $nombre_a = $asesor1[0]['nombre'];
                }
                if (sizeof($asesor1) == null) {
                    $asesor1 = $obj->get_element_by_id('enr', 'cod', $usuario[0]['cod_vend']);
                    $nombre_a = $asesor1[0]['nombre'];
                }
            ?>
                <div class="table-responsive-xl">
                    <table class="table table-hover table-striped table-bordered" width="100%">
                        <thead class="blue-gradient text-white">
                            <th>Asesor Asociado</th>
                            <th>Asesor con Carga</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?= $nombre_a; ?></td>
                                <td class="font-weight-bold"><?php 
                                if($usuario[0]['id_permiso'] == 3) {
                                    if($usuario[0]['carga'] == 0) {
                                        echo 'No';
                                    }
                                    if($usuario[0]['carga'] == 1) {
                                        echo 'Sí';
                                    }
                                }
                                ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            <?php } ?>


            <div class="table-responsive-xl">
                <h4>Copia al Correo</h4>
                <table class="table table-hover table-striped table-bordered" width="100%">
                    <thead class="blue-gradient text-white text-center">
                        <tr>
                            <th>Lista Renovaciones</th>
                            <th>Lista Clientes Vigentes</th>
                            <th>Carta Bienvenida</th>
                            <th>Carta Renovación</th>
                            <th>Tarjeta Cumpleaños</th>
                            <th>Tarjeta Promoción</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td class="text-center">
                                <?php if($usuario[0]['renov'] == 0) { ?>
                                    <i class="fas fa-times text-danger"></i>
                                <?php } else { ?>
                                    <i class="fas fa-check text-success"></i>
                                <?php } ?>
                            </td>
                            <td class="text-center">
                                <?php if($usuario[0]['ccl'] == 0) { ?>
                                    <i class="fas fa-times text-danger"></i>
                                <?php } else { ?>
                                    <i class="fas fa-check text-success"></i>
                                <?php } ?>
                            </td>
                            <td class="text-center">
                                <?php if($usuario[0]['cb'] == 0) { ?>
                                    <i class="fas fa-times text-danger"></i>
                                <?php } else { ?>
                                    <i class="fas fa-check text-success"></i>
                                <?php } ?>
                            </td>
                            <td class="text-center">
                                <?php if($usuario[0]['cr'] == 0) { ?>
                                    <i class="fas fa-times text-danger"></i>
                                <?php } else { ?>
                                    <i class="fas fa-check text-success"></i>
                                <?php } ?>
                            </td>
                            <td class="text-center">
                                <?php if($usuario[0]['cc'] == 0) { ?>
                                    <i class="fas fa-times text-danger"></i>
                                <?php } else { ?>
                                    <i class="fas fa-check text-success"></i>
                                <?php } ?>
                            </td>
                            <td class="text-center">
                                <?php if($usuario[0]['cp'] == 0) { ?>
                                    <i class="fas fa-times text-danger"></i>
                                <?php } else { ?>
                                    <i class="fas fa-check text-success"></i>
                                <?php } ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <hr>
            <center>
                <a href="e_usuario.php?id_usuario=<?= $usuario[0]['id_usuario'];?>" data-toggle="tooltip" data-placement="top" title="Editar" class="btn dusty-grass-gradient btn-lg">Editar Usuario &nbsp;<i class="fas fa-edit"></i></a>

                <button onclick="eliminarUsuario('<?= $usuario[0]['id_usuario']; ?>')" data-toggle="tooltip" data-placement="top" title="Eliminar" class="btn young-passion-gradient text-white btn-lg">Eliminar Usuario &nbsp;<i class="fas fa-trash-alt"></i></button>
            </center>
            <hr>
        </div>


    </div>



    <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'footer_b.php'; ?>

    <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'footer.php'; ?>

    <script src="../assets/view/b_poliza.js"></script>
    <script src="../assets/view/modalE.js"></script>

</body>

</html>