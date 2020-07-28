<?php require_once '../../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'Comisiones_Cobradas/prima_mes';

require_once '../../../Controller/Grafico.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . DS . '..' . DS . '..' . DS . 'layout' . DS . 'header.php'; ?>
    <style>
        .alertify .ajs-header {
            background-color: red;
        }
    </style>
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
                            <h1 class="font-weight-bold text-center">Resúmen por Mes del Año <?= $_GET['anio']; ?></h1>
                            <br>
                            <center>
                                <a href="../comisiones_c.php" class="btn blue-gradient btn-lg btn-rounded">Menú de Gráficos</a>
                            </center>
                        </div>
            </div>

            <div class="card-body p-5 animated bounceInUp">
                <div class="col-md-12 mx-auto">
                    <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('table', 'Comisiones Cobradas por Mes')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../../../assets/img/excel.png" width="60" alt=""></a></center>
                    <div class="table-responsive-xl">
                        <table class="table table-hover table-striped table-bordered" id="table" width="100%">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th class="text-center">Mes Vigencia</th>
                                    <th class="text-center">Prima Suscrita</th>
                                    <th class="text-center">Prima Cobrada</th>
                                    <th class="text-center">Prima Pendiente</th>
                                    <th class="text-center">Comisión Cobrada</th>
                                    <th class="text-center">% Com</th>
                                    <th class="text-center">GC Pagada</th>
                                    <th class="text-center">Utilidad</th>
                                    <th class="text-center">Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                for ($i = 0; $i < sizeof($mes); $i++) {
                                    if ($primaPorMesPC[$i] == 0) {
                                        $per_gc = 0;
                                    } else {
                                        $per_gc = (($primaPorMesCC[$i] * 100) / $primaPorMesPC[$i]);
                                    }
                                ?>
                                    <tr>
                                        <th scope="row"><?= $mesArray[$mes[$i]["Month(f_hastapoliza)"] - 1]; ?></th>
                                        <td align="right"><?= "$" . number_format($primaPorMes[$i], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($primaPorMesPC[$i], 2); ?></td>
                                        <td align="right" style="background-color: #ED7D31;color:white"><?= "$" . number_format($primaPorMes[$i] - $primaPorMesPC[$i], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($primaPorMesCC[$i], 2); ?></td>
                                        <td nowrap class="text-right"><?= number_format($per_gc, 2) . " %"; ?></td>
                                        <td align="right"><?= "$" . number_format($primaPorMesGCP[$i], 2); ?></td>
                                        <td align="right" style="background-color: #ED7D31;color:white"><?= "$" . number_format($primaPorMesCC[$i] - $primaPorMesGCP[$i], 2); ?></td>
                                        <td class="text-center"><?= $cantArray[$i]; ?></td>
                                    </tr>
                                <?php } ?>
                                <tr class="young-passion-gradient text-white">
                                    <th scope="col">TOTAL</th>
                                    <th class="text-right font-weight-bold"><?= "$" . number_format($totals, 2); ?></th>
                                    <th class="text-right font-weight-bold"><?= "$" . number_format($totalpc, 2); ?></th>
                                    <th class="text-right font-weight-bold"><?= "$" . number_format($totals - $totalpc, 2); ?></th>
                                    <th class="text-right font-weight-bold"><?= "$" . number_format($totalcc, 2); ?></th>
                                    <th class="text-right font-weight-bold"><?= "$" . number_format(($totalcc * 100) / $totalpc, 2); ?></th>
                                    <th class="text-right font-weight-bold"><?= "$" . number_format($totalgcp, 2); ?></th>
                                    <th class="text-right font-weight-bold"><?= "$" . number_format($totalcc - $totalgcp, 2); ?></th>
                                    <th class="text-center"><?= $totalCant; ?></th>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-center">Mes Vigencia</th>
                                    <th class="text-center">Prima Suscrita</th>
                                    <th class="text-center">Prima Cobrada</th>
                                    <th class="text-center">Prima Pendiente</th>
                                    <th class="text-center">Comisión Cobrada</th>
                                    <th class="text-center">% Com</th>
                                    <th class="text-center">GC Pagada</th>
                                    <th class="text-center">Utilidad</th>
                                    <th class="text-center">Cantidad</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>

                <div class="col-md-8 mx-auto">
                    <div class="wrapper col-12"><canvas id="chart-0" style="height:500px"></canvas></div>
                </div>

            </div>
        </div>

    </div>



    <?php require_once dirname(__DIR__) . DS . '..' . DS . '..' . DS . 'layout' . DS . 'footer_b.php'; ?>

    <?php require_once dirname(__DIR__) . DS . '..' . DS . '..' . DS . 'layout' . DS . 'footer.php'; ?>

    <script src="../../../assets/view/grafico.js"></script>

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
                        data: [<?php $a = 0;
                                for ($i = 0; $i <= 11; $i++) {
                                    if (($mes[$a]["Month(f_hastapoliza)"] - 1) == $i) {
                                        $dataPrima = $primaPorMesCC[$a];
                                        if ($a < (sizeof($mes) - 1)) {
                                            $a++;
                                        }
                                    } else {
                                        $dataPrima = 0;
                                    }
                                ?> '<?= $dataPrima; ?>',
                            <?php } ?>
                        ],
                        label: 'Comisión Cobrada',
                        fill: boundary,
                        pointHoverRadius: 30,
                        pointHitRadius: 20,
                        pointRadius: 5,
                    }]
                },
                options: Chart.helpers.merge(options, {
                    title: {
                        text: 'Comisión Cobrada por Mes',
                        fontSize: 25,
                        display: true
                    }
                })
            });
        });
    </script>
</body>

</html>