<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
require_once '../Model/Asesor.php';

$totalPrima = 0;
$totalPrimaC = 0;
$totalCant = 0;

$obj = new Asesor();
$asesores = $obj->get_ejecutivo();

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
                        <h1 class="font-weight-bold ">Lista Asesores, Ejecutivos, Vendedores y Líderes de Proyecto</h1>
                    </div>
        </div>

        <div class="card-body p-5 animated bounceInUp">
            <div class="table-responsive-xl">
                <table class="table table-hover table-striped table-bordered" id="tableA" width="100%">
                    <thead class="blue-gradient text-white">
                        <tr>
                            <th nowrap>Nombre</th>
                            <th hidden>ID</th>
                            <th nowrap>Código</th>
                            <th nowrap>C.I o Pasaporte</th>
                            <th nowrap>Cant Pólizas</th>
                            <th nowrap>Total Prima Suscrita</th>
                            <th nowrap>Total Prima Cobrada</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($asesores as $asesor) {
                            $primaS = $obj->get_prima_s_asesor_total($asesor['cod']);
                            $primaC = $obj->get_prima_c_asesor_total($asesor['cod']);

                            $totalPrima = $totalPrima + $primaS[0];
                            $totalPrimaC = $totalPrimaC + $primaC[0];
                            $totalCant = $totalCant + $primaS[1];
                        ?>
                            <tr style="cursor: pointer">

                                <?php if ($asesor['act'] == 1) { ?>
                                    <td style="color: #2B9E34;font-weight: bold"><?= utf8_encode($asesor['nombre']); ?></td>
                                <?php }else{ ?>
                                    <td style="color: #E54848;font-weight: bold"><?= utf8_encode($asesor['nombre']); ?></td>
                                <?php } ?>

                                <td hidden><?= $asesor['id_asesor']; ?></td>
                                <td><?= $asesor['cod']; ?></td>
                                <td><?= $asesor['id']; ?></td>
                                <td class="text-center"><?= $primaS[1]; ?></td>
                                <td class="text-right">$ <?= number_format($primaS[0], 2); ?></td>
                                <td class="text-right">$ <?= number_format($primaC[0], 2); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Nombre</th>
                            <th hidden="">ID</th>
                            <th>Código</th>
                            <th>C.I o Pasaporte</th>
                            <th nowrap style="font-weight: bold" class="text-center">Cant Pólizas: <?= $totalCant; ?></th>
                            <th style="font-weight: bold" class="text-right">Total Prima Suscrita $<?= number_format($totalPrima, 2); ?></th>
                            <th style="font-weight: bold" class="text-right">Total Prima Cobrada $<?= number_format($totalPrimaC, 2); ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>



    <?php require_once dirname(__DIR__) . '\layout\footer_b.php'; ?>

    <?php require_once dirname(__DIR__) . '\layout\footer.php'; ?>

    <script src="../assets/view/b_asesor.js"></script>
</body>

</html>