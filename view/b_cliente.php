<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

require_once '../Model/Cliente.php';

$obj1 = new Cliente();
$clientes = $obj1->get_cliente();
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
            <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                <- Regresar</a> <br><br>
                    <div class="text-center">
                        <h1 class="font-weight-bold ">Lista Clientes</h1>
                    </div>

                    <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('tableA', 'Listado de Pólizas')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../assets/img/excel.png" width="60" alt=""></a></center>
        </div>

        <div class="card-body p-5 animated bounceInUp" id="tablaLoad">
            <div class="table-responsive-xl">
                <table class="table table-hover table-striped table-bordered" id="tableA" width="100%">
                    <thead class="blue-gradient text-white text-center">
                        <tr>
                            <th hidden="">ocultar</th>
                            <th hidden="">ocultar</th>
                            <th>Cédula</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Cant. Pólizas</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $totalCant = 0;
                        for ($i = 1; $i < sizeof($clientes); $i++) {

                            $cant = $obj1->get_polizas_t_cliente($clientes[$i]['id_titular']);
                            $totalCant = $totalCant + $cant[0];
                        ?>
                            <tr style="cursor: pointer">
                                <td hidden><?= $clientes[$i]['id_titular']; ?></td>
                                <td hidden><?= $clientes[$i]['ci']; ?></td>
                                <td><?= $clientes[$i]['r_social'] . ' ' . $clientes[$i]['ci']; ?></td>
                                <td><?= ($clientes[$i]['nombre_t']); ?></td>
                                <td><?= ($clientes[$i]['apellido_t']); ?></td>
                                <td class="text-center"><?= $cant[0]; ?></td>
                                <?php if ($cant[0] == 0 && $_SESSION['id_permiso'] == 1) { ?>
                                    <td class="text-center">
                                        <button onclick="eliminarCliente('<?= $clientes[$i]['id_titular']; ?>')" data-toggle="tooltip" data-placement="top" title="Eliminar Cliente" class="btn young-passion-gradient text-white btn-sm"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                <?php } else { ?>
                                    <td></td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot class="text-center">
                        <tr>
                            <th hidden="">ocultar</th>
                            <th hidden="">ocultar</th>
                            <th>Cédula</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th nowrap style="font-weight: bold" class="text-center">Cant Pólizas: <?= $totalCant; ?></th>
                            <th>Acciones</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>



    <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'footer_b.php'; ?>

    <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'footer.php'; ?>

    <script src="../assets/view/b_cliente.js"></script>
    <script src="../assets/view/modalE.js"></script>
</body>

</html>