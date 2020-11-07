<?php require_once '../../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);
$pag = 'Comparativo/mm_ramo';

require_once '../../../Controller/Grafico.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . DS . '..' . DS . '..' . DS . 'layout' . DS . 'header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . DS . '..' . DS . '..' . DS . 'layout' . DS . 'navigation.php'; ?>
    <br><br><br><br><br><br>

    <div>
        <div class="card">


            <div class="card-header p-5 animated bounceInDown">
                <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                    <- Regresar</a> <br><br>
                        <div class="ml-5 mr-5">
                            <h1 class="font-weight-bold text-center">Gráfico Utilidad en Ventas</h1>
                            <h2 class="font-weight-bold text-center">Año: <span class="text-danger"><?= $_GET['anio']; ?></span></h2>

                            <?php if ($tipo_cuenta != '') { ?>
                                <h3 class="font-weight-bold text-center">
                                    Tipo de Cuenta: <span class="text-danger">
                                        <?php foreach ($tipo_cuenta as $tipo) {
                                            if ($tipo == 1) {
                                                echo ' INDIVIDUAL ';
                                            }
                                            if ($tipo == 2) {
                                                echo ' COLECTIVO ';
                                            }
                                        } ?>
                                    </span>
                                </h3>
                            <?php } ?>
                            <?php if ($cia != '') {
                                $ciaIn = implode(", ", $cia); ?>
                                <h3 class="font-weight-bold text-center">
                                    Cía: <span class="text-danger">
                                        <?= $ciaIn; ?>
                                    </span>
                                </h3>
                            <?php } ?>
                            <?php if ($ramo != '') {
                                $ramoIn = implode(", ", $ramo); ?>
                                <h3 class="font-weight-bold text-center">
                                    Ramo: <span class="text-danger">
                                        <?= $ramoIn; ?>
                                    </span>
                                </h3>
                            <?php } ?>

                            <br>
                            <center>
                                <a href="../comparativo.php" class="btn blue-gradient btn-lg btn-rounded">Menú de Gráficos</a>
                            </center>

                            <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('ComparativoGE', 'Prima Suscrita por Ramo')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../../../assets/img/excel.png" width="40" alt=""></a></center>
                        </div>
            </div>


            <div class="card-body p-5 animated bounceInUp">

                <div class="col-md-10 mx-auto">
                    <div class="table-responsive-xl">
                        <table class="table table-hover table-striped table-bordered" id="ComparativoG" width="100%">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th class="text-center">Mes Cobranza</th>
                                    <th class="text-center">Prima Cobrada</th>
                                    <th class="text-center">Comisión</th>
                                    <th class="text-center">% Com</th>
                                    <th class="text-center">GC Pagada</th>
                                    <th class="text-center">% GC</th>
                                    <th class="text-center" style="background-color: #E54848; color: white">Utilidad Ventas</th>
                                    <th class="text-center">% Util Ventas</th>
                                    <th class="text-center">Cantidad</th>

                                    <th hidden>mes</th>
                                    <th hidden>anio</th>
                                    <th hidden>ramo</th>
                                    <th hidden>cia</th>
                                    <th hidden>tipo_cuenta</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $ramo = serialize($ramo);
                                $ramo = urlencode($ramo);
                                $cia = serialize($cia);
                                $cia = urlencode($cia);
                                $tipo_cuenta = serialize($tipo_cuenta);
                                $tipo_cuenta = urlencode($tipo_cuenta);
                                for ($i = 0; $i < sizeof($mes); $i++) {
                                    $a = 0;

                                    if ($primaPorMesC[$i] == 0) {
                                        $comision = 0;
                                    } else {
                                        $comision = number_format(($comisionPorMes[$i] * 100) / $primaPorMesC[$i], 2);
                                    }
                                ?>
                                    <tr style="cursor: pointer">
                                        <th scope="row" data-toggle="tooltip" data-placement="top" title="Mes de Cobranza"><?= $mesArray[$mes[$i]["Month(f_pago_prima)"] - 1]; ?></th>
                                        <td style="text-align: right;"><?= "$" . number_format($primaPorMesC[$i], 2); ?></td>
                                        <td style="text-align: right;"><?= "$" . number_format($comisionPorMes[$i], 2); ?></td>
                                        <td style="text-align: right;"><?= $comision . '%'; ?></td>

                                        <td style="text-align: right;font-weight: bold;"><?= "$" . number_format($comisionGC[$i], 2); ?></td>

                                        <td style="text-align: right;"><?= number_format($perGCC[$i], 2) . '%'; ?></td>

                                        <td style="text-align: right;background-color: #D9D9D9;font-weight: bold"><?= "$" . number_format(($comisionPorMes[$i] - $comisionGC[$i]), 2); ?></td>
                                        <td style="text-align: right;"><?= number_format((($comisionPorMes[$i] - $comisionGC[$i]) * 100) / $comisionPorMes[$i], 2); ?>%</td>

                                        <td class="text-center" data-toggle="tooltip" data-placement="top" title="Cantidad de Pólizas con Prima Cobrada en <?= $mesArray[$mes[$i]["Month(f_pago_prima)"] - 1]; ?>"><?= $cantArrayPC[$i]; ?></td>

                                        <td hidden><?= $mes[$i]["Month(f_pago_prima)"]; ?></td>
                                        <td hidden><?= $_GET['anio']; ?></td>
                                        <td hidden><?= $ramo; ?></td>
                                        <td hidden><?= $cia; ?></td>
                                        <td hidden><?= $tipo_cuenta; ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="font-weight-bold">TOTAL</th>
                                    <th style="text-align: right;font-weight: bold"><?= "$" . number_format($totalc, 2); ?></th>
                                    <th style="text-align: right;font-weight: bold"><?= "$" . number_format($totalCom, 2); ?></th>
                                    <th style="text-align: right;font-weight: bold"><?= number_format(($totalCom * 100) / $totalc, 2) . '%'; ?></th>
                                    <th style="text-align: right;font-weight: bold"><?= "$" . number_format($totalGC, 2); ?></th>
                                    <th style="text-align: right;font-weight: bold"><?= number_format($totalperGC, 2) . '%'; ?></th>
                                    <th style="text-align: right;font-weight: bold"><?= "$" . number_format(($totalCom - $totalGC), 2); ?></th>
                                    <th style="text-align: right;font-weight: bold"><?= number_format((($totalCom - $totalGC) * 100) / $totalCom, 2); ?>%</th>
                                    <th style="text-align: center;font-weight: bold"><?= $totalCantPC; ?></th>

                                    <th colspan="5" hidden></th>
                                </tr>
                                <tr>
                                    <th class="text-center">Mes Cobranza</th>
                                    <th class="text-center">Prima Cobrada</th>
                                    <th class="text-center">Comisión</th>
                                    <th class="text-center">% Com</th>
                                    <th class="text-center">GC Pagada</th>
                                    <th class="text-center">% GC</th>
                                    <th class="text-center" style="background-color: #E54848; color: white">Utilidad Ventas</th>
                                    <th class="text-center">% Util Ventas</th>
                                    <th class="text-center">Cantidad</th>

                                    <th hidden>mes</th>
                                    <th hidden>anio</th>
                                    <th hidden>ramo</th>
                                    <th hidden>cia</th>
                                    <th hidden>tipo_cuenta</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>


                    <div class="col-md-12 mx-auto">
                        <div class="wrapper col-12"><canvas id="chart-0" style="height:500px"></canvas></div>
                    </div>


                    <br><br>
                    <h3 class="font-weight-bold text-center">Comparativo con el año: <?= $_GET['anioC']; ?></h3>
                    <div class="table-responsive-xl">
                        <table class="table table-hover table-striped table-bordered" id="ComparativoGOld" width="100%">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th class="text-center">Mes Cobranza</th>
                                    <th class="text-center">Prima Cobrada</th>
                                    <th class="text-center">Comisión</th>
                                    <th class="text-center">% Com</th>
                                    <th class="text-center">GC Pagada</th>
                                    <th class="text-center">% GC</th>
                                    <th class="text-center" style="background-color: #E54848; color: white">Utilidad Ventas</th>
                                    <th class="text-center">% Util Ventas</th>
                                    <th class="text-center">Cantidad</th>

                                    <th hidden>mes</th>
                                    <th hidden>anio</th>
                                    <th hidden>ramo</th>
                                    <th hidden>cia</th>
                                    <th hidden>tipo_cuenta</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                for ($i = 0; $i < sizeof($mesC); $i++) {
                                    $a = 0;
                                    if ($primaPorMesCC[$i] == 0) {
                                        $comision = 0;
                                    } else {
                                        $comision = number_format(($comisionPorMesC[$i] * 100) / $primaPorMesCC[$i], 2);
                                    }
                                ?>
                                    <tr style="cursor: pointer">
                                        <th scope="row" data-toggle="tooltip" data-placement="top" title="Mes de Cobranza"><?= $mesArray[$mesC[$i]["Month(f_pago_prima)"] - 1]; ?></th>
                                        <td style="text-align: right;"><?= "$" . number_format($primaPorMesCC[$i], 2); ?></td>
                                        <td style="text-align: right;"><?= "$" . number_format($comisionPorMesC[$i], 2); ?></td>
                                        <td style="text-align: right;"><?= $comision . '%'; ?></td>

                                        <td style="text-align: right;font-weight: bold;"><?= "$" . number_format($comisionGCC[$i], 2); ?></td>

                                        <td style="text-align: right;"><?= number_format($perGCCC1[$i], 2) . '%'; ?></td>

                                        <td style="text-align: right;background-color: #D9D9D9;font-weight: bold"><?= "$" . number_format(($comisionPorMesC[$i] - $comisionGCC[$i]), 2); ?></td>
                                        <td style="text-align: right;"><?= number_format((($comisionPorMesC[$i] - $comisionGCC[$i]) * 100) / $comisionPorMesC[$i], 2); ?>%</td>

                                        <td class="text-center" data-toggle="tooltip" data-placement="top" title="Cantidad de Pólizas con Prima Cobrada en <?= $mesArray[$mesC[$i]["Month(f_pago_prima)"] - 1]; ?>"><?= $cantArrayPCC[$i]; ?></td>

                                        <td hidden><?= $mesC[$i]["Month(f_pago_prima)"]; ?></td>
                                        <td hidden><?= $_GET['anioC']; ?></td>
                                        <td hidden><?= $ramo; ?></td>
                                        <td hidden><?= $cia; ?></td>
                                        <td hidden><?= $tipo_cuenta; ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="font-weight-bold">TOTAL</th>
                                    <th style="text-align: right;font-weight: bold"><?= "$" . number_format($totalcC, 2); ?></th>
                                    <th style="text-align: right;font-weight: bold"><?= "$" . number_format($totalComC, 2); ?></th>
                                    <th style="text-align: right;font-weight: bold"><?= number_format(($totalComC * 100) / $totalcC, 2) . '%'; ?></th>
                                    <th style="text-align: right;font-weight: bold"><?= "$" . number_format($totalGCC, 2); ?></th>
                                    <th style="text-align: right;font-weight: bold"><?= number_format($totalperGCC1, 2) . '%'; ?></th>
                                    <th style="text-align: right;font-weight: bold"><?= "$" . number_format(($totalComC - $totalGCC), 2); ?></th>
                                    <th style="text-align: right;font-weight: bold"><?= number_format((($totalComC - $totalGCC) * 100) / $totalComC, 2); ?>%</th>
                                    <th style="text-align: center;font-weight: bold"><?= $totalCantPCC; ?></th>

                                    <th colspan="5" hidden></th>
                                </tr>
                                <tr>
                                    <th class="text-center">Mes Cobranza</th>
                                    <th class="text-center">Prima Cobrada</th>
                                    <th class="text-center">Comisión</th>
                                    <th class="text-center">% Com</th>
                                    <th class="text-center">GC Pagada</th>
                                    <th class="text-center">% GC</th>
                                    <th class="text-center" style="background-color: #E54848; color: white">Utilidad Ventas</th>
                                    <th class="text-center">% Util Ventas</th>
                                    <th class="text-center">Cantidad</th>

                                    <th hidden>mes</th>
                                    <th hidden>anio</th>
                                    <th hidden>ramo</th>
                                    <th hidden>cia</th>
                                    <th hidden>tipo_cuenta</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>


                    <div class="table-responsive-xl" hidden>
                        <table class="table table-hover table-striped table-bordered" id="ComparativoGE" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center" colspan="9" style="background-color: #F0F0F0; font-weight: bold">Gráfico Utilidad en Ventas Año: <?= $_GET['anio']; ?></th>
                                </tr>
                                <tr>
                                    <th class="text-center" style="background-color: #4285F4; color: white">Mes Cobranza</th>
                                    <th class="text-center" style="background-color: #4285F4; color: white">Prima Cobrada</th>
                                    <th class="text-center" style="background-color: #4285F4; color: white">Comisión</th>
                                    <th class="text-center" style="background-color: #4285F4; color: white">% Com</th>
                                    <th class="text-center" style="background-color: #4285F4; color: white">GC Pagada</th>
                                    <th class="text-center" style="background-color: #4285F4; color: white">% GC</th>
                                    <th class="text-center" style="background-color: #E54848; color: white">Utilidad Ventas</th>
                                    <th class="text-center" style="background-color: #4285F4; color: white">% Util Ventas</th>
                                    <th class="text-center" style="background-color: #4285F4; color: white">Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $ramo = serialize($ramo);
                                $ramo = urlencode($ramo);
                                $cia = serialize($cia);
                                $cia = urlencode($cia);
                                $tipo_cuenta = serialize($tipo_cuenta);
                                $tipo_cuenta = urlencode($tipo_cuenta);
                                for ($i = 0; $i < sizeof($mes); $i++) {
                                    $a = 0;

                                    if ($primaPorMesC[$i] == 0) {
                                        $comision = 0;
                                    } else {
                                        $comision = number_format(($comisionPorMes[$i] * 100) / $primaPorMesC[$i], 2);
                                    }
                                ?>
                                    <tr style="cursor: pointer">
                                        <th scope="row" data-toggle="tooltip" data-placement="top" title="Mes de Cobranza"><?= $mesArray[$mes[$i]["Month(f_pago_prima)"] - 1]; ?></th>
                                        <td style="text-align: right;"><?= "$" . number_format($primaPorMesC[$i], 2); ?></td>
                                        <td style="text-align: right;"><?= "$" . number_format($comisionPorMes[$i], 2); ?></td>
                                        <td style="text-align: right;"><?= $comision . '%'; ?></td>

                                        <td style="text-align: right;font-weight: bold;"><?= "$" . number_format($comisionGC[$i], 2); ?></td>

                                        <td style="text-align: right;"><?= number_format($perGCC[$i], 2) . '%'; ?></td>

                                        <td style="text-align: right;background-color: #D9D9D9;font-weight: bold"><?= "$" . number_format(($comisionPorMes[$i] - $comisionGC[$i]), 2); ?></td>
                                        <td style="text-align: right;"><?= number_format((($comisionPorMes[$i] - $comisionGC[$i]) * 100) / $comisionPorMes[$i], 2); ?>%</td>

                                        <td align="center" data-toggle="tooltip" data-placement="top" title="Cantidad de Pólizas con Prima Cobrada en <?= $mesArray[$mes[$i]["Month(f_pago_prima)"] - 1]; ?>"><?= $cantArrayPC[$i]; ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>

                            <tr>
                                <th class="font-weight-bold">TOTAL</th>
                                <th style="text-align: right;font-weight: bold"><?= "$" . number_format($totalc, 2); ?></th>
                                <th style="text-align: right;font-weight: bold"><?= "$" . number_format($totalCom, 2); ?></th>
                                <th style="text-align: right;font-weight: bold"><?= number_format(($totalCom * 100) / $totalc, 2) . '%'; ?></th>
                                <th style="text-align: right;font-weight: bold"><?= "$" . number_format($totalGC, 2); ?></th>
                                <th style="text-align: right;font-weight: bold"><?= number_format($totalperGC, 2) . '%'; ?></th>
                                <th style="text-align: right;font-weight: bold"><?= "$" . number_format(($totalCom - $totalGC), 2); ?></th>
                                <th style="text-align: right;font-weight: bold"><?= number_format((($totalCom - $totalGC) * 100) / $totalCom, 2); ?>%</th>
                                <th style="text-align: center;font-weight: bold"><?= $totalCantPC; ?></th>
                            </tr>


                            <tr>
                                <td colspan="9" style="background-color: #00B0FF"></td>
                            </tr>
                            <tr>
                                <th class="text-center" colspan="9" style="background-color: #F0F0F0; font-weight: bold">Gráfico Utilidad en Ventas Año: <?= $_GET['anioC']; ?></th>
                            </tr>
                            <tr>
                                <th class="text-center" style="background-color: #4285F4; color: white">Mes Cobranza</th>
                                <th class="text-center" style="background-color: #4285F4; color: white">Prima Cobrada</th>
                                <th class="text-center" style="background-color: #4285F4; color: white">Comisión</th>
                                <th class="text-center" style="background-color: #4285F4; color: white">% Com</th>
                                <th class="text-center" style="background-color: #4285F4; color: white">GC Pagada</th>
                                <th class="text-center" style="background-color: #4285F4; color: white">% GC</th>
                                <th class="text-center" style="background-color: #E54848; color: white">Utilidad Ventas</th>
                                <th class="text-center" style="background-color: #4285F4; color: white">% Util Ventas</th>
                                <th class="text-center" style="background-color: #4285F4; color: white">Cantidad</th>
                            </tr>

                            <?php
                            for ($i = 0; $i < sizeof($mesC); $i++) {
                                $a = 0;
                                if ($primaPorMesCC[$i] == 0) {
                                    $comision = 0;
                                } else {
                                    $comision = number_format(($comisionPorMesC[$i] * 100) / $primaPorMesCC[$i], 2);
                                }
                            ?>
                                <tr style="cursor: pointer">
                                    <th scope="row" data-toggle="tooltip" data-placement="top" title="Mes de Cobranza"><?= $mesArray[$mesC[$i]["Month(f_pago_prima)"] - 1]; ?></th>
                                    <td style="text-align: right;"><?= "$" . number_format($primaPorMesCC[$i], 2); ?></td>
                                    <td style="text-align: right;"><?= "$" . number_format($comisionPorMesC[$i], 2); ?></td>
                                    <td style="text-align: right;"><?= $comision . '%'; ?></td>

                                    <td style="text-align: right;font-weight: bold;"><?= "$" . number_format($comisionGCC[$i], 2); ?></td>

                                    <td style="text-align: right;"><?= number_format($perGCCC1[$i], 2) . '%'; ?></td>

                                    <td style="text-align: right;background-color: #D9D9D9;font-weight: bold"><?= "$" . number_format(($comisionPorMesC[$i] - $comisionGCC[$i]), 2); ?></td>
                                    <td style="text-align: right;"><?= number_format((($comisionPorMesC[$i] - $comisionGCC[$i]) * 100) / $comisionPorMesC[$i], 2); ?>%</td>

                                    <td align="center" data-toggle="tooltip" data-placement="top" title="Cantidad de Pólizas con Prima Cobrada en <?= $mesArray[$mesC[$i]["Month(f_pago_prima)"] - 1]; ?>"><?= $cantArrayPCC[$i]; ?></td>
                                </tr>
                            <?php } ?>

                            <tr>
                                <th class="font-weight-bold">TOTAL</th>
                                <th style="text-align: right;font-weight: bold"><?= "$" . number_format($totalcC, 2); ?></th>
                                <th style="text-align: right;font-weight: bold"><?= "$" . number_format($totalComC, 2); ?></th>
                                <th style="text-align: right;font-weight: bold"><?= number_format(($totalComC * 100) / $totalcC, 2) . '%'; ?></th>
                                <th style="text-align: right;font-weight: bold"><?= "$" . number_format($totalGCC, 2); ?></th>
                                <th style="text-align: right;font-weight: bold"><?= number_format($totalperGCC1, 2) . '%'; ?></th>
                                <th style="text-align: right;font-weight: bold"><?= "$" . number_format(($totalComC - $totalGCC), 2); ?></th>
                                <th style="text-align: right;font-weight: bold"><?= number_format((($totalComC - $totalGCC) * 100) / $totalComC, 2); ?>%</th>
                                <th style="text-align: center;font-weight: bold"><?= $totalCantPCC; ?></th>
                            </tr>
                        </table>
                    </div>

                </div>
                <br><br><br>
            </div>

        </div>





        <?php require_once dirname(__DIR__) . DS . '..' . DS . '..' . DS . 'layout' . DS . 'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . DS . '..' . DS . '..' . DS . 'layout' . DS . 'footer.php'; ?>

        <script src="../../../assets/view/grafico.js"></script>

        <script src="../../../assets/js/utils.js"></script>
        <script src="../../../assets/js/analyser.js"></script>

        <script>
            var presets = window.chartColors;
            var utils = Samples.utils;
            var inputs = {
                min: 0,
                count: 12,
                decimals: 2,
                continuity: 1
            };

            function generateData(config) {
                return utils.numbers(Chart.helpers.merge(inputs, config || {}));
            }

            function generateLabels(config) {
                return utils.months(Chart.helpers.merge({
                    count: inputs.count,
                    section: 3
                }, config || {}));
            }

            var options = {
                maintainAspectRatio: false,
                spanGaps: false,
                elements: {
                    line: {
                        tension: 0.000001
                    }
                },
                plugins: {
                    filler: {
                        propagate: false
                    }
                },
                scales: {
                    xAxes: [{
                        ticks: {
                            autoSkip: false,
                            maxRotation: 0
                        }
                    }]
                }
            };

            [false, 'origin', 'start', 'end'].forEach(function(boundary, index) {

                // reset the random seed to generate the same data for all charts
                utils.srand(12);

                new Chart('chart-' + index, {
                    type: 'line',
                    data: {
                        labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                        datasets: [{
                                backgroundColor: utils.transparentize(presets.red),
                                borderColor: presets.red,
                                data: [<?php
                                        $a = 0;
                                        $cond = 0;
                                        for ($i = 0; $i <= 11; $i++) {
                                            if (($mes[$a]["Month(f_pago_prima)"] - 1) == $i) {
                                                if ($cond == 0) {
                                                    $dataPrima = ($comisionPorMes[$i] - $comisionGC[$i]);
                                                } else {
                                                    $c = $i - $cond;
                                                    $dataPrima = ($comisionPorMes[$c] - $comisionGC[$c]);
                                                }

                                                if ($a < (sizeof($mes) - 1)) {
                                                    $a++;
                                                }
                                            } else {
                                                $dataPrima = 0;
                                                $cond++;
                                            }
                                        ?> '<?= $dataPrima; ?>',
                                    <?php } ?>
                                ],
                                label: 'Utilidad en Ventas <?= $_GET['anio']; ?>  ',
                                fill: boundary,
                                pointHoverRadius: 30,
                                pointHitRadius: 20,
                                pointRadius: 5,
                            },
                            {
                                backgroundColor: utils.transparentize(presets.blue),
                                borderColor: presets.blue,
                                data: [<?php
                                        $a = 0;
                                        $cond = 0;
                                        for ($i = 0; $i <= 11; $i++) {
                                            if (($mesC[$a]["Month(f_pago_prima)"] - 1) == $i) {
                                                if ($cond == 0) {
                                                    $dataPrima = ($comisionPorMesC[$i] - $comisionGCC[$i]);
                                                } else {
                                                    $c = $i - $cond;
                                                    $dataPrima = ($comisionPorMesC[$c] - $comisionGCC[$c]);
                                                }

                                                if ($a < (sizeof($mesC) - 1)) {
                                                    $a++;
                                                }
                                            } else {
                                                $dataPrima = 0;
                                                $cond++;
                                            }
                                        ?> '<?= $dataPrima; ?>',
                                    <?php } ?>
                                ],
                                label: 'Utilidad en Ventas <?= $_GET['anioC']; ?>  ',
                                fill: boundary,
                                pointHoverRadius: 30,
                                pointHitRadius: 20,
                                pointRadius: 5,
                            }
                        ]
                    },
                    options: Chart.helpers.merge(options, {
                        title: {
                            text: 'Gráfico Utilidad en Ventas Año: <?= $_GET['anio']; ?> Vs Año: <?= $_GET['anioC']; ?>',
                            fontSize: 25,
                            display: true
                        },
                        tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                var datasetLabel = tooltipItem.yLabel.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                                return '$ ' + datasetLabel
                            }
                        }
                    }
                    })
                });
            });
        </script>
</body>

</html>