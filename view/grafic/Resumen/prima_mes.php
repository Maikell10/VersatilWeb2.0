<?php require_once '../../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'Resumen/prima_mes';

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
                            <h1 class="font-weight-bold text-center">Resúmen por Mes</h1>
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
                                <a href="../resumen.php" class="btn blue-gradient btn-lg btn-rounded">Menú de Gráficos</a>
                            </center>
                            <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('tabE', 'Comisiones Cobradas por Mes')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../../../assets/img/excel.png" width="40" alt=""></a></center>
                        </div>
            </div>

            <div class="card-body p-5 animated bounceInUp">
                <div class="col-md-12 mx-auto">
                    <div class="table-responsive-xl">
                        <table class="table table-hover table-striped table-bordered" id="tab" width="100%">
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
                                        <th scope="row"><?= $mesArray[$mes[$i]["Month(f_desdepoliza)"] - 1]; ?></th>
                                        <td align="right"><?= "$" . number_format($primaPorMes[$i], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($primaPorMesPC[$i], 2); ?></td>
                                        <td align="right" style="background-color: #D9D9D9;font-weight: bold"><?= "$" . number_format($primaPorMes[$i] - $primaPorMesPC[$i], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($primaPorMesCC[$i], 2); ?></td>
                                        <td nowrap class="text-right"><?= number_format($per_gc, 2) . " %"; ?></td>
                                        <td align="right"><?= "$" . number_format($primaPorMesGCP[$i], 2); ?></td>
                                        <td align="right" style="background-color: #D9D9D9;font-weight: bold"><?= "$" . number_format($primaPorMesCC[$i] - $primaPorMesGCP[$i], 2); ?></td>
                                        <td align="center"><?= $cantArray[$i]; ?></td>
                                    </tr>
                                <?php } 
                                $tT = ($totalpc > 0) ? (($totalcc * 100) / $totalpc) : 0 ;
                                ?>
                            </tbody>
                            <tfoot>
                                <tr class="young-passion-gradient text-white">
                                    <th scope="col">TOTAL</th>
                                    <th class="text-right font-weight-bold"><?= "$" . number_format($totals, 2); ?></th>
                                    <th class="text-right font-weight-bold"><?= "$" . number_format($totalpc, 2); ?></th>
                                    <th class="text-right font-weight-bold"><?= "$" . number_format($totals - $totalpc, 2); ?></th>
                                    <th class="text-right font-weight-bold"><?= "$" . number_format($totalcc, 2); ?></th>
                                    <th class="text-right font-weight-bold"><?= number_format($tT, 2) . " %"; ?></th>
                                    <th class="text-right font-weight-bold"><?= "$" . number_format($totalgcp, 2); ?></th>
                                    <th class="text-right font-weight-bold"><?= "$" . number_format($totalcc - $totalgcp, 2); ?></th>
                                    <th class="text-center"><?= $totalCant; ?></th>
                                </tr>
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


                    <div class="table-responsive-xl" hidden>
                        <table class="table table-hover table-striped table-bordered" id="tabE" width="100%">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th class="text-center" style="background-color: #4285F4; color: white">Mes Vigencia</th>
                                    <th class="text-center" style="background-color: #4285F4; color: white">Prima Suscrita</th>
                                    <th class="text-center" style="background-color: #4285F4; color: white">Prima Cobrada</th>
                                    <th class="text-center" style="background-color: #4285F4; color: white">Prima Pendiente</th>
                                    <th class="text-center" style="background-color: #4285F4; color: white">Comisión Cobrada</th>
                                    <th class="text-center" style="background-color: #4285F4; color: white">% Com</th>
                                    <th class="text-center" style="background-color: #4285F4; color: white">GC Pagada</th>
                                    <th class="text-center" style="background-color: #4285F4; color: white">Utilidad</th>
                                    <th class="text-center" style="background-color: #4285F4; color: white">Cantidad</th>
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
                                        <th scope="row"><?= $mesArray[$mes[$i]["Month(f_desdepoliza)"] - 1]; ?></th>
                                        <td align="right"><?= "$" . number_format($primaPorMes[$i], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($primaPorMesPC[$i], 2); ?></td>
                                        <td align="right" style="background-color: #D9D9D9;font-weight: bold"><?= "$" . number_format($primaPorMes[$i] - $primaPorMesPC[$i], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($primaPorMesCC[$i], 2); ?></td>
                                        <td nowrap class="text-right"><?= number_format($per_gc, 2) . " %"; ?></td>
                                        <td align="right"><?= "$" . number_format($primaPorMesGCP[$i], 2); ?></td>
                                        <td align="right" style="background-color: #D9D9D9;font-weight: bold"><?= "$" . number_format($primaPorMesCC[$i] - $primaPorMesGCP[$i], 2); ?></td>
                                        <td align="center"><?= $cantArray[$i]; ?></td>
                                    </tr>
                                <?php } 
                                $tT = ($totalpc > 0) ? (($totalcc * 100) / $totalpc) : 0 ;
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th scope="col" style="background-color: red; color: white">TOTAL</th>
                                    <th class="text-right font-weight-bold" style="background-color: red; color: white"><?= "$" . number_format($totals, 2); ?></th>
                                    <th class="text-right font-weight-bold" style="background-color: red; color: white"><?= "$" . number_format($totalpc, 2); ?></th>
                                    <th class="text-right font-weight-bold" style="background-color: red; color: white"><?= "$" . number_format($totals - $totalpc, 2); ?></th>
                                    <th class="text-right font-weight-bold" style="background-color: red; color: white"><?= "$" . number_format($totalcc, 2); ?></th>
                                    <th class="text-right font-weight-bold" style="background-color: red; color: white"><?= number_format($tT, 2) . " %"; ?></th>
                                    <th class="text-right font-weight-bold" style="background-color: red; color: white"><?= "$" . number_format($totalgcp, 2); ?></th>
                                    <th class="text-right font-weight-bold" style="background-color: red; color: white"><?= "$" . number_format($totalcc - $totalgcp, 2); ?></th>
                                    <th class="text-center" style="background-color: red; color: white"><?= $totalCant; ?></th>
                                </tr>
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
                                    if (($mes[$a]["Month(f_desdepoliza)"] - 1) == $i) {
                                        $dataPrima = $primaPorMesCC[$a];
                                        if ($a < (sizeof($mes) - 1)) {
                                            $a++;
                                        }
                                    } else {
                                        $dataPrima = 0;
                                    }
                                ?> '<?= ($primaPorMesCC[$i] - $primaPorMesGCP[$i]); ?>',
                            <?php } ?>
                        ],
                        label: 'Utilidad',
                        fill: boundary,
                        pointHoverRadius: 30,
                        pointHitRadius: 20,
                        pointRadius: 5,
                    }]
                },
                options: Chart.helpers.merge(options, {
                    title: {
                        text: 'Participación en la Utilidad por Mes',
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