<?php require_once '../../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
  header("Location: ../../../login.php");
  exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'Porcentaje/ramo_promedio';

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
              <h1 class="font-weight-bold text-center">Prima Promedio por Ramo</h1>
              <br>
              <center>
                <a href="../porcentaje.php" class="btn blue-gradient btn-lg btn-rounded">Menú de Gráficos</a>
              </center>
              <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('table', 'Prima Promedio por Ramo')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../../../assets/img/excel.png" width="40" alt=""></a></center>
            </div>
      </div>

      <div class="card-body p-5 animated bounceInUp">
        <div class="col-md-8 mx-auto">
          <div class="table-responsive-xl">
            <table class="table table-hover table-striped table-bordered" id="table" width="100%">
              <thead class="blue-gradient text-white">
                <tr>
                  <th class="text-center">Ramo</th>
                  <th class="text-center">Prima Suscrita</th>
                  <th class="text-center">Cantidad</th>
                </tr>
              </thead>
              <tbody>
                <?php for ($i = sizeof($ramo); $i > 0; $i--) {
                ?>
                  <tr>
                    <th scope="row"><?= utf8_encode($ramoArray[$x[$i]]); ?></th>
                    <td class="text-right"><?= "$" . number_format(($sumatotalRamo[$x[$i]]) / $cantArray[$x[$i]], 2); ?></td>
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



  <?php require_once dirname(__DIR__) . DS . '..' . DS . '..' . DS . 'layout' . DS . 'footer_b.php'; ?>

  <?php require_once dirname(__DIR__) . DS . '..' . DS . '..' . DS . 'layout' . DS . 'footer.php'; ?>

  <script src="../../../assets/view/grafico.js"></script>

  <script>
    let myChart = document.getElementById('myChart').getContext('2d');

    // Global Options
    Chart.defaults.global.defaultFontFamily = 'Lato';
    Chart.defaults.global.defaultFontSize = 18;
    Chart.defaults.global.defaultFontColor = '#777';

    var color = Chart.helpers.color;

    let massPopChart = new Chart(myChart, {
      type: 'bar', // bar, horizontalBar, pie, line, doughnut, radar, polarArea
      data: {
        labels: [<?php for ($i = sizeof($ramo); $i > 0; $i--) { ?> '<?= ($ramoArray[$x[$i]]); ?>',

          <?php } ?>
        ],

        datasets: [{
          label: "Valores Promedio",
          data: [<?php for ($i = sizeof($ramo); $i > 0; $i--) {  ?> '<?= ($sumatotalRamo[$x[$i]]) / $cantArray[$x[$i]]; ?>',
            <?php } ?>
          ],
          //backgroundColor:'green',
          //backgroundColor: color(window.chartColors.red).alpha(0.5).rgbString(),
          backgroundColor: 'red',
          borderWidth: 1,
          borderColor: '#777',
          hoverBorderWidth: 3,
          hoverBorderColor: '#000'
        }]
      },
      options: {
        title: {
          display: true,
          text: 'Prima Promedio por Ramo (%)',
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