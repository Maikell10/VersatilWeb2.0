<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}

$pag = 'v_asesor';

require_once '../Controller/Asesor.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . '\layout\header.php'; ?>
    <style>
        .alertify .ajs-header {
            background-color: red;
        }
    </style>
</head>

<body>

    <?php require_once dirname(__DIR__) . '\layout\navigation.php'; ?>
    <br><br><br><br><br><br>

    <div class="card">


        <div class="card-header p-5 animated bounceInDown">
            <div class="ml-5 mr-5">
                <h1 class="font-weight-bold ">Asesor: <?= utf8_encode($nombre); ?></h1>
                <?php
                if ($asesor[0]['act'] == 0) {
                ?>
                    <h2 class="float-right text-danger">Inactivo &nbsp;<i class="fa fa-times" aria-hidden="true"></i></h2>
                <?php
                }
                if ($asesor[0]['act'] == 1) {
                ?>
                    <h2 class="float-right text-success">Activo &nbsp;<i class="fa fa-check" aria-hidden="true"></i></h2>
                <?php
                }
                ?>
                <h2 class="title">Cod: <?= $asesor[0]['cod']; ?></h2>
            </div>
        </div>

        <div class="card-body p-5 animated bounceInUp" id="tablaLoad">
            <div class="table-responsive-xl">
                <table class="table table-hover table-striped table-bordered" width="100%">
                    <thead class="blue-gradient text-white">
                        <th>ID Asesor</th>
                        <th>Nombre Asesor</th>
                        <th>E-Mail</th>
                        <th>Cel</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= $asesor[0]['id']; ?></td>
                            <td><?= utf8_encode($nombre); ?></td>
                            <td><a href=mailto:<?= $asesor[0]['email']; ?> data-toggle="tooltip" data-placement="bottom" title="Enviar Correo"><?= $asesor[0]['email']; ?></a></td>
                            <td><?= $asesor[0]['cel']; ?></td>
                        </tr>

                        <tr class="blue-gradient text-white">
                            <th>Banco</th>
                            <th>Tipo de Cuenta</th>
                            <th colspan="2">N Cuenta</th>
                        </tr>
                        <tr>
                            <td><?= $asesor[0]['banco']; ?></td>
                            <td><?= $asesor[0]['tipo_cuenta']; ?></td>
                            <td colspan="2"><?= $asesor[0]['num_cuenta']; ?></td>
                        </tr>

                        <?php if ($a == 3) { ?>

                            <tr class="blue-gradient text-white">
                                <th>Forma de Pago</th>
                                <th>Frecuencia de Pago</th>
                                <th colspan="2">Monto</th>
                            </tr>
                            <tr>
                                <td><?= $asesor[0]['f_pago']; ?></td>
                                <td><?= $asesor[0]['pago']; ?></td>
                                <td colspan="2"><?= $asesor[0]['monto']; ?></td>
                            </tr>

                        <?php } ?>

                        <tr class="blue-gradient text-white">
                            <th colspan="3">Observaciones</th>
                            <th>Estatus</th>
                        </tr>
                        <tr>
                            <td colspan="3"><?= utf8_encode($asesor[0]['obs']); ?></td>
                            <td><?php $estatus = ($asesor[0]['act'] == 1) ? 'Activo' : 'Inactivo';
                                echo $estatus; ?></td>
                        </tr>

                        <?php if ($asesor[0]['nopre1'] != null) { ?>
                            <tr class="blue-gradient text-white">
                                <th>%GC (Nuevo)</th>
                                <th>%GC (Renovación)</th>
                                <th>%GC Viajes (Nuevo)</th>
                                <th>%GC Viajes (Renovación)</th>
                            </tr>
                            <tr>
                                <td><?= $asesor[0]['nopre1'] . " %"; ?></td>
                                <td><?= $asesor[0]['nopre1_renov'] . " %"; ?></td>
                                <td><?= $asesor[0]['gc_viajes'] . " %"; ?></td>
                                <td><?= $asesor[0]['gc_viajes_renov'] . " %"; ?></td>
                            </tr>
                        <?php } ?>

                    </tbody>
                </table>
            </div>
            <hr>
            <center>
                <a href="b_poliza2.php?asesor=<?= $asesor[0]['cod']; ?>" data-toggle="tooltip" data-placement="top" title="Ver" class="btn blue-gradient btn-lg">Ver Pólizas Asesor &nbsp;<i class="fas fa-eye" aria-hidden="true"></i></a>

                <a href="e_asesor.php?id_asesor=<?= $id; ?>&a=<?= $a; ?>" data-toggle="tooltip" data-placement="top" title="Editar" class="btn dusty-grass-gradient btn-lg">Editar Asesor &nbsp;<i class="fas fa-edit" aria-hidden="true"></i></a>


                <?php if ($_SESSION['id_permiso'] == 1) { ?>
                    <button onclick="eliminarAsesor('<?= $id; ?>', '<?= $a; ?>')" data-toggle="tooltip" data-placement="top" title="Eliminar" class="btn young-passion-gradient btn-lg text-white">Eliminar Asesor &nbsp;<i class="fas fa-trash-alt" aria-hidden="true"></i></button>
                <?php } ?>
            </center>
            <hr>
        </div>


    </div>



    <?php require_once dirname(__DIR__) . '\layout\footer_b.php'; ?>

    <?php require_once dirname(__DIR__) . '\layout\footer.php'; ?>

    <script src="../assets/view/b_poliza.js"></script>

</body>

</html>