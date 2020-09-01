<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);
//$pag = 'b_reportes';

require_once '../../Controller/Poliza.php';

$proyect = $obj->get_gc_h_p(1);

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

            <?php if ($_SESSION['id_permiso'] != 3) { ?>
                <div class="card-header p-5 animated bounceInDown" id="headerload" hidden="true">
                    <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                        <- Regresar</a> <br><br>
                            <div class="row ml-5 mr-5">
                                <h1 class="font-weight-bold ">Historial de GC (Proyectos)</h1>
                            </div>
                </div>
                <hr />


                <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">


                    <?php if ($proyect != 0) { ?>

                        <div class="table-responsive col-md-12">
                            <table class="table table-hover table-striped table-bordered" id="tablrPagoGCR" style="cursor: pointer;" width="100%">
                                <thead class="blue-gradient text-white text-center">
                                    <tr>
                                        <th hidden>Id Póliza</th>
                                        <th>N° Póliza</th>
                                        <th>Referidor</th>
                                        <th>Monto GC</th>
                                        <th>Fecha Pago</th>
                                        <th>Status</th>
                                        <th>Nº Transf</th>
                                        <th>Banco</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    for ($i = 0; $i < sizeof($proyect); $i++) {
                                        $newCreated = date("Y/m/d", strtotime($proyect[$i]['f_pago_gc_r']));
                                        $newCreatedH = date("h:i:s a", strtotime($proyect[$i]['created_at']));

                                        $status = ($proyect[$i]['status_c'] == 0) ? 'Sin Pago' : 'Pagado';
                                        $totalMonto = $totalMonto + $proyect[$i]['monto_p'];

                                        $no_renov = $obj->verRenov1($proyect[$i]['id_poliza']);
                                    ?>
                                        <tr>
                                            <td hidden><?= $proyect[$i]['id_poliza']; ?></td>

                                            <?php if ($no_renov[0]['no_renov'] != 1) {
                                                if ($proyect[$i]['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                                    <td style="color: #2B9E34;font-weight: bold"><?= $proyect[$i]['cod_poliza']; ?></td>
                                                <?php } else { ?>
                                                    <td style="color: #E54848;font-weight: bold"><?= $proyect[$i]['cod_poliza']; ?></td>
                                                <?php }
                                            } else { ?>
                                                <td style="color: #4a148c;font-weight: bold"><?= $proyect[$i]['cod_poliza']; ?></td>
                                            <?php } ?>

                                            <td><?= $proyect[$i]['nombre'].' ('.$proyect[$i]['cod'].')'; ?></td>
                                            <td class="text-right"><?= '$ ' . number_format($proyect[$i]['monto_p'],2); ?></td>
                                            <td><?= $newCreated; ?></td>
                                            <td align="center"><?= $status; ?></td>
                                            <td><?= $proyect[$i]['n_transf']; ?></td>
                                            <td><?= $proyect[$i]['n_banco']; ?></td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>

                                <tfoot class="text-center">
                                    <tr>
                                        <th hidden>Id Póliza</th>
                                        <th>N° Póliza</th>
                                        <th>Referidor</th>
                                        <th>Monto GC $<?= number_format($totalMonto,2); ?></th>
                                        <th>Fecha Pago</th>
                                        <th>Status</th>
                                        <th>Nº Transf</th>
                                        <th>Banco</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <p class="h1 text-center">Total Monto GC</p>
                        <p class="h1 text-center text-danger">$ <?php echo number_format($totalMonto, 2); ?></p>
                    
                    <?php } else { ?>
                        <div class="col-md-auto col-md-offset-2 text-center">
                            <h2 class="title text-danger">No se encuentran pagos a Proyectos</h2>
                        </div>
                    <?php } ?>


                </div>

                <!--   TABLA PARA USUARIOS QUE SON ASESORES   -->
            <?php }
            if ($_SESSION['id_permiso'] == 3) {
            } ?>


        </div>





        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer.php'; ?>

        <script src="../../assets/view/b_poliza.js"></script>
</body>

</html>