<?php require_once '../../../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../../../login.php");
    exit();
}

$pag = 'Comparativo/ramo_pc';

require_once '../../../../Controller/Grafico.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . '\..\..\..\layout\header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . '\..\..\..\layout\navigation.php'; ?>
    <br><br><br><br><br><br>

    <div>
        <div class="card">


            <div class="card-header p-5 animated bounceInDown">
                <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                    <- Regresar</a> <br><br>
                        <div class="ml-5 mr-5">
                            <h1 class="font-weight-bold text-center">Comparativo de Prima Cobrada por Ramo</h1>
                            <h2 class="text-center">Año: <?= $_POST['anio']; ?> Mes: <?= $mesArray[$_POST['mes'] - 1]; ?></h2>
                            <br>
                            <center>
                                <a href="../../comparativo.php" class="btn blue-gradient btn-lg btn-rounded">Menú de Gráficos</a>
                            </center>

                            <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('table', 'Prima Suscrita por Ramo')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../../../../assets/img/excel.png" width="40" alt=""></a></center>
                        </div>
            </div>


            <div class="card-body p-5 animated bounceInUp">

                <div class="col-md-8 mx-auto">
                    <div class="table-responsive-xl">
                        <table class="table table-hover table-striped table-bordered" id="table" width="100%">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th>Ramo</th>
                                    <th><?= $mesArray[$_POST['mes'] - 1] . ' - ' . intval($_POST['anio'] - 1); ?></th>
                                    <th>Cantidad</th>
                                    <th class="dusty-grass-gradient text-black"><?= $mesArray[$_POST['mes'] - 1] . ' - ' . $_POST['anio']; ?></th>
                                    <th class="dusty-grass-gradient text-black">Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for ($i = sizeof($ramo); $i > 0; $i--) {
                                ?>
                                    <tr>
                                        <th scope="row"><?= utf8_encode($ramoArray[$x[$i]]); ?></th>
                                        <td align="right"><?= "$" . number_format($p1[$x[$i]], 2); ?></td>
                                        <td class="text-center"><?= $cantidadOld[$x[$i]]; ?></td>
                                        <td align="right"><?= "$" . number_format($p2[$x[$i]], 2); ?></td>
                                        <td class="text-center"><?= $cantidad[$x[$i]]; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="font-weight-bold">TOTAL</th>
                                    <th class="font-weight-bold text-right"><?= "$" . number_format($primaCobradaPorMes1, 2); ?></th>
                                    <th class="font-weight-bold text-center"><?= $totalCantOld; ?></th>
                                    <th class="font-weight-bold text-right"><?= "$" . number_format($primaCobradaPorMes2, 2); ?></th>
                                    <th class="font-weight-bold text-center"><?= $totalCant; ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>


                    <canvas id="myChart"></canvas>
                </div>
                <br><br><br>
            </div>

        </div>





        <?php require_once dirname(__DIR__) . '\..\..\..\layout\footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . '\..\..\..\layout\footer.php'; ?>

        <script src="../../../../assets/view/grafico.js"></script>

        <script>
            let myChart = document.getElementById('myChart').getContext('2d');

            // Global Options
            Chart.defaults.global.defaultFontFamily = 'Lato';
            Chart.defaults.global.defaultFontSize = 18;
            Chart.defaults.global.defaultFontColor = '#777';

            let massPopChart = new Chart(myChart, {
                type: 'horizontalBar', // bar, horizontalBar, pie, line, doughnut, radar, polarArea
                data: {
                    labels: [<?php for ($i = sizeof($ramo); $i > 0; $i--) { ?> '<?= utf8_encode($ramoArray[$x[$i]]) . ' (' . intval($_POST['anio'] - 1) . ')'; ?>',
                            '<?= ' (' . $_POST['anio'] . ')'; ?>',

                        <?php } ?>
                    ],

                    datasets: [{

                        data: [<?php for ($i = sizeof($ramo); $i > 0; $i--) {
                                ?> '<?= $p1[$x[$i]]; ?>',
                                '<?= $p2[$x[$i]]; ?>',
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
                        text: 'Grafico Comparativo de Prima Cobrada por Ramo',
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