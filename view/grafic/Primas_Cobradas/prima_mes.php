<?php require_once '../../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../../login.php");
    exit();
}

$pag = 'Primas_Cobradas/prima_mes';

require_once '../../../Controller/Grafico.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . '\..\..\layout\header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . '\..\..\layout\navigation.php'; ?>
    <br><br><br><br><br><br>

    <div>
        <div class="card">


            <div class="card-header p-5 animated bounceInDown">
                <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                    <- Regresar</a> <br><br>
                        <div class="ml-5 mr-5">
                            <h1 class="font-weight-bold text-center">Primas Cobradas por Mes <strong style="color:red">(Bola de Nieve)</strong></h1>
                            <h2 class="font-weight-bold text-center">Año: <?= $_POST['anio']; ?></h2>
                            <br>
                            <center>
                                <a href="../primas_c.php" class="btn blue-gradient btn-lg btn-rounded">Menú de Gráficos</a>
                            </center>
                            <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('table', 'Primas Cobradas por Mes (Bola de Nieve)')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../../../assets/img/excel.png" width="40" alt=""></a></center>
                        </div>
            </div>

            <div class="card-body p-5 animated bounceInUp">
                <div class="col-md-12 mx-auto">
                    <div class="table-responsive-xl">
                        <table class="table table-hover table-striped table-bordered" id="table" width="100%">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th class="text-center">Mes Desde Recibo</th>
                                    <th class="text-center" data-toggle="tooltip" data-placement="top" title="Mes de Cobranza">Enero</th>
                                    <th class="text-center" data-toggle="tooltip" data-placement="top" title="Mes de Cobranza">Febrero</th>
                                    <th class="text-center" data-toggle="tooltip" data-placement="top" title="Mes de Cobranza">Marzo</th>
                                    <th class="text-center" data-toggle="tooltip" data-placement="top" title="Mes de Cobranza">Abril</th>
                                    <th class="text-center" data-toggle="tooltip" data-placement="top" title="Mes de Cobranza">Mayo</th>
                                    <th class="text-center" data-toggle="tooltip" data-placement="top" title="Mes de Cobranza">Junio</th>
                                    <th class="text-center" data-toggle="tooltip" data-placement="top" title="Mes de Cobranza">Julio</th>
                                    <th class="text-center" data-toggle="tooltip" data-placement="top" title="Mes de Cobranza">Agosto</th>
                                    <th class="text-center" data-toggle="tooltip" data-placement="top" title="Mes de Cobranza">Septiempre</th>
                                    <th class="text-center" data-toggle="tooltip" data-placement="top" title="Mes de Cobranza">Octubre</th>
                                    <th class="text-center" data-toggle="tooltip" data-placement="top" title="Mes de Cobranza">Noviembre</th>
                                    <th class="text-center" data-toggle="tooltip" data-placement="top" title="Mes de Cobranza">Diciembre</th>
                                    <th class="text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                for ($i = 0; $i < 12; $i++) {
                                ?>
                                    <tr>
                                        <th scope="row" data-toggle="tooltip" data-placement="top" title="Mes de Suscripción"><?= $mesArray[$mes[$i]["Month(f_desdepoliza)"] - 1]; ?></th>
                                        <td align="right"><?= "$" . number_format($p1[$i], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($p2[$i], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($p3[$i], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($p4[$i], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($p5[$i], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($p6[$i], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($p7[$i], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($p8[$i], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($p9[$i], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($p10[$i], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($p11[$i], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($p12[$i], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($totalMes[$i], 2); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>TOTAL</th>
                                    <th style="text-align: right;"><?= "$" . number_format($primaCobradaPorMes1, 2); ?></th>
                                    <th style="text-align: right;"><?= "$" . number_format($primaCobradaPorMes2, 2); ?></th>
                                    <th style="text-align: right;"><?= "$" . number_format($primaCobradaPorMes3, 2); ?></th>
                                    <th style="text-align: right;"><?= "$" . number_format($primaCobradaPorMes4, 2); ?></th>
                                    <th style="text-align: right;"><?= "$" . number_format($primaCobradaPorMes5, 2); ?></th>
                                    <th style="text-align: right;"><?= "$" . number_format($primaCobradaPorMes6, 2); ?></th>
                                    <th style="text-align: right;"><?= "$" . number_format($primaCobradaPorMes7, 2); ?></th>
                                    <th style="text-align: right;"><?= "$" . number_format($primaCobradaPorMes8, 2); ?></th>
                                    <th style="text-align: right;"><?= "$" . number_format($primaCobradaPorMes9, 2); ?></th>
                                    <th style="text-align: right;"><?= "$" . number_format($primaCobradaPorMes10, 2); ?></th>
                                    <th style="text-align: right;"><?= "$" . number_format($primaCobradaPorMes11, 2); ?></th>
                                    <th style="text-align: right;"><?= "$" . number_format($primaCobradaPorMes12, 2); ?></th>
                                    <th style="text-align: right;"><?= "$" . number_format($totalCant, 2); ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>


                </div>

                <div class="col-md-8 mx-auto">
                    <div class="wrapper col-12">
                        <canvas id="chart-0" style="height:500px"></canvas>
                    </div>
                </div>

            </div>
        </div>

    </div>



    <?php require_once dirname(__DIR__) . '\..\..\layout\footer_b.php'; ?>

    <?php require_once dirname(__DIR__) . '\..\..\layout\footer.php'; ?>

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
                        maxRotation: 50,
                        minRotation: 50,
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
                        data: [
                            '<?= $primaCobradaPorMes1; ?>',
                            '<?= $primaCobradaPorMes2; ?>',
                            '<?= $primaCobradaPorMes3; ?>',
                            '<?= $primaCobradaPorMes4; ?>',
                            '<?= $primaCobradaPorMes5; ?>',
                            '<?= $primaCobradaPorMes6; ?>',
                            '<?= $primaCobradaPorMes7; ?>',
                            '<?= $primaCobradaPorMes8; ?>',
                            '<?= $primaCobradaPorMes9; ?>',
                            '<?= $primaCobradaPorMes10; ?>',
                            '<?= $primaCobradaPorMes11; ?>',
                            '<?= $primaCobradaPorMes12; ?>'
                        ],
                        label: 'Prima Cobrada',
                        fill: boundary,
                        pointHoverRadius: 30,
                        pointHitRadius: 20,
                        pointRadius: 5,
                    }]
                },
                options: Chart.helpers.merge(options, {
                    title: {
                        text: 'Gráfico Prima Cobrada por Mes',
                        fontSize: 25,
                        display: true
                    }
                })
            });
        });
    </script>
</body>

</html>