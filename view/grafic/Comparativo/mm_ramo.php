<?php require_once '../../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../../login.php");
    exit();
}

$pag = 'Comparativo/mm_ramo';

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
                            <h1 class="font-weight-bold text-center">Gráfico Utilidad en Ventas</h1>
                            <h2 class="text-center">Año: <?= $_GET['anio']; ?></h2>
                            <br>
                            <center>
                                <a href="../comparativo.php" class="btn blue-gradient btn-lg btn-rounded">Menú de Gráficos</a>
                            </center>

                            <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('table', 'Prima Suscrita por Ramo')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../../../assets/img/excel.png" width="40" alt=""></a></center>
                        </div>
            </div>


            <div class="card-body p-5 animated bounceInUp">

                <div class="col-md-10 mx-auto">
                    <div class="table-responsive-xl">
                        <table class="table table-hover table-striped table-bordered" id="table" width="100%">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th class="text-center">Mes Cobranza</th>
                                    <th class="text-center">Prima Cobrada</th>
                                    <th class="text-center">Comisión</th>
                                    <th class="text-center">% Com</th>
                                    <th class="text-center" style="background-color: #E54848; color: white">GC Pagada</th>
                                    <th class="text-center">% GC</th>
                                    <th class="text-center">Utilidad Ventas</th>
                                    <th class="text-center">% Util Ventas</th>
                                    <th class="text-center">Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                for ($i = 0; $i < sizeof($mes); $i++) {
                                    $a = 0;

                                    if ($primaPorMesC[$i] == 0) {
                                        $comision = 0;
                                    } else {
                                        $comision = number_format(($comisionPorMes[$i] * 100) / $primaPorMesC[$i], 2);
                                    }
                                ?>
                                    <tr>
                                        <th scope="row" data-toggle="tooltip" data-placement="top" title="Mes de Cobranza"><?= $mesArray[$mes[$i]["Month(f_pago_prima)"] - 1]; ?></th>
                                        <td style="text-align: right;"><?= "$" . number_format($primaPorMesC[$i], 2); ?></td>
                                        <td style="text-align: right;"><?= "$" . number_format($comisionPorMes[$i], 2); ?></td>
                                        <td style="text-align: right;"><?= $comision . '%'; ?></td>

                                        <td style="text-align: right;background-color: #D9D9D9;font-weight: bold"><?= "$" . number_format($comisionGC[$i], 2); ?></td>

                                        <td style="text-align: right;"><?= number_format($perGCC[$i], 2) . '%'; ?></td>

                                        <td style="text-align: right;font-weight: bold"><?= "$" . number_format( ($comisionPorMes[$i]-$comisionGC[$i]), 2); ?></td>
                                        <td style="text-align: right;"><?= number_format( (($comisionPorMes[$i]-$comisionGC[$i])*100)/$comisionPorMes[$i], 2); ?>%</td>

                                        <td class="text-center" data-toggle="tooltip" data-placement="top" title="Cantidad de Pólizas Suscritas en <?= $mesArray[$mes[$i]["Month(f_pago_prima)"] - 1]; ?>"><?= $cantArray[$i]; ?></td>
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
                                    <th style="text-align: right;font-weight: bold"><?= "$" . number_format( ($totalCom-$totalGC), 2); ?></th>
                                    <th style="text-align: right;font-weight: bold"><?= "$" . number_format( (($totalCom-$totalGC)*100)/$totalCom, 2); ?></th>
                                    <th style="text-align: center;font-weight: bold"><?= $totalCant; ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>


                    <div class="col-md-12 mx-auto">
                        <div class="wrapper col-12"><canvas id="chart-0" style="height:500px"></canvas></div>
                    </div>
                </div>
                <br><br><br>
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
                            $a=0;
                            for ($i = 0; $i <= 11; $i++) {
                                        if (($mes[$a]["Month(f_pago_prima)"] - 1) == $i) {
                                            $dataPrima = ($comisionPorMes[$a]-$comisionGC[$a]);
                                            if ($a < (sizeof($mes) - 1)) {
                                                $a++;
                                            }
                                        } else {
                                            $dataPrima = 0;
                                        }
                                    ?> '<?= $dataPrima; ?>',
                                <?php } ?>
                            ],
                            label: 'Utilidad en Ventas',
                            fill: boundary,
                            pointHoverRadius: 30,
                            pointHitRadius: 20,
                            pointRadius: 5,
                        }]
                    },
                    options: Chart.helpers.merge(options, {
                        title: {
                            text: 'Gráfico Utilidad en Ventas',
                            fontSize: 25,
                            display: true
                        },
                    })
                });
            });
        </script>
</body>

</html>