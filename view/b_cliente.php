<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
require_once '../Model/Cliente.php';

$obj = new Cliente();
$clientes = $obj->get_cliente();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . '\layout\header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . '\layout\navigation.php'; ?>
    <br><br><br><br><br><br>

    <div class="card">


        <div class="card-header p-5 animated bounceInDown">
            <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                <- Regresar</a> <br><br>
                    <div class="text-center">
                        <h1 class="font-weight-bold ">Lista Clientes</h1>
                    </div>
        </div>

        <div class="card-body p-5 animated bounceInUp" id="tablaLoad">
            <div class="table-responsive-xl">
                <table class="table table-hover table-striped table-bordered" id="tableA" width="100%">
                    <thead class="blue-gradient text-white">
                        <tr>
                            <th hidden="">id</th>
                            <th hidden="">ci</th>
                            <th>Cédula</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Cant. Pólizas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $totalCant = 0;
                        for ($i = 1; $i < sizeof($clientes); $i++) {

                            $cant = $obj->get_polizas_t_cliente($clientes[$i]['id_titular']);
                            $totalCant = $totalCant + $cant[0];
                        ?>
                            <tr style="cursor: pointer">
                                <td hidden><?= $clientes[$i]['id_titular']; ?></td>
                                <td hidden><?= $clientes[$i]['ci']; ?></td>
                                <td><?= $clientes[$i]['r_social'] . ' ' . $clientes[$i]['ci']; ?></td>
                                <td><?= utf8_encode($clientes[$i]['nombre_t']); ?></td>
                                <td><?= utf8_encode($clientes[$i]['apellido_t']); ?></td>
                                <td class="text-center"><?= $cant[0]; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th hidden="">id</th>
                            <th hidden="">ci</th>
                            <th>Cédula</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th nowrap style="font-weight: bold" class="text-center">Cant Pólizas: <?= $totalCant; ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>



    <?php require_once dirname(__DIR__) . '\layout\footer_b.php'; ?>

    <?php require_once dirname(__DIR__) . '\layout\footer.php'; ?>

    <script src="../assets/view/b_cliente.js"></script>
</body>

</html>