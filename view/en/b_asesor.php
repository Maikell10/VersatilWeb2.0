<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}

$pag = 'ena/b_asesor';

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
                                <h1 class="font-weight-bold ">Lista de Asesores Independientes (A)</h1>
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
                                    <th nowrap>%GC</th>
                                    <th nowrap>%GC Renov</th>
                                    <th nowrap>%GC Viajes</th>
                                    <th nowrap>%GC Viajes Renov</th>
                                    <th nowrap>Cant Pólizas</th>
                                    <th nowrap>Total Prima Suscrita</th>
                                    <th nowrap>Total Prima Cobrada</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                for ($i = 0; $i < sizeof($asesor); $i++) {
                                    $asesort = $obj1->get_asesor_total($asesor[$i]['cod']);
                                    $prima = 0;

                                    $totalCant = $totalCant + $asesort[0]['COUNT(*)'];
                                    $totalPrima = $totalPrima + $asesort[0]['SUM(prima)'];
                                    

                                    $primaC = $obj1->get_prima_cobrada_asesor($asesor[$i]['cod']); 
                                    $totalPrimaC = $totalPrimaC + $primaC[0]['SUM(prima_com)'];

                                ?>
                                    <tr style="cursor: pointer">
                                        <?php
                                        if ($asesor[$i]['act'] == 1) {
                                        ?>
                                            <td nowrap class="text-success"><?= utf8_encode($asesor[$i]['idnom']); ?></td>
                                        <?php
                                        } else {
                                        ?>
                                            <td nowrap class="text-danger"><?= utf8_encode($asesor[$i]['idnom']); ?></td>
                                        <?php
                                        }
                                        ?>
                                        <td hidden=""><?= $asesor[$i]['idena']; ?></td>
                                        <td><?= $asesor[$i]['cod']; ?></td>
                                        <td><?= number_format($asesor[$i]['nopre1'], 0) . "%"; ?></td>
                                        <td><?= number_format($asesor[$i]['nopre1_renov'], 0) . "%"; ?></td>
                                        <td><?= number_format($asesor[$i]['gc_viajes'], 0) . "%"; ?></td>
                                        <td><?= number_format($asesor[$i]['gc_viajes_renov'], 0) . "%"; ?></td>
                                        <td class="text-center"><?= $asesort[0]['COUNT(*)']; ?></td>
                                        <td class="text-right"><?= "$ " . number_format($asesort[0]['SUM(prima)'], 2); ?></td>
                                        <td class="text-right"><?= "$ " . number_format($primaC[0]['SUM(prima_com)'], 2); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th nowrap>Nombre</th>
                                    <th hidden="">ID</th>
                                    <th>Código</th>
                                    <th>%GC</th>
                                    <th>%GC Renov</th>
                                    <th>%GC Viajes</th>
                                    <th>%GC Viajes Renov</th>
                                    <th nowrap class="text-center">Cant Pólizas <?= $totalCant; ?></th>
                                    <th class="text-right">Total Prima Suscrita $<?= number_format($totalPrima, 2); ?></th>
                                    <th class="text-right">Total Prima Cobrada $<?= number_format($totalPrimaC, 2); ?></th>
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