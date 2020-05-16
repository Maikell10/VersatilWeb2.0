<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}

$pag = 'b_asesor';

require_once '../Controller/Asesor.php';
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

        <div id="carga" class="d-flex justify-content-center align-items-center">
            <div class="spinner-grow text-info" style="width: 7rem; height: 7rem;"></div>
        </div>


        <div class="card-header p-5 animated bounceInDown" id="headerload" hidden="true">
            <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                <- Regresar</a> <br><br>
                    <div class="text-center">
                        <h1 class="font-weight-bold ">Lista Asesores, Ejecutivos, Vendedores y Líderes de Proyecto</h1>
                    </div>
        </div>

        <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">
            <div class="table-responsive-xl">
                <table class="table table-hover table-striped table-bordered" id="tableA" width="100%">
                    <thead class="blue-gradient text-white">
                        <tr>
                            <th nowrap>Nombre</th>
                            <th hidden>ID</th>
                            <th nowrap>Código</th>
                            <th nowrap>C.I o Pasaporte</th>
                            <th nowrap>Cant Pólizas</th>
                            <th nowrap>Activas</th>
                            <th nowrap>Inactivas</th>
                            <th nowrap>Anuladas</th>
                            <th nowrap style="background-color: #E54848; color: white">Total Prima Pendiente</th>
                            <th nowrap>% Prima Cobrada</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $obj = new Asesor();
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

                            $tooltip = 'Total Prima Suscrita: ' . number_format($primaSusc, 2) . ' | Total Prima Cobrada: ' . number_format($primaC[0], 2);

                            if ($primaSusc == 0) {
                                $perCob = 0;
                            } else {
                                $perCob = ($primaC[0] * 100) / $primaSusc;
                            }

                            $ppendiente = number_format($primaSusc - $primaC[0],2);
                            if ($ppendiente >= -0.10 && $ppendiente <= 0.10) {
                                $ppendiente = 0;
                            }

                        ?>
                            <tr style="cursor: pointer">

                                <?php if ($asesor['act'] == 1) { ?>
                                    <td style="color: #2B9E34;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= utf8_encode($asesor['nombre']); ?></td>
                                <?php } else { ?>
                                    <td style="color: #E54848;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= utf8_encode($asesor['nombre']); ?></td>
                                <?php } ?>

                                <td hidden><?= $asesor['id_asesor']; ?></td>
                                <td data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $asesor['cod']; ?></td>
                                <td data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $asesor['id']; ?></td>
                                <td class="text-center" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= sizeof($primaS); ?></td>
                                <td class="text-center" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $totalA; ?></td>
                                <td class="text-center" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $totalI; ?></td>
                                <td class="text-center" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $totalAn; ?></td>

                                <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:black;font-size: 16px" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>">$ <?= $ppendiente; ?></td>

                                <td class="text-center" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= number_format($perCob, 2); ?>%</td>
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
                            <th nowrap style="font-weight: bold" class="text-center">Cant Pólizas: <?= $tA; ?></th>
                            <th nowrap style="font-weight: bold" class="text-center">Cant Pólizas: <?= $tI; ?></th>
                            <th nowrap style="font-weight: bold" class="text-center">Cant Pólizas: <?= $tAn; ?></th>
                            <th style="font-weight: bold" class="text-right">Total Prima Pendiente $<?= number_format(($totalPrima - $totalPrimaC), 2); ?></th>
                            <th style="font-weight: bold" class="text-right">Total % Prima Cobrada <?= number_format(($totalPrimaC * 100) / $totalPrima, 2); ?>%</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <p class="h1 text-center">Total de Prima Suscrita</p>
            <p class="h1 text-center text-danger">$ <?php echo number_format($totalPrima, 2); ?></p>

            <p class="h1 text-center">Total de Prima Cobrada</p>
            <p class="h1 text-center text-danger">$ <?php echo number_format($totalPrimaC, 2); ?></p>
        </div>

    </div>



    <?php require_once dirname(__DIR__) . '\layout\footer_b.php'; ?>

    <?php require_once dirname(__DIR__) . '\layout\footer.php'; ?>

    <script src="../assets/view/b_asesor.js"></script>
</body>

</html>