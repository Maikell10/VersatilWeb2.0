<?php require_once '../../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'Resumen/ejecutivo';

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
                            <h1 class="font-weight-bold text-center">Resúmen por Ejecutivo</h1>

                            <h3 class="font-weight-bold text-center">
                                Año: <span class="text-danger"><?= $_GET['anio']; ?></span>
                                <?php if ($mes != null) { ?>
                                    Mes: <span class="text-danger"><?= $mesArray[$mes - 1]; ?></span>
                                <?php } ?>
                            </h3>
                            <?php if ($tipo_cuenta != '') { ?>
                                <h3 class="font-weight-bold text-center">
                                    Tipo de Cuenta: <span class="text-danger">
                                        <?php foreach ($tipo_cuenta as $tipo) {
                                            if ($tipo == 1) {
                                                echo ' Individual ';
                                            }
                                            if ($tipo == 2) {
                                                echo ' Colectivo ';
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
                        </div>
            </div>

            <div class="card-body p-5 animated bounceInUp">
                <div class="col-md-12 mx-auto">
                    <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('ResumenEjec', 'Resumen por Ejecutivo')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../../../assets/img/excel.png" width="60" alt=""></a></center>
                    <div class="table-responsive-xl">
                        <table class="table table-hover table-striped table-bordered" id="ResumenEjec" width="100%">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th class="text-center">Ejecutivo</th>
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
                                for ($i = 0; $i < sizeof($ejecutivo); $i++) {
                                    if ($sumatotalEjecutivoPC[$i] == 0) {
                                        $per_gc = 0;
                                    } else {
                                        $per_gc = (($sumatotalEjecutivoCC[$i] * 100) / $sumatotalEjecutivoPC[$i]);
                                    }

                                    if (isset($sumatotalEjecutivoGCP[$i])) {
                                        $gc_pagada_1 = $sumatotalEjecutivoGCP[$i];
                                    } else {
                                        //nulo
                                        $gc_pagada_1 = 0;
                                    }
                                ?>
                                    <tr>
                                        <th scope="row"><?= utf8_encode($ejecutivoArray[$i]); ?></th>
                                        <td align="right"><?= "$" . number_format($sumatotalEjecutivo[$i], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($sumatotalEjecutivoPC[$i], 2); ?></td>
                                        <td align="right" style="background-color: #D9D9D9;font-weight: bold"><?= "$" . number_format($sumatotalEjecutivo[$i] - $sumatotalEjecutivoPC[$i], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($sumatotalEjecutivoCC[$i], 2); ?></td>
                                        <td nowrap><?= number_format($per_gc, 2) . " %"; ?></td>
                                        <td align="right"><?= number_format($gc_pagada_1, 2); ?></td>
                                        <td align="right" style="background-color: #D9D9D9;font-weight: bold"><?= number_format($sumatotalEjecutivoCC[$i] - $gc_pagada_1, 2); ?></td>
                                        <td class="text-center"><?= $cantArray[$i]; ?></td>
                                    </tr>
                                <?php } ?>
                                
                            </tbody>
                            <tfoot>
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
                                <tr>
                                    <th class="text-center">Ejecutivo</th>
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
                labels: [<?php for ($i = sizeof($ejecutivo); $i > $contador; $i--) { ?> '<?= utf8_encode($ejecutivoArray[$x[$i]]); ?>',

                    <?php } ?> 'OTROS',
                ],

                datasets: [{

                    data: [<?php for ($i = sizeof($ejecutivo); $i > $contador; $i--) {
                                $sumasegurada = ($sumatotalEjecutivoCC[$x[$i]]);
                                $totalG = $totalG + $sumasegurada;
                            ?> '<?= $sumasegurada; ?>',
                        <?php }
                            echo number_format($totalcc - $totalG, 2); ?>,
                    ],
                    //backgroundColor:'green',
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(53, 57, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)',
                        'purple',
                        'rgb(10, 102, 102, 0.8)',
                        'red',
                        'blue',
                        'brown',
                        'rgb(204, 0, 153)',
                        'rgb(204, 51, 0)',
                        'rgb(255, 255, 0)',
                        'rgb(0, 0, 204)',
                        'rgb(0, 153, 153)',
                        'rgb(102, 102, 153)',
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
                        'rgb(255, 153, 204)',
                        'red',
                        'blue',
                        'yellow',
                        'white',
                        'gray',
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
                    text: 'Comisión Cobrada por Ejecutivo',
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