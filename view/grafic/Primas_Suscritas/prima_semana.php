<?php require_once '../../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
  header("Location: ../../../login.php");
  exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'Primas_Suscritas/prima_semana';

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
              <h1 class="font-weight-bold text-center">Primas Suscritas por Semana</h1>

              <h3 class="font-weight-bold text-center">
                Año: <span class="text-danger"><?= $_GET['anio']; ?></span>
                <?php if ($_GET['mes'] != null) { ?>
                  Mes: <span class="text-danger"><?= $mesArray[$_GET['mes'] - 1]; ?></span>
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
              <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('tableE', 'Prima Suscrita por Semana')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../../../assets/img/excel.png" width="40" alt=""></a></center>
            </div>
      </div>

      <div class="card-body p-5 animated bounceInUp">
        <div class="col-md-8 mx-auto">
          <div class="table-responsive-xl">
            <table class="table table-hover table-striped table-bordered" id="table" width="100%">
              <thead class="blue-gradient text-white">
                <tr>
                  <th class="text-center">Semana del Año Desde Recibo</th>
                  <th class="text-center">Prima Suscrita</th>
                  <th class="text-center">Cantidad</th>
                </tr>
              </thead>
              <tbody>
                <?php
                for ($i = 0; $i < sizeof($semSinDuplicado); $i++) {
                ?>
                  <tr>
                    <th scope="row"><?= $semSinDuplicado[$i]; ?></th>
                    <td align="right"><?= "$" . number_format($primaPorMesF[$i], 2); ?></td>
                    <td align="center"><?= $cantArrayF[$i]; ?></td>
                  </tr>
                <?php } ?>
              </tbody>
              <tfoot>
                <tr>
                  <th scope="col">TOTAL</th>
                  <th class="text-right font-weight-bold"><?= "$" . number_format($totals, 2); ?></th>
                  <th class="text-center font-weight-bold"><?= $totalCant; ?></th>
                </tr>
              </tfoot>
            </table>
          </div>


          <div class="table-responsive-xl" hidden>
            <table class="table table-hover table-striped table-bordered" id="tableE" width="100%">
              <thead class="blue-gradient text-white">
                <tr>
                  <th class="text-center" style="background-color: #4285F4; color: white">Semana del Año Desde Recibo</th>
                  <th class="text-center" style="background-color: #4285F4; color: white">Prima Suscrita</th>
                  <th class="text-center" style="background-color: #4285F4; color: white">Cantidad</th>
                </tr>
              </thead>
              <tbody>
                <?php
                for ($i = 0; $i < sizeof($semSinDuplicado); $i++) {
                ?>
                  <tr>
                    <th scope="row"><?= $semSinDuplicado[$i]; ?></th>
                    <td align="right"><?= "$" . number_format($primaPorMesF[$i], 2); ?></td>
                    <td align="center"><?= $cantArrayF[$i]; ?></td>
                  </tr>
                <?php } ?>
              </tbody>
              <tfoot>
                <tr>
                  <th scope="col">TOTAL</th>
                  <th class="text-right font-weight-bold"><?= "$" . number_format($totals, 2); ?></th>
                  <th class="text-center font-weight-bold"><?= $totalCant; ?></th>
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



  <script>
    let myChart = document.getElementById('myChart').getContext('2d');

    // Global Options
    Chart.defaults.global.defaultFontFamily = 'Lato';
    Chart.defaults.global.defaultFontSize = 18;
    Chart.defaults.global.defaultFontColor = '#777';

    let massPopChart = new Chart(myChart, {
      type: 'bar', // bar, horizontalBar, pie, line, doughnut, radar, polarArea
      data: {
        labels: [<?php for ($i = 0; $i < sizeof($semSinDuplicado); $i++) { ?> '<?= $semSinDuplicado[$i]; ?>',

          <?php } ?>
        ],

        datasets: [{
          label: "Prima por Semana (%)",
          data: [<?php for ($i = 0; $i < sizeof($semSinDuplicado); $i++) {
                  ?> '<?= number_format(($primaPorMesF[$i]*100)/$totals,2); ?>',
            <?php } ?>
          ],
          //backgroundColor:'green',
          backgroundColor: 'rgba(120, 255, 86, 0.6)',
          borderWidth: 1,
          borderColor: '#777',
          hoverBorderWidth: 3,
          hoverBorderColor: '#000'
        }]
      },
      options: {
        title: {
          display: true,
          text: 'Prima Suscrita por Semana (%)',
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