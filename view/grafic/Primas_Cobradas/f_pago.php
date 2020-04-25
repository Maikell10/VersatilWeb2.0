<?php require_once '../../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../../login.php");
    exit();
}

$pag = 'Primas_Cobradas/f_pago';

require_once '../../../Controller/Grafico.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . '\..\..\layout\header.php'; ?>
    <style>
        .alertify .ajs-header {
            background-color: red;
        }
    </style>
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
                            <h1 class="font-weight-bold text-center">Primas Cobradas por Forma de Pago</h1>
                            <h2 class="font-weight-bold text-center">Año: <?= $_POST['anio']; ?></h2>
                            <br>
                            <center>
                                <a href="../primas_c.php" class="btn blue-gradient btn-lg btn-rounded">Menú de Gráficos</a>
                            </center>
                            <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('table', 'Primas Cobradas por Forma de Pago')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../../../assets/img/excel.png" width="40" alt=""></a></center>
                        </div>
            </div>

            <div class="card-body p-5 animated bounceInUp">
                <div class="col-md-12 mx-auto">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered" id="table" width="100%">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th class="text-center">Forma de Pago</th>
                                    <th class="text-center">Enero</th>
                                    <th class="text-center">Febrero</th>
                                    <th class="text-center">Marzo</th>
                                    <th class="text-center">Abril</th>
                                    <th class="text-center">Mayo</th>
                                    <th class="text-center">Junio</th>
                                    <th class="text-center">Julio</th>
                                    <th class="text-center">Agosto</th>
                                    <th class="text-center">Septiempre</th>
                                    <th class="text-center">Octubre</th>
                                    <th class="text-center">Noviembre</th>
                                    <th class="text-center">Diciembre</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for ($i = sizeof($f_pago) - 1; $i > -1; $i--) { ?>
                                    <tr>
                                        <th scope="row"><?= utf8_encode($fPagoArray[$x[$i]]); ?></th>
                                        <td align="right"><?= "$" . number_format($p1[$x[$i]], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($p2[$x[$i]], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($p3[$x[$i]], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($p4[$x[$i]], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($p5[$x[$i]], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($p6[$x[$i]], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($p7[$x[$i]], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($p8[$x[$i]], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($p9[$x[$i]], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($p10[$x[$i]], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($p11[$x[$i]], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($p12[$x[$i]], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($totalP[$x[$i]], 2); ?></td>
                                        <td class="text-center"><?= $cantidad[$x[$i]]; ?></td>
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
                                    <th style="text-align: right;"><?= "$" . number_format($totalPC, 2); ?></th>
                                    <th class="text-center"><?= $totalCant; ?></th>
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



    <?php require_once dirname(__DIR__) . '\..\..\layout\footer_b.php'; ?>

    <?php require_once dirname(__DIR__) . '\..\..\layout\footer.php'; ?>

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
                labels: [<?php for ($i = sizeof($f_pago) - 1; $i > -1; $i--) { ?> '<?= utf8_encode($fPagoArray[$x[$i]]); ?>',

                    <?php } ?>
                ],

                datasets: [{

                    data: [<?php for ($i = sizeof($f_pago) - 1; $i > -1; $i--) { ?> '<?= $totalP[$x[$i]]; ?>',

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
                    text: 'Primas Cobradas por Forma de Pago',
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