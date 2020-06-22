<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'b_asesor';

require_once '../Controller/Asesor.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'navigation.php'; ?>
    <br><br><br><br><br><br>

    <div class="card">

        <div id="carga" class="d-flex justify-content-center align-items-center">
            <div class="spinner-grow text-info" style="width: 7rem; height: 7rem;"></div>
        </div>


        <div class="card-header p-5 animated bounceInDown" id="headerload" hidden="true">
            <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                <- Regresar</a> <br><br>
                    <div class="text-center">
                        <h1 class="font-weight-bold ">Indice Histórico de Cobranza de Asesores, Ejecutivos, Vendedores y Líderes de Proyecto</h1>
                    </div>
        </div>

        <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">
            <div class="table-responsive-xl">
                <table class="table table-hover table-striped table-bordered" id="tableA" width="100%">
                    <thead class="blue-gradient text-white text-center">
                        <tr>
                            <th nowrap>Nombre</th>
                            <th hidden>ID</th>
                            <th nowrap>Código</th>
                            <th nowrap>Cant Pólizas</th>
                            <th nowrap>Activas</th>
                            <th nowrap>Inactivas</th>
                            <th nowrap>Anuladas</th>
                            <th nowrap>Total Prima Suscrita</th>
                            <th nowrap>Total Prima Cobrada</th>
                            <th nowrap style="background-color: #E54848; color: white">Total Prima Pendiente</th>
                            <th nowrap>% Prima Cobrada de la Cartera</th>
                            <th hidden>act</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $obj = new Asesor();
                        foreach ($asesores as $asesor) {
                            $primaS = $obj->get_prima_s_asesor_total($asesor['cod']);
                            for ($i = 0; $i < sizeof($primaS); $i++) {
                                $totalPrimaT = $totalPrimaT + $primaS[$i]['prima'];
                            }
                        }
                        foreach ($asesores as $asesor) {
                            $primaSusc = 0;
                            $totalA = 0;
                            $totalI = 0;
                            $totalAn = 0;
                            $primaS = $obj->get_prima_s_asesor_total($asesor['cod']);
                            for ($i = 0; $i < sizeof($primaS); $i++) {
                                $primaSusc = $primaSusc + $primaS[$i]['prima'];
                                $totalPrima = $totalPrima + $primaS[$i]['prima'];

                                $no_renov = $obj->verRenov1($primaS[$i]['id_poliza']);
                                if ($no_renov[0]['no_renov'] != 1) {
                                    if ($primaS[$i]['f_hastapoliza'] >= date("Y-m-d")) {
                                        $totalA = $totalA + 1;
                                        $tA = $tA + 1;
                                    } else {
                                        $totalI = $totalI + 1;
                                        $tI = $tI + 1;
                                    }
                                } else {
                                    $totalAn = $totalAn + 1;
                                    $tAn = $tAn + 1;
                                }
                            }

                            $primaC = $obj->get_prima_c_asesor_total($asesor['cod']);

                            $totalPrimaC = $totalPrimaC + $primaC[0];
                            $totalCant = $totalCant + sizeof($primaS);

                            if ($primaSusc == 0) {
                                $perCob = 0;
                            } else {
                                $perCob = ($primaC[0] * 100) / $primaSusc;
                            }

                            $perCobT = ($primaC[0] * 100) / $totalPrimaT;


                            $ppendiente = number_format($primaSusc - $primaC[0], 2);
                            if ($ppendiente >= -0.10 && $ppendiente <= 0.10) {
                                $ppendiente = 0;
                            }

                            $tooltip = 'Total Prima Suscrita: ' . number_format($primaSusc, 2) . ' | Total Prima Cobrada: ' . number_format($primaC[0], 2) . ' | % Prima Cobrada del Asesor: ' . number_format($perCob, 2) . '%';

                        ?>
                            <tr style="cursor: pointer">

                                <?php if ($asesor['act'] == 1) { ?>
                                    <td style="color: #2B9E34;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= utf8_encode($asesor['nombre']); ?></td>
                                <?php } else { ?>
                                    <td style="color: #E54848;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= utf8_encode($asesor['nombre']); ?></td>
                                <?php } ?>

                                <td hidden><?= $asesor['id_asesor']; ?></td>
                                <td data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $asesor['cod']; ?></td>
                                <td class="text-center" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= sizeof($primaS); ?></td>
                                <td class="text-center" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $totalA; ?></td>
                                <td class="text-center" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $totalI; ?></td>
                                <td class="text-center" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $totalAn; ?></td>

                                <td style="text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= '$ ' . number_format($primaSusc, 2); ?></td>
                                <td style="text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= '$ ' . number_format($primaC[0], 2); ?></td>

                                <?php if ($ppendiente > 0) { ?>
                                    <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#F53333;font-size: 16px" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= '$ ' . $ppendiente; ?></td>
                                <?php }
                                if ($ppendiente == 0) { ?>
                                    <td style="background-color: #D9D9D9 ;color:black;text-align: right;font-weight: bold;" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= '$ ' . $ppendiente; ?></td>
                                <?php }
                                if ($ppendiente < 0) { ?>
                                    <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#2B9E34;font-size: 16px" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= '$ ' . $ppendiente; ?></td>
                                <?php } ?>

                                <td class="text-center" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= number_format($perCobT, 2); ?>%</td>

                                <td hidden><?= $asesor['act']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot class="text-center">
                        <tr>
                            <th>Nombre</th>
                            <th hidden="">ID</th>
                            <th>Código</th>
                            <th nowrap style="font-weight: bold" class="text-center">Cant Pólizas: <?= $totalCant; ?></th>
                            <th nowrap style="font-weight: bold" class="text-center">Cant Activas: <?= $tA; ?></th>
                            <th nowrap style="font-weight: bold" class="text-center">Cant Inactivas: <?= $tI; ?></th>
                            <th nowrap style="font-weight: bold" class="text-center">Cant Anuladas: <?= $tAn; ?></th>

                            <th style="font-weight: bold" class="text-right">Total Prima Suscrita $<?= number_format(($totalPrima), 2); ?></th>
                            <th style="font-weight: bold" class="text-right">Total Prima Cobrada $<?= number_format(($totalPrimaC), 2); ?></th>

                            <?php if (($totalPrima - $totalPrimaC) > 0) { ?>
                                <th style="text-align: right;font-weight: bold;color:#F53333;font-size: 16px">Total Prima Pendiente $<?= number_format(($totalPrima - $totalPrimaC), 2); ?></th>
                            <?php }
                            if (($totalPrima - $totalPrimaC) == 0) { ?>
                                <th style="color:black;text-align: right;font-weight: bold;">Total Prima Pendiente $<?= number_format(($totalPrima - $totalPrimaC), 2); ?></th>
                            <?php }
                            if (($totalPrima - $totalPrimaC) < 0) { ?>
                                <th style="text-align: right;font-weight: bold;color:#2B9E34;font-size: 16px">Total Prima Pendiente $<?= number_format(($totalPrima - $totalPrimaC), 2); ?></th>
                            <?php } ?>

                            <th style="font-weight: bold" class="text-right">Total % Prima Cobrada <?= number_format(($totalPrimaC * 100) / $totalPrima, 2); ?>%</th>
                            <th hidden>act</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <p class="h1 text-center">Total de Prima Suscrita</p>
            <p class="h1 text-center text-danger">$ <?php echo number_format($totalPrima, 2); ?></p>

            <p class="h1 text-center">Total de Prima Cobrada</p>
            <p class="h1 text-center text-danger">$ <?php echo number_format($totalPrimaC, 2); ?></p>

            <p class="h1 text-center">Total % Prima Cobrada</p>
            <p class="h1 text-center text-danger"><?php echo number_format(($totalPrimaC * 100) / $totalPrima, 2); ?>%</p>
        </div>

    </div>



    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'footer_b.php'; ?>

    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'footer.php'; ?>

    <script src="../assets/view/b_asesor.js"></script>
</body>

</html>