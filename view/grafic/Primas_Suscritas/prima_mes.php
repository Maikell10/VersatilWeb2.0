<?php require_once '../../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'Primas_Suscritas/prima_mes';

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
                            <h1 class="font-weight-bold text-center">Primas Suscritas por Mes</h1>
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
                                <a href="../primas_s.php" class="btn blue-gradient btn-lg btn-rounded">Menú de Gráficos</a>
                            </center>
                            <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('tableE', 'Prima Suscrita por Mes')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../../../assets/img/excel.png" width="40" alt=""></a></center>
                        </div>
            </div>

            <div class="card-body p-5 animated bounceInUp">
                <div class="col-md-8 mx-auto">
                    <div class="table-responsive-xl">
                        <table class="table table-hover table-striped table-bordered" id="table" width="100%">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th class="text-center">Mes Desde Recibo</th>
                                    <th class="text-center">Prima Suscrita Primer Año</th>
                                    <th class="text-center">Prima Suscrita Renovación</th>
                                    <th class="text-center">Prima Suscrita Total</th>
                                    <th class="text-center">Cantidad</th>

                                    <th hidden>anio</th>
                                    <th hidden>cia</th>
                                    <th hidden>tipo_cuenta</th>
                                    <th hidden>asesor_u</th>
                                    <th hidden>ramo</th>
                                    <th hidden>tpoliza</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $asesor_u = serialize($asesor_u);
                                $asesor_u = urlencode($asesor_u);
                                $cia = serialize($cia);
                                $cia = urlencode($cia);
                                $ramo = serialize($ramo);
                                $ramo = urlencode($ramo);
                                $tipo_cuenta = serialize($tipo_cuenta);
                                $tipo_cuenta = urlencode($tipo_cuenta);

                                for ($i = 0; $i < sizeof($mes); $i++) {
                                ?>
                                    <tr style="cursor: pointer">
                                        <th scope="row"><?= $mesArray[$mes[$i]["Month(f_desdepoliza)"] - 1]; ?></th>
                                        <td align="right"><?= "$" . number_format($primaPorMesPA[$i], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($primaPorMesR[$i], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($primaPorMes[$i], 2); ?></td>
                                        <td align="center"><?= $cantArray[$i]; ?></td>

                                        <td hidden><?= $_GET['anio']; ?></td>
                                        <td hidden><?= $cia; ?></td>
                                        <td hidden><?= $tipo_cuenta; ?></td>
                                        <td hidden><?= $asesor_u; ?></td>
                                        <td hidden><?= $ramo; ?></td>
                                        <td hidden><?= $mes[$i]["Month(f_desdepoliza)"]; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th scope="col">TOTAL</th>
                                    <th class="text-right font-weight-bold"><?= "$" . number_format($totalpa, 2); ?></th>
                                    <th class="text-right font-weight-bold"><?= "$" . number_format($totalr, 2); ?></th>
                                    <th class="text-right font-weight-bold"><?= "$" . number_format($totals, 2); ?></th>
                                    <th class="text-center font-weight-bold"><?= $totalCant; ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>



                    <div class="table-responsive-xl" hidden>
                        <table class="table table-hover table-striped table-bordered" id="tableE" width="100%">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th class="text-center" style="background-color: #4285F4; color: white">Mes Desde Recibo</th>
                                    <th class="text-center" style="background-color: #4285F4; color: white">Prima Suscrita Primer Año</th>
                                    <th class="text-center" style="background-color: #4285F4; color: white">Prima Suscrita Renovación</th>
                                    <th class="text-center" style="background-color: #4285F4; color: white">Prima Suscrita Total</th>
                                    <th class="text-center" style="background-color: #4285F4; color: white">Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                for ($i = 0; $i < sizeof($mes); $i++) {
                                ?>
                                    <tr>
                                        <th scope="row"><?= $mesArray[$mes[$i]["Month(f_desdepoliza)"] - 1]; ?></th>
                                        <td align="right"><?= "$" . number_format($primaPorMesPA[$i], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($primaPorMesR[$i], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($primaPorMes[$i], 2); ?></td>
                                        <td align="center"><?= $cantArray[$i]; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th scope="col">TOTAL</th>
                                    <th class="text-right font-weight-bold"><?= "$" . number_format($totalpa, 2); ?></th>
                                    <th class="text-right font-weight-bold"><?= "$" . number_format($totalr, 2); ?></th>
                                    <th class="text-right font-weight-bold"><?= "$" . number_format($totals, 2); ?></th>
                                    <th class="text-center font-weight-bold"><?= $totalCant; ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>


                    <div class="wrapper col-12">
                        <canvas id="chart-0" style="height:500px"></canvas>
                    </div>
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
        $("#table tbody tr").dblclick(function () {
            var mes = $(this).find("td").eq(9).html();
            var anio = $(this).find("td").eq(4).html();
            var cia = $(this).find("td").eq(5).html();
            var tipo_cuenta = $(this).find("td").eq(6).html();
            var asesor_u = $(this).find("td").eq(7).html();
            var ramo = $(this).find("td").eq(8).html();
        
            window.open("../Listados/Primas_Suscritas/poliza_mes.php?ramo=" + ramo + "&anio=" + anio + "&cia=" + cia + "&tipo_cuenta=" + tipo_cuenta + "&asesor_u=" + asesor_u + "&mes=" + mes , '_blank');
        });

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
                                        $dataPrima = $primaPorMes[$a];
                                        if ($a < (sizeof($mes) - 1)) {
                                            $a++;
                                        }
                                    } else {
                                        $dataPrima = 0;
                                    }
                                ?> '<?= number_format(($dataPrima*100)/$totals,2); ?>',
                            <?php } ?>
                        ],
                        label: 'Prima Suscrita (%)',
                        fill: boundary,
                        pointHoverRadius: 30,
                        pointHitRadius: 20,
                        pointRadius: 5,
                    }]
                },
                options: Chart.helpers.merge(options, {
                    title: {
                        text: 'Gráfico Prima Suscrita por Mes (%)',
                        fontSize: 25,
                        display: true
                    }
                })
            });
        });
    </script>
</body>

</html>