<?php require_once '../../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../../login.php");
    exit();
}

$pag = 'Porcentaje/ejecutivo';

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
                            <h1 class="font-weight-bold text-center">Primas Suscritas por Ejecutivo</h1>
                            <br>
                            <center>
                                <a href="../primas_s.php" class="btn blue-gradient btn-lg btn-rounded">Menú de Gráficos</a>
                            </center>
                            <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('table', 'Primas Suscritas por Ejecutivo')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../../../assets/img/excel.png" width="40" alt=""></a></center>
                        </div>
            </div>

            <div class="card-body p-5 animated bounceInUp">
                <div class="col-md-8 mx-auto">
                    <div class="table-responsive-xl">
                        <table class="table table-hover table-striped table-bordered" id="table" width="100%">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th scope="col">Ejecutivo Cuenta</th>
                                    <th scope="col">Prima Suscrita</th>
                                    <th scope="col">Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for ($i = sizeof($ejecutivo); $i > 0; $i--) {
                                ?>
                                    <tr>
                                        <th scope="row"><?= utf8_encode($ejecutivoArray[$x[$i]]); ?></th>
                                        <td align="right"><?= "$" . number_format($sumatotalEjecutivo[$x[$i]], 2); ?></td>
                                        <td class="text-center"><?= $cantArray[$x[$i]]; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th scope="col">TOTAL</th>
                                    <th class="text-right font-weight-bold"><?= "$" . number_format($totals, 2); ?></th>
                                    <th scope="col" class="text-center font-weight-bold"><?= $totalCant; ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>


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
                labels: [<?php for ($i = sizeof($ejecutivo); $i > $contador; $i--) { ?> '<?= utf8_encode($ejecutivoArray[$x[$i]]); ?>',

                    <?php } ?> 'OTROS',
                ],

                datasets: [{

                    data: [<?php for ($i = sizeof($ejecutivo); $i > $contador; $i--) {
                                $sumasegurada = $sumatotalEjecutivo[$x[$i]];
                                $totalG = $totalG + $sumasegurada;
                            ?> '<?= ($sumasegurada); ?>',
                        <?php }
                            echo ($totals - $totalG); ?>,
                    ],
                    //backgroundColor:'green',
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(53, 57, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)',
                        'rgb(102, 0, 204)',
                        'rgb(255, 153, 204)',
                        'red',
                        'blue',
                        'rgb(0, 153, 153)',
                        'rgb(204, 0, 153)',
                        'rgb(204, 51, 0)',
                        'rgb(255, 255, 0)',
                        'rgb(0, 0, 204)',
                        'rgb(0, 153, 153)',
                        'black',
                        'brown',
                        'purple',
                        'rgb(0, 102, 102)',
                        'rgb(51, 204, 51)',
                        'rgb(255, 80, 80)',
                        'rgb(102, 0, 204)',
                        'rgba(53, 57, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)',
                        'rgba(255, 99, 132, 0.6)',
                        'rgb(255, 153, 204)'
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
                    text: 'Prima Suscrita por Ejecutivo',
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