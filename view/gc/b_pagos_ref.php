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

$ref = $obj->get_gc_h_r(1);

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
                                <h1 class="font-weight-bold ">Historial de GC (Referidores)</h1>
                            </div>
                </div>
                <hr />


                <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">


                    <?php if ($ref != 0) { 
                        $ref = $obj->get_gc_h_r_distinctF(1);
                    ?>

                        <div class="table-responsive col-md-12">
                            <table class="table table-hover table-striped table-bordered" id="tablrBPagoGCR" width="100%">
                                <thead class="blue-gradient text-white text-center">
                                    <tr>
                                        <th>#</th>
                                        <th>Fecha Creación de GC</th>
                                        <th>Cantidad Pagos</th>
                                        <th hidden>created_At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php for ($i = 0; $i < sizeof($ref); $i++) { 
                                        $ref_count = $obj->get_gc_h_r_distinctF_countP(1, $ref[$i]['created_at']);
                                        $newCreated = date("Y/m/d", strtotime($ref[$i]['created_at']));    

                                        $count_faltante_pago_gc = $obj->get_count_r_reporte_gc_h_restante_by_id($ref[$i]['created_at']);
                                        if($count_faltante_pago_gc[0]['COUNT(id_gc_h_r)'] != 0) {
                                            $count_faltante_pago_gc = $count_faltante_pago_gc[0]['COUNT(id_gc_h_r)'];
                                        } else {
                                            $count_faltante_pago_gc = 0;
                                        }
                                    ?> 
                                        <tr style="cursor: pointer">
                                            <td class="text-center">
                                                <?= sizeof($ref)-$i; ?>
                                                <?php if ($count_faltante_pago_gc != 0) { ?>
                                                    <span class="badge badge-pill peach-gradient ml-2"><?= $count_faltante_pago_gc;?></span>
                                                <?php } ?>
                                            </td>
                                            <td class="text-center"><?= $newCreated; ?></td>
                                            <td class="text-center"><?= $ref_count[0]['COUNT(gc_h_r.id_poliza)']; ?></td>
                                            <td hidden><?= $ref[$i]['created_at']; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>

                                <tfoot class="text-center">
                                    <tr>
                                        <th>#</th>
                                        <th>Fecha Creación de GC</th>
                                        <th>Cantidad Pagos</th>
                                        <th hidden>created_At</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    
                    <?php } else { ?>
                        <div class="col-md-auto col-md-offset-2 text-center">
                            <h2 class="title text-danger">No se encuentran pagos a Referidores</h2>
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