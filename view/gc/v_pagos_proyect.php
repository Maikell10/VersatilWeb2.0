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

$proyect = $obj->get_gc_h_p_created(1, $_GET['created_at']);

$newCreated = date("d-m-Y", strtotime($_GET['created_at']));   
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
                            <div class="ml-5 mr-5">
                                <h1 class="font-weight-bold ">Resultado de Búsqueda de GC Pagada por Proyectos</h1>
                                <h3>Fecha Creación GC: <font style="font-weight:bold" class="text-danger"><?= $newCreated; ?></font>
                            </div>
                </div>

                <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">


                    <?php if ($proyect != 0) { ?>

                        <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('tablrPagoGCRE', 'GC Pagada por Referidor')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../../assets/img/excel.png" width="60" alt=""></a></center>

                        <div class="table-responsive col-md-12">
                            <table class="table table-hover table-striped table-bordered" id="tablrPagoGCR" style="cursor: pointer;" width="100%">
                                <thead class="blue-gradient text-white text-center">
                                    <tr>
                                        <th hidden>Id Póliza</th>
                                        <th>Proyecto</th>
                                        <th>N° Póliza</th>
                                        <th>Fecha Pago</th>
                                        <th>Nº Transf</th>
                                        <th>Banco</th>
                                        <th style="background-color: #E54848; color: white">Monto GC Pagado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $totalMonto = 0;
                                    for ($i = 0; $i < sizeof($proyect); $i++) {
                                        $newCreated = date("Y/m/d", strtotime($proyect[$i]['f_pago_gc_r']));
                                        $newCreatedH = date("h:i:s a", strtotime($proyect[$i]['created_at']));

                                        $status = ($proyect[$i]['status_c'] == 0) ? 'Sin Pago' : 'Pagado';
                                        $totalMonto = $totalMonto + $proyect[$i]['monto_p'];

                                        $no_renov = $obj->verRenov1($proyect[$i]['id_poliza']);
                                    ?>
                                        <tr>
                                            <td hidden><?= $proyect[$i]['id_poliza']; ?></td>

                                            <?php if ($proyect[$i]['act'] == 0) { ?>
                                                <td rowspan="<?= sizeof($poliza); ?>" style="font-weight: bold;color: #E54848"><?= $proyect[$i]['nombre'].' ('.$proyect[$i]['cod'].')'; ?></td>
                                            <?php }
                                            if ($proyect[$i]['act'] == 1) { ?>
                                                <td rowspan="<?= sizeof($poliza); ?>" style="font-weight: bold;color: #2B9E34"><?= $proyect[$i]['nombre'].' ('.$proyect[$i]['cod'].')'; ?></td>
                                            <?php } ?>

                                            <?php if ($no_renov[0]['no_renov'] != 1) {
                                                if ($proyect[$i]['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                                    <td style="color: #2B9E34;font-weight: bold"><?= $proyect[$i]['cod_poliza']; ?></td>
                                                <?php } else { ?>
                                                    <td style="color: #E54848;font-weight: bold"><?= $proyect[$i]['cod_poliza']; ?></td>
                                                <?php }
                                            } else { ?>
                                                <td style="color: #4a148c;font-weight: bold"><?= $proyect[$i]['cod_poliza']; ?></td>
                                            <?php } ?>
                                            
                                            
                                            <td><?= $newCreated; ?></td>
                                            <td><?= $proyect[$i]['n_transf']; ?></td>
                                            <td><?= $proyect[$i]['n_banco']; ?></td>
                                            <td style="text-align: right;background-color: #D9D9D9;font-weight: bold"><?= '$ ' . number_format($proyect[$i]['monto_p'],2); ?></td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>

                                <tfoot class="text-center">
                                    <tr>
                                        <th hidden>Id Póliza</th>
                                        <th>Proyecto</th>
                                        <th>N° Póliza</th>
                                        <th>Fecha Pago</th>
                                        <th>Nº Transf</th>
                                        <th>Banco</th>
                                        <th>Monto GC Pagado $<?= number_format($totalMonto,2); ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <p class="h1 text-center">Total Monto Pagado GC</p>
                        <p class="h1 text-center text-danger">$ <?php echo number_format($totalMonto, 2); ?></p>


                        <div class="table-responsive col-md-12" hidden>
                            <table class="table table-hover table-striped table-bordered" id="tablrPagoGCRE" style="cursor: pointer;" width="100%">
                                <thead class="blue-gradient text-white text-center">
                                    <tr>
                                        <th style="background-color: #4285F4; color: white">Proyecto</th>
                                        <th style="background-color: #4285F4; color: white">N° Póliza</th>
                                        <th style="background-color: #4285F4; color: white">Fecha Pago</th>
                                        <th style="background-color: #4285F4; color: white">Nº Transf</th>
                                        <th style="background-color: #4285F4; color: white">Banco</th>
                                        <th style="background-color: #E54848; color: white">Monto GC Pagado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $totalMonto = 0;
                                    for ($i = 0; $i < sizeof($proyect); $i++) {
                                        $newCreated = date("Y/m/d", strtotime($proyect[$i]['f_pago_gc_r']));
                                        $newCreatedH = date("h:i:s a", strtotime($proyect[$i]['created_at']));

                                        $status = ($proyect[$i]['status_c'] == 0) ? 'Sin Pago' : 'Pagado';
                                        $totalMonto = $totalMonto + $proyect[$i]['monto_p'];

                                        $no_renov = $obj->verRenov1($proyect[$i]['id_poliza']);
                                    ?>
                                        <tr>
                                            <?php if ($proyect[$i]['act'] == 0) { ?>
                                                <td rowspan="<?= sizeof($poliza); ?>" style="font-weight: bold;color: #E54848"><?= $proyect[$i]['nombre'].' ('.$proyect[$i]['cod'].')'; ?></td>
                                            <?php }
                                            if ($proyect[$i]['act'] == 1) { ?>
                                                <td rowspan="<?= sizeof($poliza); ?>" style="font-weight: bold;color: #2B9E34"><?= $proyect[$i]['nombre'].' ('.$proyect[$i]['cod'].')'; ?></td>
                                            <?php } ?>

                                            <?php if ($no_renov[0]['no_renov'] != 1) {
                                                if ($proyect[$i]['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                                    <td style="color: #2B9E34;font-weight: bold"><?= $proyect[$i]['cod_poliza']; ?></td>
                                                <?php } else { ?>
                                                    <td style="color: #E54848;font-weight: bold"><?= $proyect[$i]['cod_poliza']; ?></td>
                                                <?php }
                                            } else { ?>
                                                <td style="color: #4a148c;font-weight: bold"><?= $proyect[$i]['cod_poliza']; ?></td>
                                            <?php } ?>
                                            
                                            
                                            <td><?= $newCreated; ?></td>
                                            <td><?= $proyect[$i]['n_transf']; ?></td>
                                            <td><?= $proyect[$i]['n_banco']; ?></td>
                                            <td style="text-align: right;background-color: #D9D9D9;font-weight: bold"><?= '$ ' . number_format($proyect[$i]['monto_p'],2); ?></td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>

                                <tfoot class="text-center">
                                    <tr>
                                        <th>Proyecto</th>
                                        <th>N° Póliza</th>
                                        <th>Fecha Pago</th>
                                        <th>Nº Transf</th>
                                        <th>Banco</th>
                                        <th>Monto GC Pagado $<?= number_format($totalMonto,2); ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    
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