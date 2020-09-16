<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

require_once '../Model/Poliza.php';

$obj = new Poliza();
$cias = $obj->get_element('dcia', 'nomcia');
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
                        <h1 class="font-weight-bold ">Lista Compañías</h1>
                        <?php if ($_SESSION['id_permiso'] == 1) { ?>
                            <a href="add/crear_compania.php" class="btn blue-gradient ml-auto"><i class="fa fa-plus-circle" aria-hidden="true"></i> Nueva Compañía</a>
                        <?php } ?>
                    </div>
        </div>

        <div class="card-body p-5 animated bounceInUp" id="tablaLoad">
            <div class="table-responsive-xl">
                <table class="table table-hover table-striped table-bordered" id="tableA" width="100%">
                    <thead class="blue-gradient text-white text-center">
                        <tr>
                            <th>Nombre</th>
                            <th hidden>id</th>
                            <th>Preferencial</th>
                            <th>F Desde Preferencial (Última)</th>
                            <th>F Hasta Preferencial (Última)</th>
                            <th nowrap>Cant Pólizas</th>
                            <th nowrap>Activas</th>
                            <th nowrap>Inactivas</th>
                            <th nowrap>Anuladas</th>
                            <th nowrap>Total Prima Suscrita</th>
                            <th nowrap>Total Prima Cobrada</th>
                            <th nowrap style="background-color: #E54848; color: white">Total Prima Pendiente</th>
                            <?php if ($_SESSION['id_permiso'] == 1) { ?>
                                <th>Preferencial</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($cias as $cia) {
                            $primaS = $obj->get_prima_s_cia_total($cia['idcia']);
                            for ($i = 0; $i < sizeof($primaS); $i++) {
                                $totalPrimaT = $totalPrimaT + $primaS[$i]['prima'];
                            }
                        }
                        foreach ($cias as $cia) {

                            $f_cia_pref = $obj->get_f_cia_pref($cia['idcia']);

                            $desde_prefn = date("d/m/Y", strtotime($f_cia_pref[0]['f_desde_pref']));
                            $hasta_prefn = date("d/m/Y", strtotime($f_cia_pref[0]['f_hasta_pref']));

                            if ($desde_prefn == '01/01/1970') {
                                $desde_prefn = null;
                                $hasta_prefn = null;
                            }

                            $primaSusc = 0;
                            $totalA = 0;
                            $totalI = 0;
                            $totalAn = 0;
                            $primaS = $obj->get_prima_s_cia_total($cia['idcia']);
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

                            $primaC = $obj->get_prima_c_cia_total($cia['idcia']);

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

                            $tooltip = 'Total Prima Suscrita: ' . number_format($primaSusc, 2) . ' | Total Prima Cobrada: ' . number_format($primaC[0], 2);

                        ?>
                            <tr style="cursor:pointer">
                                <td><?= ($cia['nomcia']); ?></td>
                                <td hidden><?= $cia['idcia']; ?></td>
                                <td><?php if ($f_cia_pref[0]['f_desde_pref'] == 0) {
                                        echo "No";
                                    } else {
                                        echo "Sí";
                                    }
                                    ?></td>
                                <td><?= $desde_prefn; ?></td>
                                <td><?= $hasta_prefn; ?></td>

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

                                
                                <?php if ($_SESSION['id_permiso'] == 1) { ?>
                                    <td style="text-align: center;">
                                        <a data-toggle="tooltip" data-placement="top" title="Añadir Preferencial" href="comp_pref.php?nomcia=<?= $cia['nomcia']; ?>" class="btn blue-gradient btn-sm btn-rounded"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>
                                    </td>
                                <?php } ?>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                    <tfoot class="text-center">
                        <tr>
                            <th>Nombre</th>
                            <th hidden>id</th>
                            <th>Preferencial</th>
                            <th>F Desde Preferencial (Última)</th>
                            <th>F Hasta Preferencial (Última)</th>

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

                            <?php if ($_SESSION['id_permiso'] == 1) { ?>
                                <th>Preferencial</th>
                            <?php } ?>
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



    <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'footer_b.php'; ?>

    <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'footer.php'; ?>

    <script src="../assets/view/b_comp.js"></script>
</body>

</html>