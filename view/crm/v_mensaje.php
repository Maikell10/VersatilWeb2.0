<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'crm/v_mensaje';

require_once '../../Controller/Poliza.php';
$totalPrimaNR = 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'navigation.php'; ?>
    <br><br><br><br><br><br>

    <div>
        <div class="card">

            <div id="carga" class="d-flex justify-content-center align-items-center">
                <div class="spinner-grow text-info" style="width: 7rem; height: 7rem;"></div>
            </div>

            <div class="card-header p-5 animated bounceInDown" id="headerload" hidden="true">
                <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                    <- Regresar</a> <br><br>
                        <div class="ml-5 mr-5">
                            <h1 class="font-weight-bold text-center">Mensaje Programado de Cumpleaños a Clientes</h1>

                                <h3 class="font-weight-bold text-center">
                                    Fecha de Creación: <span class="text-danger">
                                        <?= $newCreated = date("d/m/Y", strtotime($mensaje_c1[0]['created_at']));?>
                                    </span>
                                </h3>
                        </div>
            </div>


            <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">

                <!-- Grid row -->
                <div class="row">

                    <!-- Grid column -->
                    <div class="col-md-10 m-auto">

                        <ul class="nav md-pills nav-justified pills-rounded pills-blue-gradient">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#panel100" role="tab">Cumpleañeros</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#panel101" role="tab">Carta para enviar en automático</a>
                            </li>
                        </ul>

                        <!-- Tab panels -->
                        <div class="tab-content card mt-2">

                            <!--Panel 1-->
                            <div class="tab-pane fade in show active" id="panel100" role="tabpanel">

                                <div class="table-responsive-xl">
                                    <table class="table table-hover table-striped table-bordered" id="table_cliente_bm" width="100%">
                                        <thead class="blue-gradient text-white text-center">
                                            <tr>
                                                <th hidden>id</th>
                                                <th hidden>ci</th>
                                                <th>Cédula</th>
                                                <th>Nombre</th>
                                                <th style="background-color: #E54848;">Día de Cumpleaños</th>
                                                <th hidden>mes</th>
                                                <th style="background-color: #E54848;">Mes de Cumpleaños</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php 
                                            for ($i=0; $i < sizeof($titulares); $i++) { 
                                                $m = date("m", strtotime($titulares[$i][0]['f_nac']));
                                            ?>
                                                <tr style="cursor: pointer">
                                                    <td hidden><?= $titulares[$i][0]['id_titular']; ?></td>
                                                    <td hidden><?= $titulares[$i][0]['ci']; ?></td>

                                                    <td><?= $titulares[$i][0]['r_social'] . '' . $titulares[$i][0]['ci']; ?></td>
                                                    <td>
                                                        <?= $titulares[$i][0]['nombre_t'] . ' ' . $titulares[$i][0]['apellido_t']; ?>
                                                        <?php if ($titulares[$i][0]['email'] != '-') { ?>
                                                            <span class="badge badge-pill badge-info">Email</span>
                                                        <?php } ?>
                                                    </td>
                                                    <td class="text-center"><?= date("d", strtotime($titulares[$i][0]['f_nac'])); ?></td>
                                                    <td class="text-center" hidden><?= $m; ?></td>
                                                    <td class="text-center"><?= $mes_arr[$m-1]; ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>

                                        <tfoot class="text-center">
                                            <tr>
                                                <th hidden>id</th>
                                                <th hidden>ci</th>
                                                <th>Cédula</th>
                                                <th>Nombre</th>
                                                <th style="background-color: #E54848; color: white">Día de Cumpleaños</th>
                                                <th hidden>mes</th>
                                                <th style="background-color: #E54848; color: white">Mes de Cumpleaños</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                            </div>
                            <!--/.Panel 1-->

                            <!--Panel 2-->
                            <div class="tab-pane fade" id="panel101" role="tabpanel">

                                <div class="text-center">
                                    <img src="<?= constant('URL') . 'assets/img/crm/' . $_GET["id_mensaje_c1"] . '.jpg'; ?>" class="z-depth-1" alt="tarjeta_cumpleaños" style='width: 70%;vertical-align: middle;border-style: none' />
                                </div>

                            </div>
                            <!--/.Panel 2-->


                        </div>

                    </div>
                    <!-- Grid column -->

                </div>
                <!-- Grid row -->

            </div>

        </div>





        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer.php'; ?>

        <script src="../../assets/view/b_cliente.js"></script>
        <script src="../../assets/view/modalN.js"></script>

</body>

</html>