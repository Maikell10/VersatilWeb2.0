<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'crm/v_prom';

require_once '../../Controller/Poliza.php';
require_once '../../Model/Cliente.php';

$obj1 = new Cliente();

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
                            <h1 class="font-weight-bold text-center">Mensaje Programado de Promoción a Clientes</h1>

                                <h3 class="font-weight-bold text-center">
                                    Fecha de Creación: <span class="text-danger">
                                        <?= $newCreated = date("d/m/Y", strtotime($mensaje_p1[0]['created_at']));?>
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
                                <a class="nav-link active" data-toggle="tab" href="#panel100" role="tab">Clientes en Promoción</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#panel101" role="tab">Tarjeta para enviar en automático</a>
                            </li>
                        </ul>

                        <!-- Tab panels -->
                        <div class="tab-content card mt-2">

                            <!--Panel 1-->
                            <div class="tab-pane fade in show active" id="panel100" role="tabpanel">

                                <div class="table-responsive-xl">
                                    <table class="table table-hover table-striped table-bordered" id="table_cliente_bp" width="100%">
                                        <thead class="blue-gradient text-white text-center">
                                            <tr>
                                                <th hidden>id</th>
                                                <th hidden>ci</th>
                                                <th>Cédula</th>
                                                <th>Nombre</th>
                                                <th style="background-color: #E54848;">Cant. Pólizas</th>
                                                <th>Activas</th>
                                                <th>Inactivas</th>
                                                <th>Anuladas</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php 
                                            for ($i=0; $i < sizeof($titulares); $i++) { 
                                                $m = date("m", strtotime($titulares[$i][0]['f_nac']));
                                                
                                                $totalA = 0;
                                                $totalI = 0;
                                                $totalAn = 0;

                                                $cant = $obj1->get_polizas_t_cliente($titulares[$i][0]['id_titular']);
                                                $totalCant = $totalCant + sizeof($cant);

                                                for ($a = 0; $a < sizeof($cant); $a++) {
                                                    $primaSusc = $primaSusc + $cant[$a]['prima'];
                                                    $totalPrima = $totalPrima + $cant[$a]['prima'];

                                                    $no_renov = $obj->verRenov1($cant[$a]['id_poliza']);
                                                    if ($no_renov[0]['no_renov'] != 1) {
                                                        if ($cant[$a]['f_hastapoliza'] >= date("Y-m-d")) {
                                                            $totalA = $totalA + 1;
                                                            $tA = $tA + 1;
                                                        } else {
                                                            $totalI = $totalI + 1;
                                                            $tI = $tI + 1;
                                                        }
                                                    } else {
                                                        $totalAn = $totalAn + 1;
                                                        $tAn = $tAn + 1;
                                                    }
                                                }
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
                                                    <td class="text-center"><?= sizeof($cant); ?></td>
                                                    <td class="text-center"><?= $totalA; ?></td>
                                                    <td class="text-center"><?= $totalI; ?></td>
                                                    <td class="text-center"><?= $totalAn; ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>

                                        <tfoot class="text-center">
                                            <tr>
                                                <th hidden>id</th>
                                                <th hidden>ci</th>
                                                <th>Cédula</th>
                                                <th>Nombre</th>
                                                <th style="background-color: #E54848;color: white">Cant. Pólizas</th>
                                                <th>Activas</th>
                                                <th>Inactivas</th>
                                                <th>Anuladas</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                            </div>
                            <!--/.Panel 1-->

                            <!--Panel 2-->
                            <div class="tab-pane fade" id="panel101" role="tabpanel">

                                <div class="text-center">
                                    <img src="<?= constant('URL') . 'assets/img/crm/prom/' . $_GET["id_mensaje_p1"] . '.jpg'; ?>" class="z-depth-1" alt="tarjeta_promocion" style='width: 70%;vertical-align: middle;border-style: none' />
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