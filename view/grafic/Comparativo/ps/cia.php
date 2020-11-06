<?php require_once '../../../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'Comparativo/cia_ps';

require_once '../../../../Controller/Grafico.php';

if ($_GET['mes'] != '') {
    $m1 = '( ' . $mesArray[$_GET['mes'] - 1] . ' - ' . intval($_GET['anio'] - 1) . ' )';
    $m2 = '( ' . $mesArray[$_GET['mes'] - 1] . ' - ' . $_GET['anio'] . ' )';
} else {
    $m1 = '( ' . intval($_GET['anio'] - 1) . ' )';
    $m2 = '( ' . $_GET['anio'] . ' )';
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . DS . '..' . DS . '..' . DS . '..' . DS . 'layout' . DS . 'header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . DS . '..' . DS . '..' . DS . '..' . DS . 'layout' . DS . 'navigation.php'; ?>
    <br><br><br><br><br><br>

    <div>
        <div class="card">


            <div class="card-header p-5 animated bounceInDown">
                <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                    <- Regresar</a> <br><br>
                        <div class="ml-5 mr-5">
                            <h1 class="font-weight-bold text-center">Comparativo de Prima Suscrita por Cía</h1>
                            <br>
                            <center>
                                <a href="../../comparativo.php" class="btn blue-gradient btn-lg btn-rounded">Menú de Gráficos</a>
                            </center>

                            <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('tableE', 'Prima Suscrita por Cía')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../../../../assets/img/excel.png" width="40" alt=""></a></center>
                        </div>
            </div>


            <div class="card-body p-5 animated bounceInUp">
                <div class="col-md-8 mx-auto">

                    <div class="table-responsive-xl">
                        <table class="table table-hover table-striped table-bordered" id="table" width="100%">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th class="text-center">Cía</th>
                                    <th class="text-center">Prima Suscrita <?= $m1; ?></th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="dusty-grass-gradient text-black text-center">Prima Suscrita <?= $m2; ?></th>
                                    <th class="dusty-grass-gradient text-black text-center">Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for ($i = sizeof($cia); $i > 0; $i--) {
                                ?>
                                    <tr>
                                        <th scope="row"><?= utf8_encode($ciaArray[$x[$i]]); ?></th>
                                        <td align="right"><?= "$" . number_format($sumatotalCiaOld[$x[$i]], 2); ?></td>
                                        <td align="center"><?= $cantArrayOld[$x[$i]]; ?></td>
                                        <td align="right"><?= "$" . number_format($sumatotalCia[$x[$i]], 2); ?></td>
                                        <td align="center"><?= $cantArray[$x[$i]]; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="font-weight-bold">TOTAL</th>
                                    <th class="font-weight-bold text-right"><?= "$" . number_format($totalsOld, 2); ?></th>
                                    <th class="font-weight-bold text-center"><?= $totalCantOld; ?></th>
                                    <th class="font-weight-bold text-right"><?= "$" . number_format($totals, 2); ?></th>
                                    <th class="font-weight-bold text-center"><?= $totalCant; ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="table-responsive-xl" hidden>
                        <table class="table table-hover table-striped table-bordered" id="tableE" width="100%">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th style="background-color: #4285F4; color: white">Cía</th>
                                    <th style="background-color: #4285F4; color: white">Prima Suscrita <?= $m1; ?></th>
                                    <th style="background-color: #4285F4; color: white">Cantidad</th>
                                    <th style="background-color: #d4fc79;">Prima Suscrita <?= $m2; ?></th>
                                    <th style="background-color: #d4fc79;">Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for ($i = sizeof($cia); $i > 0; $i--) {
                                ?>
                                    <tr>
                                        <th scope="row"><?= utf8_encode($ciaArray[$x[$i]]); ?></th>
                                        <td align="right"><?= "$" . number_format($sumatotalCiaOld[$x[$i]], 2); ?></td>
                                        <td align="center"><?= $cantArrayOld[$x[$i]]; ?></td>
                                        <td align="right"><?= "$" . number_format($sumatotalCia[$x[$i]], 2); ?></td>
                                        <td align="center"><?= $cantArray[$x[$i]]; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="font-weight-bold">TOTAL</th>
                                    <th class="font-weight-bold" style="text-align: right"><?= "$" . number_format($totalsOld, 2); ?></th>
                                    <th class="font-weight-bold" style="text-align: center"><?= $totalCantOld; ?></th>
                                    <th class="font-weight-bold" style="text-align: right"><?= "$" . number_format($totals, 2); ?></th>
                                    <th class="font-weight-bold" style="text-align: center"><?= $totalCant; ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>


                    <canvas id="myChart"></canvas>
                </div>
                <br><br><br>
            </div>

        </div>





        <?php require_once dirname(__DIR__) . DS . '..' . DS . '..' . DS . '..' . DS . 'layout' . DS . 'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . DS . '..' . DS . '..' . DS . '..' . DS . 'layout' . DS . 'footer.php'; ?>

        <script src="../../../../assets/view/grafico.js"></script>

        <script type="text/javascript">
            let myChart = document.getElementById('myChart').getContext('2d');

            // Global Options
            Chart.defaults.global.defaultFontFamily = 'Lato';
            Chart.defaults.global.defaultFontSize = 18;
            Chart.defaults.global.defaultFontColor = '#777';

            let massPopChart = new Chart(myChart, {
                type: 'horizontalBar', // bar, horizontalBar, pie, line, doughnut, radar, polarArea
                data: {
                    labels: [<?php for ($i = sizeof($cia); $i > 0; $i--) { ?> '<?= utf8_encode($ciaArray[$x[$i]]) . ' (' . intval($_GET['anio'] - 1) . ')'; ?>',
                            '<?= ' (' . $_GET['anio'] . ')'; ?>',

                        <?php } ?>
                    ],

                    datasets: [{

                        data: [<?php for ($i = sizeof($cia); $i > 0; $i--) {
                                ?> '<?= $sumatotalCiaOld[$x[$i]]; ?>',
                                '<?= $sumatotalCia[$x[$i]]; ?>',
                            <?php } ?>
                        ],
                        //backgroundColor:'green',
                        backgroundColor: [
                            '#17a2b8',
                            '#28a745',
                            '#17a2b8',
                            '#28a745',
                            '#17a2b8',
                            '#28a745',
                            '#17a2b8',
                            '#28a745',
                            '#17a2b8',
                            '#28a745',
                            '#17a2b8',
                            '#28a745',
                            '#17a2b8',
                            '#28a745',
                            '#17a2b8',
                            '#28a745',
                            '#17a2b8',
                            '#28a745',
                            '#17a2b8',
                            '#28a745',
                            '#17a2b8',
                            '#28a745',
                            '#17a2b8',
                            '#28a745',
                            '#17a2b8',
                            '#28a745',
                            '#17a2b8',
                            '#28a745',
                            '#17a2b8',
                            '#28a745',
                            '#17a2b8',
                            '#28a745',
                            '#17a2b8',
                            '#28a745',
                            '#17a2b8',
                            '#28a745',
                            '#17a2b8',
                            '#28a745'
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
                        text: 'Grafico Comparativo de Prima Suscrita por Cía',
                        fontSize: 25
                    },
                    legend: {
                        display: false,
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