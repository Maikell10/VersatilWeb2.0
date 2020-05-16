<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}

$pag = 'ena/b_proyecto';

require_once '../../Controller/Asesor.php';

$obj1 = new Asesor();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . '\..\layout\header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . '\..\layout\navigation.php'; ?>
    <br><br><br><br><br><br>

    <div>
        <div class="card">

            <div id="carga" class="d-flex justify-content-center align-items-center">
                <div class="spinner-grow text-info" style="width: 7rem; height: 7rem;"></div>
            </div>

            <?php if ($_SESSION['id_permiso'] != 3) { ?>
                <div class="card-header p-5 animated bounceInDown" id="headerload" hidden="true">
                    <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                        <- Regresar</a> <br><br>
                            <div class="ml-5 mr-5">
                                <h1 class="font-weight-bold ">Lista de Proyectos (P)</h1>
                            </div>
                </div>


                <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">

                    <div class="table-responsive-xl">
                        <table class="table table-hover table-striped table-bordered" id="tableAs" width="100%">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th nowrap>Nombre</th>
                                    <th hidden="">ID</th>
                                    <th nowrap>Código</th>
                                    <th nowrap>C.I o Pasaporte</th>
                                    <th nowrap>Cant Pólizas</th>
                                    <th nowrap>Total Prima Suscrita</th>
                                    <th nowrap>Total Prima Cobrada</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                for ($i = 0; $i < sizeof($proyecto); $i++) {
                                    $proyectot = $obj1->get_asesor_proyecto_total($proyecto[$i]['cod']);
                                    $prima = 0;

                                    $totalCant = $totalCant + $proyectot[0]['COUNT(*)'];
                                    $totalPrima = $totalPrima + $proyectot[0]['SUM(prima)'];

                                    $primaC = $obj1->get_prima_cobrada_asesor($proyecto[$i]['cod']); 
                                    $totalPrimaC = $totalPrimaC + $primaC[0]['SUM(prima_com)'];
                                ?>
                                    <tr style="cursor: pointer">
                                        <?php
                                        if ($proyecto[$i]['act'] == 1) {
                                        ?>
                                            <td nowrap class="text-success"><?= utf8_encode($proyecto[$i]['nombre']); ?></td>
                                        <?php
                                        } else {
                                        ?>
                                            <td nowrap class="text-danger"><?= utf8_encode($proyecto[$i]['nombre']); ?></td>
                                        <?php
                                        }
                                        ?>
                                        <td hidden=""><?= $proyecto[$i]['id_enp']; ?></td>
                                        <td><?= $proyecto[$i]['cod']; ?></td>
                                        <td><?= $proyecto[$i]['id']; ?></td>
                                        <td><?= $proyectot[0]['COUNT(*)']; ?></td>
                                        <td><?= "$ " . number_format($proyectot[0]['SUM(prima)'], 2); ?></td>
                                        <td><?= "$ " . number_format($primaC[0]['SUM(prima_com)'], 2); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th>Nombre</th>
                                    <th hidden="">ID</th>
                                    <th>Código</th>
                                    <th>C.I o Pasaporte</th>
                                    <th nowrap>Cant Pólizas <?= $totalCant; ?></th>
                                    <th>Total Prima Suscrita $<?= number_format($totalPrima, 2); ?></th>
                                    <th>Total Prima Cobrada $<?= number_format($totalPrimaC, 2); ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>

            <?php } ?>


        </div>





        <?php require_once dirname(__DIR__) . '\..\layout\footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . '\..\layout\footer.php'; ?>

        <script src="../../assets/view/b_asesor.js"></script>
</body>

</html>