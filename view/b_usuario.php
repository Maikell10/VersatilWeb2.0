<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

require_once '../Controller/Asesor.php';

$usuarios = $obj->get_element('usuarios', 'id_usuario');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'navigation.php'; ?>
    <br><br><br><br><br><br>

    <div class="card">


        <div class="card-header p-5 animated bounceInDown">
            <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                <- Regresar</a> <br><br>
                    <div class="row ml-5 mr-5">
                        <h1 class="font-weight-bold ">Lista Usuarios</h1>

                        <a href="add/crear_usuario.php" class="btn blue-gradient ml-auto"><i class="fa fa-plus-circle" aria-hidden="true"></i> Nuevo Usuario</a>
                    </div>
        </div>

        <div class="card-body p-5 animated bounceInUp">
            <div class="table-responsive-xl">
                <table class="table table-hover table-striped table-bordered" id="tableUser" width="100%">
                    <thead class="blue-gradient text-white text-center">
                        <tr>
                            <th hidden>id</th>
                            <th>Seudónimo</th>
                            <th>Nombre Usuario</th>
                            <th>CI</th>
                            <th>Permiso</th>
                            <th nowrap>Z Producc</th>
                            <th style="width: 100px">Asesor con Carga</th>
                            <th style="width: 100px">Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($usuarios as $usuario) {
                            if ($usuario['id_permiso'] == 1) {
                                $permiso = 'Administrador';
                            }
                            if ($usuario['id_permiso'] == 2) {
                                $permiso = 'Usuario';
                            }
                            if ($usuario['id_permiso'] == 3) {
                                $permiso = 'Asesor';
                            }
                        ?>
                            <tr style="cursor: pointer">
                                <td hidden><?= $usuario['id_usuario']; ?></td>
                                <?php
                                if ($usuario['activo'] == 0) {
                                ?>
                                    <td class="text-danger">
                                        <?= $usuario['seudonimo']; ?>
                                        <?php if ($usuario['updated'] == 0) { ?>
                                                <span class="badge badge-pill badge-danger">
                                                <i class="fa fa-exclamation" aria-hidden="true"></i>
                                                </span>
                                        <?php } ?>
                                    </td>
                                <?php
                                }
                                if ($usuario['activo'] == 1) {
                                ?>
                                    <td class="text-success font-weight-bold"><?= $usuario['seudonimo']; ?></td>
                                <?php
                                }
                                ?>
                                <td nowrap><?= utf8_encode($usuario['nombre_usuario'] . " " . $usuario['apellido_usuario']); ?></td>
                                <td><?= $usuario['cedula_usuario']; ?></td>
                                <td><?= $permiso; ?></td>
                                <td><?= utf8_encode($usuario['z_produccion']); ?></td>
                                <td class="text-center font-weight-bold"><?php 
                                if($usuario['id_permiso'] == 3) {
                                    if($usuario['carga'] == 0) {
                                        echo 'No';
                                    }
                                    if($usuario['carga'] == 1) {
                                        echo 'Sí';
                                    }
                                }
                                ?></td>

                                <td class="text-center font-weight-bold"><?php 
                                if($usuario['email'] == '-') { ?>
                                    <i class="fas fa-times text-danger"></i>
                                <?php } else { ?>
                                    <i class="fas fa-check text-success"></i>
                                <?php } ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot class="text-center">
                        <tr>
                            <th hidden>id</th>
                            <th>Seudónimo</th>
                            <th>Nombre Usuario</th>
                            <th>CI</th>
                            <th>Permiso</th>
                            <th>Z Producc</th>
                            <th>Asesor con Carga</th>
                            <th>Email</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>



    <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'footer_b.php'; ?>

    <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'footer.php'; ?>

    <script src="../assets/view/b_poliza.js"></script>
</body>

</html>