<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}

//$pag = 'b_reportes';

require_once '../../Controller/Poliza.php';

$ref = $obj->get_gc_h_r();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . '\..\layout\header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . '\..\layout\navigation.php'; ?>
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
                                <h1 class="font-weight-bold">Cargar pago a Referidores</h1>
                            </div>
                            <br><br><br>
                </div>

                <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden>

                    <?php if ($ref != 0) { ?>
                        <div class="table-responsive col-md-8 offset-2">
                            <table class="table table-hover table-striped table-bordered" id="tablrPagoGCR" style="cursor: pointer;">
                                <thead class="blue-gradient text-white">
                                    <tr>
                                        <th hidden>Id Póliza</th>
                                        <th>N° Póliza</th>
                                        <th>Referidor</th>
                                        <th>Monto GC</th>
                                        <th>Fecha Creación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    for ($i = 0; $i < sizeof($ref); $i++) {
                                        $newCreated = date("d/m/Y", strtotime($ref[$i]['created_at']));
                                        $newCreatedH = date("h:i:s a", strtotime($ref[$i]['created_at']));
                                    ?>
                                        <tr>
                                            <td hidden><?= $ref[$i]['id_poliza']; ?></td>
                                            <td><?= $ref[$i]['cod_poliza']; ?></td>
                                            <td><?= $ref[$i]['nombre']; ?></td>
                                            <td class="text-right"><?= '$ ' . $ref[$i]['monto']; ?></td>
                                            <td><?= $newCreated . " " . $newCreatedH; ?></td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th hidden>Id Póliza</th>
                                        <th>N° Póliza</th>
                                        <th>Referidor</th>
                                        <th>Monto GC</th>
                                        <th>Fecha Creación</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    <?php } else { ?>
                        <div class="col-md-auto col-md-offset-2 text-center">
                            <h2 class="title text-danger">No se encuentran pagos a Referidores pendientes</h2>
                        </div>
                    <?php } ?>

                </div>

                <!--   TABLA PARA USUARIOS QUE SON ASESORES   -->
            <?php }
            if ($_SESSION['id_permiso'] == 3) {
            } ?>


        </div>





        <?php require_once dirname(__DIR__) . '\..\layout\footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . '\..\layout\footer.php'; ?>

        <script src="../../assets/view/b_poliza.js"></script>

</body>

</html>