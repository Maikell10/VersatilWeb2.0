<?php require_once '../../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'Comisiones_Cobradas/fpago';

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
                            <h1 class="font-weight-bold text-center">Resúmen por Forma de Pago</h1>
                            <br>
                            <center>
                                <a href="../comisiones_c.php" class="btn blue-gradient btn-lg btn-rounded">Menú de Gráficos</a>
                            </center>
                        </div>
            </div>

            <div class="card-body p-5 animated bounceInUp">
                <div class="col-md-12 mx-auto">
                    <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('table', 'Comisiones Cobradas por Forma de Pago')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../../../assets/img/excel.png" width="60" alt=""></a></center>
                    <div class="table-responsive-xl">
                        <table class="table table-hover table-striped table-bordered" id="table" width="100%">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th class="text-center">Forma de Pago</th>
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
                                for ($i = sizeof($fpago); $i > 0; $i--) {
                                    if ($sumatotalFpagoPC[$x[$i]] == 0) {
                                        $per_gc = 0;
                                    } else {
                                        $per_gc = (($sumatotalFpagoCC[$x[$i]] * 100) / $sumatotalFpagoPC[$x[$i]]);
                                    }
                                ?>
                                    <tr>
                                        <th scope="row"><?= utf8_encode($fpagoArray[$x[$i]]); ?></th>
                                        <td align="right"><?= "$" . number_format($sumatotalFpago[$x[$i]], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($sumatotalFpagoPC[$x[$i]], 2); ?></td>
                                        <td align="right" style="background-color: #ED7D31;color:white"><?= "$" . number_format($sumatotalFpago[$x[$i]] - $sumatotalFpagoPC[$x[$i]], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($sumatotalFpagoCC[$x[$i]], 2); ?></td>
                                        <td nowrap><?= number_format($per_gc, 2) . " %"; ?></td>
                                        <td align="right"><?= number_format($sumatotalFpagoGCP[$x[$i]], 2); ?></td>
                                        <td align="right" style="background-color: #ED7D31;color:white"><?= number_format($sumatotalFpagoCC[$x[$i]] - $sumatotalFpagoGCP[$x[$i]], 2); ?></td>
                                        <td class="text-center"><?= $cantArray[$x[$i]]; ?></td>
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
                                    <th class="text-center">Forma de Pago</th>
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
                    <canvas id="myChart"></canvas>
                </div>

            </div>
        </div>

    </div>



    <?php require_once dirname(__DIR__) . DS . '..' . DS . '..' . DS . 'layout' . DS . 'footer_b.php'; ?>

    <?php require_once dirname(__DIR__) . DS . '..' . DS . '..' . DS . 'layout' . DS . 'footer.php'; ?>

    <script src="../../../assets/view/grafico.js"></script>

    <script>
        let myChart = document.getElementById('myChart').getContext('2d');

        // Global Options
        Chart.defaults.global.defaultFontFamily = 'Lato';
        Chart.defaults.global.defaultFontSize = 18;
        Chart.defaults.global.defaultFontColor = '#777';

        let massPopChart = new Chart(myChart, {
            type: 'pie', // bar, horizontalBar, pie, line, doughnut, radar, polarArea
            data: {
                labels: [<?php for ($i = sizeof($fpago); $i > 0; $i--) { ?> '<?= utf8_encode($fpagoArray[$x[$i]]); ?>',

                    <?php } ?>
                ],

                datasets: [{

                    data: [<?php for ($i = sizeof($fpago); $i > 0; $i--) {
                                $sumasegurada = ($sumatotalFpagoCC[$x[$i]]);
                            ?> '<?= $sumasegurada; ?>',
                        <?php } ?>
                    ],
                    //backgroundColor:'green',
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(53, 57, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)',
                        'rgba(255, 99, 132, 0.6)',
                        'red',
                        'blue',
                        'yellow'
                    ],
                    borderWidth: 1,
                    borderColor: '#777',
                    hoverBorderWidth: 3,
                    hoverBorderColor: '#000'
                }]
            },
            options: {
                title: {
                    display: true,
                    text: 'Comisión Cobrada por Forma de Pago',
                    fontSize: 25
                },
                legend: {
                    display: true,
                    position: 'right',
                    labels: {
                        fontColor: '#000'
                    }
                },
                layout: {
                    padding: {
                        left: 50,
                        right: 0,
                        bottom: 0,
                        top: 0
                    }
                },
                tooltips: {
                    enabled: true
                }
            }
        });
    </script>
</body>

</html>