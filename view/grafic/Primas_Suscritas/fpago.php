<?php require_once '../../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'Porcentaje/fpago';

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
                            <h1 class="font-weight-bold text-center">Primas Suscritas por Forma de Pago</h1>

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
                                <a href="../primas_s.php" class="btn blue-gradient btn-lg btn-rounded">Menú de Gráficos</a>
                            </center>
                            <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('tableE', 'Primas Suscritas por Forma de Pago')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../../../assets/img/excel.png" width="40" alt=""></a></center>
                        </div>
            </div>

            <div class="card-body p-5 animated bounceInUp">
                <div class="col-md-8 mx-auto">
                    <div class="table-responsive-xl">
                        <table class="table table-hover table-striped table-bordered" id="table" width="100%">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th class="text-center">Pago</th>
                                    <th class="text-center">Prima Suscrita</th>
                                    <th class="text-center">Cantidad</th>

                                    <th hidden>desde</th>
                                    <th hidden>hasta</th>
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

                                for ($i = sizeof($fpago); $i > 0; $i--) {
                                ?>
                                    <tr style="cursor: pointer">
                                        <th scope="row"><?= utf8_encode($fpagoArray[$x[$i]]); ?></th>
                                        <td align="right"><?= "$" . number_format($sumatotalFpago[$x[$i]], 2); ?></td>
                                        <td align="center"><?= $cantArray[$x[$i]]; ?></td>

                                        <td hidden><?= $desde; ?></td>
                                        <td hidden><?= $hasta; ?></td>
                                        <td hidden><?= $cia; ?></td>
                                        <td hidden><?= $tipo_cuenta; ?></td>
                                        <td hidden><?= $asesor_u; ?></td>
                                        <td hidden><?= $ramo; ?></td>
                                        <td hidden><?= $fpagoArray[$x[$i]]; ?></td>
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


                    <div class="table-responsive-xl" hidden>
                        <table class="table table-hover table-striped table-bordered" id="tableE" width="100%">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th class="text-center" style="background-color: #4285F4; color: white">Pago</th>
                                    <th class="text-center" style="background-color: #4285F4; color: white">Prima Suscrita</th>
                                    <th class="text-center" style="background-color: #4285F4; color: white">Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for ($i = sizeof($fpago); $i > 0; $i--) {
                                ?>
                                    <tr>
                                        <th scope="row"><?= utf8_encode($fpagoArray[$x[$i]]); ?></th>
                                        <td align="right"><?= "$" . number_format($sumatotalFpago[$x[$i]], 2); ?></td>
                                        <td align="center"><?= $cantArray[$x[$i]]; ?></td>
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



    <?php require_once dirname(__DIR__) . DS . '..' . DS . '..' . DS . 'layout' . DS . 'footer_b.php'; ?>

    <?php require_once dirname(__DIR__) . DS . '..' . DS . '..' . DS . 'layout' . DS . 'footer.php'; ?>

    <script src="../../../assets/view/grafico.js"></script>

    <script>
        $("#table tbody tr").dblclick(function () {
            var fpago = $(this).find("td").eq(8).html();
            var desde = $(this).find("td").eq(2).html();
            var hasta = $(this).find("td").eq(3).html();
            var cia = $(this).find("td").eq(4).html();
            var tipo_cuenta = $(this).find("td").eq(5).html();
            var asesor_u = $(this).find("td").eq(6).html();
            var ramo = $(this).find("td").eq(7).html();
        
            window.open("../Listados/Porcentaje/poliza_fpago.php?ramo=" + ramo + "&desde=" + desde + "&hasta=" + hasta + "&cia=" + cia + "&tipo_cuenta=" + tipo_cuenta + "&asesor_u=" + asesor_u + "&fpago=" + fpago , '_blank');
        });

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
                                $sumasegurada = $sumatotalFpago[$x[$i]] ;
                            ?> '<?= number_format(($sumatotalFpago[$x[$i]]*100)/$totals,2); ?>',
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
                    text: 'Distribución de la Cartera por Forma de Pago (%)',
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