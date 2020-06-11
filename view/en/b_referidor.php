<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'ena/b_referidor';

require_once '../../Controller/Asesor.php';

$obj1 = new Asesor();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'navigation.php'; ?>
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
                                <h1 class="font-weight-bold ">Lista de Referidores (R)</h1>
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
                                for ($i = 0; $i < sizeof($referidor); $i++) {
                                    $referidort = $obj1->get_referidor_total($referidor[$i]['cod']);
                                    $prima = 0;

                                    $totalCant = $totalCant + $referidort[0]['COUNT(*)'];
                                    $totalPrima = $totalPrima + $referidort[0]['SUM(prima)'];

                                    $primaC = $obj1->get_prima_cobrada_asesor($referidor[$i]['cod']);
                                    $totalPrimaC = $totalPrimaC + $primaC[0]['SUM(prima_com)'];

                                ?>
                                    <tr style="cursor: pointer">
                                        <?php
                                        if ($referidor[$i]['act'] == 1) {
                                        ?>
                                            <td nowrap class="text-success"><?= utf8_encode($referidor[$i]['nombre']); ?></td>
                                        <?php
                                        } else {
                                        ?>
                                            <td nowrap class="text-danger"><?= utf8_encode($referidor[$i]['nombre']); ?></td>
                                        <?php
                                        }
                                        ?>
                                        <td hidden=""><?= $referidor[$i]['id_enr']; ?></td>
                                        <td><?= $referidor[$i]['cod']; ?></td>
                                        <td><?= $referidor[$i]['id']; ?></td>
                                        <td class="text-center"><?= $referidort[0]['COUNT(*)']; ?></td>
                                        <td class="text-right"><?= "$ " . number_format($referidort[0]['SUM(prima)'], 2); ?></td>
                                        <td class="text-right"><?= "$ " . number_format($primaC[0]['SUM(prima_com)'], 2); ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th>Nombre</th>
                                    <th hidden="">ID</th>
                                    <th>Código</th>
                                    <th>C.I o Pasaporte</th>
                                    <th class="text-center" nowrap>Cant Pólizas <?= $totalCant; ?></th>
                                    <th class="text-right">Total Prima Suscrita $<?= number_format($totalPrima, 2); ?></th>
                                    <th class="text-right">Total Prima Cobrada $<?= number_format($totalPrimaC, 2); ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>

            <?php } ?>


        </div>





        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer.php'; ?>

        <script src="../../assets/view/b_asesor.js"></script>
</body>

</html>