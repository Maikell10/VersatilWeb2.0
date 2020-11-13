<?php require_once '../../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'Resumen/tipo_poliza';

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
                            <h1 class="font-weight-bold text-center">Resúmen por Tipo de Póliza</h1>

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
                            <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('tableE', 'Comisiones Cobradas por Tipo de Póliza')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../../../assets/img/excel.png" width="40" alt=""></a></center>
                        </div>
            </div>

            <div class="card-body p-5 animated bounceInUp">
                <div class="col-md-12 mx-auto">
                    <div class="table-responsive-xl">
                        <table class="table table-hover table-striped table-bordered" id="table" width="100%">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th class="text-center">Tipo de Póliza</th>
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
                                for ($i = sizeof($tpoliza); $i > 0; $i--) {
                                    if ($sumatotalTpolizaPC[$x[$i]] == 0) {
                                        $per_gc = 0;
                                    } else {
                                        $per_gc = (($sumatotalTpolizaCC[$x[$i]] * 100) / $sumatotalTpolizaPC[$x[$i]]);
                                    }
                                ?>
                                    <tr>
                                        <th scope="row"><?= ($tpolizaArray[$x[$i]]); ?></th>
                                        <td align="right"><?= "$" . number_format($sumatotalTpoliza[$x[$i]], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($sumatotalTpolizaPC[$x[$i]], 2); ?></td>
                                        <td align="right" style="background-color: #D9D9D9;font-weight: bold"><?= "$" . number_format($sumatotalTpoliza[$x[$i]] - $sumatotalTpolizaPC[$x[$i]], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($sumatotalTpolizaCC[$x[$i]], 2); ?></td>
                                        <td align="right" nowrap><?= number_format($per_gc, 2) . " %"; ?></td>
                                        <td align="right"><?= number_format($sumatotalTpolizaGCP[$x[$i]], 2); ?></td>
                                        <td align="right" style="background-color: #D9D9D9;font-weight: bold"><?= number_format($sumatotalTpolizaCC[$x[$i]] - $sumatotalTpolizaGCP[$x[$i]], 2); ?></td>
                                        <td align="center"><?= $cantArray[$x[$i]]; ?></td>
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
                                    <th class="text-center">Tipo de Póliza</th>
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
                        <table class="table table-hover table-striped table-bordered" id="tableE" width="100%">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th class="text-center" style="background-color: #4285F4; color: white">Tipo de Póliza</th>
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
                                for ($i = sizeof($tpoliza); $i > 0; $i--) {
                                    if ($sumatotalTpolizaPC[$x[$i]] == 0) {
                                        $per_gc = 0;
                                    } else {
                                        $per_gc = (($sumatotalTpolizaCC[$x[$i]] * 100) / $sumatotalTpolizaPC[$x[$i]]);
                                    }
                                ?>
                                    <tr>
                                        <th scope="row"><?= ($tpolizaArray[$x[$i]]); ?></th>
                                        <td align="right"><?= "$" . number_format($sumatotalTpoliza[$x[$i]], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($sumatotalTpolizaPC[$x[$i]], 2); ?></td>
                                        <td align="right" style="background-color: #D9D9D9;font-weight: bold"><?= "$" . number_format($sumatotalTpoliza[$x[$i]] - $sumatotalTpolizaPC[$x[$i]], 2); ?></td>
                                        <td align="right"><?= "$" . number_format($sumatotalTpolizaCC[$x[$i]], 2); ?></td>
                                        <td align="right" nowrap><?= number_format($per_gc, 2) . " %"; ?></td>
                                        <td align="right"><?= number_format($sumatotalTpolizaGCP[$x[$i]], 2); ?></td>
                                        <td align="right" style="background-color: #D9D9D9;font-weight: bold"><?= number_format($sumatotalTpolizaCC[$x[$i]] - $sumatotalTpolizaGCP[$x[$i]], 2); ?></td>
                                        <td align="center"><?= $cantArray[$x[$i]]; ?></td>
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
                                    <th class="text-center">Tipo de Póliza</th>
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
                labels: [<?php for ($i = 0; $i < sizeof($tpoliza); $i++) { ?> '<?= ($tpoliza[$i]["tipo_poliza"]); ?>  (%)',

                    <?php } ?>
                ],

                datasets: [{

                    data: [<?php for ($i = 0; $i < sizeof($tpoliza); $i++) {
                                $sumasegurada = ($sumatotalTpolizaCC[$i]);
                            ?> '<?= number_format(($sumasegurada*100)/$totalcc,2); ?>',
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
                    text: 'Participación en la Utilidad por Tipo de Poliza',
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