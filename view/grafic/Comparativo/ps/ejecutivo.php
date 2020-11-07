<?php require_once '../../../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'Comparativo/ejecutivo_ps';

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
                            <h1 class="font-weight-bold text-center">Comparativo de Prima Suscrita por Ejecutivo</h1>
                            <h2 class="font-weight-bold text-center">Año: <span class="text-danger"><?= $_GET['anio']; ?></span></h2>

                            <?php if ($mes != '') { ?>
                                <h2 class="font-weight-bold text-center">Mes: <span class="text-danger"><?= $mesArray[$_GET['mes']-1]; ?></span></h2>
                            <?php } ?>

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
                                <a href="../../comparativo.php" class="btn blue-gradient btn-lg btn-rounded">Menú de Gráficos</a>
                            </center>

                            <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('tableE', 'Prima Suscrita por Ejecutivo')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../../../../assets/img/excel.png" width="40" alt=""></a></center>
                        </div>
            </div>


            <div class="card-body p-5 animated bounceInUp">
                <div class="col-md-8 mx-auto">

                    <div class="table-responsive-xl">
                        <table class="table table-hover table-striped table-bordered" id="table" width="100%">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th class="text-center">Ejecutivo</th>
                                    <th class="text-center">Prima Suscrita <?= $m1; ?></th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="dusty-grass-gradient text-black text-center">Prima Suscrita <?= $m2; ?></th>
                                    <th class="dusty-grass-gradient text-black text-center">Cantidad</th>

                                    <th hidden>cia</th>
                                    <th hidden>ramo</th>
                                    <th hidden>tipo_cuenta</th>
                                    <th hidden>asesor_u</th>
                                    <th hidden>anio</th>
                                    <th hidden>mes</th>
                                    <th hidden>codvend</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $asesor_u = serialize($asesor_u);
                                $asesor_u = urlencode($asesor_u);
                                $ramo = serialize($ramo);
                                $ramo = urlencode($ramo);
                                $cia = serialize($cia);
                                $cia = urlencode($cia);
                                $tipo_cuenta = serialize($tipo_cuenta);
                                $tipo_cuenta = urlencode($tipo_cuenta);

                                for ($i = sizeof($ejecutivo)-1; $i > -1; $i--) {
                                ?>
                                    <tr style="cursor:pointer">
                                        <th scope="row"><?= utf8_encode($ejecutivoArray[$x[$i]]); ?></th>
                                        <td align="right"><?= "$" . number_format($sumatotalEjecutivoOld[$x[$i]], 2); ?></td>
                                        <td align="center"><?= $cantArrayOld[$x[$i]]; ?></td>
                                        <td align="right"><?= "$" . number_format($sumatotalEjecutivo[$x[$i]], 2); ?></td>
                                        <td align="center"><?= $cantArray[$x[$i]]; ?></td>

                                        <td hidden><?= $cia; ?></td>
                                        <td hidden><?= $ramo; ?></td>
                                        <td hidden><?= $tipo_cuenta; ?></td>
                                        <td hidden><?= $asesor_u; ?></td>
                                        <td hidden><?= $_GET['anio']; ?></td>
                                        <td hidden><?= $_GET['mes']; ?></td>
                                        <td hidden><?= $codejecutivoArray[$x[$i]]; ?></td>
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
                                    <th style="background-color: #4285F4; color: white">Ejecutivo</th>
                                    <th style="background-color: #4285F4; color: white">Prima Suscrita <?= $m1; ?></th>
                                    <th style="background-color: #4285F4; color: white">Cantidad</th>
                                    <th style="background-color: #d4fc79;">Prima Suscrita <?= $m2; ?></th>
                                    <th style="background-color: #d4fc79;">Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for ($i = sizeof($ejecutivo)-1; $i > -1; $i--) {
                                ?>
                                    <tr>
                                        <th scope="row"><?= utf8_encode($ejecutivoArray[$x[$i]]); ?></th>
                                        <td align="right"><?= "$" . number_format($sumatotalEjecutivoOld[$x[$i]], 2); ?></td>
                                        <td align="center"><?= $cantArrayOld[$x[$i]]; ?></td>
                                        <td align="right"><?= "$" . number_format($sumatotalEjecutivo[$x[$i]], 2); ?></td>
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

            $("#table tbody tr").dblclick(function() {
                var cia = $(this).find("td").eq(4).html();
                var ramo = $(this).find("td").eq(5).html();
                var tipo_cuenta = $(this).find("td").eq(6).html();
                var asesor_u = $(this).find("td").eq(7).html();
                var anio = $(this).find("td").eq(8).html();
                var mes = $(this).find("td").eq(9).html();
                var codvend = $(this).find("td").eq(10).html();

                window.open("../../Listados/Comparativo/poliza_ejecutivo.php?codvend=" + codvend + "&ramo=" + ramo + "&anio=" + anio + "&mes=" + mes + "&cia=" + cia + "&tipo_cuenta=" + tipo_cuenta + "&asesor_u=" + asesor_u, '_blank');
            });

            let myChart = document.getElementById('myChart').getContext('2d');

            // Global Options
            Chart.defaults.global.defaultFontFamily = 'Lato';
            Chart.defaults.global.defaultFontSize = 18;
            Chart.defaults.global.defaultFontColor = '#777';

            let massPopChart = new Chart(myChart, {
                type: 'horizontalBar', // bar, horizontalBar, pie, line, doughnut, radar, polarArea
                data: {
                    labels: [<?php for ($i = sizeof($ejecutivo)-1; $i > -1; $i--) { ?> '<?= utf8_encode($ejecutivoArray[$x[$i]]) . ' (' . intval($_GET['anio'] - 1) . ')'; ?>',
                            '<?= ' (' . $_GET['anio'] . ')'; ?>',

                        <?php } ?>
                    ],

                    datasets: [{

                        data: [<?php for ($i = sizeof($ejecutivo)-1; $i > -1; $i--) {
                                ?> '<?= $sumatotalEjecutivoOld[$x[$i]]; ?>',
                                '<?= $sumatotalEjecutivo[$x[$i]]; ?>',
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
                        text: 'Grafico Comparativo de Prima Suscrita por Ejecutivo',
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