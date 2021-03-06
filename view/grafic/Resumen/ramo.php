<?php require_once '../../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'Resumen/ramo';

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
                            <h1 class="font-weight-bold text-center">Resúmen por Ramo</h1>

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

                            <br>
                            <center>
                                <a href="../resumen.php" class="btn blue-gradient btn-lg btn-rounded">Menú de Gráficos</a>
                            </center>
                            <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('ComisionCobrE', 'Resumen por Ramo')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../../../assets/img/excel.png" width="40" alt=""></a></center>
                        </div>
            </div>

            <div class="card-body p-5 animated bounceInUp">
                <div class="col-md-12 mx-auto">
                    <div class="table-responsive-xl">
                        <table class="table table-hover table-striped table-bordered" id="ComisionCobr" width="100%">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th class="text-center">Ramo</th>
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
                                for ($i = 0; $i < sizeof($ramo); $i++) {
                                    if ($sumatotalRamoPC[$i] == 0) {
                                        $per_gc = 0;
                                    } else {
                                        $per_gc = (($sumatotalRamoCC[$i] * 100) / $sumatotalRamoPC[$i]);
                                    }
                                ?>
                                    <tr>
                                        <th scope="row"><?= utf8_encode($ramoArray[$i]); ?></th>
                                        <td align="right"><?= "$" . number_format($sumatotalRamo[$i], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($sumatotalRamoPC[$i], 2); ?></td>
                                        <td align="right" style="background-color: #D9D9D9;font-weight: bold"><?= "$" . number_format($sumatotalRamo[$i] - $sumatotalRamoPC[$i], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($sumatotalRamoCC[$i], 2); ?></td>
                                        <td nowrap class="text-right"><?= number_format($per_gc, 2) . " %"; ?></td>
                                        <td align="right"><?= "$" . number_format($sumatotalRamoGCP[$i], 2); ?></td>
                                        <td align="right" style="background-color: #D9D9D9;font-weight: bold"><?= "$" . number_format($sumatotalRamoCC[$i] - $sumatotalRamoGCP[$i], 2); ?></td>
                                        <td align="center" data-toggle="tooltip" data-placement="bottom" title="Cantidad Pólizas Suscritas"><?= $cantArray[$i]; ?></td>
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
                                    <th class="text-right font-weight-bold"><?= number_format(($totalcc * 100) / $totalpc, 2) . " %"; ?></th>
                                    <th class="text-right font-weight-bold"><?= "$" . number_format($totalgcp, 2); ?></th>
                                    <th class="text-right font-weight-bold"><?= "$" . number_format($totalcc - $totalgcp, 2); ?></th>
                                    <th class="text-center"><?= $totalCant; ?></th>
                                </tr>
                                <tr>
                                    <th class="text-center">Ramo</th>
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
                        <table class="table table-hover table-striped table-bordered" id="ComisionCobrE" width="100%">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th class="text-center" style="background-color: #4285F4; color: white">Ramo</th>
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
                                for ($i = 0; $i < sizeof($ramo); $i++) {
                                    if ($sumatotalRamoPC[$i] == 0) {
                                        $per_gc = 0;
                                    } else {
                                        $per_gc = (($sumatotalRamoCC[$i] * 100) / $sumatotalRamoPC[$i]);
                                    }
                                ?>
                                    <tr>
                                        <th scope="row"><?= utf8_encode($ramoArray[$i]); ?></th>
                                        <td align="right"><?= "$" . number_format($sumatotalRamo[$i], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($sumatotalRamoPC[$i], 2); ?></td>
                                        <td align="right" style="background-color: #D9D9D9;font-weight: bold"><?= "$" . number_format($sumatotalRamo[$i] - $sumatotalRamoPC[$i], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($sumatotalRamoCC[$i], 2); ?></td>
                                        <td nowrap class="text-right"><?= number_format($per_gc, 2) . " %"; ?></td>
                                        <td align="right"><?= "$" . number_format($sumatotalRamoGCP[$i], 2); ?></td>
                                        <td align="right" style="background-color: #D9D9D9;font-weight: bold"><?= "$" . number_format($sumatotalRamoCC[$i] - $sumatotalRamoGCP[$i], 2); ?></td>
                                        <td align="center" data-toggle="tooltip" data-placement="bottom" title="Cantidad Pólizas Suscritas"><?= $cantArray[$i]; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th scope="col" style="background-color: red; color: white">TOTAL</th>
                                    <th class="text-right font-weight-bold" style="background-color: red; color: white"><?= "$" . number_format($totals, 2); ?></th>
                                    <th class="text-right font-weight-bold" style="background-color: red; color: white"><?= "$" . number_format($totalpc, 2); ?></th>
                                    <th class="text-right font-weight-bold" style="background-color: red; color: white"><?= "$" . number_format($totals - $totalpc, 2); ?></th>
                                    <th class="text-right font-weight-bold" style="background-color: red; color: white"><?= "$" . number_format($totalcc, 2); ?></th>
                                    <th class="text-right font-weight-bold" style="background-color: red; color: white"><?= number_format(($totalcc * 100) / $totalpc, 2) . " %"; ?></th>
                                    <th class="text-right font-weight-bold" style="background-color: red; color: white"><?= "$" . number_format($totalgcp, 2); ?></th>
                                    <th class="text-right font-weight-bold" style="background-color: red; color: white"><?= "$" . number_format($totalcc - $totalgcp, 2); ?></th>
                                    <th class="text-center" style="background-color: red; color: white"><?= $totalCant; ?></th>
                                </tr>
                                <tr>
                                    <th class="text-center">Ramo</th>
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


                <div class="row row-cols-1 row-cols-md-3 mt-2">

                    <div class="col-md-4 mb-4">
                        <!-- Card -->
                        <div class="card h-100">
                            <!--Card content-->
                            <div class="card-body">

                                <br><br>
                                <h4 class="card-title text-center">Utilidad Total / Prima Cobrada</h4>
                                <h4 class="card-title text-center text-danger"><?= number_format((($totalcc - $totalgcp) * 100) / $totalpc, 2); ?> %</h4>

                                <h4 class="card-title text-center">Utilidad Total / Comisión Cobrada</h4>
                                <h4 class="card-title text-center text-danger"><?= number_format((($totalcc - $totalgcp) * 100) / $totalcc, 2); ?> %</h4>

                                <h4 class="card-title text-center">GC Total Pagada / Prima Cobrada</h4>
                                <h4 class="card-title text-center text-danger"><?= number_format((($totalgcp) * 100) / $totalpc, 2); ?> %</h4>

                                <h4 class="card-title text-center">GC Total Pagada / Comisión Cobrada</h4>
                                <h4 class="card-title text-center text-danger"><?= number_format((($totalgcp) * 100) / $totalcc, 2); ?> %</h4>

                            </div>
                        </div>
                        <!-- Card -->
                    </div>
                    <div class="col-md-8 mx-auto">
                        <div class="card">
                            <!--Card content-->
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="myChart" width="400" height="450"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
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
        Chart.defaults.global.defaultFontSize = 15;
        Chart.defaults.global.defaultFontColor = '#777';
        

        let massPopChart = new Chart(myChart, {
            type: 'pie', // bar, horizontalBar, pie, line, doughnut, radar, polarArea
            data: {
                labels: [<?php for ($i = sizeof($ramo); $i > $contador; $i--) { ?> '<?= utf8_encode($ramoArray[$x[$i]]); ?> (%)',

                    <?php } ?> 'OTROS (%)',
                ],

                datasets: [{

                    data: [<?php for ($i = sizeof($ramo); $i > $contador; $i--) {
                                $sumasegurada = ($sumatotalRamoCC[$x[$i]]);
                                $totalG = $totalG + $sumasegurada;
                            ?> '<?= number_format(($sumasegurada * 100) / $totalcc, 2); ?>',
                        <?php }
                            echo number_format((($totalcc - $totalG) * 100) / $totalcc, 2); ?>,
                    ],
                    //backgroundColor:'green',
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(53, 57, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)',
                        'purple',
                        'red',
                        'blue',
                        '#B44242',
                        '#7BB442',
                        '#42B489',
                        '#4276B4',
                        '#6F42B4',
                        '#B442A1',
                        'yellow',
                        '#7198FF',
                        '#FFBE71'
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
                    text: 'Participación en la Utilidad por Ramo',
                    fontSize: 29
                },
                legend: {
                    display: true,
                    position: 'right',
                    labels: {
                        fontColor: '#000'
                    },
                },
                responsive: true,
                maintainAspectRatio: false,
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